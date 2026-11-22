<?php

namespace Models;

class AttendanceImport extends SoftDeletableModel
{
    protected $table = 'attendance_import_batches';
    protected $fillable = [
        'batch_code',
        'file_name',
        'file_size',
        'file_path',
        'machine_source',
        'total_records',
        'processed_records',
        'failed_records',
        'status',
        'started_at',
        'completed_at',
        'errors',
        'imported_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'total_records' => 'integer',
        'processed_records' => 'integer',
        'failed_records' => 'integer',
        'errors' => 'array', // Otomatis JSON ↔ Array
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relasi
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'import_batch', 'batch_code');
    }
}