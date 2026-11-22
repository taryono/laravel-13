<?php
namespace Models;

class WalletTransaction extends SoftDeletableModel
{
    // Relasi
    public function digitalWallet()
    {
        return $this->belongsTo(DigitalWallet::class, 'digital_wallet_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}