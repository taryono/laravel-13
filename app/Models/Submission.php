<?php
namespace Models; 

class Submission extends BasedModel
{
    // Relasi
        public function assignmentQuiz()
    {
        return $this->belongsTo(AssignmentQuiz::class, 'assignment_quiz_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}