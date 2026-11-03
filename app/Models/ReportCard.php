<?php

namespace Models;

class ReportCard extends BasedModel
{
    protected $fillable = [
        'school_id',
        'student_id',
        'academic_year_id',
        'period_id',
        'class_level_id',
        'curriculum_id',
        'report_card_template_id',
        'student_curriculum_id',
        'code',
        'semester',
        'status',
        'average_score',
        'total_score',
        'rank_in_class',
        'rank_in_level',
        'total_subjects',
        'homeroom_note',
        'principal_note',
        'homeroom_teacher_id',
        'principal_id',
        'published_at',
        'locked_at',
        'is_active',
        'created_by',
        'updated_by',
        'average_score',
        'total_score',
        'rank_in_school',
        'total_character_score',
        'total_sports_score'
    ];

    protected $casts = [
        'status'       => 'string',
        'published_at' => 'datetime',
        'locked_at'    => 'datetime',
        'is_active'    => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    public function period()
    {
        return $this->belongsTo(ReportPeriod::class, 'period_id');
    }
    public function class_level()
    {
        return $this->belongsTo(ClassLevel::class);
    }
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }
    public function template()
    {
        return $this->belongsTo(ReportCardTemplate::class, 'report_card_template_id');
    }
    public function student_curriculum()
    {
        return $this->belongsTo(StudentCurriculum::class);
    }
    public function homeroom_teacher()
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }
    public function principal()
    {
        return $this->belongsTo(Teacher::class, 'principal_id');
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
    public function generated_reports()
    {
        return $this->hasMany(GeneratedReport::class);
    }

    public function current_generated_report()
    {
        return $this->hasOne(GeneratedReport::class)
            ->where('is_current', 1)
            ->where('is_active', 1);
    }

    // Scope
    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }
    public function scopeDraft($q)
    {
        return $q->where('status', 'draft');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

if (!class_exists('App\Models\ReportCard', false)) {
    class_alias(ReportCard::class, 'App\Models\ReportCard');
}

