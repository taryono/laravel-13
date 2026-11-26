<?php

namespace Models;

class FacilityCategory extends SoftDeletableModel
{
    protected $table = 'facility_categories';

    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}