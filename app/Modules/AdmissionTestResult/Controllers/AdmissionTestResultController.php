<?php

namespace App\Modules\AdmissionTestResult\Controllers;

use App\DataTables\AdmissionTestResultDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionTestResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionTestResultController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionTestResult());
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
        AdmissionTestResultDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-test-result?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionTestResult::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionTestResult = new AdmissionTestResult();
        $result = $this->getForm($admissionTestResult);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionTestResult::create',
            compact('admissionTestResult', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'admission_test_schedule_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'test_type_id' => ['required', 'integer'],
            'registration_number' => ['required', 'string', 'max:255'],
            'attendance_status' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'raw_score' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'grade' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'passing_status' => ['required', 'string', 'max:255'],
            'cbt_session_id' => ['required', 'integer'],
            'cbt_session_answer_id' => ['required', 'integer'],
            'detail_scores' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'graded_by' => ['required', 'string', 'max:255'],
            'graded_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionTestResult::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionTestResult berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionTestResult.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionTestResult gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionTestResult = $this->model->findOrFail($id);
        $result = $this->getForm($admissionTestResult);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionTestResult::edit',
            compact('admissionTestResult', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'admission_test_schedule_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'test_type_id' => ['required', 'integer'],
            'registration_number' => ['required', 'string', 'max:255'],
            'attendance_status' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'raw_score' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'grade' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'passing_status' => ['required', 'string', 'max:255'],
            'cbt_session_id' => ['required', 'integer'],
            'cbt_session_answer_id' => ['required', 'integer'],
            'detail_scores' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'graded_by' => ['required', 'string', 'max:255'],
            'graded_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionTestResult = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $admissionTestResult,
                $validated
            ) {
                $admissionTestResult->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionTestResult berhasil diperbarui.',
                $admissionTestResult
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui AdmissionTestResult.',
                [
                    'exception' => $e,
                    'AdmissionTestResult_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionTestResult gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionTestResult = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionTestResult) {
                $admissionTestResult->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionTestResult berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $admissionTestResult->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionTestResult.',
                [
                    'exception' => $e,
                    'AdmissionTestResult_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionTestResult gagal dihapus.'
            );
        }
    } 
}
