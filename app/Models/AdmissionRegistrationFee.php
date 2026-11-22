<?php
namespace Models;

class AdmissionRegistrationFee extends SoftDeletableModel
{
    // Relasi
    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function admissionPath()
    {
        return $this->belongsTo(AdmissionPath::class, 'admission_path_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}