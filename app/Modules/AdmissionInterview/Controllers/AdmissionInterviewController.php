<?php

namespace App\Modules\AdmissionInterview\Controllers;

use App\DataTables\AdmissionInterviewDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionInterview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionInterviewController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionInterview());
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
        AdmissionInterviewDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-interview?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionInterview::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionInterview = new AdmissionInterview();
        $result = $this->getForm($admissionInterview);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionInterview::create',
            compact('admissionInterview', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'interviewer_id' => ['required', 'integer'],
            'secondary_interviewer_id' => ['required', 'integer'],
            'room_id' => ['required', 'integer'],
            'schedule_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'interview_type' => ['required', 'string', 'max:255'],
            'meeting_url' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'attendance_status' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'recommendation' => ['required', 'string', 'max:255'],
            'result' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'internal_notes' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionInterview::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionInterview berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionInterview.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionInterview gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionInterview = $this->model->findOrFail($id);
        $result = $this->getForm($admissionInterview);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionInterview::edit',
            compact('admissionInterview', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'interviewer_id' => ['required', 'integer'],
            'secondary_interviewer_id' => ['required', 'integer'],
            'room_id' => ['required', 'integer'],
            'schedule_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'interview_type' => ['required', 'string', 'max:255'],
            'meeting_url' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'attendance_status' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'recommendation' => ['required', 'string', 'max:255'],
            'result' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'internal_notes' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionInterview = $this->model->findOrFail($id);
            DB::transaction(function () use ($admissionInterview, $validated) {
                $admissionInterview->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionInterview berhasil diperbarui.',
                $admissionInterview
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AdmissionInterview.',
                [
                    'exception' => $e,
                    'AdmissionInterview_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionInterview gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionInterview = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionInterview) {
                $admissionInterview->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionInterview berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $admissionInterview->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionInterview.',
                [
                    'exception' => $e,
                    'AdmissionInterview_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionInterview gagal dihapus.'
            );
        }
    }
}
