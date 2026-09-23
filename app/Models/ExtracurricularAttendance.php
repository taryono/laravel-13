<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtracurricularAttendance extends BasedModel
{
    // Scope
    protected $fillable = [
        'extracurricular_id',
        'extracurricular_day_id',
        'student_id',
        'attendance_date',
        'status',
        'score',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'attendance_date' => 'date:Y-m-d',
        'score' => 'decimal:2',
    ];

    public function extracurricular(): BelongsTo
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function scheduleDay(): BelongsTo
    {
        return $this->belongsTo(ExtracurricularDay::class, 'extracurricular_day_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
