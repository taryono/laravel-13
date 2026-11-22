<?php
namespace Models;

class CmsBanner extends SoftDeletableModel
{
    // Relasi



    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}