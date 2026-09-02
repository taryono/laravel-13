<?php

namespace Models; 

class EmployeeShift extends BasedModel
{ 
    protected $fillable = [
        'employee_id',
        'shift_id',
        'effective_date',
        'end_date',
        'day_of_week',
        'is_rotational',
        'rotation_group',
        'rotation_sequence',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'effective_date'    => 'date:Y-m-d',
        'end_date'          => 'date:Y-m-d',
        'day_of_week'       => 'integer',
        'is_rotational'     => 'boolean',
        'rotation_sequence' => 'integer',
        'is_active'         => 'boolean',
    ];

    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}