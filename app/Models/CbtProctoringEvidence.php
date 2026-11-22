<?php
namespace Models;

class CbtProctoringEvidence extends SoftDeletableModel
{
    // Relasi
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}