<?php

namespace App\Modules\CmsPost\Controllers;

use App\DataTables\CmsPostDataTable;
use App\Http\Controllers\MainController;
use Models\CmsPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsPostController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsPost());
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
        CmsPostDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-post?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsPost::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsPost = new CmsPost();
        $result = $this->getForm($cmsPost);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsPost::create',
            compact('cmsPost', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'user_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'featured_image' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'is_featured' => ['required', 'string', 'max:255'],
            'allow_comments' => ['required', 'string', 'max:255'],
            'view_count' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsPost::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsPost berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsPost.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsPost gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsPost = $this->model->findOrFail($id);
        $result = $this->getForm($cmsPost);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsPost::edit',
            compact('cmsPost', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'user_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'featured_image' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'is_featured' => ['required', 'string', 'max:255'],
            'allow_comments' => ['required', 'string', 'max:255'],
            'view_count' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsPost = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cmsPost,
                $validated
            ) {
                $cmsPost->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsPost berhasil diperbarui.',
                $cmsPost
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CmsPost.',
                [
                    'exception' => $e,
                    'CmsPost_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsPost gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsPost = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsPost) {
                $cmsPost->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsPost berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cmsPost->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsPost.',
                [
                    'exception' => $e,
                    'CmsPost_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsPost gagal dihapus.'
            );
        }
    } 
}
