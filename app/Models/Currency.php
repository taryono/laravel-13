<?php

namespace Models;

class Currency extends BasedModel
{
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'decimal_places',
        'is_base',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'decimal_places' => 'integer',
        'is_base' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relasi
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }
}
