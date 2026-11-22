<?php
namespace Models;

class QuestionMedia extends SoftDeletableModel
{
    // Relasi
    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }

    public function questionOption()
    {
        return $this->belongsTo(QuestionOption::class, 'question_option_id');
    }

    public function questionStimulus()
    {
        return $this->belongsTo(QuestionStimulus::class, 'question_stimulus_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}