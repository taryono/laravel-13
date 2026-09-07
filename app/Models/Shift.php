<?php

namespace Models; 

class Shift extends BasedModel
{ 
   protected $table = 'shifts';

    protected $fillable = [
        'code', 'name', 'start_time', 'end_time', 'crosses_midnight',
        'grace_period_start', 'grace_period_end',
        'break_start', 'break_end', 'break_duration',
        'overtime_min_threshold', 'night_shift_start', 'is_active',
    ];

    protected $casts = [
        'crosses_midnight' => 'boolean',
        'is_active'        => 'boolean',
    ];

   public function shift_rates()
    {
        return $this->hasMany(ShiftRate::class);
    }

    public function employee_shifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function attendance_dailies()
    {
        return $this->hasMany(AttendanceDaily::class);
    }

    /**
     * Get shift duration in hours
     */
    public function getDurationInHoursAttribute(): float
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        if ($end->lt($start)) {
            $end->addDay();
        }
        
        return $end->diffInHours($start) + ($end->diffInMinutes($start) % 60) / 60;
    }

    /**
     * Check if shift crosses midnight
     */
    public function getIsCrossDayAttribute(): bool
    {
        return \Carbon\Carbon::parse($this->end_time)->lt(\Carbon\Carbon::parse($this->start_time));
    }

    /**
     * Scope for active shifts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for night shifts
     */
    public function scopeNightShift($query)
    {
        return $query->where('night_shift', true);
    }

    // Contoh logika di Model Shift atau Service Payroll
    public function isCrossingMidnight(): bool
    {
        // Jika jam selesai lebih kecil dari jam mulai, artinya melewati tengah malam
        // Contoh: start = 22:00:00, end = 06:00:00
        return strtotime($this->end_time) < strtotime($this->start_time);
    }

    public function getTotalWorkMinutes(): int
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        
        // Jika melewati tengah malam, tambahkan 24 jam (86400 detik) ke waktu selesai
        if ($end < $start) {
            $end += 86400; 
        }
        
        $totalSeconds = $end - $start;
        return (int) ($totalSeconds / 60); // Kembalikan dalam menit
    }

    public function rates(): HasMany
    {
        return $this->hasMany(ShiftRate::class);
    }
}