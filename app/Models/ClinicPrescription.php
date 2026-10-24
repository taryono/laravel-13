<?php
namespace Models; 

class ClinicPrescription extends BasedModel
{
    // Relasi
        public function clinicVisit()
    {
        return $this->belongsTo(ClinicVisit::class, 'clinic_visit_id');
    }

    public function clinicMedicine()
    {
        return $this->belongsTo(ClinicMedicine::class, 'clinic_medicine_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}