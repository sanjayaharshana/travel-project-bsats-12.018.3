<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTourPlanRequest;
use App\Http\Service\TourService;
use App\Models\TourPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use OpenAI\Laravel\Facades\OpenAI;


class TourPlanController extends Controller
{

    public function create()
    {
        return view('tour-plan.steps.page.step1');
    }

    public function selectRoute(Request $request, $tourId)
    {
        $travelPlan = TourPlan::findOrFail($tourId);

        $tourDetails = [
            'routes' => [
                [
                    'route_name' => 'Route 1',
                    'places' => [
                        ['name' => 'Place A', 'coordinates' => ['lat' => 12.34, 'lng' => 56.78]],
                        ['name' => 'Place B', 'coordinates' => ['lat' => 23.45, 'lng' => 67.89]]
                    ]
                ],
                [
                    'route_name' => 'Route 2',
                    'places' => [
                        ['name' => 'Place C', 'coordinates' => ['lat' => 34.56, 'lng' => 78.90]],
                        ['name' => 'Place D', 'coordinates' => ['lat' => 45.67, 'lng' => 89.01]]
                    ]
                ]
            ]
        ];

        return view('tour-plan.steps.page.step2', [
            'tourId' => $tourId,
            'tourDetails' => $tourDetails,
        ]);
    }

    public function createTourPlan(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_location_lat' => 'required|numeric',
            'start_location_lng' => 'required|numeric',
            'start_location_name' => 'required|string',
            'end_location_lat' => 'required|numeric',
            'end_location_lng' => 'required|numeric',
            'end_location_name' => 'required|string',
            'return_type' => ['required', Rule::in(['pickup', 'specific'])],
            'return_location_lat' => 'required_if:return_type,specific|nullable|numeric',
            'return_location_lng' => 'required_if:return_type,specific|nullable|numeric',
            'return_location_name' => 'required_if:return_type,specific|nullable|string',
            'adult_count' => 'required|integer|min:1',
            'child_count' => 'required|integer|min:0',
            'infant_count' => 'required|integer|min:0',
            'beverage_preference_text' => ['nullable', Rule::in(['alcoholic', 'non_alcoholic'])],
            'meal_type_text' => ['nullable', Rule::in(['vegetarian', 'non_vegetarian', 'halal', 'mixed'])],
            'budget' => 'required|numeric|min:0',
            'budget_type' => ['required', Rule::in(['luxury', 'medium', 'emergency'])],
            'location_types' => 'nullable|array',
            'location_types.*' => ['string', Rule::in(['nature', 'cultural', 'urban', 'beach', 'adventure', 'wellness'])],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors in the form.');
        }

            // Calculate duration
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $durationDays = $startDate->diff($endDate)->days;

            $tourService = new TourService();

           $startLocationDetails =  $tourService->storeLocation([
               'latitude' => $request->start_location_lat,
               'longitude' => $request->start_location_lng,
               'name' => $request->start_location_name
            ]);

           $endLocationDetails =  $tourService->storeLocation([
                  'latitude' => $request->end_location_lat,
                  'longitude' => $request->end_location_lng,
                  'name' => $request->end_location_name
              ]);



            if ($request->return_type === 'specific') {

                $returnLocationDetails =  $tourService->storeLocation([
                    'latitude' => $request->return_location_lat,
                    'longitude' => $request->return_location_lng,
                    'name' => $request->return_location_name
                ]);

            }else{
                $returnLocationDetails = $startLocationDetails;
            }

            $getFindAvailableRoutes = $tourService->getTrvelRoutesfromAi($startLocationDetails,$endLocationDetails, $returnLocationDetails);

            $totalGroupSize = $request->adult_count + $request->child_count + $request->infant_count;

            // Create tour plan with routes
            $tourPlan = TourPlan::create([
                'user_id' => Auth::id(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration_days' => $durationDays,
                'start_location' => $startLocationDetails->id,
                'end_location' => $endLocationDetails->id,
                'return_location' => $returnLocationDetails->id,
                'return_type' => $request->return_type,
                'adult_count' => $request->adult_count,
                'child_count' => $request->child_count,
                'infant_count' => $request->infant_count,
                'total_group_size' => $totalGroupSize,
                'beverage_preference' => $request->beverage_preference_text,
                'meal_types' => [$request->meal_type_text],
                'budget' => $request->budget,
                'budget_type' => $request->budget_type,
                'location_types' => $request->location_types,
                'status' => 'draft',
                'current_step' => 1,
                'progress_data' => [
                    'step1_completed' => true,
                    'step1_completed_at' => now(),
                    'suggested_routes_id' => $getFindAvailableRoutes->id // Store the suggested routes
                ]
            ]);

            return redirect()
                ->route('tourplan.select-route', [
                    'tourId' => $tourPlan->id
                ])
                ->with('success', 'Tour plan created successfully!');

    }








}
