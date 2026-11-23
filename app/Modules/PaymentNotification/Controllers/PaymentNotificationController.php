<?php

namespace App\Modules\PaymentNotification\Controllers;

use App\DataTables\PaymentNotificationDataTable;
use App\Http\Controllers\MainController;
use Models\PaymentNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PaymentNotificationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PaymentNotification());
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
        PaymentNotificationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payment-notification?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PaymentNotification::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $paymentNotification = new PaymentNotification();
        $result = $this->getForm($paymentNotification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentNotification::create',
            compact('paymentNotification', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'payment_transaction_id' => ['required', 'integer'],
            'student_invoice_id' => ['required', 'integer'],
            'applicant_invoice_id' => ['required', 'integer'],
            'recipient_user_id' => ['required', 'integer'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:255'],
            'recipient_email' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', 'max:255'],
            'template_code' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_message_id' => ['required', 'integer'],
            'provider_response' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'delivered_at' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'failed_reason' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PaymentNotification::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentNotification berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PaymentNotification.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentNotification gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paymentNotification = $this->model->findOrFail($id);
        $result = $this->getForm($paymentNotification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentNotification::edit',
            compact('paymentNotification', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'payment_transaction_id' => ['required', 'integer'],
            'student_invoice_id' => ['required', 'integer'],
            'applicant_invoice_id' => ['required', 'integer'],
            'recipient_user_id' => ['required', 'integer'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:255'],
            'recipient_email' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', 'max:255'],
            'template_code' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_message_id' => ['required', 'integer'],
            'provider_response' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'delivered_at' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'failed_reason' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $paymentNotification = $this->model->findOrFail($id);
            DB::transaction(function () use ($paymentNotification, $validated) {
                $paymentNotification->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentNotification berhasil diperbarui.',
                $paymentNotification
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PaymentNotification.',
                [
                    'exception' => $e,
                    'PaymentNotification_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentNotification gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $paymentNotification = $this->model->findOrFail($id);

            DB::transaction(function () use ($paymentNotification) {
                $paymentNotification->delete();
            });

            return $this->successResponse(
                $request,
                'Data PaymentNotification berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $paymentNotification->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PaymentNotification.',
                [
                    'exception' => $e,
                    'PaymentNotification_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentNotification gagal dihapus.'
            );
        }
    }
}
