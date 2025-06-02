<?php

namespace App\Http\Controllers;

use App\Models\TourPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TourPlanController extends Controller
{
    public function create()
    {
        return view('tour-plan.create');
    }

    public function selectRoute(Request $request, $tourId)
    {
        $travelPlan = TourPlan::findOrFail($tourId);

        $tourDetails = [
            'start_location' => [
                'latitude' => $travelPlan->start_location['lat'] ,
                'longitude' => $travelPlan->start_location['lng'],
                'name' => 'Start Location Name'
            ],
            'end_location' => [
                'latitude' => $travelPlan->end_location['lat'],
                'longitude' =>  $travelPlan->end_location['lng'],
                'name' => 'End Location Name'
            ]
        ];

        return view('tour-plan.select-route', [
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
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Calculate duration
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $durationDays = $startDate->diff($endDate)->days;

            // Prepare location data
            $startLocation = [
                'lat' => $request->start_location_lat,
                'lng' => $request->start_location_lng,
                'name' => $request->start_location_name
            ];

            $endLocation = [
                'lat' => $request->end_location_lat,
                'lng' => $request->end_location_lng,
                'name' => $request->end_location_name
            ];

            $returnLocation = null;
            if ($request->return_type === 'specific') {
                $returnLocation = [
                    'lat' => $request->return_location_lat,
                    'lng' => $request->return_location_lng,
                    'name' => $request->return_location_name
                ];
            }

            // Calculate total group size
            $totalGroupSize = $request->adult_count + $request->child_count + $request->infant_count;

            // Create tour plan
            $tourPlan = TourPlan::create([
                'user_id' => Auth::id(), // If user is logged in
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration_days' => $durationDays,
                'start_location' => $startLocation,
                'end_location' => $endLocation,
                'return_location' => $returnLocation,
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
                    'step1_completed_at' => now()
                ]
            ]);

            return redirect()->route('tourplan.select-route', ['tourId' => $tourPlan->id]);

        } catch (\Exception $e) {
           return back()
            ->withErrors(['error' => 'Failed to create tour plan: ' . $e->getMessage()])
            ->withInput();
        }
    }








}
