<?php

namespace Models;

class Admission extends BasedModel
{
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function class_level()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
