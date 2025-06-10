<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTourPlanRequest;
use App\Http\Service\TourService;
use App\Models\TourPlan;
use App\Models\Locations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use OpenAI\Laravel\Facades\OpenAI;


class TourPlanController extends Controller
{

    public function create()
    {
        return view('tour-plan.steps.page.step1',[
            'step' => 1
        ]);
    }

    public function selectRoute(Request $request, $tourId)
    {
        $tourPlan = TourPlan::findOrFail($tourId);

        // Get location details from the tour plan
        $startLocation = Locations::find($tourPlan->start_location);
        $endLocation = Locations::find($tourPlan->end_location);
        $returnLocation = Locations::find($tourPlan->return_location);

        // Get suggested routes from the relationship
        $suggestedRoutes = $tourPlan->suggestedRoutes()->first();

        return view('tour-plan.steps.page.step2', [
            'tourId' => $tourId,
            'tourPlan' => $tourPlan,
            'suggestedRoutes' => $suggestedRoutes,
            'startLocation' => $startLocation,
            'endLocation' => $endLocation,
            'returnLocation' => $returnLocation,
            'step' => 2,
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
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors in the form.');
        }

        try {
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
            } else {
                $returnLocationDetails = $startLocationDetails;
            }

            $getFindAvailableRoutes = $tourService->getTravelRoutesFromAi($startLocationDetails,$endLocationDetails,$returnLocationDetails);

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
                    'suggested_routes_id' => $getFindAvailableRoutes->id ?? null
                ]
            ]);

            // Handle AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tour plan created successfully!',
                    'tourplan_id' => $tourPlan->id,
                    'redirect_url' => route('tourplan.select-route', ['tourId' => $tourPlan->id])
                ]);
            }

            // Handle regular form submission
            return redirect()
                ->route('tourplan.select-route', [
                    'tourId' => $tourPlan->id
                ])
                ->with('success', 'Tour plan created successfully!');

        } catch (\Exception $e) {
            \Log::error('Error creating tour plan: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the tour plan. Please try again.',
                    'debug_message' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the tour plan. Please try again.');
        }
    }








}
