<?php

namespace App\Modules\VehicleMaintenanceLog\Controllers;

use App\DataTables\VehicleMaintenanceLogDataTable;
use App\Http\Controllers\MainController;
use Models\VehicleMaintenanceLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class VehicleMaintenanceLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new VehicleMaintenanceLog());
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
        VehicleMaintenanceLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /vehicle-maintenance-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'VehicleMaintenanceLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vehicleMaintenanceLog = new VehicleMaintenanceLog();
        $result = $this->getForm($vehicleMaintenanceLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VehicleMaintenanceLog::create',
            compact('vehicleMaintenanceLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'vehicle_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'service_date' => ['required', 'string', 'max:255'],
            'odometer_reading' => ['required', 'string', 'max:255'],
            'maintenance_type' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'next_service_due_date' => ['required', 'string', 'max:255'],
            'next_service_due_odometer' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return VehicleMaintenanceLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data VehicleMaintenanceLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan VehicleMaintenanceLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VehicleMaintenanceLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $vehicleMaintenanceLog = $this->model->findOrFail($id);
        $result = $this->getForm($vehicleMaintenanceLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'VehicleMaintenanceLog::edit',
            compact('vehicleMaintenanceLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'vehicle_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'service_date' => ['required', 'string', 'max:255'],
            'odometer_reading' => ['required', 'string', 'max:255'],
            'maintenance_type' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'next_service_due_date' => ['required', 'string', 'max:255'],
            'next_service_due_odometer' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
        ]);

        try {
            $vehicleMaintenanceLog = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $vehicleMaintenanceLog,
                $validated
            ) {
                $vehicleMaintenanceLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data VehicleMaintenanceLog berhasil diperbarui.',
                $vehicleMaintenanceLog
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui VehicleMaintenanceLog.',
                [
                    'exception' => $e,
                    'VehicleMaintenanceLog_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VehicleMaintenanceLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $vehicleMaintenanceLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($vehicleMaintenanceLog) {
                $vehicleMaintenanceLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data VehicleMaintenanceLog berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $vehicleMaintenanceLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus VehicleMaintenanceLog.',
                [
                    'exception' => $e,
                    'VehicleMaintenanceLog_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data VehicleMaintenanceLog gagal dihapus.'
            );
        }
    } 
}
