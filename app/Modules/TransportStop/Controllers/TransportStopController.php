<?php

namespace App\Modules\TransportStop\Controllers;

use App\DataTables\TransportStopDataTable;
use App\Http\Controllers\MainController;
use Models\TransportStop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TransportStopController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TransportStop());
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
        TransportStopDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /transport-stop?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TransportStop::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $transportStop = new TransportStop();
        $result = $this->getForm($transportStop);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TransportStop::create',
            compact('transportStop', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transport_route_id' => ['required', 'integer'],
            'stop_order' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'estimated_pickup_time' => ['required', 'string', 'max:255'],
            'estimated_dropoff_time' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TransportStop::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TransportStop berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TransportStop.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TransportStop gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $transportStop = $this->model->findOrFail($id);
        $result = $this->getForm($transportStop);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TransportStop::edit',
            compact('transportStop', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'transport_route_id' => ['required', 'integer'],
            'stop_order' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'estimated_pickup_time' => ['required', 'string', 'max:255'],
            'estimated_dropoff_time' => ['required', 'string', 'max:255'],
        ]);

        try {
            $transportStop = $this->model->findOrFail($id);
            DB::transaction(function () use ($transportStop, $validated) {
                $transportStop->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TransportStop berhasil diperbarui.',
                $transportStop
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui TransportStop.',
                [
                    'exception' => $e,
                    'TransportStop_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TransportStop gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $transportStop = $this->model->findOrFail($id);

            DB::transaction(function () use ($transportStop) {
                $transportStop->delete();
            });

            return $this->successResponse(
                $request,
                'Data TransportStop berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $transportStop->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TransportStop.',
                [
                    'exception' => $e,
                    'TransportStop_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TransportStop gagal dihapus.'
            );
        }
    }
}
