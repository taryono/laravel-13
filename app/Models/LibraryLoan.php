<?php
namespace Models; 

class LibraryLoan extends BasedModel
{
    // Relasi
        public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

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