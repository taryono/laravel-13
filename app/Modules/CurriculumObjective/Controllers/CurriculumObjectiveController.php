<?php

namespace App\Modules\CurriculumObjective\Controllers;

use App\DataTables\CurriculumObjectiveDataTable;
use App\Http\Controllers\MainController;
use Models\CurriculumObjective;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CurriculumObjectiveController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CurriculumObjective());
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
        CurriculumObjectiveDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /curriculum-objective?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CurriculumObjective::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $curriculumObjective = new CurriculumObjective();
        $result = $this->getForm($curriculumObjective);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumObjective::create',
            compact('curriculumObjective', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'learning_outcome_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'criteria' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CurriculumObjective::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumObjective berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CurriculumObjective.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumObjective gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $curriculumObjective = $this->model->findOrFail($id);
        $result = $this->getForm($curriculumObjective);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumObjective::edit',
            compact('curriculumObjective', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'learning_outcome_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'criteria' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $curriculumObjective = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $curriculumObjective,
                $validated
            ) {
                $curriculumObjective->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumObjective berhasil diperbarui.',
                $curriculumObjective
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CurriculumObjective.',
                [
                    'exception' => $e,
                    'CurriculumObjective_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumObjective gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $curriculumObjective = $this->model->findOrFail($id);

            DB::transaction(function () use ($curriculumObjective) {
                $curriculumObjective->delete();
            });

            return $this->successResponse(
                $request,
                'Data CurriculumObjective berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $curriculumObjective->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CurriculumObjective.',
                [
                    'exception' => $e,
                    'CurriculumObjective_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumObjective gagal dihapus.'
            );
        }
    } 
}
