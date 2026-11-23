<?php

namespace App\Modules\BosReport\Controllers;

use App\DataTables\BosReportDataTable;
use App\Http\Controllers\MainController;
use Models\BosReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BosReportController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BosReport());
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
        BosReportDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bos-report?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BosReport::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bosReport = new BosReport();
        $result = $this->getForm($bosReport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosReport::create',
            compact('bosReport', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'bos_period_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'report_code' => ['required', 'string', 'max:255'],
            'report_name' => ['required', 'string', 'max:255'],
            'report_type' => ['required', 'string', 'max:255'],
            'period_number' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'total_allocation' => ['required', 'string', 'max:255'],
            'total_received' => ['required', 'string', 'max:255'],
            'total_used' => ['required', 'string', 'max:255'],
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
                return BosReport::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosReport berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BosReport.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosReport gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bosReport = $this->model->findOrFail($id);
        $result = $this->getForm($bosReport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosReport::edit',
            compact('bosReport', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'bos_period_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'report_code' => ['required', 'string', 'max:255'],
            'report_name' => ['required', 'string', 'max:255'],
            'report_type' => ['required', 'string', 'max:255'],
            'period_number' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'total_allocation' => ['required', 'string', 'max:255'],
            'total_received' => ['required', 'string', 'max:255'],
            'total_used' => ['required', 'string', 'max:255'],
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
            $bosReport = $this->model->findOrFail($id);
            DB::transaction(function () use ($bosReport, $validated) {
                $bosReport->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosReport berhasil diperbarui.',
                $bosReport
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BosReport.',
                [
                    'exception' => $e,
                    'BosReport_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosReport gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bosReport = $this->model->findOrFail($id);

            DB::transaction(function () use ($bosReport) {
                $bosReport->delete();
            });

            return $this->successResponse(
                $request,
                'Data BosReport berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bosReport->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BosReport.',
                [
                    'exception' => $e,
                    'BosReport_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosReport gagal dihapus.'
            );
        }
    }
}
