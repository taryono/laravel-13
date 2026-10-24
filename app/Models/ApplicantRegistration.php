<?php
namespace Models; 

class ApplicantRegistration extends BasedModel
{
    // Relasi
        public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function officer()
    {
        return $this->belongsTo(Officer::class, 'officer_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}