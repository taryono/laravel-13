<?php
namespace Models;

class StudentRfidCard extends SoftDeletableModel
{
    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}