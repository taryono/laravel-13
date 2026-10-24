<?php

namespace App\Modules\AttendanceImportBatch\Controllers;

use App\DataTables\AttendanceImportBatchDataTable;
use App\Http\Controllers\MainController;
use Models\AttendanceImportBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AttendanceImportBatchController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AttendanceImportBatch());
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
        AttendanceImportBatchDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /attendance-import-batch?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AttendanceImportBatch::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $attendanceImportBatch = new AttendanceImportBatch();
        $result = $this->getForm($attendanceImportBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttendanceImportBatch::create',
            compact('attendanceImportBatch', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'batch_code' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'machine_source' => ['required', 'string', 'max:255'],
            'total_records' => ['required', 'string', 'max:255'],
            'processed_records' => ['required', 'string', 'max:255'],
            'failed_records' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'errors' => ['required', 'string', 'max:255'],
            'imported_by' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AttendanceImportBatch::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttendanceImportBatch berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AttendanceImportBatch.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceImportBatch gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $attendanceImportBatch = $this->model->findOrFail($id);
        $result = $this->getForm($attendanceImportBatch);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttendanceImportBatch::edit',
            compact('attendanceImportBatch', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'batch_code' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'machine_source' => ['required', 'string', 'max:255'],
            'total_records' => ['required', 'string', 'max:255'],
            'processed_records' => ['required', 'string', 'max:255'],
            'failed_records' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'errors' => ['required', 'string', 'max:255'],
            'imported_by' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $attendanceImportBatch = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $attendanceImportBatch,
                $validated
            ) {
                $attendanceImportBatch->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttendanceImportBatch berhasil diperbarui.',
                $attendanceImportBatch
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui AttendanceImportBatch.',
                [
                    'exception' => $e,
                    'AttendanceImportBatch_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceImportBatch gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $attendanceImportBatch = $this->model->findOrFail($id);

            DB::transaction(function () use ($attendanceImportBatch) {
                $attendanceImportBatch->delete();
            });

            return $this->successResponse(
                $request,
                'Data AttendanceImportBatch berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $attendanceImportBatch->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AttendanceImportBatch.',
                [
                    'exception' => $e,
                    'AttendanceImportBatch_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceImportBatch gagal dihapus.'
            );
        }
    } 
}
