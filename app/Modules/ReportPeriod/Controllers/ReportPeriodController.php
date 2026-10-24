<?php

namespace App\Modules\ReportPeriod\Controllers;

use App\DataTables\ReportPeriodDataTable;
use App\Http\Controllers\MainController;
use Models\ReportPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReportPeriodController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportPeriod());
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
        ReportPeriodDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-period?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportPeriod::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportPeriod = new ReportPeriod();
        $result = $this->getForm($reportPeriod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportPeriod::create',
            compact('reportPeriod', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportPeriod::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportPeriod berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportPeriod.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportPeriod gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportPeriod = $this->model->findOrFail($id);
        $result = $this->getForm($reportPeriod);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportPeriod::edit',
            compact('reportPeriod', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportPeriod = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $reportPeriod,
                $validated
            ) {
                $reportPeriod->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportPeriod berhasil diperbarui.',
                $reportPeriod
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ReportPeriod.',
                [
                    'exception' => $e,
                    'ReportPeriod_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportPeriod gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportPeriod = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportPeriod) {
                $reportPeriod->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportPeriod berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $reportPeriod->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportPeriod.',
                [
                    'exception' => $e,
                    'ReportPeriod_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportPeriod gagal dihapus.'
            );
        }
    } 
}
