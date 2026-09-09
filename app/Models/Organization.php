<?php

namespace Models; 

class Organization extends BasedModel
{ 
    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}