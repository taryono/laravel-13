<?php
namespace Models;

class ApplicantAchievement extends SoftDeletableModel
{
    // Relasi
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}