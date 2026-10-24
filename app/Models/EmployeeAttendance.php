<?php
namespace Models; 

class EmployeeAttendance extends BasedModel
{
    // Relasi
        public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function workSchedule()
    {
        return $this->belongsTo(WorkSchedule::class, 'work_schedule_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function checkInDevice()
    {
        return $this->belongsTo(CheckInDevice::class, 'check_in_device_id');
    }

    public function checkOutDevice()
    {
        return $this->belongsTo(CheckOutDevice::class, 'check_out_device_id');
    }

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_request_id');
    }

    public function permissionRequest()
    {
        return $this->belongsTo(PermissionRequest::class, 'permission_request_id');
    }

    public function correction()
    {
        return $this->belongsTo(Correction::class, 'correction_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}