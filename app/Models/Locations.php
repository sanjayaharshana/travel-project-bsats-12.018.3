<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    /** @use HasFactory<\Database\Factories\LocationsFactory> */
    use HasFactory;

    protected $fillable = [
      'location_name',
      'longitude', 'latitude'
    ];
}
