<?php

namespace Models; 

class AttendanceDaily extends BasedModel
{ 
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'shift_id',
        'payroll_period_id',
        'first_scan',
        'last_scan',
        'scheduled_check_in',
        'scheduled_check_out',
        'actual_check_in',
        'actual_check_out',
        'total_hours_worked',
        'regular_hours',
        'overtime_hours',
        'late_minutes',
        'early_leave_minutes',
        'break_hours',
        'attendance_status',
        'is_approved',
        'approved_by',
        'approved_at',
        'adjustment_notes',
        'notes',
    ];

    protected $casts = [
        'attendance_date'       => 'date:Y-m-d',
        'first_scan'            => 'datetime',
        'last_scan'             => 'datetime',
        'scheduled_check_in'    => 'datetime:H:i',
        'scheduled_check_out'   => 'datetime:H:i',
        'actual_check_in'       => 'datetime', // DATETIME — aman lintas hari
        'actual_check_out'      => 'datetime',
        'total_hours_worked'    => 'decimal:2',
        'regular_hours'         => 'decimal:2',
        'overtime_hours'        => 'decimal:2',
        'late_minutes'          => 'integer',
        'early_leave_minutes'   => 'integer',
        'break_hours'           => 'decimal:2',
        'is_approved'           => 'boolean',
        'approved_at'           => 'datetime',
    ];

    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class, 'attendance_daily_id');
    }
 
    // Cek apakah jam lembur ini sudah disahkan ke tabel overtimes
    public function overtime()
    {
        return $this->hasOne(Overtime::class);
    }

    // Accessor: Cek otomatis apakah ada usulan lembur
    public function getHasOvertimeProposalAttribute()
    {
        return $this->overtime_hours > 0 && is_null($this->overtime);
    }
}