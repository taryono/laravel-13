<?php

namespace App\Modules\TimetableRoute\Controllers;

use App\DataTables\TimetableRouteDataTable;
use App\Http\Controllers\MainController;
use Models\TimetableRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TimetableRouteController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TimetableRoute());
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
        TimetableRouteDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /timetable-route?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TimetableRoute::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $timetableRoute = new TimetableRoute();
        $result = $this->getForm($timetableRoute);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TimetableRoute::create',
            compact('timetableRoute', 'forms', 'groupedForms')
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
                return TimetableRoute::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TimetableRoute berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TimetableRoute.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableRoute gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $timetableRoute = $this->model->findOrFail($id);
        $result = $this->getForm($timetableRoute);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TimetableRoute::edit',
            compact('timetableRoute', 'forms', 'groupedForms')
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
            $timetableRoute = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $timetableRoute,
                $validated
            ) {
                $timetableRoute->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TimetableRoute berhasil diperbarui.',
                $timetableRoute
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui TimetableRoute.',
                [
                    'exception' => $e,
                    'TimetableRoute_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableRoute gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $timetableRoute = $this->model->findOrFail($id);

            DB::transaction(function () use ($timetableRoute) {
                $timetableRoute->delete();
            });

            return $this->successResponse(
                $request,
                'Data TimetableRoute berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $timetableRoute->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TimetableRoute.',
                [
                    'exception' => $e,
                    'TimetableRoute_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableRoute gagal dihapus.'
            );
        }
    } 
}
