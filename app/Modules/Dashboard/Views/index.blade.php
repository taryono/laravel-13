@extends('layouts.app')
@section('title', 'Dashboard')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush
@section('content')

    {{-- ========== GREETING & SEARCH (Responsive Header) ========== --}}
    <section class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
                    @if (isset($school->name))
                        <span
                            class="badge-school d-inline-flex align-items-center gap-1 px-2.5 py-0.5 rounded-pill t-label-sm">
                            <span class="material-symbols-outlined icon-14">school</span>
                            {{ $school->name }}
                        </span>
                    @endif
                    <span class="t-body-sm text-variant d-inline-flex align-items-center gap-1">
                        <span class="dot-secondary d-inline-block rounded-circle"></span>
                        {{ $today }}
                    </span>
                </div>
                <h1 class="t-headline-lg text-primary-edu mb-1">
                    {{ $greeting }}, {{ $user->name }}
                </h1>
                <p class="t-label-md text-variant mb-0">{{ $user->role }}</p>
            </div>

            <div class="search-wrap flex-grow-1 flex-md-grow-0" style="min-width: 280px; max-width: 400px; width: 100%;">
                <span class="material-symbols-outlined icon-20 icon-left">search</span>
                <input type="text" class="form-control" placeholder="Cari data siswa, modul, laporan..."
                    aria-label="Cari data siswa, modul, laporan">
                <span class="kbd-right t-label-sm">⌘K</span>
            </div>
        </div>
    </section>

    {{-- ========== MAIN CONTENT (Desktop: 2 Columns / Mobile: Stacked) ========== --}}
    <div class="row g-4">
        {{-- KOLOM UTAMA (KIRI): Presensi & Menu Utama --}}
        <div class="col-12 col-lg-8">
            {{-- Banner Presensi --}}
            <section class="banner rounded-4 p-3 p-md-4 shadow-sm mb-4">
                <div class="position-relative" style="z-index: 1">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                        <div>
                            <span
                                class="banner-chip d-inline-flex align-items-center gap-1 px-2.5 py-1 rounded-pill t-label-sm">
                                <span class="material-symbols-outlined icon-14">calendar_month</span>
                                {{ $attendance->academic_year }}
                            </span>
                            <h2 class="t-title-sm text-white pt-2 mb-0">Presensi Siswa Hari Ini</h2>
                        </div>
                        <div class="text-start text-sm-end">
                            <span
                                class="t-display text-sf d-block lh-1">{{ number_format($attendance->percentage, 1, ',', '.') }}%</span>
                            <p class="t-label-sm text-sv mb-0 mt-1" style="opacity:.9">Target: {{ $attendance->target }}%
                            </p>
                        </div>
                    </div>

                    <div class="banner-stats row g-2 g-sm-3 text-center mt-3 pt-3 mx-0">
                        <div class="col-4 ps-0">
                            <div class="banner-stat rounded-3 p-2 p-sm-2.5">
                                <span
                                    class="t-title-sm text-white d-block">{{ number_format($attendance->present, 0, ',', '.') }}</span>
                                <span class="t-label-sm text-sv d-block">Siswa Hadir</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="banner-stat rounded-3 p-2 p-sm-2.5">
                                <span class="t-title-sm text-tf d-block">{{ $attendance->permit }}</span>
                                <span class="t-label-sm text-sv d-block">Izin / Sakit</span>
                            </div>
                        </div>
                        <div class="col-4 pe-0">
                            <div class="banner-stat rounded-3 p-2 p-sm-2.5">
                                <span class="t-title-sm text-errc d-block">{{ $attendance->absent }}</span>
                                <span class="t-label-sm text-sv d-block">Alpha</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="banner-blur"></div>
            </section>

            {{-- Menu Utama --}}
            <section class="menu-section mb-4" id="main-menu-section">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h2 class="t-headline-md text-primary-edu mb-0">Menu Utama</h2>
                            @if (session('active_menu_category_id'))
                                <a href="{{ route('dashboard.select-category.get', ['id' => 'all']) }}"
                                    class="badge bg-secondary-subtle text-secondary-emphasis text-decoration-none d-inline-flex align-items-center gap-1 px-2.5 py-1 rounded-pill t-label-sm"
                                    title="Klik untuk mereset filter dan menampilkan semua modul pada sidebar">
                                    <span>Filter Sidebar Aktif</span>
                                    <span class="material-symbols-outlined icon-14">close</span>
                                </a>
                            @endif
                        </div>
                        <p class="t-body-sm text-variant mb-0">Akses modul operasional sekolah (klik card untuk memfilter
                            menu sidebar)</p>
                    </div>
                    <span class="pill-soft px-2.5 py-1 rounded-pill t-label-sm" id="menu-total-pill">
                        <span
                            id="menu-total-count">{{ isset($menuPagination) ? $menuPagination->total() : $menus->count() }}</span>
                        Modul
                    </span>
                </div>

                <div class="position-relative">
                    <div class="menu-grid" id="menu-grid-container">
                        @include('Dashboard::partials.menu_cards', ['menus' => $menus])
                    </div>
                </div>

                {{-- Pagination / Navigation Bar di Bagian Bawah --}}
                @if (isset($menuPagination) && $menuPagination->total() > $menuPagination->perPage())
                    <div
                        class="menu-pagination-bar d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 pt-3 border-top">
                        <div class="t-label-sm text-variant" id="menu-page-info">
                            Menampilkan <span id="menu-from-count">{{ $menuPagination->firstItem() }}</span>–<span
                                id="menu-to-count">{{ $menuPagination->lastItem() }}</span> dari <span
                                id="menu-total-info">{{ $menuPagination->total() }}</span> modul (Halaman <span
                                id="menu-current-page">{{ $menuPagination->currentPage() }}</span> / <span
                                id="menu-last-page">{{ $menuPagination->lastPage() }}</span>)
                        </div>

                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <button type="button"
                                class="btn btn-sm btn-outline-edu d-inline-flex align-items-center gap-1 rounded-pill px-3 py-1.5"
                                id="btn-menu-prev"
                                style="{{ $menuPagination->currentPage() <= 1 ? 'display: none !important;' : '' }}"
                                data-page="{{ $menuPagination->currentPage() - 1 }}">
                                <span class="material-symbols-outlined icon-18">arrow_back</span>
                                <span>Sebelumnya</span>
                            </button>

                            <button type="button"
                                class="btn btn-sm btn-primary-edu d-inline-flex align-items-center gap-1 rounded-pill px-3 py-1.5 shadow-sm"
                                id="btn-menu-next"
                                style="{{ !$menuPagination->hasMorePages() ? 'display: none !important;' : '' }}"
                                data-page="{{ $menuPagination->currentPage() + 1 }}">
                                <span>Selanjutnya</span>
                                <span class="material-symbols-outlined icon-18">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                @endif
            </section>
        </div>

        {{-- KOLOM SIDEBAR (KANAN): Agenda & Info Penting (Sticky di Desktop) --}}
        <div class="col-12 col-lg-4">
            <aside class="sticky-sidebar">
                <section>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="t-headline-md text-primary-edu mb-0">Agenda &amp; Info Penting</h2>
                        <a href="#"
                            class="t-label-md text-secondary-edu text-decoration-none d-flex align-items-center gap-1">
                            Lihat Semua
                            <span class="material-symbols-outlined icon-16">chevron_right</span>
                        </a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @foreach ($agendas as $agenda)
                            <div
                                class="agenda-card border-l-{{ $agenda->tone }} rounded-4 shadow-sm p-3 d-flex align-items-start gap-3">
                                <div class="agenda-icon tone-{{ $agenda->tone }} rounded-3">
                                    <span class="material-symbols-outlined icon-22">{{ $agenda->icon }}</span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span
                                            class="badge-{{ $agenda->tone }} px-2 py-0.5 rounded-pill t-label-sm">{{ $agenda->badge }}</span>
                                        <span class="t-label-sm text-variant">{{ $agenda->time }}</span>
                                    </div>
                                    <h3 class="t-title-sm mb-1">{{ $agenda->title }}</h3>
                                    <p class="t-body-sm text-variant mb-0">{{ $agenda->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnNext = document.getElementById('btn-menu-next');
            const btnPrev = document.getElementById('btn-menu-prev');
            const container = document.getElementById('menu-grid-container');
            const fromCount = document.getElementById('menu-from-count');
            const toCount = document.getElementById('menu-to-count');
            const totalInfo = document.getElementById('menu-total-info');
            const currentPageSpan = document.getElementById('menu-current-page');
            const lastPageSpan = document.getElementById('menu-last-page');
            const totalCountPill = document.getElementById('menu-total-count');

            let isLoading = false;

            function fetchMenuPage(page, btnClicked) {
                if (isLoading || !page) return;
                isLoading = true;

                if (container) container.classList.add('is-loading');

                let originalHtml = '';
                if (btnClicked) {
                    btnClicked.disabled = true;
                    originalHtml = btnClicked.innerHTML;
                    btnClicked.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="width: 0.9rem; height: 0.9rem;"></span>
                        <span>Memuat...</span>
                    `;
                }

                const url = new URL(window.location.href);
                url.searchParams.set('page', page);
                url.searchParams.set('ajax_menus', '1');

                fetch(url.toString(), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal memuat menu (' + response.status + ')');
                        }
                        return response.json();
                    })
                    .then(res => {
                        if (res.status === 'success' && res.html) {
                            if (container) {
                                container.innerHTML = res.html;
                            }

                            const p = res.pagination;
                            if (p) {
                                if (fromCount) fromCount.textContent = p.from;
                                if (toCount) toCount.textContent = p.to;
                                if (totalInfo) totalInfo.textContent = p.total;
                                if (currentPageSpan) currentPageSpan.textContent = p.current_page;
                                if (lastPageSpan) lastPageSpan.textContent = p.last_page;
                                if (totalCountPill) totalCountPill.textContent = p.total;

                                // Update tombol Selanjutnya (Next)
                                if (btnNext) {
                                    btnNext.setAttribute('data-page', p.current_page + 1);
                                    if (p.has_more) {
                                        btnNext.style.removeProperty('display');
                                    } else {
                                        btnNext.style.setProperty('display', 'none', 'important');
                                    }
                                }

                                // Update tombol Sebelumnya (Prev)
                                if (btnPrev) {
                                    btnPrev.setAttribute('data-page', p.current_page - 1);
                                    if (p.has_prev) {
                                        btnPrev.style.removeProperty('display');
                                    } else {
                                        btnPrev.style.setProperty('display', 'none', 'important');
                                    }
                                }
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Error fetching menus:', err);
                    })
                    .finally(() => {
                        isLoading = false;
                        if (container) container.classList.remove('is-loading');
                        if (btnClicked) {
                            btnClicked.disabled = false;
                            btnClicked.innerHTML = originalHtml;
                        }
                    });
            }

            if (btnNext) {
                btnNext.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    fetchMenuPage(page, this);
                });
            }

            if (btnPrev) {
                btnPrev.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    fetchMenuPage(page, this);
                });
            }

            // Event listener saat user klik menu card untuk memfilter sidebar
            document.addEventListener('click', function(e) {
                const card = e.target.closest('.menu-card');
                if (!card) return;

                const categoryId = card.getAttribute('data-menu-category-id');
                const targetUrl = card.getAttribute('data-target-url') || card.getAttribute('href');

                if (!categoryId) return;

                e.preventDefault();

                // Feedback visual: kurangi opacity sesaat
                card.style.opacity = '0.6';

                // Simpan menu_category_id ke session via AJAX
                fetch('{{ route('dashboard.select-category') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            menu_category_id: categoryId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Jika URL adalah rute modul tujuan (bukan '#' dan bukan select-category), arahkan ke URL tersebut
                        if (targetUrl && targetUrl !== '#' && !targetUrl.startsWith('javascript:') && !
                            targetUrl.includes('select-category')) {
                            window.location.href = targetUrl;
                        } else {
                            // Reload halaman agar sidebar langsung terupdate sesuai kategori menu yang dipilih
                            window.location.reload();
                        }
                    })
                    .catch(err => {
                        console.error('Error saving menu category:', err);
                        if (targetUrl && targetUrl !== '#' && !targetUrl.startsWith('javascript:') && !
                            targetUrl.includes('select-category')) {
                            window.location.href = targetUrl;
                        } else {
                            window.location.reload();
                        }
                    });
            });
        });
    </script>
@endpush
