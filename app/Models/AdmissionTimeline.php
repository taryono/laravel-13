<?php

namespace Models;

class AdmissionTimeline extends SoftDeletableModel
{
    protected $fillable = [
        'admission_id',
        'phase_name',
        'start_date',
        'end_date',
        'description',
        'order_number',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
