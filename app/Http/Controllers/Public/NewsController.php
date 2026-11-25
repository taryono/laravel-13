<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Models\CmsCategory;
use Models\CmsPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Halaman daftar berita & pengumuman publik.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $categorySlug = trim((string) $request->query('kategori', ''));

        // 1. Ambil 1 artikel utama (Featured) jika tidak sedang filter/search
        $featuredPost = null;
        if ($search === '' && $categorySlug === '' && (int) $request->query('page', 1) === 1) {
            $featuredPost = CmsPost::published()
                ->with(['author:id,name', 'category:id,name,slug'])
                ->where('is_featured', true)
                ->latest('published_at')
                ->first();
        }

        // 2. Query daftar artikel terbit (kecualikan featuredPost agar tidak duplikat di halaman 1)
        $posts = CmsPost::published()
            ->with(['author:id,name', 'category:id,name,slug'])
            ->when($featuredPost, fn($q) => $q->where('id', '!=', $featuredPost->id))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->when($categorySlug !== '', function ($q) use ($categorySlug) {
                $q->whereHas('category', fn($cat) => $cat->where('slug', $categorySlug));
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        // 3. Daftar kategori beserta jumlah artikel terbit
        $categories = CmsCategory::withCount([
            'posts' => fn($q) => $q->published(),
        ])->orderBy('name')->get();

        return view('public.news.index', compact(
            'featuredPost',
            'posts',
            'categories',
            'search',
            'categorySlug'
        ));
    }

    /**
     * Halaman baca detail artikel publik beserta increment view_count.
     */
    public function show(Request $request, string $slug): View
    {
        $post = CmsPost::published()
            ->with(['author:id,name', 'category:id,name,slug'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view_count maksimal 1x per sesi browser untuk artikel ini (anti-spam F5)
        $sessionKey = "viewed_cms_post_{$post->id}";
        if (!$request->session()->has($sessionKey)) {
            $post->increment('view_count');
            $request->session()->put($sessionKey, true);
        }

        // Artikel terkait berdasarkan kategori yang sama
        $relatedPosts = CmsPost::published()
            ->with(['category:id,name,slug'])
            ->where('id', '!=', $post->id)
            ->when($post->cms_category_id, fn($q) => $q->where('cms_category_id', $post->cms_category_id))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('public.news.show', compact('post', 'relatedPosts'));
    }
}