<?php

namespace Models;

class Module extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'category',
        'description',
        'icon',
        'is_active',
        'price',
        'created_by',
        'updated_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
