<?php

namespace Models;

class Location extends BasedModel
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'parent_id',
        'latitude',
        'longitude',
        'qr_code',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relasi
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
