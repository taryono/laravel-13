<?php
namespace Models; 

class AdmissionQuotaSetting extends BasedModel
{
    // Relasi
        public function admission()
    {
        return $this->belongsTo(\App\Models\Admission::class, 'admission_id');
    }

    public function admissionPath()
    {
        return $this->belongsTo(\App\Models\AdmissionPath::class, 'admission_path_id');
    }

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class, 'organization_id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(\App\Models\StudyProgram::class, 'study_program_id');
    }

    public function faculty()
    {
        return $this->belongsTo(\App\Models\Faculty::class, 'faculty_id');
    }

    public function educationLevel()
    {
        return $this->belongsTo(\App\Models\EducationLevel::class, 'education_level_id');
    }

    public function classLevel()
    {
        return $this->belongsTo(\App\Models\ClassLevel::class, 'class_level_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}