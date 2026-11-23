<?php

namespace App\Modules\Location\Controllers;

use App\DataTables\LocationDataTable;
use App\Http\Controllers\MainController;
use Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LocationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Location());
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
        LocationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /location?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Location::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $location = new Location();
        $result = $this->getForm($location);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Location::create',
            compact('location', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Location::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Location berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Location.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Location gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $location = $this->model->findOrFail($id);
        $result = $this->getForm($location);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Location::edit',
            compact('location', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $location = $this->model->findOrFail($id);
            DB::transaction(function () use ($location, $validated) {
                $location->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Location berhasil diperbarui.',
                $location
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Location.',
                [
                    'exception' => $e,
                    'Location_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Location gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $location = $this->model->findOrFail($id);

            DB::transaction(function () use ($location) {
                $location->delete();
            });

            return $this->successResponse(
                $request,
                'Data Location berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $location->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Location.',
                [
                    'exception' => $e,
                    'Location_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Location gagal dihapus.'
            );
        }
    }
}
