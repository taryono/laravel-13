<?php
namespace Models;

class StudentTransportSubscription extends SoftDeletableModel
{
    // Relasi
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function transportRoute()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }

    public function transportStop()
    {
        return $this->belongsTo(TransportStop::class, 'transport_stop_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}