<?php
namespace Models;

class Gender extends SoftDeletableModel
{
    // Relasi



    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}