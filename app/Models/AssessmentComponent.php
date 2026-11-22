<?php
namespace Models;

class AssessmentComponent extends SoftDeletableModel
{
    // Relasi
    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}