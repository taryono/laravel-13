<?php

namespace Models;

class Curriculum extends SoftDeletableModel
{
    protected $table = 'curriculums';
    // Relasi
    protected $fillable = [
        'organization_id',
        'school_id',
        'report_card_id',
        'name',
        'code',
        'type',
        'description',
        'start_year',
        'end_year',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function report_card()
    {
        return $this->hasMany(ReportCard::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
