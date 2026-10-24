<?php
namespace Models; 

class CurriculumCourse extends BasedModel
{
    // Relasi
        public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}