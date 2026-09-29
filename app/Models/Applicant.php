<?php

namespace Models; 

class Applicant extends BasedModel
{ 
    protected $table = 'applicants';

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function domicile()
    {
        return $this->hasOne(ApplicantDomicile::class, 'applicant_id');
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class, 'applicant_id');
    }

    public function verifications()
    {
        return $this->hasMany(AdmissionDocumentVerification::class, 'applicant_id');
    }

    public function selections()
    {
        return $this->hasMany(AdmissionSelection::class, 'applicant_id');
    }

    public function achievements()
    {
        return $this->hasMany(ApplicantAchievement::class, 'applicant_id');
    }

    public function supportPrograms()
    {
        return $this->hasMany(ApplicantSupportProgram::class, 'applicant_id');
    }

    public function parents()
    {
        return $this->hasMany(ApplicantParent::class, 'applicant_id');
    }

    public function invoices()
    {
        return $this->hasMany(ApplicantInvoice::class, 'applicant_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}