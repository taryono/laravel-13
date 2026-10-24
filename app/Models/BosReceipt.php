<?php
namespace Models; 

class BosReceipt extends BasedModel
{
    // Relasi
        public function bosPeriod()
    {
        return $this->belongsTo(BosPeriod::class, 'bos_period_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}