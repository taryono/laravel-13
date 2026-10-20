<?php
namespace Models; 

class Vendor extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function payableAccount()
    {
        return $this->belongsTo(PayableAccount::class, 'payable_account_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}