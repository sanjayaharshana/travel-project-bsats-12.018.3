<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tour_plans', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users');

            // Tour Overview
            $table->dateTime('start_date');
            $table->date('end_date');
            $table->integer('duration_days');

            // Tour Locations
            $table->json('start_location');
            $table->json('end_location');
            $table->json('return_location')->nullable();
            $table->enum('return_type', ['pickup', 'specific'])->default('pickup');

            // Group Details
            $table->integer('adult_count')->default(1);
            $table->integer('child_count')->default(0);
            $table->integer('infant_count')->default(0);
            $table->integer('total_group_size');

            // Dining Preferences
            $table->enum('beverage_preference', ['alcoholic', 'non_alcoholic'])->nullable();
            $table->json('meal_types')->nullable();

            // Tour Budget
            $table->decimal('budget', 12, 2);
            $table->enum('budget_type', ['luxury', 'medium', 'emergency']);

            // Location Preferences
            $table->json('location_types')->nullable();

            // Status and Progress
            $table->enum('status', ['draft', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->integer('current_step')->default(1);
            $table->json('progress_data')->nullable();

            // Additional Details
            $table->text('special_requirements')->nullable();
            $table->json('additional_preferences')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('uuid');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_plans');
    }
};
