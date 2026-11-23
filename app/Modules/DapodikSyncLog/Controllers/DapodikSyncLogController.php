<?php

namespace App\Modules\DapodikSyncLog\Controllers;

use App\DataTables\DapodikSyncLogDataTable;
use App\Http\Controllers\MainController;
use Models\DapodikSyncLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DapodikSyncLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DapodikSyncLog());
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
        DapodikSyncLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /dapodik-sync-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DapodikSyncLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dapodikSyncLog = new DapodikSyncLog();
        $result = $this->getForm($dapodikSyncLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DapodikSyncLog::create',
            compact('dapodikSyncLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'sync_date' => ['required', 'string', 'max:255'],
            'sync_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'total_records' => ['required', 'string', 'max:255'],
            'total_synced' => ['required', 'string', 'max:255'],
            'total_failed' => ['required', 'string', 'max:255'],
            'total_conflicts' => ['required', 'string', 'max:255'],
            'sync_duration_seconds' => ['required', 'string', 'max:255'],
            'error_log' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string', 'max:255'],
            'triggered_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DapodikSyncLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DapodikSyncLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DapodikSyncLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DapodikSyncLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dapodikSyncLog = $this->model->findOrFail($id);
        $result = $this->getForm($dapodikSyncLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DapodikSyncLog::edit',
            compact('dapodikSyncLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'sync_date' => ['required', 'string', 'max:255'],
            'sync_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'total_records' => ['required', 'string', 'max:255'],
            'total_synced' => ['required', 'string', 'max:255'],
            'total_failed' => ['required', 'string', 'max:255'],
            'total_conflicts' => ['required', 'string', 'max:255'],
            'sync_duration_seconds' => ['required', 'string', 'max:255'],
            'error_log' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string', 'max:255'],
            'triggered_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dapodikSyncLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($dapodikSyncLog, $validated) {
                $dapodikSyncLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DapodikSyncLog berhasil diperbarui.',
                $dapodikSyncLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DapodikSyncLog.',
                [
                    'exception' => $e,
                    'DapodikSyncLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DapodikSyncLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dapodikSyncLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($dapodikSyncLog) {
                $dapodikSyncLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data DapodikSyncLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $dapodikSyncLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DapodikSyncLog.',
                [
                    'exception' => $e,
                    'DapodikSyncLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DapodikSyncLog gagal dihapus.'
            );
        }
    }
}
