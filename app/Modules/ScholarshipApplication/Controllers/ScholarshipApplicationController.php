<?php

namespace App\Modules\ScholarshipApplication\Controllers;

use App\DataTables\ScholarshipApplicationDataTable;
use App\Http\Controllers\MainController;
use Models\ScholarshipApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ScholarshipApplicationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ScholarshipApplication());
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
        ScholarshipApplicationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /scholarship-application?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ScholarshipApplication::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $scholarshipApplication = new ScholarshipApplication();
        $result = $this->getForm($scholarshipApplication);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScholarshipApplication::create',
            compact('scholarshipApplication', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholarship_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'application_number' => ['required', 'string', 'max:255'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'applicant_email' => ['required', 'string', 'max:255'],
            'applicant_phone' => ['required', 'string', 'max:255'],
            'gpa' => ['required', 'string', 'max:255'],
            'family_income' => ['required', 'string', 'max:255'],
            'family_dependents' => ['required', 'string', 'max:255'],
            'motivation_letter' => ['required', 'string', 'max:255'],
            'recommendation_letter_path' => ['required', 'string', 'max:255'],
            'essay_path' => ['required', 'string', 'max:255'],
            'certificate_paths' => ['required', 'string', 'max:255'],
            'document_paths' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'current_stage' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'reviewed_at' => ['required', 'string', 'max:255'],
            'reviewed_by' => ['required', 'string', 'max:255'],
            'review_notes' => ['required', 'string', 'max:255'],
            'interview_score' => ['required', 'string', 'max:255'],
            'test_score' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'decision' => ['required', 'string', 'max:255'],
            'decision_date' => ['required', 'string', 'max:255'],
            'decision_by' => ['required', 'string', 'max:255'],
            'decision_notes' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'approved_amount' => ['required', 'string', 'max:255'],
            'approved_percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ScholarshipApplication::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipApplication berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ScholarshipApplication.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipApplication gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $scholarshipApplication = $this->model->findOrFail($id);
        $result = $this->getForm($scholarshipApplication);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScholarshipApplication::edit',
            compact('scholarshipApplication', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'scholarship_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'application_number' => ['required', 'string', 'max:255'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'applicant_email' => ['required', 'string', 'max:255'],
            'applicant_phone' => ['required', 'string', 'max:255'],
            'gpa' => ['required', 'string', 'max:255'],
            'family_income' => ['required', 'string', 'max:255'],
            'family_dependents' => ['required', 'string', 'max:255'],
            'motivation_letter' => ['required', 'string', 'max:255'],
            'recommendation_letter_path' => ['required', 'string', 'max:255'],
            'essay_path' => ['required', 'string', 'max:255'],
            'certificate_paths' => ['required', 'string', 'max:255'],
            'document_paths' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'current_stage' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'reviewed_at' => ['required', 'string', 'max:255'],
            'reviewed_by' => ['required', 'string', 'max:255'],
            'review_notes' => ['required', 'string', 'max:255'],
            'interview_score' => ['required', 'string', 'max:255'],
            'test_score' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'decision' => ['required', 'string', 'max:255'],
            'decision_date' => ['required', 'string', 'max:255'],
            'decision_by' => ['required', 'string', 'max:255'],
            'decision_notes' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'approved_amount' => ['required', 'string', 'max:255'],
            'approved_percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $scholarshipApplication = $this->model->findOrFail($id);
            DB::transaction(function () use ($scholarshipApplication, $validated) {
                $scholarshipApplication->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipApplication berhasil diperbarui.',
                $scholarshipApplication
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ScholarshipApplication.',
                [
                    'exception' => $e,
                    'ScholarshipApplication_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipApplication gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $scholarshipApplication = $this->model->findOrFail($id);

            DB::transaction(function () use ($scholarshipApplication) {
                $scholarshipApplication->delete();
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipApplication berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $scholarshipApplication->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ScholarshipApplication.',
                [
                    'exception' => $e,
                    'ScholarshipApplication_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipApplication gagal dihapus.'
            );
        }
    }
}
