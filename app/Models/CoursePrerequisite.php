<?php
namespace Models; 

class CoursePrerequisite extends BasedModel
{
    // Relasi
        public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function prerequisiteCourse()
    {
        return $this->belongsTo(PrerequisiteCourse::class, 'prerequisite_course_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}