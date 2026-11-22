<?php

namespace Models;

class AdmissionAnnouncement extends SoftDeletableModel
{
    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function admission_path()
    {
        return $this->belongsTo(AdmissionPath::class);
    }

    public function education_level()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function study_program()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
