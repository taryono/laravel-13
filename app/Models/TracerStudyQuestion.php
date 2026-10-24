<?php
namespace Models; 

class TracerStudyQuestion extends BasedModel
{
    // Relasi
        public function tracerStudy()
    {
        return $this->belongsTo(TracerStudy::class, 'tracer_study_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}