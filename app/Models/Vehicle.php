<?php
namespace Models; 

class Vehicle extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function gpsDevice()
    {
        return $this->belongsTo(GpsDevice::class, 'gps_device_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}