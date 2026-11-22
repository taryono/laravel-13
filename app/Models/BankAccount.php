<?php

namespace Models;

class BankAccount extends SoftDeletableModel
{
    protected $fillable = [
        'chart_of_account_id',
        'school_id',
        'organization_id',
        'currency_id',
        'bank_name',
        'account_number',
        'account_name',
        'current_balance',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    // Relasi ke Chart of Accounts
    public function chart_of_account()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
