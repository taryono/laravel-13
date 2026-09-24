<?php

namespace Models;

class ReportCardSignature extends BasedModel
{
    protected $fillable = [
        'school_id',
        'generated_report_id',
        'role_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function generatedReport()
    {
        return $this->belongsTo(GeneratedReport::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
