<?php

namespace Models;

class QuestionStimuli extends SoftDeletableModel
{
    protected $table = 'question_stimuli';

    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subject()
    {
        return $this->belongsToMany(Subject::class, 'question_stimuli_subject', 'question_stimuli_id', 'subject_id');
    }



    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
