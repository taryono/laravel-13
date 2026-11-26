<?php

namespace Models;

class Facility extends SoftDeletableModel
{ 
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function images()
    {
        return $this->hasMany(FacilityImage::class)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(FacilityImage::class)
            ->where('is_primary', true)
            ->orderBy('sort_order');
    }
}