<?php

namespace App\Modules\DapodikSyncSchedule\Controllers;

use App\DataTables\DapodikSyncScheduleDataTable;
use App\Http\Controllers\MainController;
use Models\DapodikSyncSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DapodikSyncScheduleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DapodikSyncSchedule());
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
        DapodikSyncScheduleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /dapodik-sync-schedule?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DapodikSyncSchedule::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dapodikSyncSchedule = new DapodikSyncSchedule();
        $result = $this->getForm($dapodikSyncSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DapodikSyncSchedule::create',
            compact('dapodikSyncSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'schedule_name' => ['required', 'string', 'max:255'],
            'sync_type' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'string', 'max:255'],
            'last_run' => ['required', 'string', 'max:255'],
            'next_run' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DapodikSyncSchedule::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DapodikSyncSchedule berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DapodikSyncSchedule.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DapodikSyncSchedule gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dapodikSyncSchedule = $this->model->findOrFail($id);
        $result = $this->getForm($dapodikSyncSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DapodikSyncSchedule::edit',
            compact('dapodikSyncSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'schedule_name' => ['required', 'string', 'max:255'],
            'sync_type' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'string', 'max:255'],
            'last_run' => ['required', 'string', 'max:255'],
            'next_run' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dapodikSyncSchedule = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $dapodikSyncSchedule,
                $validated
            ) {
                $dapodikSyncSchedule->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DapodikSyncSchedule berhasil diperbarui.',
                $dapodikSyncSchedule
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui DapodikSyncSchedule.',
                [
                    'exception' => $e,
                    'DapodikSyncSchedule_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DapodikSyncSchedule gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dapodikSyncSchedule = $this->model->findOrFail($id);

            DB::transaction(function () use ($dapodikSyncSchedule) {
                $dapodikSyncSchedule->delete();
            });

            return $this->successResponse(
                $request,
                'Data DapodikSyncSchedule berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $dapodikSyncSchedule->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DapodikSyncSchedule.',
                [
                    'exception' => $e,
                    'DapodikSyncSchedule_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DapodikSyncSchedule gagal dihapus.'
            );
        }
    } 
}
