<?php

namespace App\Modules\ApplicantNotification\Controllers;

use App\DataTables\ApplicantNotificationDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantNotificationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantNotification());
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
        ApplicantNotificationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-notification?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantNotification::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantNotification = new ApplicantNotification();
        $result = $this->getForm($applicantNotification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantNotification::create',
            compact('applicantNotification', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'applicant_id' => ['required', 'integer'],
            'notification_type' => ['required', 'string', 'max:255'],
            'template_code' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
            'recipient' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'max_retry' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_message_id' => ['required', 'integer'],
            'provider_response' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'delivered_at' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantNotification::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantNotification berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantNotification.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantNotification gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantNotification = $this->model->findOrFail($id);
        $result = $this->getForm($applicantNotification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantNotification::edit',
            compact('applicantNotification', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'applicant_id' => ['required', 'integer'],
            'notification_type' => ['required', 'string', 'max:255'],
            'template_code' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
            'recipient' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'retry_count' => ['required', 'string', 'max:255'],
            'max_retry' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_message_id' => ['required', 'integer'],
            'provider_response' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'delivered_at' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantNotification = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $applicantNotification,
                $validated
            ) {
                $applicantNotification->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantNotification berhasil diperbarui.',
                $applicantNotification
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ApplicantNotification.',
                [
                    'exception' => $e,
                    'ApplicantNotification_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantNotification gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantNotification = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantNotification) {
                $applicantNotification->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantNotification berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $applicantNotification->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantNotification.',
                [
                    'exception' => $e,
                    'ApplicantNotification_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantNotification gagal dihapus.'
            );
        }
    } 
}
