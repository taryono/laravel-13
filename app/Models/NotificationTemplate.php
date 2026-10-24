<?php
namespace Models; 

class NotificationTemplate extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}