<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('route_selectors', function (Blueprint $table) {
            $table->id();
            $table->text('start_location_id');
            $table->text('end_location_id');
            $table->text('return_location_id');
            $table->text('route_name')->nullable();
            $table->text('route_description')->nullable();
            $table->text('routes_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_selectors');
    }
};
