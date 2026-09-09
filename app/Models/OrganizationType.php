<?php

namespace Models; 

class OrganizationType extends BasedModel
{ 
    // Relasi
    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}