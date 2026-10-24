<?php

namespace App\Modules\SoroganEvaluation\Controllers;

use App\DataTables\SoroganEvaluationDataTable;
use App\Http\Controllers\MainController;
use Models\SoroganEvaluation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SoroganEvaluationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SoroganEvaluation());
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
        SoroganEvaluationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /sorogan-evaluation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SoroganEvaluation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $soroganEvaluation = new SoroganEvaluation();
        $result = $this->getForm($soroganEvaluation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SoroganEvaluation::create',
            compact('soroganEvaluation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'sorogan_session_id' => ['required', 'integer'],
            'sorogan_schedule_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'kitab_reference_id' => ['required', 'integer'],
            'evaluation_date' => ['required', 'string', 'max:255'],
            'chapter' => ['required', 'string', 'max:255'],
            'page_from' => ['required', 'string', 'max:255'],
            'page_to' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'fluency_score' => ['required', 'string', 'max:255'],
            'tajwid_score' => ['required', 'string', 'max:255'],
            'makhraj_score' => ['required', 'string', 'max:255'],
            'understanding_score' => ['required', 'string', 'max:255'],
            'memorization_score' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'mastery_level' => ['required', 'string', 'max:255'],
            'attendance_status' => ['required', 'string', 'max:255'],
            'teacher_notes' => ['required', 'string', 'max:255'],
            'recommendations' => ['required', 'string', 'max:255'],
            'next_target' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'reviewed_by' => ['required', 'string', 'max:255'],
            'reviewed_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SoroganEvaluation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SoroganEvaluation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SoroganEvaluation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SoroganEvaluation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $soroganEvaluation = $this->model->findOrFail($id);
        $result = $this->getForm($soroganEvaluation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SoroganEvaluation::edit',
            compact('soroganEvaluation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'sorogan_session_id' => ['required', 'integer'],
            'sorogan_schedule_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'kitab_reference_id' => ['required', 'integer'],
            'evaluation_date' => ['required', 'string', 'max:255'],
            'chapter' => ['required', 'string', 'max:255'],
            'page_from' => ['required', 'string', 'max:255'],
            'page_to' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'fluency_score' => ['required', 'string', 'max:255'],
            'tajwid_score' => ['required', 'string', 'max:255'],
            'makhraj_score' => ['required', 'string', 'max:255'],
            'understanding_score' => ['required', 'string', 'max:255'],
            'memorization_score' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'mastery_level' => ['required', 'string', 'max:255'],
            'attendance_status' => ['required', 'string', 'max:255'],
            'teacher_notes' => ['required', 'string', 'max:255'],
            'recommendations' => ['required', 'string', 'max:255'],
            'next_target' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'reviewed_by' => ['required', 'string', 'max:255'],
            'reviewed_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $soroganEvaluation = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $soroganEvaluation,
                $validated
            ) {
                $soroganEvaluation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SoroganEvaluation berhasil diperbarui.',
                $soroganEvaluation
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui SoroganEvaluation.',
                [
                    'exception' => $e,
                    'SoroganEvaluation_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SoroganEvaluation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $soroganEvaluation = $this->model->findOrFail($id);

            DB::transaction(function () use ($soroganEvaluation) {
                $soroganEvaluation->delete();
            });

            return $this->successResponse(
                $request,
                'Data SoroganEvaluation berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $soroganEvaluation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SoroganEvaluation.',
                [
                    'exception' => $e,
                    'SoroganEvaluation_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SoroganEvaluation gagal dihapus.'
            );
        }
    } 
}
