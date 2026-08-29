<?php

namespace App\Modules\Overtime\Controllers;

use App\DataTables\OvertimeDataTable;
use App\Http\Controllers\MainController;
use Models\Overtime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View; 
use Models\Employee;
use Models\PayrollPeriod; 
use Carbon\Carbon;
use Throwable;

class OvertimeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Overtime());
    }

    public function index(Request $request, OvertimeDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::overtime.index');
    }

    public function create(): View
    {
        $overtime = new Overtime();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');

        $result = $this->getForm($overtime);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::overtime.edit', compact('overtime', 'forms', 'groupedForms', 'employees', 'periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'overtime_date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'total_hours' => 'required|numeric|min:0',
            'overtime_rate' => 'required|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
        ]);

        try {
            $overtime = DB::transaction(function () use ($validated) {
                // Calculate total amount if not provided
                if (empty($validated['total_amount'])) {
                    $validated['total_amount'] = $validated['total_hours'] * $validated['overtime_rate'];
                }

                return Overtime::create($validated);
            });

            return $this->successResponse($request, 'Data lembur berhasil ditambahkan.', $overtime);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan lembur.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data lembur gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $overtime = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');

        $result = $this->getForm($overtime);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::overtime.edit', compact('overtime', 'forms', 'groupedForms', 'employees', 'periods'));
    }

    public function update(Request $request, $id)
    {
        $overtime = $this->model->findOrFail($id);

        if ($overtime->is_paid) {
            return $this->errorResponse($request, 'Lembur yang sudah dibayar tidak dapat diubah.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'overtime_date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'total_hours' => 'required|numeric|min:0',
            'overtime_rate' => 'required|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
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

            DB::transaction(function () use ($overtime, $request) {
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
            Log::error('Gagal menyetujui lembur massal.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Gagal menyetujui lembur secara massal.');
        }
    }

    public function getByPeriod($periodId): JsonResponse
    {
        try {
            $period = PayrollPeriod::findOrFail($periodId);
            $overtimes = Overtime::with('employee')
                ->whereBetween('overtime_date', [
                    $period->period_start_date,
                    $period->period_end_date
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