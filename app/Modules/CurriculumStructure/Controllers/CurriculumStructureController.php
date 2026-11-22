<?php

namespace App\Modules\CurriculumStructure\Controllers;

use App\DataTables\CurriculumStructureDataTable;
use App\Http\Controllers\MainController;
use Models\CurriculumStructure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CurriculumStructureController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CurriculumStructure());
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
        CurriculumStructureDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /curriculum-structure?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CurriculumStructure::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $curriculumStructure = new CurriculumStructure();
        $result = $this->getForm($curriculumStructure);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumStructure::create',
            compact('curriculumStructure', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'curriculum_phase_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'semester' => ['required', 'string', 'max:255'],
            'hours_per_week' => ['required', 'string', 'max:255'],
            'hour_duration' => ['required', 'string', 'max:255'],
            'is_core' => ['required', 'string', 'max:255'],
            'min_student' => ['required', 'string', 'max:255'],
            'max_student' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CurriculumStructure::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumStructure berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CurriculumStructure.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumStructure gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $curriculumStructure = $this->model->findOrFail($id);
        $result = $this->getForm($curriculumStructure);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumStructure::edit',
            compact('curriculumStructure', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'curriculum_phase_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'semester' => ['required', 'string', 'max:255'],
            'hours_per_week' => ['required', 'string', 'max:255'],
            'hour_duration' => ['required', 'string', 'max:255'],
            'is_core' => ['required', 'string', 'max:255'],
            'min_student' => ['required', 'string', 'max:255'],
            'max_student' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $curriculumStructure = $this->model->findOrFail($id);
            DB::transaction(function () use ($curriculumStructure, $validated) {
                $curriculumStructure->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumStructure berhasil diperbarui.',
                $curriculumStructure
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CurriculumStructure.',
                [
                    'exception' => $e,
                    'CurriculumStructure_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumStructure gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $curriculumStructure = $this->model->findOrFail($id);

            DB::transaction(function () use ($curriculumStructure) {
                $curriculumStructure->delete();
            });

            return $this->successResponse(
                $request,
                'Data CurriculumStructure berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $curriculumStructure->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CurriculumStructure.',
                [
                    'exception' => $e,
                    'CurriculumStructure_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumStructure gagal dihapus.'
            );
        }
    }
}
