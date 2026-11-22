<?php
namespace Models;

class CurriculumCourse extends SoftDeletableModel
{
    // Relasi
    public function study_program()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}