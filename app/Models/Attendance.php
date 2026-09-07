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
    // hadir,sakit,izin,alpha,cuti,dinas_luar,late,half_day
    // 'present', 'sick', 'excused', 'absent_unexcused', 'on_leave', 'business_trip', 'late', 'half_day'
    public const STATUS_PRESENT = 'present';
    public const STATUS_SICK = 'sick';
    public const STATUS_EXCUSED = 'excused';
    public const STATUS_ABSENT_UNEXCUSED = 'absent_unexcused';
    public const STATUS_ON_LEAVE = 'on_leave';
    public const STATUS_BUSINESS_TRIP = 'business_trip';
    public const STATUS_LATE = 'late';
    public const STATUS_HALF_DAY = 'half_day';

    public const ATTENDANCE_STATUSES = [
        self::STATUS_PRESENT,
        self::STATUS_SICK,
        self::STATUS_EXCUSED,
        self::STATUS_ABSENT_UNEXCUSED,
        self::STATUS_ON_LEAVE,
        self::STATUS_BUSINESS_TRIP,
        self::STATUS_LATE,
        self::STATUS_HALF_DAY
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