<?php

namespace App\Services\Attendance;

use Models\EmployeeShift;
use Models\Shift;
use Carbon\Carbon;

class ShiftDetector
{
    /**
     * Get shift for employee on specific date
     */
    public function getShiftForEmployee($employeeId, $date): ?Shift
    {
        $dateObj = Carbon::parse($date);
        $dayOfWeek = $dateObj->format('N'); // 1=Monday, 7=Sunday

        // Check if there's a specific shift assignment for this date
        $employeeShift = EmployeeShift::where('employee_id', $employeeId)
            ->where('effective_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $date);
            })
            ->where(function ($query) use ($dayOfWeek) {
                $query->whereNull('day_of_week')
                    ->orWhere('day_of_week', $dayOfWeek);
            })
            ->orderBy('effective_date', 'desc')
            ->first();

        if ($employeeShift) {
            return Shift::find($employeeShift->shift_id);
        }

        // Check for default shift (based on department or position)
        return $this->getDefaultShift($employeeId);
    }

    /**
     * Get default shift for employee (if no specific assignment)
     */
    protected function getDefaultShift($employeeId): ?Shift
    {
        // You can implement logic to get default shift based on:
        // - Department default shift
        // - Position default shift
        // - Company default shift
        
        // For now, return first active shift or null
        return Shift::where('is_active', true)->first();
    }

    /**
     * Get all employee shifts for a period
     */
    public function getEmployeeShifts($employeeId, $startDate, $endDate): array
    {
        $shifts = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        while ($current <= $end) {
            $shift = $this->getShiftForEmployee($employeeId, $current->format('Y-m-d'));
            if ($shift) {
                $shifts[$current->format('Y-m-d')] = $shift;
            }
            $current->addDay();
        }
        
        return $shifts;
    }

    /**
     * Check if shift is night shift
     */
    public function isNightShift(Shift $shift): bool
    {
        return $shift->night_shift ?? false;
    }

    /**
     * Get shift duration in hours
     */
    public function getShiftDuration(Shift $shift): float
    {
        $start = Carbon::parse($shift->start_time);
        $end = Carbon::parse($shift->end_time);
        
        if ($end->lt($start)) {
            $end->addDay();
        }
        
        return $end->diffInMinutes($start) / 60;
    }
}