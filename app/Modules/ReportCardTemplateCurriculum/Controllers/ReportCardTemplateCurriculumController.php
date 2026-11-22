<?php

namespace App\Modules\ReportCardTemplateCurriculum\Controllers;

use App\DataTables\ReportCardTemplateCurriculumDataTable;
use App\Http\Controllers\MainController;
use Models\ReportCardTemplateCurriculum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReportCardTemplateCurriculumController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportCardTemplateCurriculum());
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
        ReportCardTemplateCurriculumDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-card-template-curriculum?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportCardTemplateCurriculum::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportCardTemplateCurriculum = new ReportCardTemplateCurriculum();
        $result = $this->getForm($reportCardTemplateCurriculum);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardTemplateCurriculum::create',
            compact('reportCardTemplateCurriculum', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_card_template_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportCardTemplateCurriculum::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardTemplateCurriculum berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportCardTemplateCurriculum.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardTemplateCurriculum gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportCardTemplateCurriculum = $this->model->findOrFail($id);
        $result = $this->getForm($reportCardTemplateCurriculum);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardTemplateCurriculum::edit',
            compact('reportCardTemplateCurriculum', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'report_card_template_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportCardTemplateCurriculum = $this->model->findOrFail($id);
            DB::transaction(function () use ($reportCardTemplateCurriculum, $validated) {
                $reportCardTemplateCurriculum->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardTemplateCurriculum berhasil diperbarui.',
                $reportCardTemplateCurriculum
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ReportCardTemplateCurriculum.',
                [
                    'exception' => $e,
                    'ReportCardTemplateCurriculum_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardTemplateCurriculum gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportCardTemplateCurriculum = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportCardTemplateCurriculum) {
                $reportCardTemplateCurriculum->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportCardTemplateCurriculum berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $reportCardTemplateCurriculum->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportCardTemplateCurriculum.',
                [
                    'exception' => $e,
                    'ReportCardTemplateCurriculum_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardTemplateCurriculum gagal dihapus.'
            );
        }
    }
}
