<?php

namespace App\Modules\Attendance\Controllers;

use App\DataTables\AttendanceDataTable;
use App\Http\Controllers\MainController;
use Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Carbon\Carbon;
use Models\Employee;
use Models\PayrollPeriod;

class AttendanceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Attendance());
    }

    public function index(Request $request, AttendanceDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::attendance.index');
    }

    public function create(): View
    {
        $attendance = new Attendance();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');

        $result = $this->getForm($attendance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::attendance.edit', compact('attendance', 'forms', 'groupedForms', 'employees', 'periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i:s',
            'check_out' => 'nullable|date_format:H:i:s|after:check_in',
            'overtime_hours' => 'nullable|numeric|min:0',
            'status' => 'required|in:hadir,sakit,izin,alpha,cuti,dinas_luar',
            'notes' => 'nullable|string',
        ]);

        try {
            $attendance = DB::transaction(function () use ($validated) {
                return Attendance::create($validated);
            });

            return $this->successResponse($request, 'Data kehadiran berhasil ditambahkan.', $attendance);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan kehadiran.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data kehadiran gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $attendance = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');

        $result = $this->getForm($attendance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::attendance.edit', compact('attendance', 'forms', 'groupedForms', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $attendance = $this->model->findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i:s',
            'check_out' => 'nullable|date_format:H:i:s|after:check_in',
            'overtime_hours' => 'nullable|numeric|min:0',
            'status' => 'required|in:hadir,sakit,izin,alpha,cuti,dinas_luar',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($attendance, $validated) {
                $attendance->update($validated);
            });

            return $this->successResponse($request, 'Data kehadiran berhasil diperbarui.', $attendance);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui kehadiran.', ['exception' => $e, 'attendance_id' => $id]);
            return $this->errorResponse($request, 'Data kehadiran gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $attendance = $this->model->findOrFail($id);
            DB::transaction(function () use ($attendance) {
                $attendance->delete();
            });

            return $this->successResponse($request, 'Data kehadiran berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus kehadiran.', ['exception' => $e, 'attendance_id' => $id]);
            return $this->errorResponse($request, 'Data kehadiran gagal dihapus.');
        }
    }

    public function bulkStore(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:hadir,sakit,izin,alpha,cuti,dinas_luar',
            'check_in' => 'nullable|date_format:H:i:s',
            'check_out' => 'nullable|date_format:H:i:s|after:check_in',
        ]);

        try {
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            $dates = [];

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                if (!$date->isWeekend()) {
                    $dates[] = $date->format('Y-m-d');
                }
            }

            DB::transaction(function () use ($validated, $dates) {
                foreach ($dates as $date) {
                    Attendance::updateOrCreate(
                        [
                            'employee_id' => $validated['employee_id'],
                            'attendance_date' => $date,
                        ],
                        [
                            'status' => $validated['status'],
                            'check_in' => $validated['check_in'] ?? null,
                            'check_out' => $validated['check_out'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });

            return $this->successResponse($request, 'Data kehadiran massal berhasil ditambahkan.');
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan kehadiran massal.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Gagal menambahkan data kehadiran massal.');
        }
    }

    public function getByPeriod($periodId): JsonResponse
    {
        try {
            $period = PayrollPeriod::findOrFail($periodId);
            $attendances = Attendance::with('employee')
                ->whereBetween('attendance_date', [
                    $period->period_start_date,
                    $period->period_end_date
                ])
                ->get();

            return response()->json(['success' => true, 'data' => $attendances]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil kehadiran per periode.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getSummary($employeeId, $periodId): JsonResponse
    {
        try {
            $period = PayrollPeriod::findOrFail($periodId);
            $attendance = Attendance::where('employee_id', $employeeId)
                ->whereBetween('attendance_date', [
                    $period->period_start_date,
                    $period->period_end_date
                ])
                ->get();

            $summary = [
                'hadir' => $attendance->where('status', 'hadir')->count(),
                'sakit' => $attendance->where('status', 'sakit')->count(),
                'izin' => $attendance->where('status', 'izin')->count(),
                'alpha' => $attendance->where('status', 'alpha')->count(),
                'cuti' => $attendance->where('status', 'cuti')->count(),
                'dinas_luar' => $attendance->where('status', 'dinas_luar')->count(),
                'total_overtime' => $attendance->sum('overtime_hours'),
            ];

            return response()->json(['success' => true, 'data' => $summary]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil summary kehadiran.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}