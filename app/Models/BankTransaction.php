<?php

namespace Models;

class BankTransaction extends SoftDeletableModel
{
    protected $fillable = [
        'bank_account_id',
        'transaction_date',
        'transaction_type',
        'amount',
        'description',
        'reference_number',
        'is_cleared',
        'cleared_at',
        'notes',
        'created_by',
        'updated_by',
    ];

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
