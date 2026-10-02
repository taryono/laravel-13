<?php

namespace App\Modules\ReportCardTemplate\Controllers;

use App\DataTables\ReportCardTemplateDataTable;
use App\Http\Controllers\MainController;
use Models\ReportCardTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReportCardTemplateController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportCardTemplate());
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
        ReportCardTemplateDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-card-template?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportCardTemplate::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportCardTemplate = new ReportCardTemplate();
        $result = $this->getForm($reportCardTemplate);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardTemplate::create',
            compact('reportCardTemplate', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportCardTemplate::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardTemplate berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportCardTemplate.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardTemplate gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportCardTemplate = $this->model->findOrFail($id);
        $result = $this->getForm($reportCardTemplate);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardTemplate::edit',
            compact('reportCardTemplate', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportCardTemplate = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $reportCardTemplate,
                $validated
            ) {
                $reportCardTemplate->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardTemplate berhasil diperbarui.',
                $reportCardTemplate
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ReportCardTemplate.',
                [
                    'exception' => $e,
                    'ReportCardTemplate_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardTemplate gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportCardTemplate = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportCardTemplate) {
                $reportCardTemplate->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportCardTemplate berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $reportCardTemplate->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportCardTemplate.',
                [
                    'exception' => $e,
                    'ReportCardTemplate_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardTemplate gagal dihapus.'
            );
        }
    } 
}
