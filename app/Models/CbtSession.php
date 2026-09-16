<?php

namespace Models; 

class CbtSession extends BasedModel
{ 
    protected $table = 'cbt_sessions';
    protected $fillable = [
        'exam_package_id', 'student_id', 'schedule_id', 'class_id',
        'start_time', 'end_time', 'duration_seconds', 'current_question_index',
        'answers', 'status', 'trust_score', 'ip_address', 'user_agent',
        'proctor_notes', 'is_submitted', 'submitted_at', 'final_score',
        'is_active', 'last_heartbeat'
    ];
    protected $casts = [
        'answers' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'submitted_at' => 'datetime',
        'last_heartbeat' => 'datetime',
    ];

    public function student(): BelongsTo { return $this->belongsTo(Student::class, 'student_id'); }
    public function examPackage(): BelongsTo { return $this->belongsTo(ExamPackage::class, 'exam_package_id'); }
    public function activityLogs(): HasMany { return $this->hasMany(CbtActivityLog::class, 'session_id'); }
    public function proctoringRecords(): HasMany { return $this->hasMany(CbtProctoring::class, 'session_id'); }

    // Skor kepercayaan berbasis log
    public function calculateTrustScore(): float
    {
        $base = 100;
        $penalties = $this->activityLogs()->get()->map(fn($log) => match($log->severity) {
            'critical' => 25, 'danger' => 15, 'warning' => 5, default => 0
        })->sum();
        return max(0, $base - $penalties);
    }
}