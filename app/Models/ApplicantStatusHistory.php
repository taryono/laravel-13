<?php

namespace Models;

class ApplicantStatusHistory extends BasedModel
{
    protected $table = 'applicant_status_histories';

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
