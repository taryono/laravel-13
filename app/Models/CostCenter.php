<?php

namespace Models;

class CostCenter extends SoftDeletableModel
{
    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function parent()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}