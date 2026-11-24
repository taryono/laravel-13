<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Observers\ExtracurricularAttendanceObserver;

#[ObservedBy([ExtracurricularAttendanceObserver::class])]
class ExtracurricularAttendance extends SoftDeletableModel
{
    // Scope
    protected $fillable = [
        'student_extracurricular_id',
        'extracurricular_day_id',
        'meeting_date',
        'status',
        'notes',
        'recorded_by',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Sembunyikan kolom helper internal dari serialisasi JSON/API.
     *
     * @var list<string>
     */
    protected $hidden = [
        'is_not_deleted',
    ];

    /**
     * Konvensi method casts() di Laravel 11/12/13.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',
            'status' => AttendanceStatus::class,
            'is_active' => 'boolean',
            'is_not_deleted' => 'boolean',
        ];
    }

    /**
     * Otomatis mengisi created_by dan updated_by dari user yang sedang login.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if (Auth::check()) {
                $model->created_by ??= Auth::id();
                $model->updated_by ??= Auth::id();
            }
        });

        static::updating(function (self $model): void {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    public function studentExtracurricular(): BelongsTo
    {
        return $this->belongsTo(StudentExtracurricular::class, 'student_extracurricular_id');
    }

    public function extracurricularDay(): BelongsTo
    {
        return $this->belongsTo(ExtracurricularDay::class, 'extracurricular_day_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'recorded_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    // =========================================================================
    // QUERY SCOPES
    // =========================================================================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOnDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('meeting_date', $date);
    }

    public function scopeForDayAndDate(Builder $query, int $dayId, string $date): Builder
    {
        return $query->where('extracurricular_day_id', $dayId)
            ->whereDate('meeting_date', $date);
    }
}
