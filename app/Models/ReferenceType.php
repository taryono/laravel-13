<?php

namespace Models; 

class ReferenceType extends BasedModel
{ 
    // Relasi
   protected $table = 'reference_types';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_system',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function values()
    {
        return $this->hasMany(ReferenceValue::class, 'reference_type_id')->orderBy('sort_order');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}