<?php
namespace Models;

class LessonProgress extends SoftDeletableModel
{
    // Relasi
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}