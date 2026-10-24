<?php
namespace Models; 

class DigitalWallet extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}