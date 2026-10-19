<?php

namespace Models;

class ReportDataSource extends BasedModel
{
    protected $table = 'report_data_sources';

    protected $fillable = [
        'organization_id',
        'key',
        'name',
        'category',
        'description',
        'fields',
        'sample_data',
        'is_system',
        'is_active',
        'order',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'fields'      => 'array',
            'sample_data' => 'array',
            'is_system'   => 'boolean',
            'is_active'   => 'boolean',
            'order'       => 'integer',
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getFieldsCountAttribute(): int
    {
        return is_array($this->fields) ? count($this->fields) : 0;
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'academic'    => 'Akademik',
            'pesantren'   => 'Kepesantrenan',
            'tahfidz'     => 'Tahfidz Al-Qur\'an',
            'attendance'  => 'Kehadiran',
            'character'   => 'Karakter & Adab',
            default       => ucfirst($this->category ?? 'Kustom'),
        };
    }
}

if (!class_exists(\App\Models\ReportDataSource::class, false)) {
    class_alias(ReportDataSource::class, \App\Models\ReportDataSource::class);
}
