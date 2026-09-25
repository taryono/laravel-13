<?php

namespace Models;

class TestSchedule extends BasedModel
{
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function admission_selection()
    {
        return $this->belongsTo(AdmissionSelection::class);
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function cbtSession()
    {
        return $this->belongsTo(CBTSession::class);
    }

    public function examPackage()
    {
        return $this->belongsTo(ExamPackage::class);
    }

    // public function supervisor()
    // {
    //     return $this->belongsTo(Supervisor::class);
    // }

    // public function coordinator()
    // {
    //     return $this->belongsTo(Coordinator::class);
    // } 

    public function test_type()
    {
        return $this->belongsTo(TestType::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
