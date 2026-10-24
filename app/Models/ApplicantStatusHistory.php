<?php
namespace Models; 

class ApplicantStatusHistory extends BasedModel
{
    // Relasi
        public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function fromStatus()
    {
        return $this->belongsTo(FromStatus::class, 'from_status_id');
    }

    public function toStatus()
    {
        return $this->belongsTo(ToStatus::class, 'to_status_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}