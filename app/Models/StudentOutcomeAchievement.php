<?php
namespace Models; 

class StudentOutcomeAchievement extends BasedModel
{
    // Relasi
        public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function learningOutcome()
    {
        return $this->belongsTo(LearningOutcome::class, 'learning_outcome_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
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