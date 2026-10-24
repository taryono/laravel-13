<?php
namespace Models; 

class GpsTelemetryLog extends BasedModel
{
    // Relasi
        public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}