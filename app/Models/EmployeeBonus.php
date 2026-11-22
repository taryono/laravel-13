<?php
namespace Models;

class EmployeeBonus extends SoftDeletableModel
{
    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function payrollDetail()
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}