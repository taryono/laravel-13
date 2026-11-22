<?php

namespace Models;

class PayrollDetail extends SoftDeletableModel
{
    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        'employee_number_snapshot',
        'employee_name_snapshot',
        'department_name_snapshot',
        'position_name_snapshot',
        'gross_salary',
        'total_deduction',
        'total_company_contribution',
        'total_tax',
        'net_salary',
        'component_details',
        'calculation_metadata',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'gross_salary' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'total_company_contribution' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'component_details' => 'array',
        'calculation_metadata' => 'array',
    ];

    // Relasi
    public function payslip()
    {
        return $this->hasOne(Payslip::class, 'payroll_detail_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }
}
