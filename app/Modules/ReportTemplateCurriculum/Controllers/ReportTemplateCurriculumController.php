<?php

namespace App\Modules\ReportTemplateCurriculum\Controllers;

use App\DataTables\ReportTemplateCurriculumDataTable;
use App\Http\Controllers\MainController;
use Models\ReportTemplateCurriculum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReportTemplateCurriculumController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportTemplateCurriculum());
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
        ReportTemplateCurriculumDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-template-curriculum?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportTemplateCurriculum::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportTemplateCurriculum = new ReportTemplateCurriculum();
        $result = $this->getForm($reportTemplateCurriculum);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportTemplateCurriculum::create',
            compact('reportTemplateCurriculum', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_template_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportTemplateCurriculum::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportTemplateCurriculum berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportTemplateCurriculum.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportTemplateCurriculum gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportTemplateCurriculum = $this->model->findOrFail($id);
        $result = $this->getForm($reportTemplateCurriculum);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportTemplateCurriculum::edit',
            compact('reportTemplateCurriculum', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'report_template_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportTemplateCurriculum = $this->model->findOrFail($id);
            DB::transaction(function () use ($reportTemplateCurriculum, $validated) {
                $reportTemplateCurriculum->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportTemplateCurriculum berhasil diperbarui.',
                $reportTemplateCurriculum
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ReportTemplateCurriculum.',
                [
                    'exception' => $e,
                    'ReportTemplateCurriculum_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportTemplateCurriculum gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportTemplateCurriculum = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportTemplateCurriculum) {
                $reportTemplateCurriculum->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportTemplateCurriculum berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $reportTemplateCurriculum->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportTemplateCurriculum.',
                [
                    'exception' => $e,
                    'ReportTemplateCurriculum_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportTemplateCurriculum gagal dihapus.'
            );
        }
    }
}
