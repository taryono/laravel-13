<?php

namespace Models;

class ReportTemplateCurriculum extends BasedModel
{
    protected $table = 'report_template_curriculums';

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}

if (!class_exists('App\Models\ReportTemplateCurriculum', false)) {
    class_alias(ReportTemplateCurriculum::class, 'App\Models\ReportTemplateCurriculum');
}
