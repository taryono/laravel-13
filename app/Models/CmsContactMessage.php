<?php
namespace Models;

class CmsContactMessage extends SoftDeletableModel
{
    // Relasi



    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}