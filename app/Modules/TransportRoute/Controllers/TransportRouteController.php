<?php

namespace App\Modules\TransportRoute\Controllers;

use App\DataTables\TransportRouteDataTable;
use App\Http\Controllers\MainController;
use Models\TransportRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TransportRouteController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TransportRoute());
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
        TransportRouteDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /transport-route?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TransportRoute::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $transportRoute = new TransportRoute();
        $result = $this->getForm($transportRoute);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TransportRoute::create',
            compact('transportRoute', 'forms', 'groupedForms')
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
            'vehicle_id' => ['required', 'integer'],
            'driver_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'route_type' => ['required', 'string', 'max:255'],
            'morning_departure_time' => ['required', 'string', 'max:255'],
            'afternoon_departure_time' => ['required', 'string', 'max:255'],
            'monthly_fare' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TransportRoute::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TransportRoute berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TransportRoute.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TransportRoute gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $transportRoute = $this->model->findOrFail($id);
        $result = $this->getForm($transportRoute);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TransportRoute::edit',
            compact('transportRoute', 'forms', 'groupedForms')
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
            'vehicle_id' => ['required', 'integer'],
            'driver_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'route_type' => ['required', 'string', 'max:255'],
            'morning_departure_time' => ['required', 'string', 'max:255'],
            'afternoon_departure_time' => ['required', 'string', 'max:255'],
            'monthly_fare' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $transportRoute = $this->model->findOrFail($id);
            DB::transaction(function () use ($transportRoute, $validated) {
                $transportRoute->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TransportRoute berhasil diperbarui.',
                $transportRoute
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui TransportRoute.',
                [
                    'exception' => $e,
                    'TransportRoute_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TransportRoute gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $transportRoute = $this->model->findOrFail($id);

            DB::transaction(function () use ($transportRoute) {
                $transportRoute->delete();
            });

            return $this->successResponse(
                $request,
                'Data TransportRoute berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $transportRoute->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TransportRoute.',
                [
                    'exception' => $e,
                    'TransportRoute_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TransportRoute gagal dihapus.'
            );
        }
    }
}
