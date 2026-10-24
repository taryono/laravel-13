<?php

namespace App\Modules\AssessmentRubric\Controllers;

use App\DataTables\AssessmentRubricDataTable;
use App\Http\Controllers\MainController;
use Models\AssessmentRubric;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssessmentRubricController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssessmentRubric());
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
        AssessmentRubricDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /assessment-rubric?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssessmentRubric::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assessmentRubric = new AssessmentRubric();
        $result = $this->getForm($assessmentRubric);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssessmentRubric::create',
            compact('assessmentRubric', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'criteria' => ['required', 'string', 'max:255'],
            'levels' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssessmentRubric::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssessmentRubric berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssessmentRubric.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentRubric gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assessmentRubric = $this->model->findOrFail($id);
        $result = $this->getForm($assessmentRubric);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssessmentRubric::edit',
            compact('assessmentRubric', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'criteria' => ['required', 'string', 'max:255'],
            'levels' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assessmentRubric = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $assessmentRubric,
                $validated
            ) {
                $assessmentRubric->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssessmentRubric berhasil diperbarui.',
                $assessmentRubric
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui AssessmentRubric.',
                [
                    'exception' => $e,
                    'AssessmentRubric_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentRubric gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assessmentRubric = $this->model->findOrFail($id);

            DB::transaction(function () use ($assessmentRubric) {
                $assessmentRubric->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssessmentRubric berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $assessmentRubric->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssessmentRubric.',
                [
                    'exception' => $e,
                    'AssessmentRubric_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssessmentRubric gagal dihapus.'
            );
        }
    } 
}
