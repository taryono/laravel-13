<?php

namespace Models;

class AdmissionPath extends BasedModel
{
    protected $table = 'admission_paths';

    protected $fillable = [
        'admission_id',
        'organization_id',
        'code',
        'name',
        'description',
        'quota',
        'min_score',
        'max_score',
        'requirements',
        'sort_order',
        'is_active',
        'open_date',
        'close_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'requirements' => 'array',
        'open_date'    => 'date',
        'close_date'   => 'date',
        'is_active'    => 'boolean',
        'min_score'    => 'float',
        'max_score'    => 'float',
    ];

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function quota_settings()
    {
        return $this->hasMany(AdmissionQuotaSetting::class, 'admission_path_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
