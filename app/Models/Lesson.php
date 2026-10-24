<?php
namespace Models; 

class Lesson extends BasedModel
{
    // Relasi
        public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}