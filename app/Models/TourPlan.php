<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TourPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'shop_id',
        'user_id',
        'guest_id',
        'start_date',
        'end_date',
        'duration_days',
        'start_location',
        'end_location',
        'return_location',
        'return_type',
        'adult_count',
        'child_count',
        'infant_count',
        'total_group_size',
        'beverage_preference',
        'meal_types',
        'budget',
        'budget_type',
        'location_types',
        'status',
        'current_step',
        'progress_data',
        'special_requirements',
        'additional_preferences'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'date',
        'start_location' => 'array',
        'end_location' => 'array',
        'return_location' => 'array',
        'meal_types' => 'array',
        'location_types' => 'array',
        'progress_data' => 'array',
        'additional_preferences' => 'array',
        'budget' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tourPlan) {
            $tourPlan->uuid = (string) Str::uuid();
        });
    }

    // Relationships
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helper Methods
    public function isEditable()
    {
        return in_array($this->status, ['draft', 'in_progress']);
    }

    public function updateProgress($step, $data = [])
    {
        $this->current_step = $step;
        $this->progress_data = array_merge($this->progress_data ?? [], $data);
        $this->save();
    }

    public function calculateTotalGroupSize()
    {
        return $this->adult_count + $this->child_count + $this->infant_count;
    }

    public function calculateDuration()
    {
        return $this->start_date->diffInDays($this->end_date);
    }
} 