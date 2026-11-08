<?php
namespace Models; 

class BankMutation extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
 
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    } 
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}