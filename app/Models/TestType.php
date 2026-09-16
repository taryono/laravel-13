<?php

namespace Models;

class TestType extends BasedModel
{
    // Relasi
    public function test_schedule()
    {
        return $this->hasMany(TestSchedule::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
