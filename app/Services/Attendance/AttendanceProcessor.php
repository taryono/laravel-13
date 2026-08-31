<?php

namespace App\Services\Attendance;

use Models\AttendanceLog;
use Models\AttendanceDaily;
use Models\EmployeeShift;
use Models\Shift;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceProcessor
{
    protected $shiftDetector;
    protected $overtimeCalculator;

    public function __construct(ShiftDetector $shiftDetector, OvertimeCalculator $overtimeCalculator)
    {
        $this->shiftDetector = $shiftDetector;
        $this->overtimeCalculator = $overtimeCalculator;
    }

    /**
     * Process all unprocessed attendance logs
     */
    public function processBatch(): array
    {
        $logs = AttendanceLog::where('is_processed', false)
            ->orderBy('employee_id')
            ->orderBy('attendance_date')
            ->orderBy('scan_time')
            ->get();

        $processed = 0;
        $failed = 0;

        foreach ($logs as $log) {
            try {
                $this->processLog($log);
                $processed++;
            } catch (\Throwable $e) {
                Log::error('Gagal memproses log absensi.', [
                    'log_id' => $log->id,
                    'exception' => $e->getMessage()
                ]);
                $failed++;
            }
        }

        return [
            'processed' => $processed,
            'failed' => $failed,
        ];
    }

    /**
     * Process single attendance log
     */
    public function processLog(AttendanceLog $log): void
    {
        DB::transaction(function () use ($log) {
            // 1. Get or create daily attendance
            $daily = AttendanceDaily::firstOrNew([
                'employee_id' => $log->employee_id,
                'attendance_date' => $log->attendance_date,
            ]);

            // 2. Determine shift for this employee on this date
            $shift = $this->getShiftForEmployee($log->employee_id, $log->attendance_date);
            
            if ($shift) {
                $daily->shift_id = $shift->id;
            }

            // 3. Get all logs for this employee on this date
            $allLogs = AttendanceLog::where('employee_id', $log->employee_id)
                ->where('attendance_date', $log->attendance_date)
                ->orderBy('scan_time')
                ->get();

            // 4. Calculate attendance data
            $this->calculateAttendance($daily, $allLogs, $shift);

            // 5. Mark all logs as processed
            AttendanceLog::where('employee_id', $log->employee_id)
                ->where('attendance_date', $log->attendance_date)
                ->update([
                    'is_processed' => true,
                    'processed_at' => now(),
                    'processed_by' => auth()->id(),
                ]);

            $daily->save();
        });
    }

    /**
     * Calculate attendance from logs
     */
    private function calculateAttendance(AttendanceDaily $daily, $logs, $shift)
    {
        if ($logs->isEmpty()) {
            $daily->attendance_status = 'alpha';
            return;
        }

        // First and last scan
        $firstScan = $logs->first();
        $lastScan = $logs->last();

        $daily->first_scan = $firstScan->scan_time;
        $daily->last_scan = $lastScan->scan_time;
        $daily->actual_check_in = $firstScan->scan_time->format('H:i:s');
        $daily->actual_check_out = $lastScan->scan_time->format('H:i:s');

        // Calculate total hours worked
        $totalSeconds = $lastScan->scan_time->diffInSeconds($firstScan->scan_time);
        $totalHours = $totalSeconds / 3600;
        $daily->total_hours_worked = round($totalHours, 2);

        // If shift exists, calculate regular hours and overtime
        if ($shift) {
            $daily->scheduled_check_in = $shift->start_time;
            $daily->scheduled_check_out = $shift->end_time;

            // Calculate regular hours
            $regularHours = $this->calculateRegularHours($daily, $shift);
            $daily->regular_hours = $regularHours;

            // Calculate overtime
            $overtime = $this->overtimeCalculator->calculate($daily, $shift);
            $daily->overtime_hours = $overtime;

            // Calculate late minutes
            $daily->late_minutes = $this->calculateLateMinutes($daily, $shift);
            $daily->early_leave_minutes = $this->calculateEarlyLeaveMinutes($daily, $shift);
        }

        // Determine attendance status
        $daily->attendance_status = $this->determineStatus($daily, $shift);
    }

    /**
     * Get shift for employee on specific date
     */
    private function getShiftForEmployee($employeeId, $date)
    {
        $dateObj = Carbon::parse($date);
        $dayOfWeek = $dateObj->format('N');

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

        return null;
    }

    /**
     * Calculate regular hours
     */
    private function calculateRegularHours(AttendanceDaily $daily, Shift $shift)
    {
        $scheduledStart = Carbon::parse($daily->attendance_date . ' ' . $shift->start_time);
        $scheduledEnd = Carbon::parse($daily->attendance_date . ' ' . $shift->end_time);

        // If shift crosses midnight
        if ($scheduledEnd->lt($scheduledStart)) {
            $scheduledEnd->addDay();
        }

        $actualStart = Carbon::parse($daily->first_scan);
        $actualEnd = Carbon::parse($daily->last_scan);

        // Actual duration
        $actualDuration = $actualEnd->diffInSeconds($actualStart);

        // Scheduled duration
        $scheduledDuration = $scheduledEnd->diffInSeconds($scheduledStart);

        // Regular hours = min(actual, scheduled)
        $regularSeconds = min($actualDuration, $scheduledDuration);
        
        return round($regularSeconds / 3600, 2);
    }

    /**
     * Calculate late minutes
     */
    private function calculateLateMinutes(AttendanceDaily $daily, Shift $shift)
    {
        if (!$daily->actual_check_in) {
            return 0;
        }

        $scheduledStart = Carbon::parse($daily->attendance_date . ' ' . $shift->start_time);
        $actualStart = Carbon::parse($daily->attendance_date . ' ' . $daily->actual_check_in);

        $lateMinutes = $actualStart->diffInMinutes($scheduledStart, false);

        // Subtract grace period
        $lateMinutes -= ($shift->grace_period_start ?? 0);

        return max(0, $lateMinutes);
    }

    /**
     * Calculate early leave minutes
     */
    private function calculateEarlyLeaveMinutes(AttendanceDaily $daily, Shift $shift)
    {
        if (!$daily->actual_check_out) {
            return 0;
        }

        $scheduledEnd = Carbon::parse($daily->attendance_date . ' ' . $shift->end_time);
        
        // If shift crosses midnight
        if (Carbon::parse($shift->end_time)->lt(Carbon::parse($shift->start_time))) {
            $scheduledEnd->addDay();
        }

        $actualEnd = Carbon::parse($daily->attendance_date . ' ' . $daily->actual_check_out);

        $earlyMinutes = $scheduledEnd->diffInMinutes($actualEnd, false);

        // Subtract grace period
        $earlyMinutes -= ($shift->grace_period_end ?? 0);

        return max(0, $earlyMinutes);
    }

    /**
     * Determine attendance status
     */
    private function determineStatus(AttendanceDaily $daily, $shift = null)
    {
        // If no logs at all
        if (!$daily->first_scan) {
            return 'alpha';
        }

        // If late more than 2 hours, consider half day or alpha
        if (($daily->late_minutes ?? 0) > 120) {
            if (($daily->total_hours_worked ?? 0) < 4) {
                return 'alpha';
            }
            return 'late';
        }

        // If working hours less than 4 hours
        if (($daily->total_hours_worked ?? 0) < 4) {
            if (($daily->late_minutes ?? 0) > 60) {
                return 'alpha';
            }
            return 'half_day';
        }

        return 'hadir';
    }
}