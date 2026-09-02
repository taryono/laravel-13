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