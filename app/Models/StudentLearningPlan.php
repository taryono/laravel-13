<?php
namespace Models; 

class StudentLearningPlan extends BasedModel
{
     protected $fillable = [
        'school_id',
        'student_id',
        'curriculum_id',
        'academic_year_id',
        'age_group',
        'plan_data',
        'start_date',
        'end_date',
        'is_active',
     ];

    protected $casts = [
        'plan_data' => 'array', 
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}