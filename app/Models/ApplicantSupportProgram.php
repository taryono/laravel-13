<?php

namespace Models;

class ApplicantSupportProgram extends BasedModel
{
    protected $table = 'applicant_support_programs';

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
