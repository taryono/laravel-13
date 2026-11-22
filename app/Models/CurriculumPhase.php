<?php
namespace Models;

class CurriculumPhase extends SoftDeletableModel
{
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}