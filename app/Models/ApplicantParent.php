<?php

namespace Models;

class ApplicantParent extends BasedModel
{
    protected $table = 'applicant_parents';

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
