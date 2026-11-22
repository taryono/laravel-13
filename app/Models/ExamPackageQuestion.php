<?php
namespace Models;

class ExamPackageQuestion extends SoftDeletableModel
{
    // Relasi
    public function examPackage()
    {
        return $this->belongsTo(ExamPackage::class, 'exam_package_id');
    }

    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}