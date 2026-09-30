<?php

namespace Models;

class ApplicantDomicile extends BasedModel
{
    protected $table = 'applicant_domiciles';

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
