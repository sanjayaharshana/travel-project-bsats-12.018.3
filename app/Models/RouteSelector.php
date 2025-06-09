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
}
