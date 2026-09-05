<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendanceDaily;
use App\Modules\Employee\Models\Employee;
use App\Modules\Shift\Models\EmployeeShift;
use App\Modules\Shift\Models\Shift;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceProcessor
{
    protected ShiftDetector $shiftDetector;
    protected OvertimeCalculator $overtimeCalculator;
    
    // Konfigurasi
    protected array $config = [
        'min_work_hours' => 2,  // Minimal jam kerja untuk dianggap hadir
        'max_work_hours' => 16, // Maksimal jam kerja per hari
        'half_day_hours' => 4,  // Kurang dari ini dianggap half day
        'break_duration' => 60, // Durasi istirahat default (menit)
    ];

    public function __construct(
        ShiftDetector $shiftDetector,
        OvertimeCalculator $overtimeCalculator
    ) {
        $this->shiftDetector = $shiftDetector;
        $this->overtimeCalculator = $overtimeCalculator;
    }

    /**
     * Process all unprocessed attendance logs
     */
    public function processBatch(?string $date = null): array
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $dateFormatted = $date->format('Y-m-d');
        
        $logs = AttendanceLog::where('is_processed', false)
            ->whereDate('attendance_date', $dateFormatted)
            ->orderBy('employee_id')
            ->orderBy('scan_time')
            ->get();

        $processed = 0;
        $failed = 0;
        $errors = [];

        foreach ($logs as $log) {
            try {
                $this->processLog($log);
                $processed++;
            } catch (Throwable $e) {
                $failed++;
                $errors[] = [
                    'log_id' => $log->id,
                    'employee_id' => $log->employee_id,
                    'error' => $e->getMessage(),
                ];
                
                Log::error('Gagal memproses log absensi.', [
                    'log_id' => $log->id,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        return [
            'processed' => $processed,
            'failed' => $failed,
            'errors' => $errors,
            'date' => $dateFormatted,
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
            $shift = $this->shiftDetector->getShiftForEmployee(
                $log->employee_id,
                $log->attendance_date
            );
            
            if ($shift) {
                $daily->shift_id = $shift->id;
            }

            // 3. Get all logs for this employee on this date
            $allLogs = AttendanceLog::where('employee_id', $log->employee_id)
                ->whereDate('attendance_date', $log->attendance_date)
                ->orderBy('scan_time')
                ->get();

            // 4. Calculate attendance data
            $this->calculateAttendance($daily, $allLogs, $shift);

            // 5. Mark all logs as processed
            AttendanceLog::where('employee_id', $log->employee_id)
                ->whereDate('attendance_date', $log->attendance_date)
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
    protected function calculateAttendance(AttendanceDaily $daily, $logs, $shift): void
    {
        if ($logs->isEmpty()) {
            $daily->attendance_status = 'alpha';
            $daily->total_hours_worked = 0;
            return;
        }

        // First and last scan
        $firstScan = $logs->first();
        $lastScan = $logs->last();

        $daily->first_scan = $firstScan->scan_time;
        $daily->last_scan = $lastScan->scan_time;
        $daily->actual_check_in = $firstScan->scan_time->format('H:i:s');
        $daily->actual_check_out = $lastScan->scan_time->format('H:i:s');

        // Detect scan patterns (multiple scans)
        $patterns = $this->detectScanPatterns($logs, $shift);
        
        // Calculate total hours worked (including break deduction)
        $totalMinutes = $this->calculateTotalWorkMinutes($patterns, $shift);
        $totalHours = $totalMinutes / 60;
        $daily->total_hours_worked = round($totalHours, 2);

        // Calculate break hours
        $breakMinutes = $this->calculateBreakMinutes($patterns);
        $daily->break_hours = round($breakMinutes / 60, 2);

        // If shift exists, calculate regular hours and overtime
        if ($shift) {
            $daily->scheduled_check_in = $shift->start_time;
            $daily->scheduled_check_out = $shift->end_time;

            // Calculate regular hours
            $regularHours = $this->calculateRegularHours($daily, $shift);
            $daily->regular_hours = round($regularHours, 2);

            // Calculate overtime
            $overtime = $this->overtimeCalculator->calculate($daily, $shift);
            $daily->overtime_hours = round($overtime, 2);

            // Calculate late minutes
            $daily->late_minutes = $this->calculateLateMinutes($daily, $shift);
            $daily->early_leave_minutes = $this->calculateEarlyLeaveMinutes($daily, $shift);
        }

        // Determine attendance status
        $daily->attendance_status = $this->determineStatus($daily, $shift);
    }

    /**
     * Detect scan patterns (multiple scans in a day)
     */
    protected function detectScanPatterns($logs, $shift): array
    {
        $patterns = [];
        $breakStart = null;
        $workStart = null;
        
        foreach ($logs as $index => $log) {
            $scanTime = Carbon::parse($log->scan_time);
            
            // Determine scan type based on context
            if ($this->isBreakTime($scanTime, $shift)) {
                if ($breakStart === null) {
                    // Starting break
                    $breakStart = $scanTime;
                    $patterns[] = [
                        'type' => 'break_start',
                        'time' => $scanTime,
                        'log_id' => $log->id,
                    ];
                } else {
                    // Ending break
                    $patterns[] = [
                        'type' => 'break_end',
                        'time' => $scanTime,
                        'log_id' => $log->id,
                    ];
                    $breakStart = null;
                }
            } else {
                if ($workStart === null) {
                    // First work scan (check-in)
                    $workStart = $scanTime;
                    $patterns[] = [
                        'type' => 'work_start',
                        'time' => $scanTime,
                        'log_id' => $log->id,
                    ];
                } else {
                    // Additional work scan (could be check-out or multiple)
                    // Check if there's a break in between
                    $prevPattern = end($patterns);
                    if ($prevPattern && $prevPattern['type'] === 'break_end') {
                        // After break, this is work_start
                        $patterns[] = [
                            'type' => 'work_start',
                            'time' => $scanTime,
                            'log_id' => $log->id,
                        ];
                    } else {
                        // Could be work_end
                        $patterns[] = [
                            'type' => 'work_end',
                            'time' => $scanTime,
                            'log_id' => $log->id,
                        ];
                    }
                }
            }
        }
        
        // If last pattern is work_start and there's no work_end, assume it's also work_end
        $lastPattern = end($patterns);
        if ($lastPattern && $lastPattern['type'] === 'work_start') {
            $patterns[] = [
                'type' => 'work_end',
                'time' => $lastPattern['time'],
                'log_id' => $lastPattern['log_id'],
                'note' => 'Assume same as work_start',
            ];
        }
        
        return $patterns;
    }

    /**
     * Check if time is break time
     */
    protected function isBreakTime(Carbon $time, $shift): bool
    {
        if (!$shift || !$shift->break_start || !$shift->break_end) {
            return false;
        }
        
        $breakStart = Carbon::parse($shift->break_start);
        $breakEnd = Carbon::parse($shift->break_end);
        
        // If break crosses midnight
        if ($breakEnd->lt($breakStart)) {
            $breakEnd->addDay();
        }
        
        $timeCopy = $time->copy();
        $breakStartCopy = $breakStart->copy()->setDate(
            $time->year,
            $time->month,
            $time->day
        );
        $breakEndCopy = $breakEnd->copy()->setDate(
            $time->year,
            $time->month,
            $time->day
        );
        
        return $timeCopy->between($breakStartCopy, $breakEndCopy);
    }

    /**
     * Calculate total work minutes from patterns
     */
    protected function calculateTotalWorkMinutes(array $patterns, $shift): float
    {
        $totalMinutes = 0;
        $workStart = null;
        $breakMinutes = 0;
        
        foreach ($patterns as $pattern) {
            if ($pattern['type'] === 'work_start') {
                $workStart = $pattern['time'];
            } elseif ($pattern['type'] === 'work_end' && $workStart) {
                $minutes = $pattern['time']->diffInMinutes($workStart);
                $totalMinutes += $minutes;
                $workStart = null;
            } elseif ($pattern['type'] === 'break_start') {
                // Break is excluded from work time
                // But we need to handle it properly
            } elseif ($pattern['type'] === 'break_end') {
                // Break ended, work continues
            }
        }
        
        // If there's an unclosed work period, use last scan as end
        if ($workStart) {
            $lastPattern = end($patterns);
            if ($lastPattern) {
                $minutes = $lastPattern['time']->diffInMinutes($workStart);
                $totalMinutes += $minutes;
            }
        }
        
        // Subtract break duration if not properly captured
        $breakMinutes = $this->calculateBreakMinutes($patterns);
        $totalMinutes -= $breakMinutes;
        
        // Apply max work hours limit
        $maxMinutes = ($this->config['max_work_hours'] ?? 16) * 60;
        if ($totalMinutes > $maxMinutes) {
            $totalMinutes = $maxMinutes;
        }
        
        return max(0, $totalMinutes);
    }

    /**
     * Calculate break minutes from patterns
     */
    protected function calculateBreakMinutes(array $patterns): float
    {
        $breakMinutes = 0;
        $breakStart = null;
        
        foreach ($patterns as $pattern) {
            if ($pattern['type'] === 'break_start') {
                $breakStart = $pattern['time'];
            } elseif ($pattern['type'] === 'break_end' && $breakStart) {
                $minutes = $pattern['time']->diffInMinutes($breakStart);
                $breakMinutes += $minutes;
                $breakStart = null;
            }
        }
        
        return $breakMinutes;
    }

    /**
     * Calculate regular hours
     */
    protected function calculateRegularHours(AttendanceDaily $daily, $shift): float
    {
        if (!$shift) {
            return $daily->total_hours_worked ?? 0;
        }
        
        $scheduledStart = Carbon::parse($daily->attendance_date . ' ' . $shift->start_time);
        $scheduledEnd = Carbon::parse($daily->attendance_date . ' ' . $shift->end_time);
        
        // If shift crosses midnight
        if ($scheduledEnd->lt($scheduledStart)) {
            $scheduledEnd->addDay();
        }
        
        $scheduledDuration = $scheduledEnd->diffInMinutes($scheduledStart);
        
        // Check for break deduction
        $breakMinutes = 0;
        if ($shift->break_duration) {
            $breakMinutes = $shift->break_duration;
        } elseif ($shift->break_start && $shift->break_end) {
            $breakStart = Carbon::parse($shift->break_start);
            $breakEnd = Carbon::parse($shift->break_end);
            if ($breakEnd->lt($breakStart)) {
                $breakEnd->addDay();
            }
            $breakMinutes = $breakEnd->diffInMinutes($breakStart);
        }
        
        $scheduledDuration -= $breakMinutes;
        
        // Regular hours = min(actual, scheduled)
        $actualMinutes = ($daily->total_hours_worked ?? 0) * 60;
        $regularMinutes = min($actualMinutes, $scheduledDuration);
        
        return max(0, $regularMinutes / 60);
    }

    /**
     * Calculate late minutes
     */
    protected function calculateLateMinutes(AttendanceDaily $daily, $shift): int
    {
        if (!$daily->actual_check_in || !$shift) {
            return 0;
        }

        $scheduledStart = Carbon::parse($daily->attendance_date . ' ' . $shift->start_time);
        $actualStart = Carbon::parse($daily->attendance_date . ' ' . $daily->actual_check_in);

        // If actual start is after scheduled start
        if ($actualStart->gt($scheduledStart)) {
            $lateMinutes = $actualStart->diffInMinutes($scheduledStart);
            
            // Subtract grace period
            $gracePeriod = $shift->grace_period_start ?? 15;
            $lateMinutes -= $gracePeriod;
            
            return max(0, $lateMinutes);
        }
        
        return 0;
    }

    /**
     * Calculate early leave minutes
     */
    protected function calculateEarlyLeaveMinutes(AttendanceDaily $daily, $shift): int
    {
        if (!$daily->actual_check_out || !$shift) {
            return 0;
        }

        $scheduledEnd = Carbon::parse($daily->attendance_date . ' ' . $shift->end_time);
        
        // If shift crosses midnight
        if (Carbon::parse($shift->end_time)->lt(Carbon::parse($shift->start_time))) {
            $scheduledEnd->addDay();
        }
        
        $actualEnd = Carbon::parse($daily->attendance_date . ' ' . $daily->actual_check_out);

        // If actual end is before scheduled end
        if ($actualEnd->lt($scheduledEnd)) {
            $earlyMinutes = $scheduledEnd->diffInMinutes($actualEnd);
            
            // Subtract grace period
            $gracePeriod = $shift->grace_period_end ?? 15;
            $earlyMinutes -= $gracePeriod;
            
            return max(0, $earlyMinutes);
        }
        
        return 0;
    }

    /**
     * Determine attendance status
     */
    protected function determineStatus(AttendanceDaily $daily, $shift = null): string
    {
        // If no logs at all
        if (!$daily->first_scan) {
            return 'alpha';
        }

        // If total hours worked is very low
        $totalHours = $daily->total_hours_worked ?? 0;
        
        if ($totalHours < $this->config['min_work_hours']) {
            // Check if there's a valid reason (sakit, izin, cuti)
            // This would come from leave/absence requests
            return 'alpha';
        }

        // If less than half day hours
        if ($totalHours < $this->config['half_day_hours']) {
            return 'half_day';
        }

        // Check late status
        if (($daily->late_minutes ?? 0) > 60) {
            return 'late';
        }

        return 'hadir';
    }
}