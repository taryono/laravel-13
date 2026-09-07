<?php

namespace Models; 

class EmployeeShift extends BasedModel
{ 
    protected $fillable = [
        'employee_id',
        'shift_id',
        'effective_date',
        'end_date',
        'day_of_week',
        'is_rotational',
        'rotation_group',
        'rotation_sequence',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'effective_date'    => 'date:Y-m-d',
        'end_date'          => 'date:Y-m-d',
        'day_of_week'       => 'integer',
        'is_rotational'     => 'boolean',
        'rotation_sequence' => 'integer',
        'is_active'         => 'boolean',
    ];

    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getEffectiveShift($employeeId, $date)
{
    // 1. Cek apakah ada pertukaran yang disetujui
    $swap = ShiftSwap::where('date', $date)
        ->where('status', 'approved')
        ->where(function ($q) use ($employeeId) {
            $q->where('employee_id_a', $employeeId)
              ->orWhere('employee_id_b', $employeeId);
        })
        ->first();

    if ($swap) {
        // Kembalikan shift hasil pertukaran
        return $swap->employee_id_a == $employeeId ? $swap->targetShiftA : $swap->targetShiftB;
    }

    // 2. Tidak ada pertukaran → ambil jadwal biasa
    return self::with('shift')
        ->where('employee_id', $employeeId)
        ->where('effective_date', '<=', $date)
        ->where(function ($q) use ($date) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', $date);
        })
        ->first()?->shift;
    }
}