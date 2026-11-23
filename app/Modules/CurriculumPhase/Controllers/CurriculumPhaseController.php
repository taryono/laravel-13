<?php

namespace App\Modules\CurriculumPhase\Controllers;

use App\DataTables\CurriculumPhaseDataTable;
use App\Http\Controllers\MainController;
use Models\CurriculumPhase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CurriculumPhaseController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CurriculumPhase());
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
        CurriculumPhaseDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /curriculum-phase?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CurriculumPhase::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $curriculumPhase = new CurriculumPhase();
        $result = $this->getForm($curriculumPhase);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumPhase::create',
            compact('curriculumPhase', 'forms', 'groupedForms')
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
            'description' => ['required', 'string', 'max:255'],
            'age_range' => ['required', 'string', 'max:255'],
            'class_levels' => ['required', 'string', 'max:255'],
            'competencies' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CurriculumPhase::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumPhase berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CurriculumPhase.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumPhase gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $curriculumPhase = $this->model->findOrFail($id);
        $result = $this->getForm($curriculumPhase);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumPhase::edit',
            compact('curriculumPhase', 'forms', 'groupedForms')
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
            'description' => ['required', 'string', 'max:255'],
            'age_range' => ['required', 'string', 'max:255'],
            'class_levels' => ['required', 'string', 'max:255'],
            'competencies' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $curriculumPhase = $this->model->findOrFail($id);
            DB::transaction(function () use ($curriculumPhase, $validated) {
                $curriculumPhase->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumPhase berhasil diperbarui.',
                $curriculumPhase
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CurriculumPhase.',
                [
                    'exception' => $e,
                    'CurriculumPhase_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumPhase gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $curriculumPhase = $this->model->findOrFail($id);

            DB::transaction(function () use ($curriculumPhase) {
                $curriculumPhase->delete();
            });

            return $this->successResponse(
                $request,
                'Data CurriculumPhase berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $curriculumPhase->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CurriculumPhase.',
                [
                    'exception' => $e,
                    'CurriculumPhase_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumPhase gagal dihapus.'
            );
        }
    }
}
