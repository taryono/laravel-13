<?php

namespace Models;

class FiscalYear extends SoftDeletableModel
{
    protected $fillable = [
        'school_id',
        'organization_id',
        'year',
        'month',
        'start_date',
        'end_date',
        'status',
        'is_closed',
        'is_active',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'is_closed' => 'boolean',
        'is_active' => 'boolean',
        'closed_at' => 'datetime',
    ];
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}