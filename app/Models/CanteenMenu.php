<?php
namespace Models; 

class CanteenMenu extends BasedModel
{
    // Relasi
        public function canteenTenant()
    {
        return $this->belongsTo(CanteenTenant::class, 'canteen_tenant_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}