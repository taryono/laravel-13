<?php

namespace App\Modules\CurriculumAssignment\Controllers;

use App\DataTables\CurriculumAssignmentDataTable;
use App\Http\Controllers\MainController;
use Models\CurriculumAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CurriculumAssignmentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CurriculumAssignment());
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
        CurriculumAssignmentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /curriculum-assignment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CurriculumAssignment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $curriculumAssignment = new CurriculumAssignment();
        $result = $this->getForm($curriculumAssignment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumAssignment::create',
            compact('curriculumAssignment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'curriculum_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CurriculumAssignment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumAssignment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CurriculumAssignment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumAssignment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $curriculumAssignment = $this->model->findOrFail($id);
        $result = $this->getForm($curriculumAssignment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumAssignment::edit',
            compact('curriculumAssignment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'curriculum_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $curriculumAssignment = $this->model->findOrFail($id);
            DB::transaction(function () use ($curriculumAssignment, $validated) {
                $curriculumAssignment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumAssignment berhasil diperbarui.',
                $curriculumAssignment
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CurriculumAssignment.',
                [
                    'exception' => $e,
                    'CurriculumAssignment_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumAssignment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $curriculumAssignment = $this->model->findOrFail($id);

            DB::transaction(function () use ($curriculumAssignment) {
                $curriculumAssignment->delete();
            });

            return $this->successResponse(
                $request,
                'Data CurriculumAssignment berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $curriculumAssignment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CurriculumAssignment.',
                [
                    'exception' => $e,
                    'CurriculumAssignment_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumAssignment gagal dihapus.'
            );
        }
    }
}
