@extends('layouts.app')
@section('title', 'CMS & Publikasi')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/budgeting.css') }}">
@endpush
@section('content')
    <div class="bgt d-flex flex-column gap-4">
        @include('Dashboard::partials.ep_subnav', [
            'title' => 'Modul CMS & Publikasi',
            'badge' => ($totalDraft ?? 0) . ' Konten Draft',
            'subtitle' => 'Kelola website, berita, galeri, dan informasi publik sekolah',
            'metaIcon' => 'web',
            'metaLabel' => 'Status Website',
            'metaValue' => $siteStatus ?? 'Online',
        ])

        <!-- Page Header (tombol di kanan) -->
        <section
            class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center gap-3 bg-lowest p-3 p-sm-4 r-2xl border-soft shadow-sm">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ url('/') }}" target="_blank" class="btn-edu btn-edu-outline pressable text-decoration-none">
                    <span class="material-symbols-outlined tc-secondary">open_in_new</span>
                    <span>Lihat Website</span>
                </a>
                <button type="button" class="btn-edu btn-edu-solid pressable text-decoration-none" data-toggle="ajax-modal"
                    data-size="lg" data-method="GET" data-title="Tulis Berita Baru"
                    data-url="{{ route('cms_post.create') }}"
                    data-submit='<button class="btn btn-primary" type="submit" form="formCmsPost">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                    <span class="material-symbols-outlined icon-18">edit_square</span>
                    <span>Tulis Berita Baru</span>
                </button>
            </div>
        </section>

        <!-- KPI -->
        <section class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
            <!-- Konten Terbit -->
            <div class="col">
                <div class="kpi-card p-3 p-sm-4" style="--kpi-accent: var(--c-primary-container)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-label-md tc-variant">Konten Terbit</span>
                        <span class="ibox bg-high tc-primary-container">
                            <span class="material-symbols-outlined icon-20">article</span>
                        </span>
                    </div>
                    <div class="text-headline-md tc-primary">{{ $totalPublished ?? 0 }}</div>
                    <div class="d-flex align-items-center gap-1 mt-2 text-body-sm tc-variant">
                        <span class="material-symbols-outlined tc-secondary icon-16">check_circle</span>
                        <span>Berita, halaman &amp; pengumuman</span>
                    </div>
                </div>
            </div>

            <!-- Draft -->
            <div class="col">
                <div class="kpi-card p-3 p-sm-4" style="--kpi-accent: var(--c-secondary)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-label-md tc-variant">Draft &amp; Review</span>
                        <span class="ibox bg-secondary-container-40 tc-secondary">
                            <span class="material-symbols-outlined icon-20">edit_note</span>
                        </span>
                    </div>
                    <div class="text-headline-md tc-primary">{{ $totalDraft ?? 0 }}</div>
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <span class="bpill text-label-sm bg-secondary-container"
                            style="color:var(--c-on-secondary-container)">{{ $pendingReviewCount ?? ($draftPosts ?: 0) }}
                            Menunggu Review</span>
                    </div>
                </div>
            </div>

            <!-- Pengunjung -->
            <div class="col">
                <div class="kpi-card p-3 p-sm-4" style="--kpi-accent: var(--c-tertiary-fixed-dim)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-label-md tc-variant">Pengunjung Bulan Ini</span>
                        <span class="ibox bg-high tc-gold">
                            <span class="material-symbols-outlined icon-20">groups</span>
                        </span>
                    </div>
                    <div class="text-headline-md tc-primary">{{ number_format($currentMonthVisitors ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="d-flex align-items-center gap-1 mt-2 text-body-sm tc-variant">
                        <span
                            class="material-symbols-outlined {{ ($visitorGrowthPercent ?? 0) >= 0 ? 'tc-secondary' : 'tc-error' }} icon-16">
                            {{ ($visitorGrowthPercent ?? 0) >= 0 ? 'trending_up' : 'trending_down' }}
                        </span>
                        <span>{{ (($visitorGrowthPercent ?? 0) >= 0 ? '+' : '') . ($visitorGrowthPercent ?? 0) }}% dari
                            bulan lalu</span>
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div class="col">
                <div class="kpi-card p-3 p-sm-4" style="--kpi-accent: var(--c-secondary-container)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-label-md tc-variant">Penyimpanan Media</span>
                        <span class="ibox bg-high tc-secondary">
                            <span class="material-symbols-outlined icon-20">cloud</span>
                        </span>
                    </div>
                    <div class="text-headline-md tc-secondary">{{ $mediaCount ?? 0 }} File</div>
                    <div class="mt-2">
                        <div class="progress-edu">
                            <div class="bg-secondary-c" style="width: {{ max(2, min(100, $mediaPercentage ?? 0)) }}%">
                            </div>
                        </div>
                        <div class="text-body-sm tc-variant mt-1">{{ $mediaPercentage ?? 0 }}% dari kuota 10 GB
                            ({{ $mediaFormattedSize ?? '0 MB' }})</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Aksi Cepat -->
        <section class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between px-1">
                <h2 class="text-title-sm tc-primary d-flex align-items-center gap-2 mb-0">
                    <span class="material-symbols-outlined tc-secondary">bolt</span>
                    Aksi Cepat CMS
                </h2>
                <span class="text-body-sm tc-variant">Pintasan Publikasi Konten</span>
            </div>

            <div class="row row-cols-2 row-cols-md-4 g-3">
                <div class="col">
                    <button type="button" class="quick-card text-decoration-none d-block w-100 text-start border-0 bg-transparent p-0 cursor-pointer"
                        data-toggle="ajax-modal" data-size="lg" data-method="GET"
                        data-title="Tulis Berita Baru" data-url="{{ route('cms_post.create') }}"
                        data-submit='<button class="btn btn-primary" type="submit" form="formCmsPost">Simpan</button>'
                        data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                        <div class="ibox-lg bg-primary-container text-white mb-3">
                            <span class="material-symbols-outlined">post_add</span>
                        </div>
                        <div>
                            <div class="text-title-sm tc-on-surface quick-title">Tulis Berita</div>
                            <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">Artikel &amp; kegiatan sekolah</p>
                        </div>
                    </button>
                </div>
                <div class="col">
                    <button type="button" class="quick-card text-decoration-none d-block w-100 text-start border-0 bg-transparent p-0 cursor-pointer"
                        data-toggle="ajax-modal" data-size="lg" data-method="GET"
                        data-title="Buat Pengumuman Baru" data-url="{{ Route::has('announcement.create') ? route('announcement.create') : route('cms_post.create') }}"
                        data-submit='<button class="btn btn-primary" type="submit" form="formAnnouncement">Simpan</button>'
                        data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                        <div class="ibox-lg bg-high tc-primary mb-3">
                            <span class="material-symbols-outlined">campaign</span>
                        </div>
                        <div>
                            <div class="text-title-sm tc-on-surface quick-title">Buat Pengumuman</div>
                            <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">Info penting untuk publik</p>
                        </div>
                    </button>
                </div>
                <div class="col">
                    <button type="button" class="quick-card text-decoration-none d-block w-100 text-start border-0 bg-transparent p-0 cursor-pointer"
                        data-toggle="ajax-modal" data-size="lg" data-method="GET"
                        data-title="Unggah Galeri Baru" data-url="{{ route('cms_gallery.create') }}"
                        data-submit='<button class="btn btn-primary" type="submit" form="formCmsGallery">Simpan</button>'
                        data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                        <div class="ibox-lg bg-high tc-secondary mb-3">
                            <span class="material-symbols-outlined">add_photo_alternate</span>
                        </div>
                        <div>
                            <div class="text-title-sm tc-on-surface quick-title">Unggah Galeri</div>
                            <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">Foto &amp; video kegiatan</p>
                        </div>
                    </button>
                </div>
                <div class="col">
                    <button type="button" class="quick-card text-decoration-none d-block w-100 text-start border-0 bg-transparent p-0 cursor-pointer"
                        data-toggle="ajax-modal" data-size="lg" data-method="GET"
                        data-title="Tambah Agenda Kegiatan" data-url="{{ route('academic_calendar_event.create') }}"
                        data-submit='<button class="btn btn-primary" type="submit" form="formAcademicCalendarEvent">Simpan</button>'
                        data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                        <div class="ibox-lg bg-secondary-container mb-3" style="color:var(--c-on-secondary-container)">
                            <span class="material-symbols-outlined">event</span>
                        </div>
                        <div>
                            <div class="text-title-sm tc-on-surface quick-title">Tambah Agenda</div>
                            <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">Kalender kegiatan sekolah</p>
                        </div>
                    </button>
                </div>
            </div>
        </section>

        <!-- Modul Konten -->
        <section class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between px-1">
                <h2 class="text-title-sm tc-primary d-flex align-items-center gap-2 mb-0">
                    <span class="material-symbols-outlined tc-secondary">dashboard_customize</span>
                    Kelola Konten Website
                </h2>
                <span class="text-body-sm tc-variant">8 modul tersedia</span>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
                @php
                    $modules = [
                        [
                            'icon' => 'newspaper',
                            'title' => 'Berita & Artikel',
                            'desc' => 'Tulis, edit, dan jadwalkan publikasi',
                            'count' => ($publishedPosts ?? 0) . ' konten',
                            'tone' => 'bg-primary-container text-white',
                            'url' => route('cms_post.index'),
                        ],
                        [
                            'icon' => 'campaign',
                            'title' => 'Pengumuman',
                            'desc' => 'Informasi resmi untuk siswa & orang tua',
                            'count' => ($announcementsCount ?? 0) . ' aktif',
                            'tone' => 'bg-high tc-primary',
                            'url' => route('announcement.index'),
                        ],
                        [
                            'icon' => 'photo_library',
                            'title' => 'Galeri Foto & Video',
                            'desc' => 'Album kegiatan dan dokumentasi',
                            'count' => ($galleriesCount ?? 0) . ' album',
                            'tone' => 'bg-high tc-secondary',
                            'url' => route('cms_gallery.index'),
                        ],
                        [
                            'icon' => 'calendar_month',
                            'title' => 'Agenda Kegiatan',
                            'desc' => 'Kalender akademik dan event',
                            'count' => ($eventsCount ?? 0) . ' kegiatan',
                            'tone' => 'bg-secondary-container',
                            'url' => route('academic_calendar_event.index'),
                        ],
                        [
                            'icon' => 'school',
                            'title' => 'Profil Sekolah',
                            'desc' => 'Visi misi, sejarah, struktur organisasi',
                            'count' => ($pagesCount ?? 0) . ' halaman',
                            'tone' => 'bg-high tc-primary',
                            'url' => route('cms_page.index'),
                        ],
                        [
                            'icon' => 'view_carousel',
                            'title' => 'Banner & Slider',
                            'desc' => 'Tampilan utama beranda website',
                            'count' => ($bannersCount ?? 0) . ' aktif',
                            'tone' => 'bg-high tc-secondary',
                            'url' => Route::has('cms_banner.index') ? route('cms_banner.index') : route('slider.index'),
                        ],
                        [
                            'icon' => 'folder_open',
                            'title' => 'Dokumen & Unduhan',
                            'desc' => 'Formulir, brosur, dan panduan',
                            'count' => ($documentsCount ?? 0) . ' berkas',
                            'tone' => 'bg-secondary-container',
                            'url' => route('document.index'),
                        ],
                        [
                            'icon' => 'emoji_events',
                            'title' => 'Prestasi & Testimoni',
                            'desc' => 'Capaian siswa dan testimoni',
                            'count' => ($achievementsCount ?? 0) . ' data',
                            'tone' => 'bg-primary-container text-white',
                            'url' => route('achievement.index'),
                        ],
                    ];
                @endphp

                @foreach ($modules as $m)
                    <div class="col">
                        <a href="{{ $m['url'] }}" class="quick-card text-decoration-none d-block w-100 text-start">
                            <div class="d-flex align-items-start justify-content-between w-100 mb-3">
                                <div class="ibox-lg {{ $m['tone'] }}">
                                    <span class="material-symbols-outlined">{{ $m['icon'] }}</span>
                                </div>
                                <span class="bpill text-label-sm bg-low tc-primary-container">{{ $m['count'] }}</span>
                            </div>
                            <div>
                                <div class="text-title-sm tc-on-surface quick-title">{{ $m['title'] }}</div>
                                <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">{{ $m['desc'] }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Pengaturan Website -->
        <section class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between px-1">
                <h2 class="text-title-sm tc-primary d-flex align-items-center gap-2 mb-0">
                    <span class="material-symbols-outlined tc-secondary">tune</span>
                    Pengaturan &amp; Tampilan
                </h2>
                <span class="text-body-sm tc-variant">Konfigurasi website</span>
            </div>

            <div class="row row-cols-2 row-cols-md-4 g-3">
                @php
                    $settings = [
                        [
                            'icon' => 'menu',
                            'title' => 'Menu & Navigasi',
                            'desc' => 'Susunan menu website',
                            'url' => route('navigation.index'),
                        ],
                        [
                            'icon' => 'perm_media',
                            'title' => 'Media Library',
                            'desc' => 'Semua gambar & berkas',
                            'url' => route('cms_media.index'),
                        ],
                        [
                            'icon' => 'travel_explore',
                            'title' => 'SEO & Metadata',
                            'desc' => 'Judul, deskripsi, sitemap',
                            'url' => route('cms_setting.index'),
                        ],
                        [
                            'icon' => 'palette',
                            'title' => 'Tema & Identitas',
                            'desc' => 'Logo, warna, footer',
                            'url' => route('cms_setting.index'),
                        ],
                    ];
                @endphp

                @foreach ($settings as $s)
                    <div class="col">
                        <a href="{{ $s['url'] }}" class="quick-card text-decoration-none d-block w-100 text-start">
                            <div class="ibox-lg bg-high tc-primary mb-3">
                                <span class="material-symbols-outlined">{{ $s['icon'] }}</span>
                            </div>
                            <div>
                                <div class="text-title-sm tc-on-surface quick-title">{{ $s['title'] }}</div>
                                <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">{{ $s['desc'] }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Konten Terbaru vs Review -->
        <div class="row g-4 align-items-start">

            <!-- Aktivitas Konten -->
            <section class="col-lg-7">
                <div class="bg-lowest p-3 p-sm-4 r-2xl border-soft shadow-sm d-flex flex-column gap-4">
                    <div class="d-flex align-items-center justify-content-between border-soft-b pb-3">
                        <div>
                            <h2 class="text-title-sm tc-primary d-flex align-items-center gap-2 mb-0">
                                <span class="material-symbols-outlined tc-secondary">history</span>
                                Konten Terbaru
                            </h2>
                            <p class="text-body-sm tc-variant mt-1 mb-0">Aktivitas publikasi 7 hari terakhir</p>
                        </div>
                        <span class="bpill text-label-sm bg-low tc-primary-container py-1 ms-2 text-nowrap">Terbaru</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @if ($recentItems->isNotEmpty())
                            @foreach ($recentItems as $item)
                                <div class="pos-item d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="text-title-sm tc-on-surface d-flex align-items-center gap-2">
                                            <span class="dot {{ $item->dot_class }}"></span>
                                            {{ $item->title }}
                                        </div>
                                        <div class="text-body-sm tc-variant mt-1">
                                            {{ $item->type_label }} &bull;
                                            {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                    <span
                                        class="text-label-sm tc-secondary text-nowrap">{{ $item->views_formatted ?? 'Aktif' }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4 px-3 bg-low r-xl border-soft">
                                <span class="material-symbols-outlined tc-secondary icon-40 mb-2">article</span>
                                <h3 class="text-title-sm tc-primary mb-1">Belum Ada Konten Terbit</h3>
                                <p class="text-body-sm tc-variant mb-0">Publikasikan berita atau artikel baru untuk
                                    menampilkannya di sini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Menunggu Review -->
            <section class="col-lg-5">
                <div class="bg-lowest p-3 p-sm-4 r-2xl border-soft shadow-sm d-flex flex-column gap-4">
                    <div class="d-flex align-items-center justify-content-between border-soft-b pb-3">
                        <div>
                            <h2 class="text-title-sm tc-primary d-flex align-items-center gap-2 mb-0">
                                <span class="material-symbols-outlined"
                                    style="color:var(--c-tertiary-fixed-dim)">pending_actions</span>
                                Menunggu Review
                            </h2>
                            <p class="text-body-sm tc-variant mt-1 mb-0">Konten belum dipublikasikan</p>
                        </div>
                        <span class="bpill text-label-sm bg-error-container ms-2 text-nowrap"
                            style="color:var(--c-on-error-container)">{{ $pendingReviewCount ?? count($pendingItems) }}
                            Pending</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @if ($pendingItems->isNotEmpty())
                            @foreach ($pendingItems as $p)
                                <div class="approval-item d-flex flex-column gap-3">
                                    <div>
                                        <span
                                            class="btag text-label-sm mb-2">{{ $p->category_label ?? 'Berita / Artikel' }}</span>
                                        <h3 class="text-title-sm tc-on-surface mb-0">{{ $p->title }}</h3>
                                        <p class="text-body-sm tc-variant mt-1 mb-0">
                                            {{ $p->snippet ?? Str::limit($p->excerpt ?: strip_tags($p->content ?? 'Draf konten artikel sekolah'), 75) }}
                                        </p>
                                    </div>
                                    <div
                                        class="d-flex align-items-center flex-wrap gap-2 text-body-sm tc-variant border-soft-t pt-2">
                                        <span class="material-symbols-outlined icon-16 tc-outline">person</span>
                                        <span>Penulis: {{ $p->author_name ?? 'Admin / Staf' }}</span>
                                        <span class="tc-outline">&bull;</span>
                                        <span
                                            class="text-label-sm tc-variant">{{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <div class="row g-2 pt-1">
                                        <div class="col-6">
                                            <a href="{{ route('cms_post.preview', $p->id) }}"
                                                class="btn-sm-edu btn-detail pressable text-decoration-none d-flex align-items-center justify-content-center gap-1">
                                                <span
                                                    class="material-symbols-outlined icon-16">visibility</span><span>Pratinjau</span>
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('cms_post.edit', $p->id) }}"
                                                class="btn-sm-edu btn-approve pressable text-decoration-none d-flex align-items-center justify-content-center gap-1">
                                                <span
                                                    class="material-symbols-outlined icon-16">publish</span><span>Kelola</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4 px-3 bg-low r-xl border-soft">
                                <span class="material-symbols-outlined tc-secondary icon-40 mb-2">task_alt</span>
                                <h3 class="text-title-sm tc-primary mb-1">Semua Draf Sudah Ditinjau</h3>
                                <p class="text-body-sm tc-variant mb-3">Tidak ada konten yang sedang menunggu persetujuan
                                    review.</p>
                                <a href="{{ route('cms_post.create') }}"
                                    class="btn-edu btn-edu-outline pressable text-decoration-none d-inline-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined icon-16">add</span>
                                    <span>Tulis Draf Baru</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="bg-container p-3 r-xl d-flex align-items-center gap-3"
                        style="border:1px solid rgba(0,106,97,.2)">
                        <div class="bg-secondary-10 tc-secondary d-flex align-items-center justify-content-center flex-shrink-0 rounded-circle"
                            style="width:32px;height:32px">
                            <span class="material-symbols-outlined icon-18">info</span>
                        </div>
                        <p class="text-body-sm tc-on-surface mb-0">
                            Konten yang dikirim guru perlu ditinjau admin sebelum tampil di website publik.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('bottom_navigation')
    <!-- Bottom Nav -->
    <nav class="bottom-nav">
        <a href="{{ url('/dashboard') }}" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">dashboard</span>
            <span class="text-label-sm mt-1">Beranda</span>
        </a>
        <a href="{{ route('academic_year.index') }}" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">school</span>
            <span class="text-label-sm mt-1">Akademik</span>
        </a>
        <a href="{{ route('cms_post.index') }}" class="bottom-nav-item active" aria-current="page">
            <span class="material-symbols-outlined icon-fill" style="font-size:24px;">web</span>
            <span class="text-label-sm fw-bold mt-1">CMS</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">chat</span>
            <span class="text-label-sm mt-1">Pesan</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">person</span>
            <span class="text-label-sm mt-1">Profil</span>
        </a>
    </nav>
@endsection
