<?php
namespace Models; 

class StudentRubricScore extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function studentAssessment()
    {
        return $this->belongsTo(StudentAssessment::class, 'student_assessment_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}