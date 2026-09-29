<?php

namespace Models;

class ApplicantAchievement extends BasedModel
{
    protected $table = 'applicant_achievements';

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
