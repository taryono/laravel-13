<?php

namespace Models;

class AttendanceLog extends SoftDeletableModel
{
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'scan_time',
        'scan_type',
        'machine_id',
        'import_batch',
        'raw_data',
        'is_processed',
        'processed_at',
        'processed_by',
        'validation_status',
        'validation_notes',
    ];

    protected $casts = [
        'attendance_date' => 'date:Y-m-d',
        'scan_time' => 'datetime',
        'raw_data' => 'array',
        'is_processed' => 'boolean',
        'processed_at' => 'datetime',
    ];

    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function importBatch()
    {
        return $this->belongsTo(AttendanceImportBatch::class, 'import_batch', 'batch_code');
    }
}