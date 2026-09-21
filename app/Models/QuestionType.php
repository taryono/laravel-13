<?php

namespace Models; 

class QuestionType extends BasedModel
{ 
    // Relasi
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}