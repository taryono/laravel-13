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
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,sick,excused,absent_unexcused,on_leave,business_trip',
            'notes' => 'nullable|string',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
            // ✅ Validasi jam masuk/pulang
            'actual_check_in' => 'nullable|date_format:Y-m-d\TH:i',
            'actual_check_out' => 'nullable|date_format:Y-m-d\TH:i|after:actual_check_in',
            'overtime_hours' => 'nullable|numeric|min:0',
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
            $attendance = DB::transaction(function () use ($validated) {

                // ✅ === TAMBAHKAN BARIS INI ===
                $analysis = $this->calculateAttendance(
                    $validated['employee_id'],
                    $validated['attendance_date'],
                    $validated['actual_check_in'] ?? null,
                    $validated['actual_check_out'] ?? null
                );

                // Opsional: Jika ingin menolak simpan jika tidak ada jadwal
                // if (!$analysis['success']) {
                //     throw new \Exception($analysis['message']);
                // }
                // ✅ === AKHIR PENAMBAHAN ===

                // =============================================
                // LANGKAH 1: Simpan/Update ke attendance_daily
                // =============================================
                $attendanceDaily = AttendanceDaily::firstOrNew([
                    'employee_id' => $validated['employee_id'],
                    'attendance_date' => $validated['attendance_date'],
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
                    'employee_id' => $validated['employee_id'],
                    'attendance_date' => $validated['attendance_date'],
                ];

                $data = [
                    'payroll_period_id' => $validated['payroll_period_id'],
                    'attendance_daily_id' => $attendanceDaily->id,
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? '(Input manual)',
                    'approved_by' => request()->user()?->id,
                    'approved_at' => now(),
                ];

                // ✅ Kalau ADA → UPDATE, Kalau TIDAK ADA → CREATE
                $attendance = Attendance::updateOrCreate($uniqueKey, $data);

                return $attendance;
            });

            return $this->successResponse(
                $request,
                'Catatan absensi berhasil ditambahkan. ' .
                ($analysis['is_swap'] ? ' ⚠️ Menggunakan shift hasil pertukaran.' : '') .
                ($analysis['is_late'] ? ' Karyawan terlambat ' . $analysis['minutes_late'] . ' menit.' : ''),
                $attendance
            );
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan catatan absensi.', [
                'exception' => $e,
                'data' => $validated,
            ]);

            return $this->errorResponse($request, 'Gagal menyimpan data. Silakan coba lagi.');
        }
    }

    public function edit($id): View
    {
        $attendance = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');
        return view('Attendance::edit', compact('attendance', 'employees', 'periods'));
    }

    public function update(Request $request, $id)
    {
        // Ambil data utama yang akan diedit
        $attendance = Attendance::findOrFail($id);

        // ✅ Validasi sama persis seperti store()
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,sick,excused,absent_unexcused,on_leave,business_trip',
            'notes' => 'nullable|string',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
            'actual_check_in' => 'nullable|date_format:Y-m-d\TH:i',
            'actual_check_out' => 'nullable|date_format:Y-m-d\TH:i|after:actual_check_in',
            'overtime_hours' => 'nullable|numeric|min:0',
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
            $attendance = DB::transaction(function () use ($attendance, $validated) {

                // ✅ === TAMBAHKAN BARIS INI ===
                $analysis = $this->calculateAttendance(
                    $validated['employee_id'],
                    $validated['attendance_date'],
                    $validated['actual_check_in'] ?? null,
                    $validated['actual_check_out'] ?? null
                );
                // ✅ === AKHIR PENAMBAHAN ===
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
                $validated['approved_by'] = request()->user()?->id;
                $validated['approved_at'] = now();

                // Simpan perubahan ke tabel attendances
                $attendance->update($validated);

                return $attendance;
            });

            return $this->successResponse(
                $request,
                'Catatan absensi berhasil diperbarui.. ' .
                ($analysis['is_swap'] ? ' ⚠️ Menggunakan shift hasil pertukaran.' : '') .
                ($analysis['is_late'] ? ' Karyawan terlambat ' . $analysis['minutes_late'] . ' menit.' : ''),
                $attendance
            );
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
            'status' => 'required|in:present,sick,excused,absent_unexcused,on_leave,business_trip',
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
                'present' => $attendance->where('status', 'present')->count(),
                'sick' => $attendance->where('status', 'sick')->count(),
                'excused' => $attendance->where('status', 'excused')->count(),
                'absent_unexcused' => $attendance->where('status', 'absent_unexcused')->count(),
                'on_leave' => $attendance->where('status', 'on_leave')->count(),
                'business_trip' => $attendance->where('status', 'business_trip')->count(),
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
            'total_hadir' => $attendances->where('status', 'present')->count(),
            'total_sakit' => $attendances->where('status', 'sick')->count(),
            'total_izin' => $attendances->where('status', 'excused')->count(),
            'total_alpha' => $attendances->where('status', 'absent_unexcused')->count(),
            'total_cuti' => $attendances->where('status', 'on_leave')->count(),
            'total_dinas_luar' => $attendances->where('status', 'business_trip')->count(),
            'total_overtime' => $attendances->sum('overtime_hours'),
        ];

        return view('Attendance::report', compact('period', 'attendances', 'summary'));
    }


    /**
     * Hitung kehadiran berdasarkan jam masuk/keluar DAN jadwal shift yang berlaku
     * (Termasuk pengecekan pertukaran shift yang sudah disetujui)
     */
    protected function calculateAttendance($employeeId, $attendanceDate, $actualCheckIn, $actualCheckOut)
    {
        // 🔑 LANGKAH UTAMA: Ambil shift yang berlaku (cek pertukaran dulu!)
        $shift = \Models\EmployeeShift::getEffectiveShift($employeeId, $attendanceDate);

        if (!$shift) {
            return [
                'success' => false,
                'message' => 'Tidak ada jadwal shift untuk tanggal ini',
                'data' => null
            ];
        }

        // Konversi waktu
        $dateStr = $attendanceDate;
        $startTime = strtotime($dateStr . ' ' . $shift->start_time);
        $endTime = strtotime($dateStr . ' ' . $shift->end_time);

        // Tangani shift melewati tengah malam
        if ($shift->crosses_midnight) {
            $endTime = strtotime($dateStr . ' +1 day ' . $shift->end_time);
        }

        // Toleransi telat
        $lateThreshold = $startTime + ($shift->grace_period_start * 60);

        // Analisis jam masuk
        $isLate = false;
        $minutesLate = 0;
        $isEarlyLeave = false;
        $minutesEarly = 0;

        if ($actualCheckIn) {
            $clockInTime = strtotime($actualCheckIn);
            $isLate = $clockInTime > $lateThreshold;
            $minutesLate = $isLate ? round(($clockInTime_$lateThreshold) / 60) : 0;
        }

        // Analisis jam pulang
        if ($actualCheckOut) {
            $clockOutTime = strtotime($actualCheckOut);
            $earlyThreshold = $endTime_($shift->grace_period_end * 60);
            $isEarlyLeave = $clockOutTime < $earlyThreshold;
            $minutesEarly = $isEarlyLeave ? round(($earlyThreshold_$clockOutTime) / 60) : 0;
        }

        // ⚠️ Deteksi apakah ini hasil pertukaran shift
        $swapCheck = \Models\ShiftSwap::where('date', $attendanceDate)
            ->where('status', 'approved')
            ->where(function ($q) use ($employeeId) {
                $q->where('employee_id_a', $employeeId)
                    ->orWhere('employee_id_b', $employeeId);
            })
            ->exists();

        return [
            'success' => true,
            'shift_code' => $shift->code,
            'shift_name' => $shift->name,
            'shift_start' => $shift->start_time,
            'shift_end' => $shift->end_time,
            'is_late' => $isLate,
            'minutes_late' => $minutesLate,
            'is_early_leave' => $isEarlyLeave,
            'minutes_early' => $minutesEarly,
            'is_swap' => $swapCheck, // ⬅️ Tandai jika hasil tukar shift
        ];
    }
}