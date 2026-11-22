<?php
namespace Models;

class PayrollDetailComponent extends SoftDeletableModel
{
    // Relasi
    public function payrollDetail()
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }

    public function salaryComponent()
    {
        return $this->belongsTo(SalaryComponent::class, 'salary_component_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}