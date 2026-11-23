<?php

namespace App\Modules\PayrollPaymentBatch\Controllers;

use App\DataTables\PayrollPaymentBatchDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollPaymentBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PayrollPaymentBatchController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollPaymentBatch());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        PayrollPaymentBatchDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payroll-payment-batch?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PayrollPaymentBatch::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $payrollPaymentBatch = new PayrollPaymentBatch();
        $result = $this->getForm($payrollPaymentBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollPaymentBatch::create',
            compact('payrollPaymentBatch', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'payroll_run_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'batch_number' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'employee_count' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'processed_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'failed_reason' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PayrollPaymentBatch::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollPaymentBatch berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PayrollPaymentBatch.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollPaymentBatch gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $payrollPaymentBatch = $this->model->findOrFail($id);
        $result = $this->getForm($payrollPaymentBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollPaymentBatch::edit',
            compact('payrollPaymentBatch', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'payroll_run_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'batch_number' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'employee_count' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'processed_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'failed_reason' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $payrollPaymentBatch = $this->model->findOrFail($id);
            DB::transaction(function () use ($payrollPaymentBatch, $validated) {
                $payrollPaymentBatch->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollPaymentBatch berhasil diperbarui.',
                $payrollPaymentBatch
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PayrollPaymentBatch.',
                [
                    'exception' => $e,
                    'PayrollPaymentBatch_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollPaymentBatch gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $payrollPaymentBatch = $this->model->findOrFail($id);

            DB::transaction(function () use ($payrollPaymentBatch) {
                $payrollPaymentBatch->delete();
            });

            return $this->successResponse(
                $request,
                'Data PayrollPaymentBatch berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $payrollPaymentBatch->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PayrollPaymentBatch.',
                [
                    'exception' => $e,
                    'PayrollPaymentBatch_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollPaymentBatch gagal dihapus.'
            );
        }
    }
}
