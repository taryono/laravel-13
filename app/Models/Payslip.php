<?php

namespace Models;

use App\Services\DocumentNumberGenerator;

class Payslip extends BasedModel
{
    protected $table = 'payslips';

    protected $fillable = [
        'payroll_detail_id',
        'employee_id',
        'organization_id',
        'payroll_period_id',
        'payslip_number',
        'total_allowance',
        'total_deduction',
        'gross_salary',
        'net_salary',
        'currency',
        'status',
        'generated_at',
        'pdf_path',
        'html_content',
        'is_sent',
        'sent_at',
        'is_downloaded',
        'downloaded_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'total_allowance' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'gross_salary'    => 'decimal:2',
        'net_salary'      => 'decimal:2',
        'generated_at'    => 'datetime',
        'sent_at'         => 'datetime',
        'downloaded_at'   => 'datetime',
        'is_sent'         => 'boolean',
        'is_downloaded'   => 'boolean',
    ];

    /**
     * Generate unique payslip code/number.
     */
    public function generateUniqueCode(?int $organizationId = null): string
    {
        $orgId = $organizationId
            ?? $this->organization_id
            ?? session('active_organization_id')
            ?? auth()->user()?->active_organization_id
            ?? 1;

        try {
            $generator = app(DocumentNumberGenerator::class);
            return $generator->generate($orgId, 'payslip');
        } catch (\Throwable $e) {
            $year = date('Y');
            $month = date('m');

            $last = static::withoutGlobalScopes()
                ->where('payslip_number', 'LIKE', 'PS/%/' . $year . $month . '/%')
                ->orWhere('payslip_number', 'LIKE', 'PS/' . $year . $month . '/%')
                ->orWhere('payslip_number', 'LIKE', 'SLIP-' . $year . '-' . $month . '-%')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($last && preg_match('/(\d+)$/', $last->payslip_number, $matches)) {
                $nextNumber = ((int) $matches[1]) + 1;
            }

            return 'PS/' . $year . $month . '/' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
        }
    }

    // Relasi
    public function payrollDetail()
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }

    public function payroll_detail()
    {
        return $this->payrollDetail();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function payroll_period()
    {
        return $this->payrollPeriod();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // Helper
    public function getStatusLabelAttribute(): string
    {
        $statusColors = [
            'draft'      => 'secondary',
            'generated'  => 'info',
            'sent'       => 'primary',
            'downloaded' => 'success',
            'void'       => 'danger',
        ];
        $color = $statusColors[$this->status] ?? 'secondary';
        $label = ucfirst($this->status ?? 'draft');

        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
    }

    public function getFormattedNetSalaryAttribute(): string
    {
        return ($this->currency ?? 'IDR') . ' ' . number_format((float) ($this->net_salary ?? 0), 0, ',', '.');
    }
}
