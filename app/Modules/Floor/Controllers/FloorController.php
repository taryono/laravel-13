<?php

namespace App\Modules\Floor\Controllers;

use App\DataTables\FloorDataTable;
use App\Http\Controllers\MainController;
use Models\Floor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FloorController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Floor());
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
        FloorDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /floor?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Floor::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $floor = new Floor();
        $result = $this->getForm($floor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Floor::create',
            compact('floor', 'forms', 'groupedForms')
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
            'building_id' => ['required', 'integer'],
            'floor_number' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Floor::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Floor berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Floor.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Floor gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $floor = $this->model->findOrFail($id);
        $result = $this->getForm($floor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Floor::edit',
            compact('floor', 'forms', 'groupedForms')
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
            'building_id' => ['required', 'integer'],
            'floor_number' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $floor = $this->model->findOrFail($id);

            DB::transaction(function () use ($floor, $validated) {
                $floor->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Floor berhasil diperbarui.',
                $floor
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $floor->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $floor belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui Floor.',
                [
                    'exception' => $e,
                    'Floor_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Floor gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $floor = $this->model->findOrFail($id);

            DB::transaction(function () use ($floor) {
                $floor->delete();
            });

            return $this->successResponse(
                $request,
                'Data Floor berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $floor->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Floor.',
                [
                    'exception' => $e,
                    'Floor_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Floor gagal dihapus.'
            );
        }
    }

}
