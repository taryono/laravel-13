<?php

namespace App\Modules\AssessmentComponent\Controllers;

use App\DataTables\AssessmentComponentDataTable;
use App\Http\Controllers\MainController;
use Models\AssessmentComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssessmentComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssessmentComponent());
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
        AssessmentComponentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /assessment-component?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssessmentComponent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assessmentComponent = new AssessmentComponent();
        $result = $this->getForm($assessmentComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssessmentComponent::create',
            compact('assessmentComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssessmentComponent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssessmentComponent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssessmentComponent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentComponent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assessmentComponent = $this->model->findOrFail($id);
        $result = $this->getForm($assessmentComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssessmentComponent::edit',
            compact('assessmentComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'assessment_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assessmentComponent = $this->model->findOrFail($id);
            DB::transaction(function () use ($assessmentComponent, $validated) {
                $assessmentComponent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssessmentComponent berhasil diperbarui.',
                $assessmentComponent
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AssessmentComponent.',
                [
                    'exception' => $e,
                    'AssessmentComponent_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentComponent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assessmentComponent = $this->model->findOrFail($id);

            DB::transaction(function () use ($assessmentComponent) {
                $assessmentComponent->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssessmentComponent berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $assessmentComponent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssessmentComponent.',
                [
                    'exception' => $e,
                    'AssessmentComponent_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentComponent gagal dihapus.'
            );
        }
    }
}
