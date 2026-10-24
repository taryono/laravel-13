<?php

namespace App\Modules\GradeScale\Controllers;

use App\DataTables\GradeScaleDataTable;
use App\Http\Controllers\MainController;
use Models\GradeScale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GradeScaleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new GradeScale());
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
        GradeScaleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /grade-scale?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'GradeScale::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $gradeScale = new GradeScale();
        $result = $this->getForm($gradeScale);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GradeScale::create',
            compact('gradeScale', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'min_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'grade_point' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return GradeScale::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data GradeScale berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan GradeScale.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GradeScale gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $gradeScale = $this->model->findOrFail($id);
        $result = $this->getForm($gradeScale);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GradeScale::edit',
            compact('gradeScale', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'min_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'grade_point' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $gradeScale = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $gradeScale,
                $validated
            ) {
                $gradeScale->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data GradeScale berhasil diperbarui.',
                $gradeScale
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui GradeScale.',
                [
                    'exception' => $e,
                    'GradeScale_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GradeScale gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $gradeScale = $this->model->findOrFail($id);

            DB::transaction(function () use ($gradeScale) {
                $gradeScale->delete();
            });

            return $this->successResponse(
                $request,
                'Data GradeScale berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $gradeScale->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus GradeScale.',
                [
                    'exception' => $e,
                    'GradeScale_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GradeScale gagal dihapus.'
            );
        }
    } 
}
