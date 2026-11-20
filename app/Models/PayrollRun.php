<?php

namespace Models; 

class PayrollRun extends BasedModel
{    
    protected $table = 'payroll_runs';
    
    protected $fillable = [
        'organization_id',
        'payroll_period_id',
        'run_number',
        'run_type',
        'code',
        'total_employees',
        'total_gross',
        'total_gross_salary',
        'total_deduction',
        'total_deductions',
        'total_company_contribution',
        'total_tax',
        'total_net',
        'total_net_salary',
        'status',
        'calculated_at',
        'approved_by',
        'approved_at',
        'posted_by',
        'posted_at',
        'locked_by',
        'locked_at',
        'processed_by',
        'processed_at',
        'notes',
        'created_by',
        'updated_by',
        'version',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'total_gross_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net_salary' => 'decimal:2',
    ];

    // Override generate code with custom format
    public function generateUniqueCode(): string
    {
        $period = PayrollPeriod::find($this->payroll_period_id);
        $year = $period ? $period->period_year : date('Y');
        $month = $period ? str_pad($period->period_month, 2, '0', STR_PAD_LEFT) : date('m');
        
        $last = $this->where('code', 'LIKE', 'RUN-' . $year . '-' . $month . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->code, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'RUN-' . $year . '-' . $month . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    // Relasi
    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function payrollDetails()
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function payslips()
    {
        return $this->hasManyThrough(Payslip::class, PayrollDetail::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scope
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}