@extends('layouts.public')

@section('title', 'Berita & Pengumuman Sekolah')

@section('content')
    <div class="container py-4 py-lg-5">
        {{-- Header & Form Pencarian --}}
        <div class="row align-items-center g-3 mb-4 pb-2 border-bottom border-secondary-subtle">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill fw-semibold">
                        <i class="bi bi-newspaper me-1"></i> Warta &amp; Agenda Terkini
                    </span>
                </div>
                <h1 class="display-6 fw-bold text-body-emphasis mb-2">Berita &amp; Pengumuman</h1>
                <p class="text-body-secondary mb-0">Informasi akademik resmi, prestasi membanggakan, dan dokumentasi agenda kegiatan sekolah.</p>
            </div>
            <div class="col-lg-5">
                <form method="GET" action="{{ route('public.news.index') }}">
                    @if ($categorySlug !== '')
                        <input type="hidden" name="kategori" value="{{ $categorySlug }}">
                    @endif
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border border-secondary-subtle bg-body">
                        <span class="input-group-text bg-transparent border-0 ps-3 text-body-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" value="{{ $search }}" class="form-control border-0 bg-transparent text-body ps-1 focus-ring-0"
                            placeholder="Cari topik atau judul berita..." aria-label="Cari berita">
                        @if ($search !== '')
                            <a href="{{ route('public.news.index', array_filter(['kategori' => $categorySlug])) }}" class="btn btn-link text-body-secondary border-0 text-decoration-none px-2 d-flex align-items-center" title="Hapus pencarian">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary rounded-pill px-4 m-1 fw-semibold">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Filter Pill Kategori --}}
        <div class="d-flex flex-nowrap flex-md-wrap gap-2 mb-4 pb-2 overflow-x-auto text-nowrap">
            <a href="{{ route('public.news.index', array_filter(['q' => $search])) }}"
                class="btn btn-sm rounded-pill px-3 py-1.5 fw-medium d-inline-flex align-items-center gap-1.5 {{ $categorySlug === '' ? 'btn-primary shadow-sm' : 'btn-outline-secondary border-secondary-subtle text-body-secondary bg-body' }}">
                <span>Semua Kategori</span>
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('public.news.index', array_filter(['kategori' => $cat->slug, 'q' => $search])) }}"
                    class="btn btn-sm rounded-pill px-3 py-1.5 fw-medium d-inline-flex align-items-center gap-1.5 {{ $categorySlug === $cat->slug ? 'btn-primary shadow-sm' : 'btn-outline-secondary border-secondary-subtle text-body-secondary bg-body' }}">
                    <span>{{ $cat->name }}</span>
                    <span class="badge {{ $categorySlug === $cat->slug ? 'bg-white text-primary' : 'bg-body-secondary text-body-secondary border border-secondary-subtle' }} rounded-pill" style="font-size: 0.72rem;">
                        {{ $cat->posts_count }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Informasi Filter Aktif (Jika Pencarian / Kategori Aktif) --}}
        @if ($search !== '' || $categorySlug !== '')
            <div class="alert alert-light border border-secondary-subtle bg-body-tertiary d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3 rounded-4 mb-4 small text-body">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-funnel text-primary"></i>
                    <span>
                        Menampilkan hasil untuk:
                        @if ($search !== '')
                            kata kunci <strong>"{{ $search }}"</strong>
                        @endif
                        @if ($categorySlug !== '')
                            kategori <strong>"{{ $categories->firstWhere('slug', $categorySlug)?->name ?? $categorySlug }}"</strong>
                        @endif
                    </span>
                </div>
                <a href="{{ route('public.news.index') }}" class="btn btn-sm btn-link text-decoration-none text-danger p-0 fw-semibold">
                    <i class="bi bi-x-circle me-1"></i> Reset Filter
                </a>
            </div>
        @endif

        {{-- Hero Card Artikel Unggulan (Featured) --}}
        @if ($featuredPost)
            <div class="card border border-secondary-subtle shadow-sm rounded-4 overflow-hidden mb-5 featured-news-card bg-body">
                <div class="row g-0">
                    <div class="col-lg-6 featured-card-img-wrap bg-body-tertiary position-relative overflow-hidden">
                        <a href="{{ route('public.news.show', $featuredPost->slug) }}" class="d-block h-100">
                            <img src="{{ $featuredPost->featured_image_url ?? asset('images/placeholder-news.svg') }}"
                                class="w-100 h-100 object-fit-cover" style="min-height: 300px; max-height: 400px;"
                                alt="{{ $featuredPost->title }}"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder-news.svg') }}';">
                        </a>
                        <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                            <span class="badge bg-warning text-dark shadow-sm px-3 py-1.5 rounded-pill fw-bold">
                                <i class="bi bi-star-fill me-1"></i> Unggulan
                            </span>
                            @if ($featuredPost->category)
                                <span class="badge bg-body text-primary shadow-sm border border-secondary-subtle px-2.5 py-1.5 rounded-pill fw-semibold">
                                    {{ $featuredPost->category->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 p-4 p-lg-5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 text-body-secondary small mb-2">
                                <span><i class="bi bi-calendar3 me-1"></i> {{ $featuredPost->published_at?->translatedFormat('d F Y') }}</span>
                                <span>•</span>
                                <span><i class="bi bi-clock me-1"></i> {{ ceil(str_word_count(strip_tags($featuredPost->content)) / 200) }} menit baca</span>
                            </div>

                            <h2 class="h3 fw-bold text-body-emphasis mb-3">
                                <a href="{{ route('public.news.show', $featuredPost->slug) }}"
                                    class="text-decoration-none text-body-emphasis hover-primary">
                                    {{ $featuredPost->title }}
                                </a>
                            </h2>

                            <p class="text-body-secondary mb-4 lh-base">
                                {{ Str::limit($featuredPost->excerpt ?: strip_tags($featuredPost->content), 180) }}
                            </p>
                        </div>

                        <div class="pt-3 border-top border-secondary-subtle d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-2 small text-body-secondary">
                                <span class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($featuredPost->author?->name ?? 'A', 0, 1)) }}
                                </span>
                                <div>
                                    <div class="fw-semibold text-body-emphasis">{{ $featuredPost->author?->name ?? 'Admin Sekolah' }}</div>
                                    <div style="font-size: 0.72rem;">{{ number_format($featuredPost->view_count) }} pembaca</div>
                                </div>
                            </div>

                            <a href="{{ route('public.news.show', $featuredPost->slug) }}" class="btn btn-sm btn-primary rounded-pill px-3.5 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm">
                                <span>Baca Artikel</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Grid Daftar Berita --}}
        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border border-secondary-subtle shadow-sm rounded-4 overflow-hidden news-card bg-body">
                        {{-- Thumbnail Gambar --}}
                        <div class="news-card-img-wrap bg-body-tertiary">
                            <a href="{{ route('public.news.show', $post->slug) }}" class="d-block">
                                <img src="{{ $post->featured_image_url ?? asset('images/placeholder-news.svg') }}"
                                    class="card-img-top object-fit-cover w-100" style="height: 210px;" alt="{{ $post->title }}"
                                    loading="lazy"
                                    onerror="this.onerror=null; this.src='{{ asset('images/placeholder-news.svg') }}';">
                            </a>
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-body text-primary shadow-sm border border-secondary-subtle rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">
                                    {{ $post->category?->name ?? 'Umum' }}
                                </span>
                            </div>
                        </div>

                        {{-- Isi Konten Kartu --}}
                        <div class="card-body d-flex flex-column p-3.5 p-lg-4">
                            <div class="d-flex justify-content-between align-items-center mb-2 small text-body-secondary" style="font-size: 0.78rem;">
                                <span><i class="bi bi-calendar3 me-1"></i> {{ $post->published_at?->translatedFormat('d M Y') }}</span>
                                <span><i class="bi bi-eye me-1"></i> {{ number_format($post->view_count) }}</span>
                            </div>

                            <h5 class="card-title fw-bold text-body-emphasis mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.8rem; line-height: 1.4;">
                                <a href="{{ route('public.news.show', $post->slug) }}"
                                    class="text-decoration-none text-body-emphasis hover-primary">
                                    {{ $post->title }}
                                </a>
                            </h5>

                            <p class="card-text text-body-secondary small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                {{ $post->excerpt ?: strip_tags($post->content) }}
                            </p>

                            <div class="pt-3 border-top border-secondary-subtle d-flex justify-content-between align-items-center small">
                                <span class="text-body-secondary d-flex align-items-center gap-1.5" style="font-size: 0.78rem;">
                                    <i class="bi bi-person-circle text-primary"></i>
                                    <span>{{ Str::limit($post->author?->name ?? 'Admin', 18) }}</span>
                                </span>

                                <a href="{{ route('public.news.show', $post->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                    <span>Baca</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border border-secondary-subtle bg-body-tertiary text-center py-5 px-3 rounded-4 shadow-sm">
                        <div class="card-body">
                            <div class="text-body-secondary mb-3">
                                <i class="bi bi-journal-x display-4 text-secondary"></i>
                            </div>
                            <h4 class="fw-bold text-body-emphasis mb-2">Tidak Ada Berita Ditemukan</h4>
                            <p class="text-body-secondary mb-4 mx-auto" style="max-width: 480px;">
                                Maaf, kami tidak menemukan artikel berita atau pengumuman yang sesuai dengan filter atau kata kunci pencarian Anda.
                            </p>
                            <a href="{{ route('public.news.index') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Tampilkan Semua Berita
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($posts->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection