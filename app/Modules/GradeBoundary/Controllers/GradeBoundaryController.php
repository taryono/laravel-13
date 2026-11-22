<?php

namespace App\Modules\GradeBoundary\Controllers;

use App\DataTables\GradeBoundaryDataTable;
use App\Http\Controllers\MainController;
use Models\GradeBoundary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GradeBoundaryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new GradeBoundary());
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
        GradeBoundaryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /grade-boundary?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'GradeBoundary::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $gradeBoundary = new GradeBoundary();
        $result = $this->getForm($gradeBoundary);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GradeBoundary::create',
            compact('gradeBoundary', 'forms', 'groupedForms')
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
            'assessment_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'min_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return GradeBoundary::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data GradeBoundary berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan GradeBoundary.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GradeBoundary gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $gradeBoundary = $this->model->findOrFail($id);
        $result = $this->getForm($gradeBoundary);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GradeBoundary::edit',
            compact('gradeBoundary', 'forms', 'groupedForms')
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
            'assessment_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'min_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $gradeBoundary = $this->model->findOrFail($id);
            DB::transaction(function () use ($gradeBoundary, $validated) {
                $gradeBoundary->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data GradeBoundary berhasil diperbarui.',
                $gradeBoundary
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui GradeBoundary.',
                [
                    'exception' => $e,
                    'GradeBoundary_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GradeBoundary gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $gradeBoundary = $this->model->findOrFail($id);

            DB::transaction(function () use ($gradeBoundary) {
                $gradeBoundary->delete();
            });

            return $this->successResponse(
                $request,
                'Data GradeBoundary berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $gradeBoundary->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus GradeBoundary.',
                [
                    'exception' => $e,
                    'GradeBoundary_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GradeBoundary gagal dihapus.'
            );
        }
    }
}
