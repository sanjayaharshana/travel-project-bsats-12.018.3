<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequstedTokenCount extends Model
{
    /** @use HasFactory<\Database\Factories\RequstedTokenCountFactory> */
    use HasFactory;

    protected $casts = [
        'token_usage' => 'array'
    ];

    protected $fillable = [
        'token_usage',
        'user_id',
        'request_type'
    ];

}
