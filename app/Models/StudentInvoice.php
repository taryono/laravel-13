<?php
namespace Models;

class StudentInvoice extends SoftDeletableModel
{
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function feeMaster()
    {
        return $this->belongsTo(FeeMaster::class, 'fee_master_id');
    }

    public function studentClassroom()
    {
        return $this->belongsTo(StudentClassroom::class, 'student_classroom_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}