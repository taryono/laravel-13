<?php

namespace Models;

class AdmissionTestResult extends SoftDeletableModel
{
    // Relasi
    public function admissionTestSchedule()
    {
        return $this->belongsTo(admissionTestSchedule::class, 'admission_test_schedule_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function admissionPath()
    {
        return $this->belongsTo(AdmissionPath::class, 'admission_path_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function testType()
    {
        return $this->belongsTo(TestType::class, 'test_type_id');
    }

    public function cbtSession()
    {
        return $this->belongsTo(CbtSession::class, 'cbt_session_id');
    }

    public function cbtSessionAnswer()
    {
        return $this->belongsTo(CbtSessionAnswer::class, 'cbt_session_answer_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
