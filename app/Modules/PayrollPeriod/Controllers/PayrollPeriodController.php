<?php

namespace App\Modules\PayrollPeriod\Controllers;

use App\DataTables\PayrollPeriodDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PayrollPeriodController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollPeriod());
    }

    public function index(Request $request, PayrollPeriodDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('PayrollPeriod::index');
    }

    public function create(): View
    {
        $period = new PayrollPeriod();
        $result = $this->getForm($period);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('PayrollPeriod::create', compact('period', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:payroll_periods,code',
            'period_month' => 'required|integer|min:1|max:12',
            'period_year' => 'required|integer|min:2000|max:2100',
            'period_start_date' => 'required|date',
            'period_end_date' => 'required|date|after:period_start_date',
            'payment_date' => 'nullable|date|after:period_end_date',
            'status' => 'required|in:draft,processing,completed,cancelled',
            'is_closed' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            $period = DB::transaction(function () use ($validated) {
                return PayrollPeriod::create($validated);
            });

            return $this->successResponse($request, 'Data periode payroll berhasil ditambahkan.', $period);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan periode payroll.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data periode payroll gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $period = $this->model->findOrFail($id);
        $result = $this->getForm($period);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('PayrollPeriod::edit', compact('period', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $period = $this->model->findOrFail($id);

        if ($period->is_closed) {
            return $this->errorResponse($request, 'Periode yang sudah ditutup tidak dapat diubah.');
        }

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:payroll_periods,code,' . $id,
            'period_month' => 'required|integer|min:1|max:12',
            'period_year' => 'required|integer|min:2000|max:2100',
            'period_start_date' => 'required|date',
            'period_end_date' => 'required|date|after:period_start_date',
            'payment_date' => 'nullable|date|after:period_end_date',
            'status' => 'required|in:draft,processing,completed,cancelled',
            'is_closed' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($period, $validated) {
                $period->update($validated);
            });

            return $this->successResponse($request, 'Data periode payroll berhasil diperbarui.', $period);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui periode payroll.', ['exception' => $e, 'period_id' => $id]);
            return $this->errorResponse($request, 'Data periode payroll gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $period = $this->model->findOrFail($id);

            if ($period->payrollRuns()->exists()) {
                return $this->errorResponse($request, 'Periode tidak dapat dihapus karena sudah memiliki proses payroll.');
            }

            DB::transaction(function () use ($period) {
                $period->delete();
            });

            return $this->successResponse($request, 'Data periode payroll berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus periode payroll.', ['exception' => $e, 'period_id' => $id]);
            return $this->errorResponse($request, 'Data periode payroll gagal dihapus.');
        }
    }

    public function close(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $period = $this->model->findOrFail($id);

            if ($period->status !== 'completed') {
                return $this->errorResponse($request, 'Periode harus completed terlebih dahulu sebelum ditutup.');
            }

            DB::transaction(function () use ($period) {
                $period->update([
                    'is_closed' => true,
                    'closed_at' => now(),
                ]);
            });

            return $this->successResponse($request, 'Periode payroll berhasil ditutup.', $period);
        } catch (Throwable $e) {
            Log::error('Gagal menutup periode payroll.', ['exception' => $e, 'period_id' => $id]);
            return $this->errorResponse($request, 'Gagal menutup periode payroll.');
        }
    }

    public function reopen(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $period = $this->model->findOrFail($id);

            DB::transaction(function () use ($period) {
                $period->update([
                    'is_closed' => false,
                    'closed_at' => null,
                ]);
            });

            return $this->successResponse($request, 'Periode payroll berhasil dibuka kembali.', $period);
        } catch (Throwable $e) {
            Log::error('Gagal membuka periode payroll.', ['exception' => $e, 'period_id' => $id]);
            return $this->errorResponse($request, 'Gagal membuka periode payroll.');
        }
    }

    public function getCurrentPeriod(): JsonResponse
    {
        try {
            $period = PayrollPeriod::where('status', 'completed')
                ->where('is_closed', 0)
                ->orderBy('period_year', 'desc')
                ->orderBy('period_month', 'desc')
                ->first();

            return response()->json(['success' => true, 'data' => $period]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil periode aktif.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}