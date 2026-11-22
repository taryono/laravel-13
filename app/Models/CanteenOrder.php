<?php
namespace Models;

class CanteenOrder extends SoftDeletableModel
{
    // Relasi
    public function canteenTenant()
    {
        return $this->belongsTo(CanteenTenant::class, 'canteen_tenant_id');
    }

    public function digitalWallet()
    {
        return $this->belongsTo(DigitalWallet::class, 'digital_wallet_id');
    }
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}