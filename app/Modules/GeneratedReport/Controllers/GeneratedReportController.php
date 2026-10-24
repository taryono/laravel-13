<?php

namespace App\Modules\GeneratedReport\Controllers;

use App\DataTables\GeneratedReportDataTable;
use App\Http\Controllers\MainController;
use Models\GeneratedReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GeneratedReportController extends MainController
{
    public function __construct()
    {
        parent::__construct(new GeneratedReport());
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
        GeneratedReportDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /generated-report?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'GeneratedReport::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $generatedReport = new GeneratedReport();
        $result = $this->getForm($generatedReport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GeneratedReport::create',
            compact('generatedReport', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'report_card_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'report_card_template_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'version' => ['required', 'string', 'max:255'],
            'is_current' => ['required', 'string', 'max:255'],
            'pdf_path' => ['required', 'string', 'max:255'],
            'report_data' => ['required', 'string', 'max:255'],
            'generated_at' => ['required', 'string', 'max:255'],
            'downloaded_count' => ['required', 'string', 'max:255'],
            'last_download_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return GeneratedReport::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data GeneratedReport berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan GeneratedReport.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GeneratedReport gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $generatedReport = $this->model->findOrFail($id);
        $result = $this->getForm($generatedReport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GeneratedReport::edit',
            compact('generatedReport', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'report_card_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'report_card_template_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'version' => ['required', 'string', 'max:255'],
            'is_current' => ['required', 'string', 'max:255'],
            'pdf_path' => ['required', 'string', 'max:255'],
            'report_data' => ['required', 'string', 'max:255'],
            'generated_at' => ['required', 'string', 'max:255'],
            'downloaded_count' => ['required', 'string', 'max:255'],
            'last_download_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $generatedReport = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $generatedReport,
                $validated
            ) {
                $generatedReport->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data GeneratedReport berhasil diperbarui.',
                $generatedReport
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui GeneratedReport.',
                [
                    'exception' => $e,
                    'GeneratedReport_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GeneratedReport gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $generatedReport = $this->model->findOrFail($id);

            DB::transaction(function () use ($generatedReport) {
                $generatedReport->delete();
            });

            return $this->successResponse(
                $request,
                'Data GeneratedReport berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $generatedReport->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus GeneratedReport.',
                [
                    'exception' => $e,
                    'GeneratedReport_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GeneratedReport gagal dihapus.'
            );
        }
    } 
}
