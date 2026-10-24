<?php
namespace Models; 

class PesantrenActivity extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function dormitory()
    {
        return $this->belongsTo(Dormitory::class, 'dormitory_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function kitabReference()
    {
        return $this->belongsTo(KitabReference::class, 'kitab_reference_id');
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


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}