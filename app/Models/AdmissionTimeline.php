<?php

namespace Models;

class AdmissionTimeline extends BasedModel
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
