<?php

namespace Models;

class ApplicantRegistration extends BasedModel
{
    protected $table = 'applicant_registrations';

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
}
