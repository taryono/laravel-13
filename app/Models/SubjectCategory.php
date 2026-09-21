<?php

namespace Models;

class SubjectCategory extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'description',
        'is_active',
        'sort_order',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
