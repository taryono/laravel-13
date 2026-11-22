<?php
namespace Models;

class StudentKrsItem extends SoftDeletableModel
{
    // Relasi
    public function studentKrs()
    {
        return $this->belongsTo(StudentKrs::class, 'student_krs_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}