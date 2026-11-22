<?php

namespace Models;

class ShiftSwap extends SoftDeletableModel
{
    protected $fillable = [
        'date',
        'employee_id_a',
        'original_shift_a',
        'target_shift_a',
        'employee_id_b',
        'original_shift_b',
        'target_shift_b',
        'status',
        'approved_by',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relasi Karyawan A
    public function employeeA()
    {
        return $this->belongsTo(Employee::class, 'employee_id_a');
    }

    // Relasi Karyawan B
    public function employeeB()
    {
        return $this->belongsTo(Employee::class, 'employee_id_b');
    }

    // Shift Asli & Tujuan
    public function originalShiftA()
    {
        return $this->belongsTo(Shift::class, 'original_shift_a');
    }
    public function targetShiftA()
    {
        return $this->belongsTo(Shift::class, 'target_shift_a');
    }
    public function originalShiftB()
    {
        return $this->belongsTo(Shift::class, 'original_shift_b');
    }
    public function targetShiftB()
    {
        return $this->belongsTo(Shift::class, 'target_shift_b');
    }
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    // Scope
    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }
    public function scopeByDate($q, $date)
    {
        return $q->where('date', $date);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}