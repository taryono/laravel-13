<?php

namespace App\Modules\CmsGalleryItem\Controllers;

use App\DataTables\CmsGalleryItemDataTable;
use App\Http\Controllers\MainController;
use Models\CmsGalleryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsGalleryItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsGalleryItem());
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
        CmsGalleryItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-gallery-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsGalleryItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsGalleryItem = new CmsGalleryItem();
        $result = $this->getForm($cmsGalleryItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsGalleryItem::create',
            compact('cmsGalleryItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gallery_id' => ['required', 'integer'],
            'media_id' => ['required', 'integer'],
            'image' => ['required', 'string', 'max:255'],
            'caption' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsGalleryItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsGalleryItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsGalleryItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsGalleryItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsGalleryItem = $this->model->findOrFail($id);
        $result = $this->getForm($cmsGalleryItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsGalleryItem::edit',
            compact('cmsGalleryItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'gallery_id' => ['required', 'integer'],
            'media_id' => ['required', 'integer'],
            'image' => ['required', 'string', 'max:255'],
            'caption' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsGalleryItem = $this->model->findOrFail($id);
            DB::transaction(function () use ($cmsGalleryItem, $validated) {
                $cmsGalleryItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsGalleryItem berhasil diperbarui.',
                $cmsGalleryItem
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CmsGalleryItem.',
                [
                    'exception' => $e,
                    'CmsGalleryItem_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsGalleryItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsGalleryItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsGalleryItem) {
                $cmsGalleryItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsGalleryItem berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cmsGalleryItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsGalleryItem.',
                [
                    'exception' => $e,
                    'CmsGalleryItem_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsGalleryItem gagal dihapus.'
            );
        }
    }
}
