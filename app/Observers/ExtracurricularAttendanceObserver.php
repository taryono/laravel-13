<?php

namespace App\Observers;

use Models\ExtracurricularAttendance;
use Illuminate\Support\Facades\Auth;

class ExtracurricularAttendanceObserver
{
    /**
     * Dijalankan sebelum record baru disimpan ke database (INSERT).
     */
    public function creating(ExtracurricularAttendance $attendance): void
    {
        if (Auth::check()) {
            $attendance->created_by ??= Auth::id();
            $attendance->updated_by ??= Auth::id();
        }
    }

    /**
     * Dijalankan sebelum record yang sudah ada diperbarui (UPDATE).
     */
    public function updating(ExtracurricularAttendance $attendance): void
    {
        if (Auth::check()) {
            $attendance->updated_by = Auth::id();
        }
    }

    /**
     * Dijalankan sebelum record di-soft delete (UPDATE deleted_at).
     * Berguna untuk mencatat siapa yang menghapus data absensi.
     */
    public function deleting(ExtracurricularAttendance $attendance): void
    {
        if (Auth::check() && !$attendance->isForceDeleting()) {
            $attendance->updated_by = Auth::id();
            $attendance->saveQuietly(); // Simpan updated_by tanpa memicu loop event updating
        }
    }

    /**
     * Dijalankan saat record yang di-soft delete dipulihkan kembali.
     */
    public function restoring(ExtracurricularAttendance $attendance): void
    {
        if (Auth::check()) {
            $attendance->updated_by = Auth::id();
        }
    }
}