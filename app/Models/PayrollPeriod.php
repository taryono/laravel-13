<?php

namespace Models;

class PayrollPeriod extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'period',
        'start_date',
        'end_date',
        'payment_date',
        'status',
        'is_closed',
        'closed_at',
        'closed_by',
        'is_active'
    ];

    public function getNameAttribute()
    {
        return $this->attributes['period'] ?? ($this->attributes['name'] ?? null);
    }

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'is_closed' => 'boolean',
        'closed_at' => 'datetime',
        'is_active' => 'boolean',
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

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}