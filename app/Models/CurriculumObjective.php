<?php
namespace Models;

class CurriculumObjective extends SoftDeletableModel
{
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function learningOutcome()
    {
        return $this->belongsTo(LearningOutcome::class, 'learning_outcome_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}