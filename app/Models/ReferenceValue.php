<?php

namespace Models; 

class ReferenceValue extends BasedModel
{ 
     protected $fillable = [
        'reference_type_id',
        'code',
        'name',
        'description',
        'badge_color',
        'badge_class',
        'text_color',
        'sort_order',
        'is_system',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_system'  => 'boolean',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function reference_type()
    {
        return $this->belongsTo(ReferenceType::class, 'reference_type_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}