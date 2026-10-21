<?php

namespace App\Modules\CmsGallery\Controllers;

use App\DataTables\CmsGalleryDataTable;
use App\Http\Controllers\MainController;
use Models\CmsGallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsGalleryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsGallery());
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
        CmsGalleryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-gallery?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsGallery::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsGallery = new CmsGallery();
        $result = $this->getForm($cmsGallery);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsGallery::create',
            compact('cmsGallery', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'cover_image' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsGallery::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsGallery berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsGallery.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsGallery gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsGallery = $this->model->findOrFail($id);
        $result = $this->getForm($cmsGallery);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsGallery::edit',
            compact('cmsGallery', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'cover_image' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsGallery = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cmsGallery,
                $validated
            ) {
                $cmsGallery->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsGallery berhasil diperbarui.',
                $cmsGallery
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CmsGallery.',
                [
                    'exception' => $e,
                    'CmsGallery_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsGallery gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsGallery = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsGallery) {
                $cmsGallery->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsGallery berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cmsGallery->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsGallery.',
                [
                    'exception' => $e,
                    'CmsGallery_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsGallery gagal dihapus.'
            );
        }
    } 
}
