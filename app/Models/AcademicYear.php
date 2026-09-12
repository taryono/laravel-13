<?php

namespace Models; 

class AcademicYear extends BasedModel
{ 
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}