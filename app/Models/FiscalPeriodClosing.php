<?php
namespace Models; 

class FiscalPeriodClosing extends BasedModel
{
    // Relasi
        public function fiscalPeriod()
    {
        return $this->belongsTo(FiscalPeriod::class, 'fiscal_period_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}