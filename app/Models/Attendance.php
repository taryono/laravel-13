<?php

namespace Models; 

class Attendance extends BasedModel
{   
    protected $fillable = [
        'employee_id',
        'attendance_daily_id',
        'payroll_period_id',
        'attendance_date', 
        'overtime_hours',
        'status',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'attendance_date' => 'date:Y-m-d',
        'approved_at'     => 'datetime',
    ];

    public const STATUS_PRESENT = 'present';
    public const STATUS_SICK = 'sick';
    public const STATUS_EXCUSED = 'excused';
    public const STATUS_ABSENT_UNEXCUSED = 'absent_unexcused';
    public const STATUS_ON_LEAVE = 'on_leave';
    public const STATUS_BUSINESS_TRIP = 'business_trip';

    public const ATTENDANCE_STATUSES = [
        self::STATUS_PRESENT,
        self::STATUS_SICK,
        self::STATUS_EXCUSED,
        self::STATUS_ABSENT_UNEXCUSED,
        self::STATUS_ON_LEAVE,
        self::STATUS_BUSINESS_TRIP,
    ];

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    } 

    public function attendanceDaily()
    {
        return $this->belongsTo(attendanceDaily::class, 'employee_id');
    } 
}