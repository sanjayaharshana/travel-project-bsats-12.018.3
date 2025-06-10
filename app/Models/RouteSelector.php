<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteSelector extends Model
{
    /** @use HasFactory<\Database\Factories\RouteSelectorFactory> */
    use HasFactory;

    protected $casts = [
      'routes_data' => 'array'
    ];

    // Relationships
    public function startLocation()
    {
        return $this->belongsTo(Locations::class, 'start_location_id');
    }

    public function endLocation()
    {
        return $this->belongsTo(Locations::class, 'end_location_id');
    }

    public function returnLocation()
    {
        return $this->belongsTo(Locations::class, 'return_location_id');
    }

    // Reverse relationship to get tour plans that use this route
    public function tourPlans()
    {
        return $this->hasMany(TourPlan::class, 'suggested_routes_id', 'id');
    }

    // Helper method to get tour plans that use this route
    public function getTourPlans()
    {
        return TourPlan::where('progress_data->suggested_routes_id', $this->id)->get();
    }
}
