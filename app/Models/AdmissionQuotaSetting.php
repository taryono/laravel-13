<?php

namespace Models;

class AdmissionQuotaSetting extends SoftDeletableModel
{
    protected $table = 'admission_quota_settings';

    public static function bootSoftDeletes()
    {
        // Table has no deleted_at column
    }

    // Relasi
    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function path()
    {
        return $this->belongsTo(AdmissionPath::class, 'admission_path_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function study_program()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    public function education_level()
    {
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }

    public function class_level()
    {
        return $this->belongsTo(ClassLevel::class, 'class_level_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
