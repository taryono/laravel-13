<?php

namespace App\Modules\CmsTag\Controllers;

use App\DataTables\CmsTagDataTable;
use App\Http\Controllers\MainController;
use Models\CmsTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsTagController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsTag());
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
        CmsTagDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-tag?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsTag::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsTag = new CmsTag();
        $result = $this->getForm($cmsTag);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsTag::create',
            compact('cmsTag', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsTag::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsTag berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsTag.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsTag gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsTag = $this->model->findOrFail($id);
        $result = $this->getForm($cmsTag);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsTag::edit',
            compact('cmsTag', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsTag = $this->model->findOrFail($id);
            DB::transaction(function () use ($cmsTag, $validated) {
                $cmsTag->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsTag berhasil diperbarui.',
                $cmsTag
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CmsTag.',
                [
                    'exception' => $e,
                    'CmsTag_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsTag gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsTag = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsTag) {
                $cmsTag->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsTag berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cmsTag->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsTag.',
                [
                    'exception' => $e,
                    'CmsTag_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsTag gagal dihapus.'
            );
        }
    }
}
