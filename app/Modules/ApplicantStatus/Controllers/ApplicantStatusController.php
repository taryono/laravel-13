<?php

namespace App\Modules\ApplicantStatus\Controllers;

use App\DataTables\ApplicantStatusDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantStatusController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantStatus());
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
        ApplicantStatusDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-status?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantStatus::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantStatus = new ApplicantStatus();
        $result = $this->getForm($applicantStatus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantStatus::create',
            compact('applicantStatus', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'status_group' => ['required', 'string', 'max:255'],
            'status_type' => ['required', 'string', 'max:255'],
            'stage_order' => ['required', 'string', 'max:255'],
            'is_final' => ['required', 'string', 'max:255'],
            'is_success' => ['required', 'string', 'max:255'],
            'is_failure' => ['required', 'string', 'max:255'],
            'is_default' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'allow_edit' => ['required', 'string', 'max:255'],
            'allow_upload_document' => ['required', 'string', 'max:255'],
            'allow_payment' => ['required', 'string', 'max:255'],
            'next_status_ids' => ['required', 'string', 'max:255'],
            'notify_applicant' => ['required', 'string', 'max:255'],
            'notification_template' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'badge_class' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantStatus::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantStatus berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantStatus.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantStatus gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantStatus = $this->model->findOrFail($id);
        $result = $this->getForm($applicantStatus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantStatus::edit',
            compact('applicantStatus', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'status_group' => ['required', 'string', 'max:255'],
            'status_type' => ['required', 'string', 'max:255'],
            'stage_order' => ['required', 'string', 'max:255'],
            'is_final' => ['required', 'string', 'max:255'],
            'is_success' => ['required', 'string', 'max:255'],
            'is_failure' => ['required', 'string', 'max:255'],
            'is_default' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'allow_edit' => ['required', 'string', 'max:255'],
            'allow_upload_document' => ['required', 'string', 'max:255'],
            'allow_payment' => ['required', 'string', 'max:255'],
            'next_status_ids' => ['required', 'string', 'max:255'],
            'notify_applicant' => ['required', 'string', 'max:255'],
            'notification_template' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'badge_class' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantStatus = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $applicantStatus,
                $validated
            ) {
                $applicantStatus->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantStatus berhasil diperbarui.',
                $applicantStatus
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ApplicantStatus.',
                [
                    'exception' => $e,
                    'ApplicantStatus_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantStatus gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantStatus = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantStatus) {
                $applicantStatus->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantStatus berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $applicantStatus->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantStatus.',
                [
                    'exception' => $e,
                    'ApplicantStatus_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantStatus gagal dihapus.'
            );
        }
    } 
}
