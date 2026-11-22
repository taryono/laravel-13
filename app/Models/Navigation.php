<?php
namespace Models;

class Navigation extends SoftDeletableModel
{
    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function parent()
    {
        return $this->belongsTo(parent::class, 'parent_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}