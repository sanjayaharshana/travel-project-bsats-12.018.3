<?php

namespace App\Http\Service;

use App\Models\RequstedTokenCount;
use App\Models\RouteSelector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Locations;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Validator;

class TourService
{
    /**
     * Store or update a location based on coordinates and name
     *
     * @param array $locationData
     * @return Locations
     * @throws \Exception
     */
    public function storeLocation(array $locationData)
    {
        try {
            DB::beginTransaction();

            // Validate required fields
            $validator = Validator::make($locationData, [
                'name' => 'required|string|max:255',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'address' => 'nullable|string|max:500',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                throw new \Exception('Validation failed: ' . $validator->errors()->first());
            }

            // Check for existing location with same coordinates or name
            $existingLocation = Locations::where(function ($query) use ($locationData) {
                $query->where(function ($q) use ($locationData) {
                    // Check exact coordinates match
                    $q->where('latitude', $locationData['latitude'])
                      ->where('longitude', $locationData['longitude']);
                })->orWhere(function ($q) use ($locationData) {
                    // Check name match (case insensitive)
                    $q->whereRaw('LOWER(location_name) = ?', [strtolower($locationData['name'])]);
                });
            })->first();

            if ($existingLocation) {
                // Update existing location
                $existingLocation->update([
                    'location_name' => $locationData['name'],
                    'latitude' => $locationData['latitude'],
                    'longitude' => $locationData['longitude'],
                    'address' => $locationData['address'] ?? $existingLocation->address,
                    'description' => $locationData['description'] ?? $existingLocation->description,
                    'is_active' => $locationData['is_active'] ?? $existingLocation->is_active,
                    'updated_at' => now()
                ]);

                DB::commit();
                Log::info('Location updated: ' . $existingLocation->location_name);
                return $existingLocation;
            }

            // Create new location
            $location = Locations::create([
                'location_name' => $locationData['name'],
                'latitude' => $locationData['latitude'],
                'longitude' => $locationData['longitude'],
                'address' => $locationData['address'] ?? null,
                'description' => $locationData['description'] ?? null,
                'is_active' => $locationData['is_active'] ?? true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            Log::info('New location created: ' . $location->location_name);
            return $location;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing location: ' . $e->getMessage(), [
                'location_data' => $locationData,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate travel routes using AI
     *
     * @param Locations $startLocation
     * @param Locations $endLocation
     * @param Locations $returnLocation
     * @return RouteSelector|null
     * @throws \Exception
     */
    public function getTravelRoutesFromAi($startLocation, $endLocation, $returnLocation)
    {
        try {
            // Validate input locations
            if (!$startLocation || !$endLocation || !$returnLocation) {
                throw new \Exception('All three locations (start, end, return) are required');
            }

            $prompt = $this->buildRoutePrompt($startLocation, $endLocation, $returnLocation);

            $result = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000
            ]);

            $gptOutputArray = $result->toArray();
            
            // Track token usage
            $tokenUsage = $this->tokenUsageAdd($gptOutputArray['usage'] ?? null, 'route_calculate');

            if (!$tokenUsage) {
                Log::warning('Failed to track token usage for route calculation');
            }

            $getResponsesMessage = $gptOutputArray['choices'] ?? null;
            
            if (!$getResponsesMessage || empty($getResponsesMessage)) {
                Log::error('No response received from OpenAI');
                return null;
            }

            $messageContent = $getResponsesMessage[0]['message']['content'] ?? null;
            $messageDetails = $this->messageFilter($messageContent);

            if (empty($messageDetails) || !isset($messageDetails['routes'])) {
                Log::error('Invalid response format from AI', ['content' => $messageContent]);
                return null;
            }

            // Store all locations from routes
            $storedLocations = [];
            foreach ($messageDetails['routes'] as $route) {
                if (isset($route['places']) && is_array($route['places'])) {
                    foreach ($route['places'] as $place) {
                        try {
                            $location = $this->storeLocation([
                                'name' => $place['name'],
                                'latitude' => $place['coordinates']['lat'],
                                'longitude' => $place['coordinates']['lng'],
                            ]);
                            $storedLocations[] = $location;
                        } catch (\Exception $e) {
                            Log::warning('Failed to store location: ' . $place['name'], ['error' => $e->getMessage()]);
                        }
                    }
                }
            }

            // Create route selector record
            $routeSelector = new RouteSelector();
            $routeSelector->start_location_id = $startLocation->id;
            $routeSelector->end_location_id = $endLocation->id;
            $routeSelector->return_location_id = $returnLocation->id;
            $routeSelector->route_name = $messageDetails['routes'][0]['route_name'] ?? 'Generated Route';
            $routeSelector->routes_data = $messageDetails['routes'];
            $routeSelector->stored_locations_count = count($storedLocations);
            $routeSelector->save();

            Log::info('Route generation completed successfully', [
                'route_id' => $routeSelector->id,
                'locations_stored' => count($storedLocations)
            ]);

            return $routeSelector;

        } catch (\Exception $e) {
            Log::error('Error generating travel routes: ' . $e->getMessage(), [
                'start_location' => $startLocation->location_name ?? 'unknown',
                'end_location' => $endLocation->location_name ?? 'unknown',
                'return_location' => $returnLocation->location_name ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Build the AI prompt for route generation
     *
     * @param Locations $startLocation
     * @param Locations $endLocation
     * @param Locations $returnLocation
     * @return string
     */
    private function buildRoutePrompt($startLocation, $endLocation, $returnLocation)
    {
        return "Generate alternative travel routes between [Start] and [End] that pass through nearby cities or towns, and [Return] back to the starting location. Provide 2-3 different paths with place names and coordinates.

Start: {$startLocation->location_name} (Lat: {$startLocation->latitude}, Lng: {$startLocation->longitude})
End: {$endLocation->location_name} (Lat: {$endLocation->latitude}, Lng: {$endLocation->longitude})
Return: {$returnLocation->location_name} (Lat: {$returnLocation->latitude}, Lng: {$returnLocation->longitude})

Generate JSON with the following structure:
{
    \"routes\": [
        {
            \"route_name\": \"Scenic Mountain Route\",
            \"places\": [
                {\"name\": \"Place A\", \"coordinates\": {\"lat\": 12.34, \"lng\": 56.78}},
                {\"name\": \"Place B\", \"coordinates\": {\"lat\": 23.45, \"lng\": 67.89}}
            ]
        },
        {
            \"route_name\": \"Coastal Highway Route\",
            \"places\": [
                {\"name\": \"Place C\", \"coordinates\": {\"lat\": 34.56, \"lng\": 78.90}},
                {\"name\": \"Place D\", \"coordinates\": {\"lat\": 45.67, \"lng\": 89.01}}
            ]
        }
    ]
}

Ensure all coordinates are realistic and the routes are practical for travel.";
    }

    /**
     * Filter and validate AI response message
     *
     * @param string|null $messageContent
     * @return array
     */
    public function messageFilter($messageContent)
    {
        if (!$messageContent) {
            Log::warning('Empty message content received from AI');
            return [];
        }

        // Clean the message content (remove markdown code blocks if present)
        $cleanContent = preg_replace('/```json\s*|\s*```/', '', trim($messageContent));

        // Decode JSON string to array
        $decodedMessage = json_decode($cleanContent, true);

        // Check if decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON decode error: ' . json_last_error_msg(), [
                'content' => $messageContent,
                'clean_content' => $cleanContent
            ]);
            return [];
        }

        // Validate the structure
        if (!isset($decodedMessage['routes']) || !is_array($decodedMessage['routes'])) {
            Log::error('Invalid response structure: missing or invalid routes array');
            return [];
        }

        return $decodedMessage;
    }

    /**
     * Add token usage tracking
     *
     * @param array|null $tokenUsage
     * @param string $requestType
     * @param int|null $authId
     * @return bool
     */
    public function tokenUsageAdd($tokenUsage, $requestType, $authId = null)
    {
        if (!$tokenUsage) {
            Log::warning('No token usage data provided for tracking');
            return false;
        }

        try {
            $tokenUsageDetails = new RequstedTokenCount();
            $tokenUsageDetails->token_usage = $tokenUsage;
            $tokenUsageDetails->user_id = $authId ?? auth()->id();
            $tokenUsageDetails->request_type = $requestType;
            $tokenUsageDetails->save();

            Log::info('Token usage tracked successfully', [
                'request_type' => $requestType,
                'user_id' => $tokenUsageDetails->user_id,
                'tokens_used' => $tokenUsage['total_tokens'] ?? 'unknown'
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to track token usage: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get route statistics
     *
     * @param int $routeId
     * @return array
     */
    public function getRouteStatistics($routeId)
    {
        try {
            $route = RouteSelector::findOrFail($routeId);
            
            return [
                'route_id' => $route->id,
                'route_name' => $route->route_name,
                'total_places' => count($route->routes_data[0]['places'] ?? []),
                'total_routes' => count($route->routes_data),
                'created_at' => $route->created_at,
                'locations_stored' => $route->stored_locations_count ?? 0
            ];
        } catch (\Exception $e) {
            Log::error('Error getting route statistics: ' . $e->getMessage());
            return [];
        }
    }
}
