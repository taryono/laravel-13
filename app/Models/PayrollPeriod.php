<?php

namespace Models; 

class PayrollPeriod extends BasedModel
{ 
    protected $fillable = [
        'code',
        'name',
        'start_date',
        'end_date',
        'is_closed',
        'closed_at',
        'closed_by',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date'   => 'date:Y-m-d',
        'is_closed'  => 'boolean',
        'closed_at'  => 'datetime',
        'is_active'  => 'boolean',
    ];

    // Relasi
    public function attendanceDailies()
    {
        return $this->hasMany(AttendanceDaily::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}