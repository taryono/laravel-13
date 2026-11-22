<?php

namespace Models;

class Dictionary extends SoftDeletableModel
{
    protected $fillable = [
        'key',
        'group',
        'en',
        'id',
        'metadata',
        'is_active'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get translation by locale
     */
    public function translate(?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();

        return match ($locale) {
            'in' => $this->in,
            'en' => $this->en,
            default => $this->en
        };
    }

    /**
     * Scope untuk filter aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter group
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}