<?php

namespace Models; 

class Overtime extends BasedModel
{ 
    protected $fillable = [
        'employee_id',
        'attendance_daily_id', // ✅ penghubung
        'overtime_date',
        'start_time',
        'end_time',
        'total_hours',
        'overtime_rate',
        'total_amount',
        'notes',
        'approved_by',
        'approved_at',
        'is_paid',
        'payroll_period_id',
    ];

    protected $casts = [
        'overtime_date' => 'date:Y-m-d',
        'total_hours' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_paid' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Sumber data dari kehadiran harian
    public function attendanceDaily()
    {
        return $this->belongsTo(AttendanceDaily::class);
    }

    // Tetap hubungkan ke periode gaji
    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}