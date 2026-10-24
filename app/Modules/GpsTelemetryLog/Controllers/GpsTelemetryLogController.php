<?php

namespace App\Modules\GpsTelemetryLog\Controllers;

use App\DataTables\GpsTelemetryLogDataTable;
use App\Http\Controllers\MainController;
use Models\GpsTelemetryLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GpsTelemetryLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new GpsTelemetryLog());
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
        GpsTelemetryLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /gps-telemetry-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'GpsTelemetryLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $gpsTelemetryLog = new GpsTelemetryLog();
        $result = $this->getForm($gpsTelemetryLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GpsTelemetryLog::create',
            compact('gpsTelemetryLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'vehicle_id' => ['required', 'integer'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'speed_kmh' => ['required', 'string', 'max:255'],
            'heading_degrees' => ['required', 'string', 'max:255'],
            'is_engine_on' => ['required', 'string', 'max:255'],
            'odometer_km' => ['required', 'string', 'max:255'],
            'recorded_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return GpsTelemetryLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data GpsTelemetryLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan GpsTelemetryLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GpsTelemetryLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $gpsTelemetryLog = $this->model->findOrFail($id);
        $result = $this->getForm($gpsTelemetryLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GpsTelemetryLog::edit',
            compact('gpsTelemetryLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'vehicle_id' => ['required', 'integer'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'speed_kmh' => ['required', 'string', 'max:255'],
            'heading_degrees' => ['required', 'string', 'max:255'],
            'is_engine_on' => ['required', 'string', 'max:255'],
            'odometer_km' => ['required', 'string', 'max:255'],
            'recorded_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $gpsTelemetryLog = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $gpsTelemetryLog,
                $validated
            ) {
                $gpsTelemetryLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data GpsTelemetryLog berhasil diperbarui.',
                $gpsTelemetryLog
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui GpsTelemetryLog.',
                [
                    'exception' => $e,
                    'GpsTelemetryLog_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GpsTelemetryLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $gpsTelemetryLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($gpsTelemetryLog) {
                $gpsTelemetryLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data GpsTelemetryLog berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $gpsTelemetryLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus GpsTelemetryLog.',
                [
                    'exception' => $e,
                    'GpsTelemetryLog_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GpsTelemetryLog gagal dihapus.'
            );
        }
    } 
}
