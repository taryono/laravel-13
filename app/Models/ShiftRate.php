<?php

namespace Models; 

class ShiftRate extends BasedModel
{ 
    protected $fillable = [
        'shift_id',
        'day_type',
        'base_rate',
        'overtime_rate_per_hour',
        'overtime_multiplier',
        'night_allowance',
        'meal_allowance',
        'transport_allowance',
        'effective_date',
        'is_active',
    ];

    protected $casts = [
        'effective_date'         => 'date:Y-m-d',
        'base_rate'              => 'decimal:2',
        'overtime_rate_per_hour' => 'decimal:2',
        'overtime_multiplier'    => 'decimal:2',
        'night_allowance'        => 'decimal:2',
        'meal_allowance'         => 'decimal:2',
        'transport_allowance'    => 'decimal:2',
        'is_active'              => 'boolean',
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