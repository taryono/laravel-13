<?php
// app/Models/GeneratedReport.php

namespace Models;

class GeneratedReport extends BasedModel
{
    protected $fillable = [
        'school_id',
        'student_id',
        'period_id',
        'report_card_template_id',
        'curriculum_id',
        'pdf_path',
        'report_data',
        'generated_at',
        'downloaded_count',
        'last_download_at',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'report_data' => 'array',
        'generated_at' => 'datetime',
        'last_download_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function template()
    {
        return $this->belongsTo(ReportCardTemplate::class, 'report_card_template_id');
    }
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }
    public function period()
    {
        return $this->belongsTo(ReportPeriod::class, 'period_id');
    }
    public function grades()
    {
        return $this->hasMany(ReportCardGrade::class);
    }
    public function notes()
    {
        return $this->hasMany(ReportCardNote::class);
    }
    public function signatures()
    {
        return $this->hasMany(ReportCardSignature::class);
    }
}
