<?php

namespace App\Modules\WhatsappLog\Controllers;

use App\DataTables\WhatsappLogDataTable;
use App\Http\Controllers\MainController;
use Models\WhatsappLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WhatsappLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WhatsappLog());
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
        WhatsappLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /whatsapp-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WhatsappLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $whatsappLog = new WhatsappLog();
        $result = $this->getForm($whatsappLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WhatsappLog::create',
            compact('whatsappLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'notification_template_id' => ['required', 'integer'],
            'recipient_user_id' => ['required', 'integer'],
            'recipient_student_id' => ['required', 'integer'],
            'recipient_employee_id' => ['required', 'integer'],
            'phone_number' => ['required', 'string', 'max:255'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'message_type' => ['required', 'string', 'max:255'],
            'message_body' => ['required', 'string', 'max:255'],
            'template_name' => ['required', 'string', 'max:255'],
            'template_variables' => ['required', 'string', 'max:255'],
            'media_url' => ['required', 'string', 'max:255'],
            'media_path' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_message_id' => ['required', 'integer'],
            'provider_response' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'error_code' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'max_retries' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'delivered_at' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'failed_at' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WhatsappLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WhatsappLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WhatsappLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WhatsappLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $whatsappLog = $this->model->findOrFail($id);
        $result = $this->getForm($whatsappLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WhatsappLog::edit',
            compact('whatsappLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'notification_template_id' => ['required', 'integer'],
            'recipient_user_id' => ['required', 'integer'],
            'recipient_student_id' => ['required', 'integer'],
            'recipient_employee_id' => ['required', 'integer'],
            'phone_number' => ['required', 'string', 'max:255'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'message_type' => ['required', 'string', 'max:255'],
            'message_body' => ['required', 'string', 'max:255'],
            'template_name' => ['required', 'string', 'max:255'],
            'template_variables' => ['required', 'string', 'max:255'],
            'media_url' => ['required', 'string', 'max:255'],
            'media_path' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_message_id' => ['required', 'integer'],
            'provider_response' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'error_code' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'max_retries' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'delivered_at' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'failed_at' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $whatsappLog = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $whatsappLog,
                $validated
            ) {
                $whatsappLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WhatsappLog berhasil diperbarui.',
                $whatsappLog
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui WhatsappLog.',
                [
                    'exception' => $e,
                    'WhatsappLog_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WhatsappLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $whatsappLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($whatsappLog) {
                $whatsappLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data WhatsappLog berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $whatsappLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WhatsappLog.',
                [
                    'exception' => $e,
                    'WhatsappLog_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WhatsappLog gagal dihapus.'
            );
        }
    } 
}
