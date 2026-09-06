<?php

namespace Models; 

class Deduction extends BasedModel
{ 
    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function deduction_category()
    {
        return $this->belongsTo(DeductionCategory::class);
    }

     public function payroll_period()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}