<?php

namespace App\Modules\Assessment\Controllers;

use App\DataTables\AssessmentDataTable;
use App\Http\Controllers\MainController;
use Models\Assessment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssessmentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Assessment());
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
        AssessmentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /assessment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Assessment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assessment = new Assessment();
        $result = $this->getForm($assessment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Assessment::create',
            compact('assessment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'curriculum_structure_id' => ['required', 'integer'],
            'assessment_type_id' => ['required', 'integer'],
            'assessment_method_id' => ['required', 'integer'],
            'assessment_rubric_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'semester' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'string', 'max:255'],
            'assessment_date' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Assessment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Assessment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Assessment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Assessment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assessment = $this->model->findOrFail($id);
        $result = $this->getForm($assessment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Assessment::edit',
            compact('assessment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'curriculum_structure_id' => ['required', 'integer'],
            'assessment_type_id' => ['required', 'integer'],
            'assessment_method_id' => ['required', 'integer'],
            'assessment_rubric_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'semester' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'string', 'max:255'],
            'assessment_date' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assessment = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $assessment,
                $validated
            ) {
                $assessment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Assessment berhasil diperbarui.',
                $assessment
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Assessment.',
                [
                    'exception' => $e,
                    'Assessment_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Assessment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assessment = $this->model->findOrFail($id);

            DB::transaction(function () use ($assessment) {
                $assessment->delete();
            });

            return $this->successResponse(
                $request,
                'Data Assessment berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $assessment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Assessment.',
                [
                    'exception' => $e,
                    'Assessment_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Assessment gagal dihapus.'
            );
        }
    } 
}
