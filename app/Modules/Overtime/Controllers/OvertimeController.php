<?php

namespace App\Modules\Overtime\Controllers;

use App\DataTables\OvertimeDataTable;
use App\Http\Controllers\MainController;
use Models\Overtime;
use Models\AttendanceDaily; // ✅ Tambah ini
use Models\Employee;
use Models\PayrollPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;
use Throwable;

class OvertimeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Overtime(), 'overtime');
    }

    // ✅ INDEX: Diperbarui untuk memuat Usulan Lembur + DataTable
    public function index(Request $request, OvertimeDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        // 📋 Usulan Lembur Otomatis dari attendance_daily
        $proposals = AttendanceDaily::with('employee', 'payrollPeriod')
            ->where('overtime_hours', '>', 0)
            ->whereDoesntHave('overtime') // belum disahkan
            ->orderBy('attendance_date', 'desc')
            ->get();

         return $dataTable->render('Overtime::index', compact('proposals'));
    }

    public function create(): View
    {
        $overtime = new Overtime();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');
        return view('Overtime::create', compact('overtime', 'employees', 'periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'         => 'required|exists:employees,id',
            'attendance_daily_id' => 'nullable|exists:attendance_dailies,id', // ✅ Tambah
            'overtime_date'       => 'required|date',
            'start_time'          => 'required|date_format:H:i:s',
            'end_time'            => 'required|date_format:H:i:s|after:start_time',
            'total_hours'         => 'required|numeric|min:0',
            'overtime_rate'       => 'required|numeric|min:0',
            'total_amount'        => 'nullable|numeric|min:0',
            'notes'               => 'nullable|string',
            'payroll_period_id'   => 'nullable|exists:payroll_periods,id',
        ]);

        try {
            $overtime = DB::transaction(function () use ($validated) {
                // Hitung total_amount jika kosong
                if (empty($validated['total_amount'])) {
                    $validated['total_amount'] = $validated['total_hours'] * $validated['overtime_rate'];
                }

                // Auto-fill persetujuan jika dari usulan
                if (!empty($validated['attendance_daily_id'])) {
                    $validated['approved_by'] = auth()->id();
                    $validated['approved_at'] = now();
                }

                return Overtime::create($validated);
            });

            return $this->successResponse($request, 'Data lembur berhasil ditambahkan.', $overtime);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan lembur.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data lembur gagal ditambahkan.');
        }
    }

    // ⭐ METODE BARU: Sahkan Usulan Lembur Otomatis
    public function storeFromProposal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'attendance_daily_id' => 'required|exists:attendance_dailies,id',
            'overtime_rate'       => 'nullable|numeric|min:1',
            'notes'               => 'nullable|string',
        ]);

        try {
           $overtime =  DB::transaction(function () use ($validated) {
                $attendanceDaily = AttendanceDaily::findOrFail($validated['attendance_daily_id']);

                $rate = $validated['overtime_rate'] ?? 1.5;
                $hours = $attendanceDaily->overtime_hours;

                // Hitung rentang waktu lembur (setelah jam kerja normal 8 jam)
                $startOvertime = $attendanceDaily->actual_check_in?->copy()->addHours(8);
                $endOvertime = $attendanceDaily->actual_check_out;

                // Cari periode penggajian otomatis
                $period = PayrollPeriod::where('start_date', '<=', $attendanceDaily->attendance_date)
                    ->where('end_date', '>=', $attendanceDaily->attendance_date)
                    ->first();

                $overtime =Overtime::create([
                    'employee_id'         => $attendanceDaily->employee_id,
                    'attendance_daily_id' => $attendanceDaily->id, // 🔗 Hubungkan
                    'overtime_date'       => $attendanceDaily->attendance_date,
                    'start_time'          => $startOvertime?->format('H:i:s') ?? '17:00:00',
                    'end_time'            => $endOvertime?->format('H:i:s') ?? '17:00:00',
                    'total_hours'         => $hours,
                    'overtime_rate'       => $rate,
                    'total_amount'        => $hours * $rate, // Bisa diubah pakai gaji pokok
                    'notes'               => $validated['notes'] ?? 'Diusulkan otomatis dari kehadiran',
                    'payroll_period_id'   => $period?->id ?? $attendanceDaily->payroll_period_id,
                    'approved_by'         => auth()->id(),
                    'approved_at'         => now(),
                    'is_paid'             => false,
                ]);

                // Update status kehadiran
                $attendanceDaily->update([
                    'is_overtime' => true,
                ]);
                return $overtime;
            });
 
            return $this->successResponse($request, '✅ Lembur berhasil disahkan & dipindahkan ke daftar resmi.', $overtime);
        } catch (Throwable $e) {
            Log::error('Gagal menyahkan usulan lembur.', ['exception' => $e]); 
            return $this->errorResponse($request, 'Data lembur gagal diperbarui.');
        }
    }

    public function edit($id): View
    {
        $overtime = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');
        
        return view('Overtime::edit', compact('overtime', 'employees', 'periods'));
    }

    public function update(Request $request, $id)
    {
        $overtime = $this->model->findOrFail($id);

        if ($overtime->is_paid) {
            return $this->errorResponse($request, 'Lembur yang sudah dibayar tidak dapat diubah.');
        }

        $validated = $request->validate([
            'employee_id'         => 'required|exists:employees,id',
            'attendance_daily_id' => 'nullable|exists:attendance_dailies,id',
            'overtime_date'       => 'required|date',
            'start_time'          => 'required|date_format:H:i:s',
            'end_time'            => 'required|date_format:H:i:s|after:start_time',
            'total_hours'         => 'required|numeric|min:0',
            'overtime_rate'       => 'required|numeric|min:0',
            'total_amount'        => 'nullable|numeric|min:0',
            'notes'               => 'nullable|string',
            'payroll_period_id'   => 'nullable|exists:payroll_periods,id',
        ]);

        try {
            DB::transaction(function () use ($overtime, $validated) {
                if (empty($validated['total_amount'])) {
                    $validated['total_amount'] = $validated['total_hours'] * $validated['overtime_rate'];
                }
                $overtime->update($validated);
            });
            return $this->successResponse($request, 'Data lembur berhasil diperbarui.', $overtime);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui lembur.', ['exception' => $e, 'overtime_id' => $id]);
            return $this->errorResponse($request, 'Data lembur gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $overtime = $this->model->findOrFail($id);
            if ($overtime->is_paid) {
                return $this->errorResponse($request, 'Lembur yang sudah dibayar tidak dapat dihapus.');
            }
            DB::transaction(function () use ($overtime) {
                $overtime->delete();
            });
            return $this->successResponse($request, 'Data lembur berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus lembur.', ['exception' => $e, 'overtime_id' => $id]);
            return $this->errorResponse($request, 'Data lembur gagal dihapus.');
        }
    }

    public function approve(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $overtime = $this->model->findOrFail($id);
            DB::transaction(function () use ($overtime) {
                $overtime->update([
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);
            });
            return $this->successResponse($request, 'Lembur berhasil disetujui.', $overtime);
        } catch (Throwable $e) {
            Log::error('Gagal menyetujui lembur.', ['exception' => $e, 'overtime_id' => $id]);
            return $this->errorResponse($request, 'Gagal menyetujui lembur.');
        }
    }

    public function bulkApprove(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:overtimes,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                Overtime::whereIn('id', $validated['ids'])
                    ->where('is_paid', 0)
                    ->update([
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);
            });
            return $this->successResponse($request, 'Lembur berhasil disetujui secara massal.');
        } catch (Throwable $e) {
            Log::error('Gagal menyetujui lembur massal.', ['exception' => $e]);
            return $this->errorResponse($request, 'Gagal menyetujui lembur secara massal.');
        }
    }

    public function getByPeriod($periodId): JsonResponse
    {
        try {
            $period = PayrollPeriod::findOrFail($periodId);

            // ⚠️ Sesuaikan nama kolom tanggal periode sesuai tabelmu
            $overtimes = Overtime::with('employee')
                ->whereBetween('overtime_date', [
                    $period->start_date,
                    $period->end_date,
                ])
                ->where('is_paid', 0)
                ->get();

            return response()->json(['success' => true, 'data' => $overtimes]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil lembur per periode.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}