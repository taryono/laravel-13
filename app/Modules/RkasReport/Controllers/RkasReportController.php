<?php

namespace App\Modules\RkasReport\Controllers;

use App\DataTables\RkasReportDataTable;
use App\Http\Controllers\MainController;
use Models\RkasReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasReportController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasReport());
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
        RkasReportDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-report?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasReport::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasReport = new RkasReport();
        $result = $this->getForm($rkasReport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasReport::create',
            compact('rkasReport', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'rkas_document_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'report_code' => ['required', 'string', 'max:255'],
            'report_name' => ['required', 'string', 'max:255'],
            'report_type' => ['required', 'string', 'max:255'],
            'period_number' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'total_budget' => ['required', 'string', 'max:255'],
            'total_realization' => ['required', 'string', 'max:255'],
            'total_remaining' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'file_url' => ['required', 'string', 'max:255'],
            'report_data' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'submitted_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasReport::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasReport berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasReport.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasReport gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasReport = $this->model->findOrFail($id);
        $result = $this->getForm($rkasReport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasReport::edit',
            compact('rkasReport', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'rkas_document_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'report_code' => ['required', 'string', 'max:255'],
            'report_name' => ['required', 'string', 'max:255'],
            'report_type' => ['required', 'string', 'max:255'],
            'period_number' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'total_budget' => ['required', 'string', 'max:255'],
            'total_realization' => ['required', 'string', 'max:255'],
            'total_remaining' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'file_url' => ['required', 'string', 'max:255'],
            'report_data' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'submitted_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasReport = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasReport, $validated) {
                $rkasReport->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasReport berhasil diperbarui.',
                $rkasReport
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasReport.',
                [
                    'exception' => $e,
                    'RkasReport_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasReport gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasReport = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasReport) {
                $rkasReport->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasReport berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasReport->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasReport.',
                [
                    'exception' => $e,
                    'RkasReport_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasReport gagal dihapus.'
            );
        }
    }
}
