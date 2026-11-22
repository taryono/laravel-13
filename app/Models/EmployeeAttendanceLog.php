<?php
namespace Models;

class EmployeeAttendanceLog extends SoftDeletableModel
{
    // Relasi
    public function employeeAttendance()
    {
        return $this->belongsTo(EmployeeAttendance::class, 'employee_attendance_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}