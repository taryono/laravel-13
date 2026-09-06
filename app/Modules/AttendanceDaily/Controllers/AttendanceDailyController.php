<?php

namespace App\Modules\AttendanceDaily\Controllers;

use App\DataTables\AttendanceDailyDataTable;
use App\Http\Controllers\MainController;
use Models\AttendanceDaily;
use Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AttendanceDailyController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AttendanceDaily());
    }

    /**
     * Display a listing of daily attendance.
     */
    public function index(Request $request, AttendanceDailyDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        // Filter summary
        $summary = $this->getSummary($request);
        
        return $dataTable->render('Attendance::index', compact('summary'));
    }

    /**
     * Show daily attendance detail for an employee
     */
    public function show($id): View
    {
        $attendance = $this->model->with(['employee', 'shift'])->findOrFail($id);
        
        // Ambil semua log untuk hari itu
        $logs = $attendance->employee->attendanceLogs()
            ->where('attendance_date', $attendance->attendance_date)
            ->orderBy('scan_time')
            ->get();

        return view('Attendance::detail', compact('attendance', 'logs'));
    }

    /**
     * Show form to edit daily attendance (manual correction)
     */
    public function edit($id): View
    {
        $attendance = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', true)->orderBy('full_name')->get();
        
        return view('Attendance::edit', compact('attendance', 'employees'));
    }

    /**
     * Update daily attendance (manual correction)
     */
    public function update(Request $request, $id)
    {
        $attendance = $this->model->findOrFail($id);

        $validated = $request->validate([
            'actual_check_in' => 'nullable|date_format:H:i',
            'actual_check_out' => 'nullable|date_format:H:i|after:actual_check_in',
            'total_hours_worked' => 'nullable|numeric|min:0|max:24',
            'overtime_hours' => 'nullable|numeric|min:0|max:24',
            'late_minutes' => 'nullable|integer|min:0',
            'early_leave_minutes' => 'nullable|integer|min:0',
            'attendance_status' => 'required|in:hadir,sakit,izin,alpha,cuti,dinas_luar,late,half_day',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($attendance, $validated) {
                $validated['is_approved'] = true;
                $validated['approved_by'] = auth()->id();
                $validated['approved_at'] = now();
                
                $attendance->update($validated);
            });

            return $this->successResponse($request, 'Data absensi berhasil diperbarui.', $attendance);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui absensi.', ['exception' => $e, 'attendance_id' => $id]);
            return $this->errorResponse($request, 'Gagal memperbarui data absensi.');
        }
    }

    /**
     * Bulk update attendance status
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:attendance_daily,id',
            'attendance_status' => 'required|in:hadir,sakit,izin,alpha,cuti,dinas_luar,late,half_day',
        ]);

        try {
            $count = DB::transaction(function () use ($validated) {
                return AttendanceDaily::whereIn('id', $validated['ids'])
                    ->update([
                        'attendance_status' => $validated['attendance_status'],
                        'is_approved' => true,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);
            });

            return $this->successResponse($request, "Berhasil memperbarui {$count} data absensi.");
        } catch (Throwable $e) {
            Log::error('Gagal bulk update absensi.', ['exception' => $e]);
            return $this->errorResponse($request, 'Gagal memperbarui data absensi.');
        }
    }

    /**
     * Export attendance report
     */
    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'in:excel,pdf',
        ]);

        // Implementasi export menggunakan Maatwebsite Excel atau DOMPDF
        // return Excel::download(new AttendanceExport($request), 'attendance.xlsx');
        
        return $this->successResponse($request, 'Export akan diproses.');
    }

    /**
     * Get summary for dashboard
     */
    private function getSummary(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-t'));

        return [
            'total' => AttendanceDaily::whereBetween('attendance_date', [$startDate, $endDate])->count(),
            'hadir' => AttendanceDaily::whereBetween('attendance_date', [$startDate, $endDate])
                ->where('attendance_status', 'hadir')->count(),
            'sakit' => AttendanceDaily::whereBetween('attendance_date', [$startDate, $endDate])
                ->where('attendance_status', 'sakit')->count(),
            'izin' => AttendanceDaily::whereBetween('attendance_date', [$startDate, $endDate])
                ->where('attendance_status', 'izin')->count(),
            'alpha' => AttendanceDaily::whereBetween('attendance_date', [$startDate, $endDate])
                ->where('attendance_status', 'alpha')->count(),
            'total_overtime' => AttendanceDaily::whereBetween('attendance_date', [$startDate, $endDate])
                ->sum('overtime_hours'),
        ];
    }
}