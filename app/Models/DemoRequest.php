<?php
namespace Models;

class DemoRequest extends SoftDeletableModel
{
    // Relasi



    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}