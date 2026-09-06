<?php

namespace Models; 

class BankAccount extends BasedModel
{ 
     protected $fillable = [
        'account_id',
        'bank_name',
        'account_number',
        'account_name',
        'currency_code',
        'current_balance',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
        'is_active'       => 'boolean',
        'is_default'      => 'boolean',
    ];

    // Relasi ke Chart of Accounts
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}