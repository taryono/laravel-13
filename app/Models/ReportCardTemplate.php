<?php
// app/Models/ReportCardTemplate.php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportCardTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'template_data',
        'is_default',
        'is_active',
        'version',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'template_data' => 'array',
        'is_default'    => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function curriculums()
    {
        return $this->belongsToMany(
            Curriculum::class,
            'report_card_template_curriculums',
            'report_card_template_id',
            'curriculum_id'
        )->withPivot('is_active')->withTimestamps();
    }
}
