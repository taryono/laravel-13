<?php

namespace Models;

class EmployeeLeave extends BasedModel
{
    // Relasi
    public function leave_type()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
