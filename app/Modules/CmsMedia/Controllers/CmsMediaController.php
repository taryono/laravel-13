<?php

namespace App\Modules\CmsMedia\Controllers;

use App\DataTables\CmsMediaDataTable;
use App\Http\Controllers\MainController;
use Models\CmsMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsMediaController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsMedia());
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
        CmsMediaDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-media?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsMedia::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsMedia = new CmsMedia();
        $result = $this->getForm($cmsMedia);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsMedia::create',
            compact('cmsMedia', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'disk' => ['required', 'string', 'max:255'],
            'path' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:255'],
            'size' => ['required', 'string', 'max:255'],
            'width' => ['required', 'string', 'max:255'],
            'height' => ['required', 'string', 'max:255'],
            'alt_text' => ['required', 'string', 'max:255'],
            'caption' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsMedia::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsMedia berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsMedia.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsMedia gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsMedia = $this->model->findOrFail($id);
        $result = $this->getForm($cmsMedia);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsMedia::edit',
            compact('cmsMedia', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'disk' => ['required', 'string', 'max:255'],
            'path' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:255'],
            'size' => ['required', 'string', 'max:255'],
            'width' => ['required', 'string', 'max:255'],
            'height' => ['required', 'string', 'max:255'],
            'alt_text' => ['required', 'string', 'max:255'],
            'caption' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsMedia = $this->model->findOrFail($id);
            DB::transaction(function () use ($cmsMedia, $validated) {
                $cmsMedia->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsMedia berhasil diperbarui.',
                $cmsMedia
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CmsMedia.',
                [
                    'exception' => $e,
                    'CmsMedia_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsMedia gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsMedia = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsMedia) {
                $cmsMedia->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsMedia berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cmsMedia->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsMedia.',
                [
                    'exception' => $e,
                    'CmsMedia_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsMedia gagal dihapus.'
            );
        }
    }
}
