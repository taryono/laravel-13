<?php
namespace Models; 

class Tag extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}