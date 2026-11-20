<?php

namespace Models;

class Bonus extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'employee_id',
        'bonus_code',
        'bonus_type',
        'amount',
        'payment_date',
        'description',
        'payroll_period_id',
        'is_paid',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];
    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function payroll_period()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }
}