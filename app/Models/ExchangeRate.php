<?php
namespace Models;

class ExchangeRate extends SoftDeletableModel
{
    // Relasi
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function baseCurrency()
    {
        return $this->belongsTo(BaseCurrency::class, 'base_currency_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}