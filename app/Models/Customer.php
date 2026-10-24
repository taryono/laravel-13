<?php
namespace Models; 

class Customer extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function receivableAccount()
    {
        return $this->belongsTo(ReceivableAccount::class, 'receivable_account_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}