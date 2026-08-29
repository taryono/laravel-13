<?php

namespace App\Modules\Building\Controllers;

use App\DataTables\BuildingDataTable;
use App\Http\Controllers\MainController;
use Models\Building;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BuildingController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Building());
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
        BuildingDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /building?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Building::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $building = new Building();
        $result = $this->getForm($building);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Building::create',
            compact('building', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'total_floors' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Building::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Building berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Building.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Building gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $building = $this->model->findOrFail($id);
        $result = $this->getForm($building);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Building::edit',
            compact('building', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'total_floors' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $building = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $building,
                $validated
            ) {
                $building->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Building berhasil diperbarui.',
                $building
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $building->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $building belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui Building.',
                [
                    'exception' => $e,
                    'Building_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Building gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $building = $this->model->findOrFail($id);

            DB::transaction(function () use ($building) {
                $building->delete();
            });

            return $this->successResponse(
                $request,
                'Data Building berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $building->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Building.',
                [
                    'exception' => $e,
                    'Building_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Building gagal dihapus.'
            );
        }
    } 
}
