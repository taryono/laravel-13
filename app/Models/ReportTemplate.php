<?php

namespace Models;

class ReportTemplate extends BasedModel
{
    protected $table = 'report_templates';

    protected $guarded = ['id'];

    protected $casts = [
        'configuration' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'custom_paper_width' => 'float',
        'custom_paper_height' => 'float',
        'margin_top' => 'float',
        'margin_right' => 'float',
        'margin_bottom' => 'float',
        'margin_left' => 'float',
    ];

    // Relations
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function versions()
    {
        return $this->hasMany(ReportTemplateVersion::class, 'template_id');
    }

    public function curriculums()
    {
        return $this->hasMany(ReportTemplateCurriculum::class, 'report_template_id');
    }

    public function signatories()
    {
        return $this->hasMany(ReportTemplateSignatory::class, 'report_template_id');
    }

    public function assets()
    {
        return $this->hasMany(TemplateAsset::class, 'template_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

if (!class_exists('App\Models\ReportTemplate', false)) {
    class_alias(ReportTemplate::class, 'App\Models\ReportTemplate');
}