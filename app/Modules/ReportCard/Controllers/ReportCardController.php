<?php

namespace App\Modules\ReportCard\Controllers;

use App\DataTables\ReportCardDataTable;
use App\Http\Controllers\MainController;
use App\Services\ReportCard\ReportCardRenderer;
use Models\ReportCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Models\GeneratedReport;
use Models\Student;
use Throwable;

class ReportCardController extends MainController
{
    public function __construct(
        protected ReportCardRenderer $renderer,
    ) {
        parent::__construct(new ReportCard());
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
        ReportCardDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-card?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportCard::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $report_card = new ReportCard();
        return view(
            'ReportCard::create',
            compact('report_card')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportCard::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCard berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportCard.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCard gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportCard = $this->model->findOrFail($id);
        $result = $this->getForm($reportCard);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCard::edit',
            compact('reportCard', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportCard = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $reportCard,
                $validated
            ) {
                $reportCard->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCard berhasil diperbarui.',
                $reportCard
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ReportCard.',
                [
                    'exception' => $e,
                    'ReportCard_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCard gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportCard = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportCard) {
                $reportCard->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportCard berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $reportCard->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportCard.',
                [
                    'exception' => $e,
                    'ReportCard_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCard gagal dihapus.'
            );
        }
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'student_id'  => 'required|exists:students,id',
            'period_id'   => 'required|exists:report_periods,id',
            'template_id' => 'nullable|exists:report_card_templates,id',
        ]);

        $report = $this->renderer->generate(
            $validated['student_id'],
            $validated['period_id'],
            $validated['template_id'] ?? null,
        );

        return response()->json([
            'success'   => true,
            'report_id' => $report->id,
            'pdf_url'   => Storage::disk('public')->url($report->pdf_path),
        ]);
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'period_id'      => 'required|exists:report_periods,id',
        ]);

        $studentIds = Student::where('class_level_id', $validated['class_level_id'])
            ->where('deleted_at', null)
            ->pluck('id')
            ->toArray();

        $reports = $this->renderer->generateBulk($studentIds, $validated['period_id']);

        return response()->json([
            'success' => true,
            'total'   => count($reports),
            'failed'  => count(array_filter($reports, fn($r) => $r === null)),
        ]);
    }

    public function download(GeneratedReport $report)
    {
        abort_unless($report->pdf_path, 404);

        $report->increment('downloaded_count');
        $report->update(['last_download_at' => now()]);

        return Storage::disk('public')->download($report->pdf_path);
    }

    public function preview($id)
    {
        $report = GeneratedReport::find($id);
        abort_unless($report->pdf_path, 404);
        return Storage::disk('public')->response($report->pdf_path, null, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline',
        ]);
    }

    public function previewHtml($id)
    {
        $report = GeneratedReport::find($id);
        $template = app(\App\Services\ReportCard\TemplateParser::class)
            ->parse($report->template->template_data);

        return view('report_cards.preview', [
            'template' => $template,
            'data'     => $report->report_data,
        ]);
    }
}
