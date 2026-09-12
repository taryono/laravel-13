<?php

namespace Models; 

class Period extends BasedModel
{ 
    // Relasi
    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}