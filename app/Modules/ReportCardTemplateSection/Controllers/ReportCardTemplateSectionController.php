<?php

namespace App\Modules\ReportCardTemplateSection\Controllers;

use App\DataTables\ReportCardTemplateSectionDataTable;
use App\Http\Controllers\MainController;
use Models\ReportCardTemplateSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReportCardTemplateSectionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportCardTemplateSection());
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
        ReportCardTemplateSectionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-card-template-section?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportCardTemplateSection::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportCardTemplateSection = new ReportCardTemplateSection();
        $result = $this->getForm($reportCardTemplateSection);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardTemplateSection::create',
            compact('reportCardTemplateSection', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'report_card_template_id' => ['required', 'integer'],
            'section_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportCardTemplateSection::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardTemplateSection berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportCardTemplateSection.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardTemplateSection gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportCardTemplateSection = $this->model->findOrFail($id);
        $result = $this->getForm($reportCardTemplateSection);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardTemplateSection::edit',
            compact('reportCardTemplateSection', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'report_card_template_id' => ['required', 'integer'],
            'section_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportCardTemplateSection = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $reportCardTemplateSection,
                $validated
            ) {
                $reportCardTemplateSection->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardTemplateSection berhasil diperbarui.',
                $reportCardTemplateSection
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ReportCardTemplateSection.',
                [
                    'exception' => $e,
                    'ReportCardTemplateSection_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardTemplateSection gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportCardTemplateSection = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportCardTemplateSection) {
                $reportCardTemplateSection->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportCardTemplateSection berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $reportCardTemplateSection->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportCardTemplateSection.',
                [
                    'exception' => $e,
                    'ReportCardTemplateSection_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardTemplateSection gagal dihapus.'
            );
        }
    } 
}
