<?php
namespace Models; 

class SoroganEvaluation extends BasedModel
{
    // Relasi
        public function soroganSession()
    {
        return $this->belongsTo(SoroganSession::class, 'sorogan_session_id');
    }

    public function soroganSchedule()
    {
        return $this->belongsTo(SoroganSchedule::class, 'sorogan_schedule_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function kitabReference()
    {
        return $this->belongsTo(KitabReference::class, 'kitab_reference_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}