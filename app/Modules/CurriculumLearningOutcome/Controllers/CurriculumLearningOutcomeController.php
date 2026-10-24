<?php

namespace App\Modules\CurriculumLearningOutcome\Controllers;

use App\DataTables\CurriculumLearningOutcomeDataTable;
use App\Http\Controllers\MainController;
use Models\CurriculumLearningOutcome;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CurriculumLearningOutcomeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CurriculumLearningOutcome());
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
        CurriculumLearningOutcomeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /curriculum-learning-outcome?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CurriculumLearningOutcome::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $curriculumLearningOutcome = new CurriculumLearningOutcome();
        $result = $this->getForm($curriculumLearningOutcome);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumLearningOutcome::create',
            compact('curriculumLearningOutcome', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'curriculum_phase_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'element' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CurriculumLearningOutcome::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumLearningOutcome berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CurriculumLearningOutcome.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumLearningOutcome gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $curriculumLearningOutcome = $this->model->findOrFail($id);
        $result = $this->getForm($curriculumLearningOutcome);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumLearningOutcome::edit',
            compact('curriculumLearningOutcome', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'curriculum_phase_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'element' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $curriculumLearningOutcome = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $curriculumLearningOutcome,
                $validated
            ) {
                $curriculumLearningOutcome->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumLearningOutcome berhasil diperbarui.',
                $curriculumLearningOutcome
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CurriculumLearningOutcome.',
                [
                    'exception' => $e,
                    'CurriculumLearningOutcome_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumLearningOutcome gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $curriculumLearningOutcome = $this->model->findOrFail($id);

            DB::transaction(function () use ($curriculumLearningOutcome) {
                $curriculumLearningOutcome->delete();
            });

            return $this->successResponse(
                $request,
                'Data CurriculumLearningOutcome berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $curriculumLearningOutcome->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CurriculumLearningOutcome.',
                [
                    'exception' => $e,
                    'CurriculumLearningOutcome_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumLearningOutcome gagal dihapus.'
            );
        }
    } 
}
