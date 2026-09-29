<?php

namespace Models;

class EnrollmentConversion extends BasedModel
{
    protected $table = 'enrollment_conversions';

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
}
