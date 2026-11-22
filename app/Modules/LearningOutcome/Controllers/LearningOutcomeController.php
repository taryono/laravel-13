<?php

namespace App\Modules\LearningOutcome\Controllers;

use App\DataTables\LearningOutcomeDataTable;
use App\Http\Controllers\MainController;
use Models\LearningOutcome;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LearningOutcomeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LearningOutcome());
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
        LearningOutcomeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /learning-outcome?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LearningOutcome::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $learningOutcome = new LearningOutcome();
        $result = $this->getForm($learningOutcome);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LearningOutcome::create',
            compact('learningOutcome', 'forms', 'groupedForms')
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
            'element' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'competency' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LearningOutcome::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LearningOutcome berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LearningOutcome.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LearningOutcome gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $learningOutcome = $this->model->findOrFail($id);
        $result = $this->getForm($learningOutcome);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LearningOutcome::edit',
            compact('learningOutcome', 'forms', 'groupedForms')
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
            'element' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'competency' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $learningOutcome = $this->model->findOrFail($id);
            DB::transaction(function () use ($learningOutcome, $validated) {
                $learningOutcome->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LearningOutcome berhasil diperbarui.',
                $learningOutcome
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui LearningOutcome.',
                [
                    'exception' => $e,
                    'LearningOutcome_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LearningOutcome gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $learningOutcome = $this->model->findOrFail($id);

            DB::transaction(function () use ($learningOutcome) {
                $learningOutcome->delete();
            });

            return $this->successResponse(
                $request,
                'Data LearningOutcome berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $learningOutcome->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LearningOutcome.',
                [
                    'exception' => $e,
                    'LearningOutcome_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LearningOutcome gagal dihapus.'
            );
        }
    }
}
