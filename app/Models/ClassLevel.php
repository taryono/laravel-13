<?php

namespace Models;

class ClassLevel extends BasedModel
{
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function class_level()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    // Relasi
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
