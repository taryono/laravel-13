<?php

namespace Models;

class AdmissionDocumentVerification extends BasedModel
{
    // Relasi
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function applicationDocument()
    {
        return $this->belongsTo(ApplicationDocument::class, 'application_document_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
