<?php
namespace Models; 

class Warehouse extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'manager_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}