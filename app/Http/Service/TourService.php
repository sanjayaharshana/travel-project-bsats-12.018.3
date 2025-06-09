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

            // Set execution time limit from config
            $timeout = config('tour.timeouts.ai_processing', 600);
            set_time_limit($timeout);
            ini_set('max_execution_time', $timeout);

            // Configure OpenAI request parameters
            $openAiConfig = [
                'model' => config('tour.ai.model', 'gpt-4'),
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => config('tour.ai.temperature', 0.7),
                'max_tokens' => config('tour.ai.max_tokens', 2000),
            ];

            // Make the OpenAI request with retry logic
            $result = $this->makeOpenAiRequestWithRetry($openAiConfig);

            if (!$result) {
                Log::error('Failed to get response from OpenAI after all retry attempts');
                return null;
            }

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

            // Validate route limits from config
            $maxRoutes = config('tour.validation.max_routes_per_request', 5);
            if (count($messageDetails['routes']) > $maxRoutes) {
                $messageDetails['routes'] = array_slice($messageDetails['routes'], 0, $maxRoutes);
                Log::warning("Routes limited to {$maxRoutes} as per configuration");
            }

            // Store all locations from routes
            $storedLocations = [];
            foreach ($messageDetails['routes'] as $route) {
                if (isset($route['places']) && is_array($route['places'])) {
                    // Validate location limits
                    $maxLocations = config('tour.validation.max_locations_per_route', 10);
                    if (count($route['places']) > $maxLocations) {
                        $route['places'] = array_slice($route['places'], 0, $maxLocations);
                    }
                    
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
            $routeSelector->save();

            Log::info('Route generation completed successfully', [
                'route_id' => $routeSelector->id,
                'locations_stored' => count($storedLocations),
                'processing_time' => microtime(true) - LARAVEL_START
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
     * Make OpenAI request with retry logic and proper timeout handling
     *
     * @param array $config
     * @return mixed|null
     */
    private function makeOpenAiRequestWithRetry(array $config)
    {
        $maxRetries = config('tour.ai.retry_attempts', 3);
        $retryDelay = config('tour.ai.retry_delay', 5);
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                Log::info("OpenAI request attempt {$attempt} of {$maxRetries}");
                
                // Configure custom HTTP client with extended timeouts
                $this->configureOpenAiClient();
                
                $result = OpenAI::chat()->create($config);
                
                Log::info("OpenAI request successful on attempt {$attempt}");
                return $result;
                
            } catch (\Exception $e) {
                Log::warning("OpenAI request failed on attempt {$attempt}: " . $e->getMessage());
                
                if ($attempt === $maxRetries) {
                    Log::error("All OpenAI request attempts failed", [
                        'error' => $e->getMessage(),
                        'attempts' => $maxRetries
                    ]);
                    return null;
                }
                
                // Wait before retrying
                sleep($retryDelay);
            }
        }
        
        return null;
    }

    /**
     * Configure OpenAI client with custom HTTP client and timeouts
     */
    private function configureOpenAiClient()
    {
        $curlTimeout = config('tour.timeouts.curl_timeout', 600);
        $curlConnectTimeout = config('tour.timeouts.curl_connect_timeout', 30);
        
        // Set cURL options globally
        curl_setopt_array(curl_init(), [
            CURLOPT_TIMEOUT => $curlTimeout,
            CURLOPT_CONNECTTIMEOUT => $curlConnectTimeout,
        ]);
        
        // Set socket timeout
        if (function_exists('ini_set')) {
            ini_set('default_socket_timeout', $curlTimeout);
        }
        
        // Configure Guzzle HTTP client if available
        if (class_exists('\GuzzleHttp\Client')) {
            try {
                $client = new \GuzzleHttp\Client([
                    'timeout' => $curlTimeout,
                    'connect_timeout' => $curlConnectTimeout,
                    'http_errors' => false,
                ]);
                
                // Set the client globally for OpenAI
                if (method_exists(OpenAI::class, 'setHttpClient')) {
                    OpenAI::setHttpClient($client);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to configure custom HTTP client: ' . $e->getMessage());
            }
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
            
            // Calculate total places from routes data
            $totalPlaces = 0;
            if (isset($route->routes_data) && is_array($route->routes_data)) {
                foreach ($route->routes_data as $routeData) {
                    if (isset($routeData['places']) && is_array($routeData['places'])) {
                        $totalPlaces += count($routeData['places']);
                    }
                }
            }
            
            return [
                'route_id' => $route->id,
                'route_name' => $route->route_name,
                'total_places' => $totalPlaces,
                'total_routes' => count($route->routes_data),
                'created_at' => $route->created_at,
                'locations_stored' => $totalPlaces // Calculate dynamically
            ];
        } catch (\Exception $e) {
            Log::error('Error getting route statistics: ' . $e->getMessage());
            return [];
        }
    }
}
