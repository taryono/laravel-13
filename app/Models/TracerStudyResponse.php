<?php
namespace Models; 

class TracerStudyResponse extends BasedModel
{
    // Relasi
        public function tracerStudy()
    {
        return $this->belongsTo(TracerStudy::class, 'tracer_study_id');
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}