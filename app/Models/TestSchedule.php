<?php

namespace Models;

class TestSchedule extends BasedModel
{
    // Relasi
    public function test_type()
    {
        return $this->belongsTo(TestType::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
