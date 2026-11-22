<?php

namespace Models;

class AdmissionSelection extends SoftDeletableModel
{
    // Relasi
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function test_schedule()
    {
        return $this->belongsTo(TestSchedule::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
