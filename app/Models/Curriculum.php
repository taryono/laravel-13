<?php

namespace Models;

class Curriculum extends BasedModel
{
    protected $table = 'curriculums';
    // Relasi
    public function report_card()
    {
        return $this->hasMany(ReportCard::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
