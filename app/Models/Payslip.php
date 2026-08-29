<?php

namespace Models; 

class Payslip extends BasedModel {
     
    protected $table = 'payslips';
    
    protected $fillable = [
        'payroll_detail_id',
        'employee_id',
        'payroll_period_id',
        'payslip_number',
        'generated_at',
        'pdf_path',
        'is_sent',
        'sent_at',
        'is_downloaded'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
        'is_downloaded' => 'boolean',
    ];

    // Override generate code
    public function generateUniqueCode(): string
    {
        $year = date('Y');
        $month = date('m');
        
        $last = $this->where('payslip_number', 'LIKE', 'SLIP-' . $year . '-' . $month . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->payslip_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'SLIP-' . $year . '-' . $month . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Relasi
    public function payrollDetail()
    {
        return $this->belongsTo(PayrollDetail::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    // Helper
    public function getStatusLabelAttribute()
    {
        if ($this->is_sent) {
            return '<span class="badge bg-success">Terkirim</span>';
        }
        return '<span class="badge bg-warning">Belum Dikirim</span>';
    }
}