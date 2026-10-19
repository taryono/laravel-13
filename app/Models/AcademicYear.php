<?php

namespace Models; 

class AcademicYear extends BasedModel
{ 
    protected $appends = ['start_year', 'end_year', 'formatted_name'];

    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    // Accessors
    public function getStartYearAttribute()
    {
        return !empty($this->start_date) ? date('Y', strtotime($this->start_date)) : null;
    }

    public function getEndYearAttribute()
    {
        return !empty($this->end_date) ? date('Y', strtotime($this->end_date)) : null;
    }

    public function getFormattedNameAttribute(): string
    {
        if (!empty($this->name)) {
            return $this->name;
        }
        if (!empty($this->start_date)) {
            $start = date('Y', strtotime($this->start_date));
            $end = !empty($this->end_date) ? date('Y', strtotime($this->end_date)) : ($start + 1);
            return "{$start}/{$end}";
        }
        return (string) $this->id;
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

if (!class_exists(\App\Models\AcademicYear::class, false)) {
    class_alias(AcademicYear::class, \App\Models\AcademicYear::class);
}