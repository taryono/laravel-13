<?php

namespace Models; 

class Shift extends BasedModel
{ 
   protected $table = 'shifts';

    protected $fillable = [
        'code',
        'name',
        'start_time',
        'end_time',
        'grace_period_start',
        'grace_period_end',
        'break_start',
        'break_end',
        'break_duration',
        'overtime_threshold',
        'overtime_rate',
        'night_shift',
        'night_shift_start',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
        'break_start' => 'string',
        'break_end' => 'string',
        'night_shift_start' => 'string',
        'grace_period_start' => 'integer',
        'grace_period_end' => 'integer',
        'break_duration' => 'integer',
        'overtime_threshold' => 'integer',
        'overtime_rate' => 'decimal:2',
        'night_shift' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Employee shifts using this shift
     */
    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    /**
     * Relationship: Daily attendance using this shift
     */
    public function attendanceDaily(): HasMany
    {
        return $this->hasMany(AttendanceDaily::class);
    }

    /**
     * Get shift duration in hours
     */
    public function getDurationInHoursAttribute(): float
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        if ($end->lt($start)) {
            $end->addDay();
        }
        
        return $end->diffInHours($start) + ($end->diffInMinutes($start) % 60) / 60;
    }

    /**
     * Check if shift crosses midnight
     */
    public function getIsCrossDayAttribute(): bool
    {
        return \Carbon\Carbon::parse($this->end_time)->lt(\Carbon\Carbon::parse($this->start_time));
    }

    /**
     * Scope for active shifts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for night shifts
     */
    public function scopeNightShift($query)
    {
        return $query->where('night_shift', true);
    }
}