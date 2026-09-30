<?php

namespace Models;

class ApplicantCategory extends BasedModel
{
    protected $table = 'applicant_categories';

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function parent()
    {
        return $this->belongsTo(ApplicantCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ApplicantCategory::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
