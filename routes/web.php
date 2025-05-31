<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\TourPlanController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/plan-trip', function (Request $request) {
    // Validate the request
    $validated = $request->validate([
        'from' => 'required|string|max:255',
        'to' => 'required|string|max:255',
        'dates' => 'required|string',
        'travelers' => 'required|integer|min:1|max:10',
        'budget' => 'required|integer|min:500|max:10000',
    ]);

    // TODO: Implement AI travel planning logic here
    // For now, we'll just redirect back with a success message
    return redirect()->route('home')->with('success', 'Your travel plan request has been received! Our AI is working on creating your personalized itinerary.');
})->name('plan-trip');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tour Plan Routes
    Route::get('/tour-plan/create', [\App\Http\Controllers\TourPlanController::class, 'create'])->name('tourplan.create');
    Route::post('/tour-plan/create', [\App\Http\Controllers\TourPlanController::class, 'createTourPlan'])->name('tourplan.store');

    Route::prefix('tour-plans')->group(function () {
        Route::post('/', [TourPlanController::class, 'store'])->name('tour-plans.store');
        Route::get('/{tourPlan}', [TourPlanController::class, 'show'])->name('tour-plans.show');
        Route::put('/{tourPlan}', [TourPlanController::class, 'update'])->name('tour-plans.update');
        Route::post('/{tourPlan}/step', [TourPlanController::class, 'updateStep'])->name('tour-plans.update-step');
        Route::post('/{tourPlan}/cancel', [TourPlanController::class, 'cancel'])->name('tour-plans.cancel');
    });
});

Route::get('/careers', [App\Http\Controllers\CareerPageController::class, 'index'])->name('careers.index');

require __DIR__.'/auth.php';
