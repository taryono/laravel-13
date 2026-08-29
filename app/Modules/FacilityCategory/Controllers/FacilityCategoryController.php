<?php

namespace App\Modules\FacilityCategory\Controllers;

use App\DataTables\FacilityCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\FacilityCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FacilityCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FacilityCategory());
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
        FacilityCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /facility-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FacilityCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $facility_category = new FacilityCategory();
        $result = $this->getForm($facility_category);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FacilityCategory::create',
            compact('facility_category', 'forms', 'groupedForms')
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
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FacilityCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data facility_category berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan facility_category.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data facility_category gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $facility_category = $this->model->findOrFail($id);
        $result = $this->getForm($facility_category);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FacilityCategory::edit',
            compact('facility_category', 'forms', 'groupedForms')
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
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $facility_category = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $facility_category,
                $validated
            ) {
                $facility_category->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data facility_category berhasil diperbarui.',
                $facility_category
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $facility_category->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $facility_category belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui facility_category.',
                [
                    'exception' => $e,
                    'facility_category_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data facility_category gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $facility_category = $this->model->findOrFail($id);

            DB::transaction(function () use ($facility_category) {
                $facility_category->delete();
            });

            return $this->successResponse(
                $request,
                'Data facility_category berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $facility_category->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus facility_category.',
                [
                    'exception' => $e,
                    'facility_category_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data facility_category gagal dihapus.'
            );
        }
    } 
}
