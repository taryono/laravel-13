<?php
namespace Models; 

class CbtScheduleFeature extends BasedModel
{
    // Relasi
        public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}