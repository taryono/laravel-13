<?php

namespace Models;

class Admission extends BasedModel
{
    protected $table = 'admissions';

    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function class_level()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function paths()
    {
        return $this->hasMany(AdmissionPath::class, 'admission_id');
    }

    public function timelines()
    {
        return $this->hasMany(AdmissionTimeline::class, 'admission_id')->orderBy('order_number');
    }

    public function quotaSettings()
    {
        return $this->hasMany(AdmissionQuotaSetting::class, 'admission_id');
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'admission_id');
    }

    public function selections()
    {
        return $this->hasMany(AdmissionSelection::class, 'admission_id');
    }

    public function documentVerifications()
    {
        return $this->hasMany(AdmissionDocumentVerification::class, 'admission_id');
    }

    public function announcements()
    {
        return $this->hasMany(AdmissionAnnouncement::class, 'admission_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
