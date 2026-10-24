<?php
namespace Models; 

class QuestionMediaAnnotation extends BasedModel
{
    // Relasi
        public function questionMedia()
    {
        return $this->belongsTo(QuestionMedia::class, 'question_media_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}