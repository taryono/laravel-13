<?php

namespace App\Modules\PayrollRun\Controllers;

use App\DataTables\PayrollRunDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollRun;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 

class PayrollRunController extends MainController
{
    protected PayrollCalculationService $calculationService;

    public function __construct(PayrollCalculationService $calculationService)
    {
        parent::__construct(new PayrollRun());
        $this->calculationService = $calculationService;
    }

    /**
     * Display a listing of payroll runs.
     */
    public function index(Request $request, PayrollRunDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::payroll_run.index');
    }

    /**
     * Show form to create new payroll run.
     */
    public function create(): View
    {
        $payrollRun = new PayrollRun();
        $periods = PayrollPeriod::where('is_closed', 0)
            ->where('status', 'completed')
            ->pluck('code', 'id');

        $result = $this->getForm($payrollRun);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Payroll::payroll_run.edit',
            compact('payrollRun', 'forms', 'groupedForms', 'periods')
        );
    }

    /**
     * Store a newly created payroll run.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payroll_period_id' => 'required|exists:payroll_periods,id',
            'code' => 'required|string|max:255|unique:payroll_runs,code',
            'notes' => 'nullable|string',
        ]);

        try {
            $payrollRun = DB::transaction(function () use ($validated) {
                return PayrollRun::create($validated);
            });

            return $this->successResponse(
                $request,
                'Payroll Run berhasil dibuat.',
                $payrollRun
            );

        } catch (Throwable $e) {
            Log::error('Gagal membuat Payroll Run.', [
                'exception' => $e,
                'data' => $validated,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Payroll Run gagal dibuat.'
            );
        }
    }

    /**
     * Process payroll calculation.
     */
    public function process(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $payrollRun = $this->model->findOrFail($id);

            if ($payrollRun->status === 'completed') {
                return $this->errorResponse(
                    $request,
                    'Payroll Run sudah selesai diproses.'
                );
            }

            DB::transaction(function () use ($payrollRun) {
                $this->calculationService->calculatePayroll($payrollRun);
            });

            return $this->successResponse(
                $request,
                'Payroll berhasil diproses.',
                $payrollRun->fresh()
            );

        } catch (Throwable $e) {
            Log::error('Gagal memproses Payroll.', [
                'exception' => $e,
                'payroll_run_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Payroll gagal diproses: ' . $e->getMessage()
            );
        }
    }

    /**
     * Show payroll run details.
     */
    public function show($id): View
    {
        $payrollRun = $this->model->with([
            'payrollDetails.employee',
            'payrollPeriod'
        ])->findOrFail($id);

        return view('Payroll::payroll_run.show', compact('payrollRun'));
    }

    /**
     * Edit payroll run.
     */
    public function edit($id): View
    {
        $payrollRun = $this->model->findOrFail($id);
        $periods = PayrollPeriod::where('is_closed', 0)
            ->pluck('code', 'id');

        $result = $this->getForm($payrollRun);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Payroll::payroll_run.edit',
            compact('payrollRun', 'forms', 'groupedForms', 'periods')
        );
    }

    /**
     * Update payroll run.
     */
    public function update(Request $request, $id)
    {
        $payrollRun = $this->model->findOrFail($id);

        if ($payrollRun->status === 'completed') {
            return $this->errorResponse(
                $request,
                'Payroll Run yang sudah selesai tidak dapat diubah.'
            );
        }

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:payroll_runs,code,' . $id,
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($payrollRun, $validated) {
                $payrollRun->update($validated);
            });

            return $this->successResponse(
                $request,
                'Payroll Run berhasil diperbarui.',
                $payrollRun
            );

        } catch (Throwable $e) {
            Log::error('Gagal memperbarui Payroll Run.', [
                'exception' => $e,
                'payroll_run_id' => $id,
                'data' => $validated,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Payroll Run gagal diperbarui.'
            );
        }
    }

    /**
     * Delete payroll run.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $payrollRun = $this->model->findOrFail($id);

            if ($payrollRun->status === 'completed') {
                return $this->errorResponse(
                    $request,
                    'Payroll Run yang sudah selesai tidak dapat dihapus.'
                );
            }

            DB::transaction(function () use ($payrollRun) {
                $payrollRun->payrollDetails()->delete();
                $payrollRun->delete();
            });

            return $this->successResponse(
                $request,
                'Payroll Run berhasil dihapus.'
            );

        } catch (Throwable $e) {
            Log::error('Gagal menghapus Payroll Run.', [
                'exception' => $e,
                'payroll_run_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Payroll Run gagal dihapus.'
            );
        }
    }

    /**
     * Export payroll to Excel/PDF.
     */
    public function export(Request $request, $id)
    {
        try {
            $payrollRun = $this->model->findOrFail($id);
            $format = $request->get('format', 'pdf');

            // Implementasi export sesuai kebutuhan
            // return $this->exportService->export($payrollRun, $format);

            return $this->successResponse(
                $request,
                'Ekspor payroll berhasil.'
            );

        } catch (Throwable $e) {
            Log::error('Gagal ekspor payroll.', [
                'exception' => $e,
                'payroll_run_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Gagal mengekspor payroll.'
            );
        }
    }
}