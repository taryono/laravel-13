<?php

namespace App\Modules\StudentAssessment\Controllers;

use App\DataTables\StudentAssessmentDataTable;
use App\Http\Controllers\MainController;
use Models\StudentAssessment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentAssessmentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentAssessment());
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
        StudentAssessmentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-assessment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentAssessment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentAssessment = new StudentAssessment();
        $result = $this->getForm($studentAssessment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentAssessment::create',
            compact('studentAssessment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'assessment_id' => ['required', 'integer'],
            'assessment_component_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'score' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'grade_point' => ['required', 'string', 'max:255'],
            'rubric_data' => ['required', 'string', 'max:255'],
            'teacher_notes' => ['required', 'string', 'max:255'],
            'assessed_by' => ['required', 'string', 'max:255'],
            'assessed_at' => ['required', 'string', 'max:255'],
            'is_remedial' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentAssessment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentAssessment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentAssessment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentAssessment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentAssessment = $this->model->findOrFail($id);
        $result = $this->getForm($studentAssessment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentAssessment::edit',
            compact('studentAssessment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'assessment_id' => ['required', 'integer'],
            'assessment_component_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'score' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'grade_point' => ['required', 'string', 'max:255'],
            'rubric_data' => ['required', 'string', 'max:255'],
            'teacher_notes' => ['required', 'string', 'max:255'],
            'assessed_by' => ['required', 'string', 'max:255'],
            'assessed_at' => ['required', 'string', 'max:255'],
            'is_remedial' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentAssessment = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentAssessment,
                $validated
            ) {
                $studentAssessment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentAssessment berhasil diperbarui.',
                $studentAssessment
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentAssessment.',
                [
                    'exception' => $e,
                    'StudentAssessment_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentAssessment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentAssessment = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentAssessment) {
                $studentAssessment->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentAssessment berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentAssessment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentAssessment.',
                [
                    'exception' => $e,
                    'StudentAssessment_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentAssessment gagal dihapus.'
            );
        }
    } 
}
