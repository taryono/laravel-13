<?php
namespace Models;

class QuestionOption extends SoftDeletableModel
{
    // Relasi
    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}