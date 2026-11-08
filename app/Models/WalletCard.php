<?php
namespace Models; 

class WalletCard extends BasedModel
{
    // Relasi
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