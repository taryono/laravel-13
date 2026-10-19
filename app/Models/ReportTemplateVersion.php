<?php

namespace Models;

class ReportTemplateVersion extends BasedModel
{
    protected $table = 'report_template_versions';

    protected $guarded = ['id'];

    protected $casts = [
        'configuration' => 'array',
        'version' => 'integer',
    ];

    public function template()
    {
        return $this->belongsTo(ReportTemplate::class, 'template_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}

if (!class_exists('App\Models\ReportTemplateVersion', false)) {
    class_alias(ReportTemplateVersion::class, 'App\Models\ReportTemplateVersion');
}
