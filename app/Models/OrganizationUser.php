<?php

namespace Models; 

class OrganizationUser extends BasedModel
{ 
    protected $table = 'organization_user';
    // Relasi
    public function user()
    {
        return $this->belongsTo(App\Models\User::class);
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