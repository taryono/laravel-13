<?php
namespace Models; 

class TimetableConstraint extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}