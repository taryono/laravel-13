<?php

namespace App\Modules\CmsPage\Controllers;

use App\DataTables\CmsPageDataTable;
use App\Http\Controllers\MainController;
use Models\CmsPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsPageController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsPage());
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
        CmsPageDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-page?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsPage::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsPage = new CmsPage();
        $result = $this->getForm($cmsPage);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsPage::create',
            compact('cmsPage', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'featured_image' => ['required', 'string', 'max:255'],
            'template' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsPage::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsPage berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsPage.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsPage gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsPage = $this->model->findOrFail($id);
        $result = $this->getForm($cmsPage);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsPage::edit',
            compact('cmsPage', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'featured_image' => ['required', 'string', 'max:255'],
            'template' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsPage = $this->model->findOrFail($id);
            DB::transaction(function () use ($cmsPage, $validated) {
                $cmsPage->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsPage berhasil diperbarui.',
                $cmsPage
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CmsPage.',
                [
                    'exception' => $e,
                    'CmsPage_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsPage gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsPage = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsPage) {
                $cmsPage->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsPage berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cmsPage->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsPage.',
                [
                    'exception' => $e,
                    'CmsPage_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsPage gagal dihapus.'
            );
        }
    }
}
