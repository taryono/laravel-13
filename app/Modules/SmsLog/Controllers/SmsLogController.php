<?php

namespace App\Modules\SmsLog\Controllers;

use App\DataTables\SmsLogDataTable;
use App\Http\Controllers\MainController;
use Models\SmsLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SmsLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SmsLog());
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
        SmsLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /sms-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SmsLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $smsLog = new SmsLog();
        $result = $this->getForm($smsLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SmsLog::create',
            compact('smsLog', 'forms', 'groupedForms')
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
            'message_body' => ['required', 'string', 'max:255'],
            'message_length' => ['required', 'string', 'max:255'],
            'message_parts' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_message_id' => ['required', 'integer'],
            'provider_response' => ['required', 'string', 'max:255'],
            'sender_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'error_code' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'max_retries' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'delivered_at' => ['required', 'string', 'max:255'],
            'failed_at' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SmsLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SmsLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SmsLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SmsLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $smsLog = $this->model->findOrFail($id);
        $result = $this->getForm($smsLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SmsLog::edit',
            compact('smsLog', 'forms', 'groupedForms')
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
            'message_body' => ['required', 'string', 'max:255'],
            'message_length' => ['required', 'string', 'max:255'],
            'message_parts' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_message_id' => ['required', 'integer'],
            'provider_response' => ['required', 'string', 'max:255'],
            'sender_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'error_code' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'max_retries' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'delivered_at' => ['required', 'string', 'max:255'],
            'failed_at' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $smsLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($smsLog, $validated) {
                $smsLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SmsLog berhasil diperbarui.',
                $smsLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui SmsLog.',
                [
                    'exception' => $e,
                    'SmsLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SmsLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $smsLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($smsLog) {
                $smsLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data SmsLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $smsLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SmsLog.',
                [
                    'exception' => $e,
                    'SmsLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SmsLog gagal dihapus.'
            );
        }
    }
}
