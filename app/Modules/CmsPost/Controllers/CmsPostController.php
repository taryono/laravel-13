<?php

namespace App\Modules\CmsPost\Controllers;

use App\DataTables\CmsPostDataTable;
use App\Http\Controllers\MainController;
use App\Models\User;
use Models\CmsPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', 'unique:cms_posts,slug'],
            'category_id'      => ['nullable', 'integer', 'exists:cms_categories,id'],
            'cms_category_id'  => ['nullable', 'integer', 'exists:cms_categories,id'],
            'user_id'          => ['nullable', 'integer', 'exists:users,id'],
            'excerpt'          => ['nullable', 'string'],
            'content'          => ['nullable', 'string'],
            'featured_image'   => ['nullable'],
            'status'           => ['nullable', 'string', Rule::in(['draft', 'published', 'scheduled', 'archived'])],
            'published_at'     => ['nullable', 'date'],
            'is_featured'      => ['nullable', 'boolean'],
            'allow_comments'   => ['nullable', 'boolean'],
            'view_count'       => ['nullable', 'integer', 'min:0'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'tags'             => ['nullable', 'array'],
        ]);

        $catId = $request->input('cms_category_id') ?? $request->input('category_id');
        if ($catId) {
            $validated['cms_category_id'] = $catId;
        }
        unset($validated['category_id']);

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['title']) ?: 'post-' . time();
            $slug = $baseSlug;
            $count = 1;
            while (CmsPost::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }
            $validated['slug'] = $slug;
        }

        if (empty($validated['user_id'])) {
            $validated['user_id'] = auth()->id() ?? User::first()?->id ?? 1;
        }

        if (empty($validated['status'])) {
            $validated['status'] = 'draft';
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('cms/posts', 'public');
        }

        try {
            $model = DB::transaction(function () use ($validated) {
                $tags = $validated['tags'] ?? null;
                unset($validated['tags']);

                $post = CmsPost::create($validated);

                if (!empty($tags)) {
                    $post->tags()->sync($tags);
                }

                return $post;
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
                'Data CmsPost gagal ditambahkan: ' . $e->getMessage()
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
        $cmsPost = $this->model->findOrFail($id);

        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', Rule::unique('cms_posts', 'slug')->ignore($cmsPost->id)],
            'category_id'      => ['nullable', 'integer', 'exists:cms_categories,id'],
            'cms_category_id'  => ['nullable', 'integer', 'exists:cms_categories,id'],
            'user_id'          => ['nullable', 'integer', 'exists:users,id'],
            'excerpt'          => ['nullable', 'string'],
            'content'          => ['nullable', 'string'],
            'featured_image'   => ['nullable'],
            'status'           => ['nullable', 'string', Rule::in(['draft', 'published', 'scheduled', 'archived'])],
            'published_at'     => ['nullable', 'date'],
            'is_featured'      => ['nullable', 'boolean'],
            'allow_comments'   => ['nullable', 'boolean'],
            'view_count'       => ['nullable', 'integer', 'min:0'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'tags'             => ['nullable', 'array'],
        ]);

        $catId = $request->input('cms_category_id') ?? $request->input('category_id');
        if ($catId) {
            $validated['cms_category_id'] = $catId;
        }
        unset($validated['category_id']);

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['title']) ?: 'post-' . time();
            $slug = $baseSlug;
            $count = 1;
            while (CmsPost::where('slug', $slug)->where('id', '!=', $cmsPost->id)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }
            $validated['slug'] = $slug;
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('cms/posts', 'public');
        }

        try {
            DB::transaction(function () use ($cmsPost, $validated) {
                $tags = $validated['tags'] ?? null;
                unset($validated['tags']);

                $cmsPost->update($validated);

                if ($tags !== null) {
                    $cmsPost->tags()->sync($tags);
                }
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
                'Data CmsPost gagal diperbarui: ' . $e->getMessage()
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View|JsonResponse
    {
        $cmsPost = $this->model->with(['user', 'category', 'tags'])->findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'data' => $cmsPost]);
        }

        return view('CmsPost::show', compact('cmsPost'));
    }

    /**
     * Preview the post.
     */
    public function preview($id): View|JsonResponse
    {
        $cmsPost = $this->model->with(['user', 'category', 'tags'])->findOrFail($id);

        return view('CmsPost::preview', compact('cmsPost'));
    }

    /**
     * Print view for the post.
     */
    public function print($id): View
    {
        $cmsPost = $this->model->with(['user', 'category'])->findOrFail($id);

        return view('CmsPost::print', compact('cmsPost'));
    }

    /**
     * Search posts by title or slug.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', $request->get('search', ''));
        $results = $this->model->where('title', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->limit(20)
            ->get(['id', 'title', 'slug', 'status', 'published_at']);

        return response()->json([
            'success' => true,
            'data'    => $results,
        ]);
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
