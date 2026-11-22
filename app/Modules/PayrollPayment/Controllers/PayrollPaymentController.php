<?php

namespace App\Modules\PayrollPayment\Controllers;

use App\DataTables\PayrollPaymentDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PayrollPaymentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollPayment());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        PayrollPaymentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payroll-payment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PayrollPayment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $payrollPayment = new PayrollPayment();
        $result = $this->getForm($payrollPayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollPayment::create',
            compact('payrollPayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payroll_payment_batch_id' => ['required', 'integer'],
            'payroll_detail_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'bank_reference' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'failure_reason' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PayrollPayment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollPayment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PayrollPayment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollPayment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $payrollPayment = $this->model->findOrFail($id);
        $result = $this->getForm($payrollPayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PayrollPayment::edit',
            compact('payrollPayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'payroll_payment_batch_id' => ['required', 'integer'],
            'payroll_detail_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'bank_reference' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'failure_reason' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $payrollPayment = $this->model->findOrFail($id);
            DB::transaction(function () use ($payrollPayment, $validated) {
                $payrollPayment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PayrollPayment berhasil diperbarui.',
                $payrollPayment
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PayrollPayment.',
                [
                    'exception' => $e,
                    'PayrollPayment_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollPayment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $payrollPayment = $this->model->findOrFail($id);

            DB::transaction(function () use ($payrollPayment) {
                $payrollPayment->delete();
            });

            return $this->successResponse(
                $request,
                'Data PayrollPayment berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $payrollPayment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PayrollPayment.',
                [
                    'exception' => $e,
                    'PayrollPayment_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PayrollPayment gagal dihapus.'
            );
        }
    }
}
