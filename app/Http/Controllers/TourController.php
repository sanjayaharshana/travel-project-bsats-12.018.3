<?php

namespace App\Http\Controllers;

use App\Http\Service\TourService;
use App\Models\Locations;
use App\Models\RouteSelector;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TourController extends Controller
{
    protected $tourService;

    public function __construct(TourService $tourService)
    {
        $this->tourService = $tourService;
    }

    /**
     * Show the tour creation form
     */
    public function create(): View
    {
        $locations = Locations::where('is_active', true)
            ->orderBy('location_name')
            ->get();

        return view('tour.create', compact('locations'));
    }

    /**
     * Generate travel routes via AJAX
     */
    public function generateRoutes(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'start_location' => 'required|exists:locations,id',
                'end_location' => 'required|exists:locations,id|different:start_location',
                'return_location' => 'required|exists:locations,id|different:start_location|different:end_location',
                'preferences' => 'nullable|array',
                'preferences.*' => 'string|in:scenic,fastest,avoid_tolls'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get location models
            $startLocation = Locations::findOrFail($request->start_location);
            $endLocation = Locations::findOrFail($request->end_location);
            $returnLocation = Locations::findOrFail($request->return_location);

            // Set execution time limit for AI processing from config
            $timeout = config('tour.timeouts.ai_processing', 600);
            set_time_limit($timeout);
            ini_set('max_execution_time', $timeout);
            ini_set('default_socket_timeout', config('tour.timeouts.curl_timeout', 600));

            // Generate routes using AI
            $routeSelector = $this->tourService->getTravelRoutesFromAi(
                $startLocation,
                $endLocation,
                $returnLocation
            );

            if (!$routeSelector) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate routes. Please try again.'
                ], 500);
            }

            // Format response data
            $responseData = [
                'route_id' => $routeSelector->id,
                'route_name' => $routeSelector->route_name,
                'routes' => $routeSelector->routes_data,
                'start_location' => [
                    'id' => $startLocation->id,
                    'name' => $startLocation->location_name,
                    'coordinates' => [
                        'lat' => $startLocation->latitude,
                        'lng' => $startLocation->longitude
                    ]
                ],
                'end_location' => [
                    'id' => $endLocation->id,
                    'name' => $endLocation->location_name,
                    'coordinates' => [
                        'lat' => $endLocation->latitude,
                        'lng' => $endLocation->longitude
                    ]
                ],
                'return_location' => [
                    'id' => $returnLocation->id,
                    'name' => $returnLocation->location_name,
                    'coordinates' => [
                        'lat' => $returnLocation->latitude,
                        'lng' => $returnLocation->longitude
                    ]
                ],
                'statistics' => $this->tourService->getRouteStatistics($routeSelector->id),
                'generated_at' => now()->toISOString()
            ];

            Log::info('Routes generated successfully', [
                'route_id' => $routeSelector->id,
                'user_id' => auth()->id(),
                'locations_count' => count($routeSelector->routes_data),
                'processing_time' => microtime(true) - LARAVEL_START
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Routes generated successfully!',
                'data' => $responseData
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating routes: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating routes. Please try again.',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show generated routes
     */
    public function showRoutes($routeId): View
    {
        try {
            $routeSelector = RouteSelector::with(['startLocation', 'endLocation', 'returnLocation'])
                ->findOrFail($routeId);

            $statistics = $this->tourService->getRouteStatistics($routeId);

            return view('tour.show', compact('routeSelector', 'statistics'));

        } catch (\Exception $e) {
            Log::error('Error showing routes: ' . $e->getMessage());
            abort(404, 'Route not found');
        }
    }

    /**
     * List all generated routes
     */
    public function index(): View
    {
        $routes = RouteSelector::with(['startLocation', 'endLocation', 'returnLocation'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tour.index', compact('routes'));
    }

    /**
     * Delete a route
     */
    public function destroy($routeId): JsonResponse
    {
        try {
            $route = RouteSelector::findOrFail($routeId);
            $route->delete();

            Log::info('Route deleted', [
                'route_id' => $routeId,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Route deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting route: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete route'
            ], 500);
        }
    }

    /**
     * Get route statistics for API
     */
    public function getRouteStats($routeId): JsonResponse
    {
        try {
            $statistics = $this->tourService->getRouteStatistics($routeId);
            
            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get route statistics'
            ], 500);
        }
    }
} 