<?php

namespace App\Modules\FacilityImage\Controllers;

use App\DataTables\FacilityImageDataTable;
use App\Http\Controllers\MainController;
use Models\FacilityImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FacilityImageController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FacilityImage());
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
        FacilityImageDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /facility-image?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FacilityImage::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $facilityImage = new FacilityImage();
        $result = $this->getForm($facilityImage);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FacilityImage::create',
            compact('facilityImage', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'facility_id' => ['required', 'integer'],
            'file_path' => ['required', 'string', 'max:255'],
            'caption' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FacilityImage::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FacilityImage berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FacilityImage.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FacilityImage gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $facilityImage = $this->model->findOrFail($id);
        $result = $this->getForm($facilityImage);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FacilityImage::edit',
            compact('facilityImage', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'facility_id' => ['required', 'integer'],
            'file_path' => ['required', 'string', 'max:255'],
            'caption' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
        ]);

        try {
            $facilityImage = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $facilityImage,
                $validated
            ) {
                $facilityImage->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FacilityImage berhasil diperbarui.',
                $facilityImage
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui FacilityImage.',
                [
                    'exception' => $e,
                    'FacilityImage_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FacilityImage gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $facilityImage = $this->model->findOrFail($id);

            DB::transaction(function () use ($facilityImage) {
                $facilityImage->delete();
            });

            return $this->successResponse(
                $request,
                'Data FacilityImage berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $facilityImage->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FacilityImage.',
                [
                    'exception' => $e,
                    'FacilityImage_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FacilityImage gagal dihapus.'
            );
        }
    } 
}
