<?php

namespace Models;

class ReportTemplateSignatory extends BasedModel
{
    protected $table = 'report_template_signatories';

    protected $guarded = ['id'];

    public function template()
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}

if (!class_exists('App\Models\ReportTemplateSignatory', false)) {
    class_alias(ReportTemplateSignatory::class, 'App\Models\ReportTemplateSignatory');
}
