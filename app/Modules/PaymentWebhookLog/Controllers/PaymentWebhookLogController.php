<?php

namespace App\Modules\PaymentWebhookLog\Controllers;

use App\DataTables\PaymentWebhookLogDataTable;
use App\Http\Controllers\MainController;
use Models\PaymentWebhookLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PaymentWebhookLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PaymentWebhookLog());
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
        PaymentWebhookLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payment-webhook-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PaymentWebhookLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $paymentWebhookLog = new PaymentWebhookLog();
        $result = $this->getForm($paymentWebhookLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentWebhookLog::create',
            compact('paymentWebhookLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_gateway_id' => ['required', 'integer'],
            'order_id' => ['required', 'integer'],
            'transaction_id' => ['required', 'integer'],
            'event_type' => ['required', 'string', 'max:255'],
            'signature_key' => ['required', 'string', 'max:255'],
            'signature_valid' => ['required', 'string', 'max:255'],
            'is_processed' => ['required', 'string', 'max:255'],
            'processed_at' => ['required', 'string', 'max:255'],
            'processing_notes' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'headers' => ['required', 'string', 'max:255'],
            'payload' => ['required', 'string', 'max:255'],
            'response_body' => ['required', 'string', 'max:255'],
            'response_status' => ['required', 'string', 'max:255'],
            'received_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PaymentWebhookLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentWebhookLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PaymentWebhookLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentWebhookLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paymentWebhookLog = $this->model->findOrFail($id);
        $result = $this->getForm($paymentWebhookLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentWebhookLog::edit',
            compact('paymentWebhookLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_gateway_id' => ['required', 'integer'],
            'order_id' => ['required', 'integer'],
            'transaction_id' => ['required', 'integer'],
            'event_type' => ['required', 'string', 'max:255'],
            'signature_key' => ['required', 'string', 'max:255'],
            'signature_valid' => ['required', 'string', 'max:255'],
            'is_processed' => ['required', 'string', 'max:255'],
            'processed_at' => ['required', 'string', 'max:255'],
            'processing_notes' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'headers' => ['required', 'string', 'max:255'],
            'payload' => ['required', 'string', 'max:255'],
            'response_body' => ['required', 'string', 'max:255'],
            'response_status' => ['required', 'string', 'max:255'],
            'received_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $paymentWebhookLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($paymentWebhookLog, $validated) {
                $paymentWebhookLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentWebhookLog berhasil diperbarui.',
                $paymentWebhookLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PaymentWebhookLog.',
                [
                    'exception' => $e,
                    'PaymentWebhookLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentWebhookLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $paymentWebhookLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($paymentWebhookLog) {
                $paymentWebhookLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data PaymentWebhookLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $paymentWebhookLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PaymentWebhookLog.',
                [
                    'exception' => $e,
                    'PaymentWebhookLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentWebhookLog gagal dihapus.'
            );
        }
    }
}
