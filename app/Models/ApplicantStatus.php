<?php

namespace Models;

class ApplicantStatus extends BasedModel
{
    protected $table = 'applicant_statuses';

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
