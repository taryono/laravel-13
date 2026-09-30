<?php

namespace Models;

class ApplicantChoice extends BasedModel
{
    // Relasi
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

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
