<?php
namespace Models; 

class ScholarshipDisbursement extends BasedModel
{
    // Relasi
        public function scholarshipRecipient()
    {
        return $this->belongsTo(ScholarshipRecipient::class, 'scholarship_recipient_id');
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function academicSemester()
    {
        return $this->belongsTo(AcademicSemester::class, 'academic_semester_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function paymentChannel()
    {
        return $this->belongsTo(PaymentChannel::class, 'payment_channel_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}