<?php
namespace Models; 

class CanteenTenantUser extends BasedModel
{
    // Relasi
        public function canteenTenant()
    {
        return $this->belongsTo(CanteenTenant::class, 'canteen_tenant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}