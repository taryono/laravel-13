<?php
namespace Models; 

class CurriculumLearningOutcome extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function curriculumPhase()
    {
        return $this->belongsTo(CurriculumPhase::class, 'curriculum_phase_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}