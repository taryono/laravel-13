<?php

namespace Models;

class ClassLevel extends BasedModel
{
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
