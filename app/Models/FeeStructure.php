<?php
namespace Models; 

class FeeStructure extends BasedModel
{
    // Relasi
        public function feeMaster()
    {
        return $this->belongsTo(FeeMaster::class, 'fee_master_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}