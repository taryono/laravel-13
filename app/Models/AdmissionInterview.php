<?php
namespace Models; 

class AdmissionInterview extends BasedModel
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

    public function applicant()
    {
        return $this->belongsTo(\App\Models\Applicant::class, 'applicant_id');
    }

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class, 'organization_id');
    }

    public function interviewer()
    {
        return $this->belongsTo(\App\Models\Interviewer::class, 'interviewer_id');
    }

    public function secondaryInterviewer()
    {
        return $this->belongsTo(\App\Models\SecondaryInterviewer::class, 'secondary_interviewer_id');
    }

    public function room()
    {
        return $this->belongsTo(\App\Models\Room::class, 'room_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}