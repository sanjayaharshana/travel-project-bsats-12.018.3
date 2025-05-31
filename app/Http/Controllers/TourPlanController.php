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
            'beverage_preference' => ['nullable', Rule::in(['alcoholic', 'non_alcoholic'])],
            'meal_types' => 'nullable|array',
            'meal_types.*' => ['string', Rule::in(['vegetarian', 'non_vegetarian', 'halal', 'mixed'])],
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
                'beverage_preference' => $request->beverage_preference,
                'meal_types' => $request->meal_types,
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

            return response()->json([
                'success' => true,
                'message' => 'Tour plan created successfully',
                'data' => [
                    'tour_plan_id' => $tourPlan->id,
                    'uuid' => $tourPlan->uuid,
                    'next_step' => 2
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tour plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, TourPlan $tourPlan)
    {
        // Check if tour plan is editable
        if (!$tourPlan->isEditable()) {
            return response()->json([
                'success' => false,
                'message' => 'This tour plan cannot be edited'
            ], 403);
        }

        // Similar validation and update logic as store method
        // Add specific update logic here
    }

    public function show(TourPlan $tourPlan)
    {
        return response()->json([
            'success' => true,
            'data' => $tourPlan->load(['shop', 'user', 'guest'])
        ]);
    }

    public function updateStep(Request $request, TourPlan $tourPlan)
    {
        $validator = Validator::make($request->all(), [
            'step' => 'required|integer|min:1|max:5',
            'data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tourPlan->updateProgress($request->step, $request->data);

            return response()->json([
                'success' => true,
                'message' => 'Tour plan progress updated successfully',
                'data' => [
                    'current_step' => $tourPlan->current_step,
                    'next_step' => $request->step + 1
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tour plan progress',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel(TourPlan $tourPlan)
    {
        if (!$tourPlan->isEditable()) {
            return response()->json([
                'success' => false,
                'message' => 'This tour plan cannot be cancelled'
            ], 403);
        }

        try {
            $tourPlan->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Tour plan cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel tour plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
