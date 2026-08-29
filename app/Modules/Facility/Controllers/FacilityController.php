<?php

namespace App\Modules\Facility\Controllers;

use App\DataTables\FacilityDataTable;
use App\Http\Controllers\MainController;
use Models\Facility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FacilityController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Facility());
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
        FacilityDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /facility?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Facility::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $facility = new Facility();
        $result = $this->getForm($facility);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Facility::create',
            compact('facility', 'forms', 'groupedForms')
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
            'location_id' => ['required', 'integer'],
            'capacity' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Facility::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Facility berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Facility.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Facility gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $facility = $this->model->findOrFail($id);
        $result = $this->getForm($facility);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Facility::edit',
            compact('facility', 'forms', 'groupedForms')
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
            'location_id' => ['required', 'integer'],
            'capacity' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $facility = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $facility,
                $validated
            ) {
                $facility->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Facility berhasil diperbarui.',
                $facility
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $facility->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $facility belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui Facility.',
                [
                    'exception' => $e,
                    'Facility_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Facility gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $facility = $this->model->findOrFail($id);

            DB::transaction(function () use ($facility) {
                $facility->delete();
            });

            return $this->successResponse(
                $request,
                'Data Facility berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $facility->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Facility.',
                [
                    'exception' => $e,
                    'Facility_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Facility gagal dihapus.'
            );
        }
    }
 
}
