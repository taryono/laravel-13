<?php
namespace Models; 

class AssignmentsQuiz extends BasedModel
{
    // Relasi
        public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
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