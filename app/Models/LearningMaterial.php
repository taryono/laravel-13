<?php
namespace Models;

class LearningMaterial extends SoftDeletableModel
{
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function lessonPlan()
    {
        return $this->belongsTo(LessonPlan::class, 'lesson_plan_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class, 'class_level_id');
    }

    public function curriculumPhase()
    {
        return $this->belongsTo(CurriculumPhase::class, 'curriculum_phase_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}