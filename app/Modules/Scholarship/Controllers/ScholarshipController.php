<?php

namespace App\Modules\Scholarship\Controllers;

use App\DataTables\ScholarshipDataTable;
use App\Http\Controllers\MainController;
use Models\Scholarship;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ScholarshipController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Scholarship());
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
        ScholarshipDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /scholarship?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Scholarship::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $scholarship = new Scholarship();
        $result = $this->getForm($scholarship);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Scholarship::create',
            compact('scholarship', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholarship_type_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'faculty_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'eligibility_criteria' => ['required', 'string', 'max:255'],
            'required_documents' => ['required', 'string', 'max:255'],
            'selection_stages' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'filled' => ['required', 'string', 'max:255'],
            'waitlist_quota' => ['required', 'string', 'max:255'],
            'benefit_type' => ['required', 'string', 'max:255'],
            'benefit_amount' => ['required', 'string', 'max:255'],
            'benefit_percentage' => ['required', 'string', 'max:255'],
            'benefit_currency_id' => ['required', 'integer'],
            'benefit_duration_semesters' => ['required', 'string', 'max:255'],
            'min_gpa' => ['required', 'string', 'max:255'],
            'max_income' => ['required', 'string', 'max:255'],
            'min_attendance_percentage' => ['required', 'string', 'max:255'],
            'application_open_date' => ['required', 'string', 'max:255'],
            'application_close_date' => ['required', 'string', 'max:255'],
            'selection_date' => ['required', 'string', 'max:255'],
            'announcement_date' => ['required', 'string', 'max:255'],
            'disbursement_start_date' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:255'],
            'guideline_path' => ['required', 'string', 'max:255'],
            'poster_path' => ['required', 'string', 'max:255'],
            'is_renewable' => ['required', 'string', 'max:255'],
            'renewal_requirements' => ['required', 'string', 'max:255'],
            'requires_interview' => ['required', 'string', 'max:255'],
            'requires_test' => ['required', 'string', 'max:255'],
            'requires_recommendation' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_featured' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
            'total_applicants' => ['required', 'string', 'max:255'],
            'total_recipients' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Scholarship::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Scholarship berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Scholarship.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Scholarship gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $scholarship = $this->model->findOrFail($id);
        $result = $this->getForm($scholarship);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Scholarship::edit',
            compact('scholarship', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'scholarship_type_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'faculty_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'eligibility_criteria' => ['required', 'string', 'max:255'],
            'required_documents' => ['required', 'string', 'max:255'],
            'selection_stages' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'filled' => ['required', 'string', 'max:255'],
            'waitlist_quota' => ['required', 'string', 'max:255'],
            'benefit_type' => ['required', 'string', 'max:255'],
            'benefit_amount' => ['required', 'string', 'max:255'],
            'benefit_percentage' => ['required', 'string', 'max:255'],
            'benefit_currency_id' => ['required', 'integer'],
            'benefit_duration_semesters' => ['required', 'string', 'max:255'],
            'min_gpa' => ['required', 'string', 'max:255'],
            'max_income' => ['required', 'string', 'max:255'],
            'min_attendance_percentage' => ['required', 'string', 'max:255'],
            'application_open_date' => ['required', 'string', 'max:255'],
            'application_close_date' => ['required', 'string', 'max:255'],
            'selection_date' => ['required', 'string', 'max:255'],
            'announcement_date' => ['required', 'string', 'max:255'],
            'disbursement_start_date' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:255'],
            'guideline_path' => ['required', 'string', 'max:255'],
            'poster_path' => ['required', 'string', 'max:255'],
            'is_renewable' => ['required', 'string', 'max:255'],
            'renewal_requirements' => ['required', 'string', 'max:255'],
            'requires_interview' => ['required', 'string', 'max:255'],
            'requires_test' => ['required', 'string', 'max:255'],
            'requires_recommendation' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_featured' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
            'total_applicants' => ['required', 'string', 'max:255'],
            'total_recipients' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $scholarship = $this->model->findOrFail($id);
            DB::transaction(function () use ($scholarship, $validated) {
                $scholarship->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Scholarship berhasil diperbarui.',
                $scholarship
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Scholarship.',
                [
                    'exception' => $e,
                    'Scholarship_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Scholarship gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $scholarship = $this->model->findOrFail($id);

            DB::transaction(function () use ($scholarship) {
                $scholarship->delete();
            });

            return $this->successResponse(
                $request,
                'Data Scholarship berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $scholarship->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Scholarship.',
                [
                    'exception' => $e,
                    'Scholarship_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Scholarship gagal dihapus.'
            );
        }
    }
}
