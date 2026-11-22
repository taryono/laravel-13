<?php

namespace Models;

class DeductionCategory extends SoftDeletableModel
{
    // Relasi
    public function deduction()
    {
        return $this->hasMany(Deduction::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}