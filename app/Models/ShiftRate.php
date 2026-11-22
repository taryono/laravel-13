<?php

namespace Models;

class ShiftRate extends SoftDeletableModel
{
    protected $fillable = [
        'shift_id',
        'day_type',
        'base_rate_per_shift',
        'overtime_multiplier',
        'overtime_rate_per_hour',
        'night_allowance_per_shift',
        'meal_allowance_per_shift',
        'transport_allowance_per_shift',
        'is_active',
    ];

    protected $casts = [
        'day_type' => 'string',
        'is_active' => 'boolean',
    ];

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}