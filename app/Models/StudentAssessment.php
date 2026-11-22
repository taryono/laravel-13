<?php
namespace Models;

class StudentAssessment extends SoftDeletableModel
{
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function assessmentComponent()
    {
        return $this->belongsTo(AssessmentComponent::class, 'assessment_component_id');
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