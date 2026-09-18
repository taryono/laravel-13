<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionCriteria extends BasedModel
{
    protected $table = 'promotion_criteria';

    protected $fillable = [
        'academic_year_id',
        'class_level_id',
        'min_final_score',
        'min_attendance_rate',
        'min_behavior_score',
        'max_failed_subjects',
        'auto_promote',
        'allow_manual_override',
        'notes',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'min_final_score' => 'decimal:2',
        'min_attendance_rate' => 'decimal:2',
        'min_behavior_score' => 'decimal:2',
        'max_failed_subjects' => 'integer',
        'auto_promote' => 'boolean',
        'allow_manual_override' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ============ RELASI ============

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classLevel(): BelongsTo
    {
        return $this->belongsTo(ClassLevel::class);
    }

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    public function scopeForLevel($query, $classLevelId)
    {
        return $query->where(function ($q) use ($classLevelId) {
            $q->where('class_level_id', $classLevelId)
                ->orWhereNull('class_level_id');
        });
    }

    // ============ HELPERS ============

    /**
     * Cek apakah siswa memenuhi kriteria naik kelas.
     */
    public function isEligibleForPromotion(
        float $finalScore,
        float $attendanceRate,
        float $behaviorScore,
        int $failedSubjects = 0
    ): bool {
        if ($this->min_final_score !== null && $finalScore < $this->min_final_score) {
            return false;
        }

        if ($this->min_attendance_rate !== null && $attendanceRate < $this->min_attendance_rate) {
            return false;
        }

        if ($this->min_behavior_score !== null && $behaviorScore < $this->min_behavior_score) {
            return false;
        }

        if ($failedSubjects > $this->max_failed_subjects) {
            return false;
        }

        return true;
    }
}
