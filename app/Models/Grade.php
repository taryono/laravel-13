<?php
namespace Models;

class Grade extends SoftDeletableModel
{
    // Relasi
    public function studentClassroom()
    {
        return $this->belongsTo(StudentClassroom::class, 'student_classroom_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}