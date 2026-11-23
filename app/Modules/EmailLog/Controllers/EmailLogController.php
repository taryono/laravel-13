<?php

namespace App\Modules\EmailLog\Controllers;

use App\DataTables\EmailLogDataTable;
use App\Http\Controllers\MainController;
use Models\EmailLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmailLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmailLog());
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
        EmailLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /email-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmailLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $emailLog = new EmailLog();
        $result = $this->getForm($emailLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmailLog::create',
            compact('emailLog', 'forms', 'groupedForms')
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
            'to_email' => ['required', 'string', 'max:255'],
            'to_name' => ['required', 'string', 'max:255'],
            'cc' => ['required', 'string', 'max:255'],
            'bcc' => ['required', 'string', 'max:255'],
            'from_email' => ['required', 'string', 'max:255'],
            'from_name' => ['required', 'string', 'max:255'],
            'reply_to' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
            'body_html' => ['required', 'string', 'max:255'],
            'attachments' => ['required', 'string', 'max:255'],
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
            'opened_at' => ['required', 'string', 'max:255'],
            'clicked_at' => ['required', 'string', 'max:255'],
            'bounced_at' => ['required', 'string', 'max:255'],
            'failed_at' => ['required', 'string', 'max:255'],
            'open_count' => ['required', 'string', 'max:255'],
            'click_count' => ['required', 'string', 'max:255'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmailLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmailLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmailLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmailLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $emailLog = $this->model->findOrFail($id);
        $result = $this->getForm($emailLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmailLog::edit',
            compact('emailLog', 'forms', 'groupedForms')
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
            'to_email' => ['required', 'string', 'max:255'],
            'to_name' => ['required', 'string', 'max:255'],
            'cc' => ['required', 'string', 'max:255'],
            'bcc' => ['required', 'string', 'max:255'],
            'from_email' => ['required', 'string', 'max:255'],
            'from_name' => ['required', 'string', 'max:255'],
            'reply_to' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
            'body_html' => ['required', 'string', 'max:255'],
            'attachments' => ['required', 'string', 'max:255'],
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
            'opened_at' => ['required', 'string', 'max:255'],
            'clicked_at' => ['required', 'string', 'max:255'],
            'bounced_at' => ['required', 'string', 'max:255'],
            'failed_at' => ['required', 'string', 'max:255'],
            'open_count' => ['required', 'string', 'max:255'],
            'click_count' => ['required', 'string', 'max:255'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $emailLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($emailLog, $validated) {
                $emailLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmailLog berhasil diperbarui.',
                $emailLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui EmailLog.',
                [
                    'exception' => $e,
                    'EmailLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmailLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $emailLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($emailLog) {
                $emailLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmailLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $emailLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmailLog.',
                [
                    'exception' => $e,
                    'EmailLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmailLog gagal dihapus.'
            );
        }
    }
}
