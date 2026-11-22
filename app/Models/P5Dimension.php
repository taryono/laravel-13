<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class P5Dimension extends SoftDeletableModel
{

    protected $table = 'p5_dimensions';

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

    public function elements(): HasMany
    {
        return $this->hasMany(P5Element::class, 'dimension_id')->orderBy('order');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            P5Project::class,
            'p5_project_dimensions',
            'dimension_id',
            'project_id'
        )->withPivot(['element_ids', 'weight', 'notes'])->withTimestamps();
    }

    public function assessmentDetails(): HasMany
    {
        return $this->hasMany(P5AssessmentDetail::class, 'dimension_id');
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

    // ============ HELPERS ============

    /**
     * Label capaian standar untuk dimensi ini.
     */
    public static function levelLabels(): array
    {
        return [
            'sangat_berkembang' => 'Sangat Berkembang',
            'berkembang_sesuai_harapan' => 'Berkembang Sesuai Harapan',
            'mulai_berkembang' => 'Mulai Berkembang',
            'belum_berkembang' => 'Belum Berkembang',
        ];
    }

    public function getLevelBadgeAttribute(): array
    {
        return [
            'sangat_berkembang' => 'success',
            'berkembang_sesuai_harapan' => 'primary',
            'mulai_berkembang' => 'warning',
            'belum_berkembang' => 'danger',
        ];
    }
}
