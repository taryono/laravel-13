<?php

namespace Models; 

class StudentHealthRecord extends BasedModel
{ 
    protected $fillable = [
        'student_id', 
        'school_id', 
        'record_date', 
        'complaint', 
        'diagnosis', 
        'treatment', 
        'height_cm', 
        'weight_kg', 
        'blood_type', 
        'blood_pressure', 
        'allergies', 
        'vaccination_notes', 
        'handled_by', 
        'referred_to', 
        'is_active', 
        'created_by', 
        'updated_by'
    ];
    // Relasi
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}