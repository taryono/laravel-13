@extends('layouts.public')

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: Str::limit(strip_tags($post->excerpt ?: $post->content), 155))
@section('meta_keywords', $post->meta_keywords)

@section('content')
    {{-- Reading Progress Bar di bagian paling atas --}}
    <div id="readingProgressBar" style="position: fixed; top: 0; left: 0; height: 3.5px; width: 0%; background: linear-gradient(90deg, #0d6efd, #0dcaf0); z-index: 1050; transition: width 0.1s ease-out;"></div>

    <div class="container py-4 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                {{-- Breadcrumb Navigasi --}}
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb small px-3 py-2 rounded-pill bg-body-tertiary border border-secondary-subtle d-inline-flex align-items-center mb-0 shadow-xs">
                        <li class="breadcrumb-item">
                            <a href="{{ route('public.news.index') }}" class="text-decoration-none text-body-secondary">
                                <i class="bi bi-newspaper me-1"></i> Berita
                            </a>
                        </li>
                        @if ($post->category)
                            <li class="breadcrumb-item">
                                <a href="{{ route('public.news.index', ['kategori' => $post->category->slug]) }}" class="text-decoration-none text-body-secondary">
                                    {{ $post->category->name }}
                                </a>
                            </li>
                        @endif
                        <li class="breadcrumb-item active text-body-emphasis" aria-current="page">
                            {{ Str::limit($post->title, 35) }}
                        </li>
                    </ol>
                </nav>

                {{-- Header Artikel --}}
                <header class="mb-4">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        @if ($post->category)
                            <a href="{{ route('public.news.index', ['kategori' => $post->category->slug]) }}" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-1.5 rounded-pill fw-semibold">
                                {{ $post->category->name }}
                            </a>
                        @endif
                        @if ($post->is_featured)
                            <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">
                                <i class="bi bi-star-fill me-1"></i> Unggulan
                            </span>
                        @endif
                    </div>

                    <h1 class="display-6 fw-bold text-body-emphasis mb-3 lh-sm">{{ $post->title }}</h1>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 py-3 border-top border-bottom border-secondary-subtle">
                        <div class="d-flex align-items-center gap-2.5 small text-body-secondary">
                            <span class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle fw-bold" style="width: 36px; height: 36px;">
                                {{ strtoupper(substr($post->author?->name ?? 'A', 0, 1)) }}
                            </span>
                            <div>
                                <div class="fw-semibold text-body-emphasis">{{ $post->author?->name ?? 'Admin Sekolah' }}</div>
                                <div class="d-flex align-items-center gap-2" style="font-size: 0.74rem;">
                                    <span><i class="bi bi-calendar3 me-1"></i> {{ $post->published_at?->translatedFormat('l, d F Y') }}</span>
                                    <span>•</span>
                                    <span><i class="bi bi-eye me-1"></i> {{ number_format($post->view_count) }}x dibaca</span>
                                    <span>•</span>
                                    <span><i class="bi bi-clock me-1"></i> {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} mnt</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Bagikan (Social Share) --}}
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="small text-body-secondary me-1 d-none d-sm-inline" style="font-size: 0.8rem;">Bagikan:</span>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="btn btn-sm btn-outline-success rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs"
                                style="width: 34px; height: 34px;" title="Bagikan ke WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs"
                                style="width: 34px; height: 34px;" title="Bagikan ke Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="btn btn-sm btn-outline-dark rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs"
                                style="width: 34px; height: 34px;" title="Bagikan ke X / Twitter">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                            <button type="button" id="copyUrlBtn"
                                class="btn btn-sm btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs"
                                style="width: 34px; height: 34px;" title="Salin Tautan Artikel">
                                <i class="bi bi-link-45deg fs-6"></i>
                            </button>
                        </div>
                    </div>
                </header>

                {{-- Featured Image Banner --}}
                <figure class="mb-4 position-relative overflow-hidden rounded-4 border border-secondary-subtle shadow-sm bg-body-tertiary">
                    <img src="{{ $post->featured_image_url ?? asset('images/placeholder-news.svg') }}"
                        class="img-fluid w-100 object-fit-cover" style="max-height: 480px; min-height: 260px;"
                        alt="{{ $post->title }}"
                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder-news.svg') }}';">
                </figure>

                {{-- Excerpt Highlight Box --}}
                @if ($post->excerpt)
                    <div class="p-3.5 px-4 bg-body-tertiary border-start border-4 border-primary rounded-end-4 mb-4 text-body-secondary fst-italic shadow-xs">
                        <i class="bi bi-quote fs-3 text-primary me-2 align-middle"></i>
                        {{ $post->excerpt }}
                    </div>
                @endif

                {{-- Isi Artikel (Tersanitasi via HTMLPurifier) --}}
                <article class="cms-post-body lh-lg text-body mb-5">
                    {!! $post->content !!}
                </article>

                {{-- Tombol Kembali & Bagikan di Bawah Artikel --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-4 pb-2 border-top border-secondary-subtle">
                    <a href="{{ route('public.news.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-medium d-inline-flex align-items-center gap-2">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Semua Berita</span>
                    </a>

                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-body-secondary" style="font-size: 0.8rem;">Bagikan artikel ini:</span>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                            target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                            <i class="bi bi-whatsapp me-1"></i> WhatsApp
                        </a>
                    </div>
                </div>

                {{-- Komentar Section --}}
                @include('public.news.partials._comments', ['post' => $post])

                {{-- Artikel Terkait Lainnya --}}
                @if ($relatedPosts->isNotEmpty())
                    <section class="pt-5 mt-5 border-top border-secondary-subtle">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="fw-bold text-body-emphasis mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-collection-play-fill text-primary"></i>
                                <span>Berita Terkait Lainnya</span>
                            </h5>
                            <a href="{{ route('public.news.index', ['kategori' => $post->category?->slug]) }}" class="small text-decoration-none text-primary fw-semibold">
                                Lihat Semua <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <div class="row g-3">
                            @foreach ($relatedPosts as $related)
                                <div class="col-md-4">
                                    <div class="card h-100 border border-secondary-subtle rounded-4 shadow-sm bg-body news-card overflow-hidden">
                                        <div class="news-card-img-wrap bg-body-tertiary">
                                            <a href="{{ route('public.news.show', $related->slug) }}">
                                                <img src="{{ $related->featured_image_url ?? asset('images/placeholder-news.svg') }}"
                                                    class="card-img-top object-fit-cover w-100" style="height: 130px;"
                                                    alt="{{ $related->title }}"
                                                    onerror="this.onerror=null; this.src='{{ asset('images/placeholder-news.svg') }}';">
                                            </a>
                                        </div>
                                        <div class="card-body p-3 d-flex flex-column">
                                            <small class="text-body-secondary d-block mb-1.5" style="font-size: 0.74rem;">
                                                <i class="bi bi-calendar3 me-1"></i>{{ $related->published_at?->translatedFormat('d M Y') }}
                                            </small>
                                            <h6 class="fw-bold text-body-emphasis mb-2 flex-grow-1" style="font-size: 0.88rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                <a href="{{ route('public.news.show', $related->slug) }}"
                                                    class="text-decoration-none text-body-emphasis hover-primary">
                                                    {{ $related->title }}
                                                </a>
                                            </h6>
                                            <div class="mt-auto pt-2 border-top border-secondary-subtle d-flex justify-content-between align-items-center" style="font-size: 0.72rem;">
                                                <span class="text-body-secondary">{{ $related->category?->name ?? 'Berita' }}</span>
                                                <span class="text-primary fw-semibold">Baca <i class="bi bi-chevron-right"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </div>

    {{-- Toast Notifikasi Copy URL --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
        <div id="copyToast" class="toast align-items-center text-bg-primary border-0 rounded-4 shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body small fw-semibold">
                    <i class="bi bi-check-circle-fill me-1"></i> Tautan artikel berhasil disalin ke clipboard!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Reading Progress Bar
            const progressBar = document.getElementById('readingProgressBar');
            window.addEventListener('scroll', function() {
                const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
                if (totalHeight > 0 && progressBar) {
                    const progress = (window.scrollY / totalHeight) * 100;
                    progressBar.style.width = Math.min(100, Math.max(0, progress)) + '%';
                }
            });

            // 2. Salin Tautan (Copy URL)
            const copyBtn = document.getElementById('copyUrlBtn');
            const toastEl = document.getElementById('copyToast');
            if (copyBtn && toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
                copyBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText(window.location.href).then(function() {
                        toast.show();
                    }).catch(function() {
                        const input = document.createElement('input');
                        input.value = window.location.href;
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        document.body.removeChild(input);
                        toast.show();
                    });
                });
            }
        });
    </script>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Reading Progress Bar
            const progressBar = document.getElementById('readingProgressBar');
            window.addEventListener('scroll', function() {
                const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
                if (totalHeight > 0 && progressBar) {
                    const progress = (window.scrollY / totalHeight) * 100;
                    progressBar.style.width = Math.min(100, Math.max(0, progress)) + '%';
                }
            });

            // 2. Salin Tautan (Copy URL)
            const copyBtn = document.getElementById('copyUrlBtn');
            const toastEl = document.getElementById('copyToast');
            if (copyBtn && toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
                copyBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText(window.location.href).then(function() {
                        toast.show();
                    }).catch(function() {
                        const input = document.createElement('input');
                        input.value = window.location.href;
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        document.body.removeChild(input);
                        toast.show();
                    });
                });
            }
        });
    </script>
@endpush
