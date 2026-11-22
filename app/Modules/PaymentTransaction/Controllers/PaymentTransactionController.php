<?php

namespace App\Modules\PaymentTransaction\Controllers;

use App\DataTables\PaymentTransactionDataTable;
use App\Http\Controllers\MainController;
use Models\PaymentTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PaymentTransactionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PaymentTransaction());
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
        PaymentTransactionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payment-transaction?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PaymentTransaction::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $paymentTransaction = new PaymentTransaction();
        $result = $this->getForm($paymentTransaction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentTransaction::create',
            compact('paymentTransaction', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'payment_gateway_id' => ['required', 'integer'],
            'payment_channel_id' => ['required', 'integer'],
            'student_invoice_id' => ['required', 'integer'],
            'applicant_invoice_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'order_id' => ['required', 'integer'],
            'midtrans_transaction_id' => ['required', 'integer'],
            'transaction_type' => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'gross_amount' => ['required', 'string', 'max:255'],
            'admin_fee' => ['required', 'string', 'max:255'],
            'net_amount' => ['required', 'string', 'max:255'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'functional_amount' => ['required', 'string', 'max:255'],
            'transaction_status' => ['required', 'string', 'max:255'],
            'fraud_status' => ['required', 'string', 'max:255'],
            'status_code' => ['required', 'string', 'max:255'],
            'status_message' => ['required', 'string', 'max:255'],
            'transaction_time' => ['required', 'string', 'max:255'],
            'settlement_time' => ['required', 'string', 'max:255'],
            'expiry_time' => ['required', 'string', 'max:255'],
            'va_number' => ['required', 'string', 'max:255'],
            'va_bank' => ['required', 'string', 'max:255'],
            'qris_string' => ['required', 'string', 'max:255'],
            'qris_url' => ['required', 'string', 'max:255'],
            'payment_code' => ['required', 'string', 'max:255'],
            'pdf_url' => ['required', 'string', 'max:255'],
            'raw_request' => ['required', 'string', 'max:255'],
            'raw_response' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'student_payment_id' => ['required', 'integer'],
            'applicant_payment_id' => ['required', 'integer'],
            'is_posted' => ['required', 'string', 'max:255'],
            'posted_at' => ['required', 'string', 'max:255'],
            'expired_at' => ['required', 'string', 'max:255'],
            'cancelled_at' => ['required', 'string', 'max:255'],
            'refunded_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PaymentTransaction::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentTransaction berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PaymentTransaction.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentTransaction gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paymentTransaction = $this->model->findOrFail($id);
        $result = $this->getForm($paymentTransaction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentTransaction::edit',
            compact('paymentTransaction', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'payment_gateway_id' => ['required', 'integer'],
            'payment_channel_id' => ['required', 'integer'],
            'student_invoice_id' => ['required', 'integer'],
            'applicant_invoice_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'order_id' => ['required', 'integer'],
            'midtrans_transaction_id' => ['required', 'integer'],
            'transaction_type' => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'gross_amount' => ['required', 'string', 'max:255'],
            'admin_fee' => ['required', 'string', 'max:255'],
            'net_amount' => ['required', 'string', 'max:255'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'functional_amount' => ['required', 'string', 'max:255'],
            'transaction_status' => ['required', 'string', 'max:255'],
            'fraud_status' => ['required', 'string', 'max:255'],
            'status_code' => ['required', 'string', 'max:255'],
            'status_message' => ['required', 'string', 'max:255'],
            'transaction_time' => ['required', 'string', 'max:255'],
            'settlement_time' => ['required', 'string', 'max:255'],
            'expiry_time' => ['required', 'string', 'max:255'],
            'va_number' => ['required', 'string', 'max:255'],
            'va_bank' => ['required', 'string', 'max:255'],
            'qris_string' => ['required', 'string', 'max:255'],
            'qris_url' => ['required', 'string', 'max:255'],
            'payment_code' => ['required', 'string', 'max:255'],
            'pdf_url' => ['required', 'string', 'max:255'],
            'raw_request' => ['required', 'string', 'max:255'],
            'raw_response' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'student_payment_id' => ['required', 'integer'],
            'applicant_payment_id' => ['required', 'integer'],
            'is_posted' => ['required', 'string', 'max:255'],
            'posted_at' => ['required', 'string', 'max:255'],
            'expired_at' => ['required', 'string', 'max:255'],
            'cancelled_at' => ['required', 'string', 'max:255'],
            'refunded_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $paymentTransaction = $this->model->findOrFail($id);
            DB::transaction(function () use ($paymentTransaction, $validated) {
                $paymentTransaction->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentTransaction berhasil diperbarui.',
                $paymentTransaction
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PaymentTransaction.',
                [
                    'exception' => $e,
                    'PaymentTransaction_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentTransaction gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $paymentTransaction = $this->model->findOrFail($id);

            DB::transaction(function () use ($paymentTransaction) {
                $paymentTransaction->delete();
            });

            return $this->successResponse(
                $request,
                'Data PaymentTransaction berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $paymentTransaction->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PaymentTransaction.',
                [
                    'exception' => $e,
                    'PaymentTransaction_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentTransaction gagal dihapus.'
            );
        }
    }
}
