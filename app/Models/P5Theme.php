<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class P5Theme extends BasedModel
{

    protected $table = 'p5_themes';

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'description',
        'icon',
        'color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // ============ RELASI ============

    public function projects(): HasMany
    {
        return $this->hasMany(P5Project::class, 'theme_id');
    }

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
