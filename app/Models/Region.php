<?php
namespace Models; 

class Region extends BasedModel
{
    // Relasi
        public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}