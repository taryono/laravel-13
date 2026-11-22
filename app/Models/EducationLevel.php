<?php

namespace Models;

class EducationLevel extends SoftDeletableModel
{
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}