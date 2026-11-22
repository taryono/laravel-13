<?php

namespace App\Modules\GradeWeightConfig\Controllers;

use App\DataTables\GradeWeightConfigDataTable;
use App\Http\Controllers\MainController;
use Models\GradeWeightConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GradeWeightConfigController extends MainController
{
    public function __construct()
    {
        parent::__construct(new GradeWeightConfig());
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
        GradeWeightConfigDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /grade-weight-config?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'GradeWeightConfig::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $gradeWeightConfig = new GradeWeightConfig();
        $result = $this->getForm($gradeWeightConfig);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GradeWeightConfig::create',
            compact('gradeWeightConfig', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return GradeWeightConfig::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data GradeWeightConfig berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan GradeWeightConfig.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GradeWeightConfig gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $gradeWeightConfig = $this->model->findOrFail($id);
        $result = $this->getForm($gradeWeightConfig);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GradeWeightConfig::edit',
            compact('gradeWeightConfig', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $gradeWeightConfig = $this->model->findOrFail($id);
            DB::transaction(function () use ($gradeWeightConfig, $validated) {
                $gradeWeightConfig->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data GradeWeightConfig berhasil diperbarui.',
                $gradeWeightConfig
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui GradeWeightConfig.',
                [
                    'exception' => $e,
                    'GradeWeightConfig_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GradeWeightConfig gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $gradeWeightConfig = $this->model->findOrFail($id);

            DB::transaction(function () use ($gradeWeightConfig) {
                $gradeWeightConfig->delete();
            });

            return $this->successResponse(
                $request,
                'Data GradeWeightConfig berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $gradeWeightConfig->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus GradeWeightConfig.',
                [
                    'exception' => $e,
                    'GradeWeightConfig_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GradeWeightConfig gagal dihapus.'
            );
        }
    }
}
