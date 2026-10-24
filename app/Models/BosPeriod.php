<?php
namespace Models; 

class BosPeriod extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
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