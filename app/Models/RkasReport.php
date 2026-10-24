<?php
namespace Models; 

class RkasReport extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function rkasDocument()
    {
        return $this->belongsTo(RkasDocument::class, 'rkas_document_id');
    }

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