<?php
namespace Models; 

class WalletTopup extends BasedModel
{
    // Relasi
        public function digitalWallet()
    {
        return $this->belongsTo(DigitalWallet::class, 'digital_wallet_id');
    }

    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class, 'wallet_transaction_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}