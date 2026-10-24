<?php

namespace App\Modules\ActivityLog\Controllers;

use App\DataTables\ActivityLogDataTable;
use App\Http\Controllers\MainController;
use Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ActivityLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ActivityLog());
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
        ActivityLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /activity-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ActivityLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $activityLog = new ActivityLog();
        $result = $this->getForm($activityLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ActivityLog::create',
            compact('activityLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'user_id' => ['required', 'integer'],
            'user_role' => ['required', 'string', 'max:255'],
            'action' => ['required', 'string', 'max:255'],
            'module' => ['required', 'string', 'max:255'],
            'record_id' => ['required', 'integer'],
            'record_type' => ['required', 'string', 'max:255'],
            'old_values' => ['required', 'string', 'max:255'],
            'new_values' => ['required', 'string', 'max:255'],
            'changed_fields' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ActivityLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ActivityLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ActivityLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ActivityLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $activityLog = $this->model->findOrFail($id);
        $result = $this->getForm($activityLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ActivityLog::edit',
            compact('activityLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'user_id' => ['required', 'integer'],
            'user_role' => ['required', 'string', 'max:255'],
            'action' => ['required', 'string', 'max:255'],
            'module' => ['required', 'string', 'max:255'],
            'record_id' => ['required', 'integer'],
            'record_type' => ['required', 'string', 'max:255'],
            'old_values' => ['required', 'string', 'max:255'],
            'new_values' => ['required', 'string', 'max:255'],
            'changed_fields' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        try {
            $activityLog = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $activityLog,
                $validated
            ) {
                $activityLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ActivityLog berhasil diperbarui.',
                $activityLog
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ActivityLog.',
                [
                    'exception' => $e,
                    'ActivityLog_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ActivityLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $activityLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($activityLog) {
                $activityLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data ActivityLog berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $activityLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ActivityLog.',
                [
                    'exception' => $e,
                    'ActivityLog_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ActivityLog gagal dihapus.'
            );
        }
    } 
}
