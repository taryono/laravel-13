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
use Models\AttendanceDaily;

class AttendanceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Attendance(), 'attendance');
    }

    public function index(Request $request, AttendanceDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Attendance::index');
    }

    public function create(): View
    {
        $attendance = new Attendance();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id'); 

        return view('Attendance::create', compact('attendance', 'employees', 'periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'        => 'required|exists:employees,id',
            'attendance_date'    => 'required|date',
            'status'             => 'required|in:hadir,sakit,izin,alpha,cuti,dinas_luar',
            'notes'              => 'nullable|string',
            'payroll_period_id'  => 'nullable|exists:payroll_periods,id',
            // ✅ Validasi jam masuk/pulang
            'actual_check_in'    => 'nullable|date_format:Y-m-d\TH:i',
            'actual_check_out'   => 'nullable|date_format:Y-m-d\TH:i|after:actual_check_in',
            'overtime_hours'     => 'nullable|numeric|min:0',
        ]);

        // Cari periode penggajian otomatis
        $period = PayrollPeriod::where('start_date', '<=', $validated['attendance_date'])
            ->where('end_date', '>=', $validated['attendance_date'])
            ->first();
        if (!$period) { 
            return $this->errorResponse($request, 'Tidak ada periode penggajian untuk tanggal ini.');
        }
        $validated['payroll_period_id'] = $period->id;


         try {
            $attendance =  DB::transaction(function () use ($validated) {
            // =============================================
            // LANGKAH 1: Simpan/Update ke attendance_daily
            // =============================================
            $attendanceDaily = AttendanceDaily::firstOrNew([
                'employee_id'      => $validated['employee_id'],
                'attendance_date'  => $validated['attendance_date'],
            ]);

            if (!empty($validated['actual_check_in'])) {
                $attendanceDaily->actual_check_in = Carbon::parse($validated['actual_check_in']);
                $attendanceDaily->first_scan = Carbon::parse($validated['actual_check_in']);
            }
            if (!empty($validated['actual_check_out'])) {
                $attendanceDaily->actual_check_out = Carbon::parse($validated['actual_check_out']);
                $attendanceDaily->last_scan = Carbon::parse($validated['actual_check_out']);
            }
            if (isset($validated['overtime_hours'])) {
                $attendanceDaily->overtime_hours = $validated['overtime_hours'];
            }

            $attendanceDaily->attendance_status = $validated['status'];
            $attendanceDaily->payroll_period_id = $validated['payroll_period_id'];
            $attendanceDaily->is_approved = true;
            $attendanceDaily->adjustment_notes = $validated['notes'] ?? 'Input manual oleh admin';
            $attendanceDaily->save();

            // =============================================
            // ✅ LANGKAH 2: Gunakan updateOrCreate untuk attendances
            // =============================================
            $uniqueKey = [
                'employee_id'     => $validated['employee_id'],
                'attendance_date' => $validated['attendance_date'],
            ];

            $data = [
                'payroll_period_id'   => $validated['payroll_period_id'],
                'attendance_daily_id' => $attendanceDaily->id,
                'status'              => $validated['status'],
                'notes'               => $validated['notes'] ?? '(Input manual)',
                'approved_by'         => auth()->id(),
                'approved_at'         => now(),
            ];

            // ✅ Kalau ADA → UPDATE, Kalau TIDAK ADA → CREATE
            $attendance = Attendance::updateOrCreate($uniqueKey, $data);

            return $attendance;
        });

            return $this->successResponse(
                $request,
                'Catatan absensi berhasil ditambahkan & terhubung dengan data mesin.',
                $attendance
            );
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan catatan absensi.', [
                'exception' => $e,
                'data'      => $validated,
            ]);

            return $this->errorResponse($request, 'Gagal menyimpan data. Silakan coba lagi.');
        }
    }

    public function edit($id): View
    {
        $attendance = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');  
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id'); 
        return view('Attendance::edit', compact('attendance',  'employees', 'periods'));
    }

    public function update(Request $request, $id)
    {
        // Ambil data utama yang akan diedit
        $attendance = Attendance::findOrFail($id);

        // ✅ Validasi sama persis seperti store()
        $validated = $request->validate([
            'employee_id'        => 'required|exists:employees,id',
            'attendance_date'    => 'required|date',
            'status'             => 'required|in:hadir,sakit,izin,alpha,cuti,dinas_luar',
            'notes'              => 'nullable|string',
            'payroll_period_id'  => 'nullable|exists:payroll_periods,id',
            'actual_check_in'    => 'nullable|date_format:Y-m-d\TH:i',
            'actual_check_out'   => 'nullable|date_format:Y-m-d\TH:i|after:actual_check_in',
            'overtime_hours'     => 'nullable|numeric|min:0',
        ]);

        // =============================================
        // ✅ Otomatis tentukan Periode Penggajian
        // =============================================
        $period = PayrollPeriod::where('start_date', '<=', $validated['attendance_date'])
            ->where('end_date', '>=', $validated['attendance_date'])
            ->first();

        if (!$period) {
            return back()->with('error', 'Tidak ada periode penggajian yang mencakup tanggal ini.');
        }
        $validated['payroll_period_id'] = $period->id;

        try {
            $attendance =  DB::transaction(function () use ($attendance, $validated) {
            // =============================================
            // ✅ LANGKAH 1: Cari/buat data attendance_daily
            // =============================================
            // Jika karyawan atau tanggal BERUBAH → cari yang baru
            $attendanceDaily = AttendanceDaily::where('employee_id', $validated['employee_id'])
                ->where('attendance_date', $validated['attendance_date'])
                ->first();

            // Jika TIDAK DITEMUKAN → buat baru (karena berubah tanggal/karyawan)
            if (!$attendanceDaily) {
                $attendanceDaily = new AttendanceDaily();
                $attendanceDaily->employee_id = $validated['employee_id'];
                $attendanceDaily->attendance_date = $validated['attendance_date'];
            }

            // ✅ Isi/perbarui jam masuk & pulang
            if (!empty($validated['actual_check_in'])) {
                $attendanceDaily->actual_check_in = Carbon::parse($validated['actual_check_in']);
                $attendanceDaily->first_scan = Carbon::parse($validated['actual_check_in']);
            }
            if (!empty($validated['actual_check_out'])) {
                $attendanceDaily->actual_check_out = Carbon::parse($validated['actual_check_out']);
                $attendanceDaily->last_scan = Carbon::parse($validated['actual_check_out']);
            }
            if (isset($validated['overtime_hours'])) {
                $attendanceDaily->overtime_hours = $validated['overtime_hours'];
            }

            // ✅ Perbarui status & metadata
            $attendanceDaily->attendance_status = $validated['status'];
            $attendanceDaily->payroll_period_id = $validated['payroll_period_id'];
            $attendanceDaily->is_approved = true;
            $attendanceDaily->adjustment_notes = $validated['notes'] ?? 'Diedit manual oleh admin';
            $attendanceDaily->save();

            // =============================================
            // ✅ LANGKAH 2: Perbarui data attendance
            // =============================================
            // Hapus dulu kolom yang TIDAK ada di tabel attendances
            unset(
                $validated['actual_check_in'],
                $validated['actual_check_out'],
                $validated['overtime_hours']
            );

            // Tambahkan penghubung ke attendance_daily yang baru/diperbarui
            $validated['attendance_daily_id'] = $attendanceDaily->id;
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();

            // Simpan perubahan ke tabel attendances
            $attendance->update($validated);

            return $attendance;
        });

            return $this->successResponse($request, 'Data absensi berhasil diperbarui.', $attendance);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui absensi.', ['exception' => $e]);
            return $this->errorResponse($request, 'Gagal memperbarui data.');
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

    public function report($periodId)
{
    $period = PayrollPeriod::findOrFail($periodId);
    $attendances = Attendance::with('employee')
                             ->where('payroll_period_id', $periodId)
                             ->get();
    
    // Hitung rekap
    $summary = [
        'total_hadir' => $attendances->where('status', 'hadir')->count(),
        'total_sakit' => $attendances->where('status', 'sakit')->count(),
        'total_izin' => $attendances->where('status', 'izin')->count(),
        'total_alpha' => $attendances->where('status', 'alpha')->count(),
        'total_cuti' => $attendances->where('status', 'cuti')->count(),
        'total_dinas_luar' => $attendances->where('status', 'dinas_luar')->count(),
        'total_overtime' => $attendances->sum('overtime_hours'),
    ];
    
    return view('Attendance::report', compact('period', 'attendances', 'summary'));
}
}