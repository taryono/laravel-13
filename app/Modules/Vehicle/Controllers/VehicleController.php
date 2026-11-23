<?php

namespace App\Modules\Vehicle\Controllers;

use App\DataTables\VehicleDataTable;
use App\Http\Controllers\MainController;
use Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class VehicleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Vehicle());
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
        VehicleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /vehicle?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Vehicle::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vehicle = new Vehicle();
        $result = $this->getForm($vehicle);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Vehicle::create',
            compact('vehicle', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'plate_number' => ['required', 'string', 'max:255'],
            'vehicle_type' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'seat_capacity' => ['required', 'string', 'max:255'],
            'gps_device_id' => ['required', 'integer'],
            'stnk_expiry_date' => ['required', 'string', 'max:255'],
            'kir_expiry_date' => ['required', 'string', 'max:255'],
            'insurance_expiry_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Vehicle::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Vehicle berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Vehicle.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Vehicle gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $vehicle = $this->model->findOrFail($id);
        $result = $this->getForm($vehicle);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Vehicle::edit',
            compact('vehicle', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'plate_number' => ['required', 'string', 'max:255'],
            'vehicle_type' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'seat_capacity' => ['required', 'string', 'max:255'],
            'gps_device_id' => ['required', 'integer'],
            'stnk_expiry_date' => ['required', 'string', 'max:255'],
            'kir_expiry_date' => ['required', 'string', 'max:255'],
            'insurance_expiry_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        try {
            $vehicle = $this->model->findOrFail($id);
            DB::transaction(function () use ($vehicle, $validated) {
                $vehicle->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Vehicle berhasil diperbarui.',
                $vehicle
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Vehicle.',
                [
                    'exception' => $e,
                    'Vehicle_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Vehicle gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $vehicle = $this->model->findOrFail($id);

            DB::transaction(function () use ($vehicle) {
                $vehicle->delete();
            });

            return $this->successResponse(
                $request,
                'Data Vehicle berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $vehicle->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Vehicle.',
                [
                    'exception' => $e,
                    'Vehicle_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Vehicle gagal dihapus.'
            );
        }
    }
}
