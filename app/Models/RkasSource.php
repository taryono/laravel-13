<?php
namespace Models; 

class RkasSource extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}