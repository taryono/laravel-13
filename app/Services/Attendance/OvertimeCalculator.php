<?php

namespace App\Services\Attendance;

use App\Modules\Attendance\Models\AttendanceDaily;
use App\Modules\Shift\Models\Shift;
use Carbon\Carbon;

class OvertimeCalculator
{
    protected array $config = [
        'overtime_minutes' => 30, // Minimal overtime minutes to count
        'regular_hours_limit' => 8, // Regular hours per day
        'weekly_regular_hours' => 40, // Regular hours per week
    ];

    /**
     * Calculate overtime hours
     */
    public function calculate(AttendanceDaily $daily, Shift $shift): float
    {
        if (!$shift) {
            return 0;
        }

        $totalHours = $daily->total_hours_worked ?? 0;
        $regularHours = $this->getRegularHours($shift);
        $breakMinutes = $this->getBreakMinutes($shift);

        // Calculate actual working hours (excluding break)
        $actualWorkHours = $totalHours - ($breakMinutes / 60);
        
        // Calculate overtime
        $overtimeHours = $actualWorkHours - $regularHours;
        
        // Apply minimum overtime threshold
        $minOvertimeMinutes = $shift->overtime_threshold ?? $this->config['overtime_minutes'];
        if ($overtimeHours > 0 && ($overtimeHours * 60) < $minOvertimeMinutes) {
            return 0;
        }

        // Apply maximum overtime limit (optional)
        $maxOvertimeHours = 4; // Max 4 hours overtime per day
        if ($overtimeHours > $maxOvertimeHours) {
            $overtimeHours = $maxOvertimeHours;
        }

        return max(0, round($overtimeHours, 2));
    }

    /**
     * Calculate overtime with different rates
     */
    public function calculateWithRates(AttendanceDaily $daily, Shift $shift): array
    {
        $overtimeHours = $this->calculate($daily, $shift);
        
        if ($overtimeHours <= 0) {
            return [
                'total_hours' => 0,
                'normal_rate' => 0,
                'weekend_rate' => 0,
                'holiday_rate' => 0,
                'total_amount' => 0,
            ];
        }

        $date = Carbon::parse($daily->attendance_date);
        $isWeekend = $date->isWeekend();
        $isHoliday = $this->isHoliday($date);

        // Determine rates
        $normalRate = $shift->overtime_rate ?? 1.5;
        
        if ($isHoliday) {
            $rate = $normalRate * 2;
        } elseif ($isWeekend) {
            $rate = $normalRate * 1.5;
        } else {
            $rate = $normalRate;
        }

        return [
            'total_hours' => $overtimeHours,
            'normal_rate' => $normalRate,
            'weekend_rate' => $isWeekend ? $normalRate * 1.5 : 0,
            'holiday_rate' => $isHoliday ? $normalRate * 2 : 0,
            'total_amount' => $overtimeHours * $rate,
        ];
    }

    /**
     * Get regular hours for shift
     */
    protected function getRegularHours(Shift $shift): float
    {
        $start = Carbon::parse($shift->start_time);
        $end = Carbon::parse($shift->end_time);
        
        if ($end->lt($start)) {
            $end->addDay();
        }
        
        $hours = $end->diffInMinutes($start) / 60;
        
        // Limit to regular hours max
        return min($hours, $this->config['regular_hours_limit']);
    }

    /**
     * Get break minutes for shift
     */
    protected function getBreakMinutes(Shift $shift): float
    {
        if ($shift->break_duration) {
            return $shift->break_duration;
        }
        
        if ($shift->break_start && $shift->break_end) {
            $start = Carbon::parse($shift->break_start);
            $end = Carbon::parse($shift->break_end);
            
            if ($end->lt($start)) {
                $end->addDay();
            }
            
            return $end->diffInMinutes($start);
        }
        
        return 60; // Default 1 hour break
    }

    /**
     * Check if date is holiday
     */
    protected function isHoliday(Carbon $date): bool
    {
        // You can implement holiday check from database
        // For now, return false
        return false;
    }
}