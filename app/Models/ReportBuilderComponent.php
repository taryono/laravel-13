<?php

namespace Models;

class ReportBuilderComponent extends SoftDeletableModel
{
    protected $table = 'report_builder_components';

    protected $guarded = ['id'];

    protected $casts = [
        'default_config' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // Relations
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

if (!class_exists('App\Models\ReportBuilderComponent', false)) {
    class_alias(ReportBuilderComponent::class, 'App\Models\ReportBuilderComponent');
}