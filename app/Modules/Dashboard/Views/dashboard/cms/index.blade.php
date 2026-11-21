@extends('layouts.app')
@section('title', 'CMS & Publikasi')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/budgeting.css') }}">
@endpush
@section('content')
    <div class="bgt d-flex flex-column gap-4">
        @include('Dashboard::partials.ep_subnav', [
            'title' => 'Modul CMS & Publikasi',
            'badge' => '12 Konten Draft',
            'subtitle' => 'Kelola website, berita, galeri, dan informasi publik sekolah',
            'metaIcon' => 'web',
            'metaLabel' => 'Status Website',
            'metaValue' => 'Online',
        ])

        <!-- Page Header (tombol di kanan) -->
        <section
            class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center gap-3 bg-lowest p-3 p-sm-4 r-2xl border-soft shadow-sm">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button type="button" class="btn-edu btn-edu-outline pressable">
                    <span class="material-symbols-outlined tc-secondary">open_in_new</span>
                    <span>Lihat Website</span>
                </button>
                <button type="button" class="btn-edu btn-edu-solid pressable">
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
                    <div class="text-headline-md tc-primary">248</div>
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
                    <div class="text-headline-md tc-primary">12</div>
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <span class="bpill text-label-sm bg-secondary-container"
                            style="color:var(--c-on-secondary-container)">3 Menunggu Review</span>
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
                    <div class="text-headline-md tc-primary">18.420</div>
                    <div class="d-flex align-items-center gap-1 mt-2 text-body-sm tc-variant">
                        <span class="material-symbols-outlined tc-secondary icon-16">trending_up</span>
                        <span>+12% dari bulan lalu</span>
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
                    <div class="text-headline-md tc-secondary">3,2 GB</div>
                    <div class="mt-2">
                        <div class="progress-edu">
                            <div class="bg-secondary-c" style="width: 32%"></div>
                        </div>
                        <div class="text-body-sm tc-variant mt-1">32% dari kuota 10 GB</div>
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
                    <button type="button" class="quick-card">
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
                    <button type="button" class="quick-card">
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
                    <button type="button" class="quick-card">
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
                    <button type="button" class="quick-card">
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
                            'count' => '96 konten',
                            'tone' => 'bg-primary-container text-white',
                        ],
                        [
                            'icon' => 'campaign',
                            'title' => 'Pengumuman',
                            'desc' => 'Informasi resmi untuk siswa & orang tua',
                            'count' => '34 aktif',
                            'tone' => 'bg-high tc-primary',
                        ],
                        [
                            'icon' => 'photo_library',
                            'title' => 'Galeri Foto & Video',
                            'desc' => 'Album kegiatan dan dokumentasi',
                            'count' => '42 album',
                            'tone' => 'bg-high tc-secondary',
                        ],
                        [
                            'icon' => 'calendar_month',
                            'title' => 'Agenda Kegiatan',
                            'desc' => 'Kalender akademik dan event',
                            'count' => '18 mendatang',
                            'tone' => 'bg-secondary-container',
                        ],
                        [
                            'icon' => 'school',
                            'title' => 'Profil Sekolah',
                            'desc' => 'Visi misi, sejarah, struktur organisasi',
                            'count' => '9 halaman',
                            'tone' => 'bg-high tc-primary',
                        ],
                        [
                            'icon' => 'view_carousel',
                            'title' => 'Banner & Slider',
                            'desc' => 'Tampilan utama beranda website',
                            'count' => '5 aktif',
                            'tone' => 'bg-high tc-secondary',
                        ],
                        [
                            'icon' => 'folder_open',
                            'title' => 'Dokumen & Unduhan',
                            'desc' => 'Formulir, brosur, dan panduan',
                            'count' => '27 berkas',
                            'tone' => 'bg-secondary-container',
                        ],
                        [
                            'icon' => 'emoji_events',
                            'title' => 'Prestasi & Alumni',
                            'desc' => 'Capaian siswa dan testimoni',
                            'count' => '61 data',
                            'tone' => 'bg-primary-container text-white',
                        ],
                    ];
                @endphp

                @foreach ($modules as $m)
                    <div class="col">
                        <button type="button" class="quick-card">
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
                        </button>
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
                        ['icon' => 'menu', 'title' => 'Menu & Navigasi', 'desc' => 'Susunan menu website'],
                        ['icon' => 'perm_media', 'title' => 'Media Library', 'desc' => 'Semua gambar & berkas'],
                        [
                            'icon' => 'travel_explore',
                            'title' => 'SEO & Metadata',
                            'desc' => 'Judul, deskripsi, sitemap',
                        ],
                        ['icon' => 'palette', 'title' => 'Tema & Identitas', 'desc' => 'Logo, warna, footer'],
                    ];
                @endphp

                @foreach ($settings as $s)
                    <div class="col">
                        <button type="button" class="quick-card">
                            <div class="ibox-lg bg-high tc-primary mb-3">
                                <span class="material-symbols-outlined">{{ $s['icon'] }}</span>
                            </div>
                            <div>
                                <div class="text-title-sm tc-on-surface quick-title">{{ $s['title'] }}</div>
                                <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">{{ $s['desc'] }}</p>
                            </div>
                        </button>
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
                        <span class="bpill text-label-sm bg-low tc-primary-container py-1 ms-2 text-nowrap">4
                            Terbaru</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @php
                            $latest = [
                                [
                                    'dot' => 'bg-secondary-c',
                                    'title' => 'Juara 1 Olimpiade Sains Tingkat Kota',
                                    'type' => 'Berita',
                                    'meta' => 'Terbit hari ini, 08:30',
                                    'views' => '1.240 dilihat',
                                ],
                                [
                                    'dot' => 'bg-primary-container',
                                    'title' => 'Jadwal Penilaian Akhir Semester',
                                    'type' => 'Pengumuman',
                                    'meta' => 'Terbit kemarin, 15:10',
                                    'views' => '3.870 dilihat',
                                ],
                                [
                                    'dot' => 'bg-on-primary-fixed-variant',
                                    'title' => 'Album Kunjungan Industri Kelas XI',
                                    'type' => 'Galeri',
                                    'meta' => 'Terbit 2 hari lalu',
                                    'views' => '642 dilihat',
                                ],
                                [
                                    'dot' => 'bg-gold',
                                    'title' => 'Formulir Pendaftaran Ekstrakurikuler',
                                    'type' => 'Dokumen',
                                    'meta' => 'Terbit 4 hari lalu',
                                    'views' => '415 diunduh',
                                ],
                            ];
                        @endphp

                        @foreach ($latest as $item)
                            <div class="pos-item d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="text-title-sm tc-on-surface d-flex align-items-center gap-2">
                                        <span class="dot {{ $item['dot'] }}"></span>
                                        {{ $item['title'] }}
                                    </div>
                                    <div class="text-body-sm tc-variant mt-1">{{ $item['type'] }} &bull;
                                        {{ $item['meta'] }}</div>
                                </div>
                                <span class="text-label-sm tc-secondary text-nowrap">{{ $item['views'] }}</span>
                            </div>
                        @endforeach
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
                            style="color:var(--c-on-error-container)">2 Pending</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- Pending 1 -->
                        <div class="approval-item d-flex flex-column gap-3">
                            <div>
                                <span class="btag text-label-sm mb-2">Berita</span>
                                <h3 class="text-title-sm tc-on-surface mb-0">Penerimaan Peserta Didik Baru 2025</h3>
                                <p class="text-body-sm tc-variant mt-1 mb-0">Alur pendaftaran dan jadwal seleksi</p>
                            </div>
                            <div
                                class="d-flex align-items-center flex-wrap gap-2 text-body-sm tc-variant border-soft-t pt-2">
                                <span class="material-symbols-outlined icon-16 tc-outline">person</span>
                                <span>Penulis: Humas Sekolah</span>
                                <span class="tc-outline">&bull;</span>
                                <span class="text-label-sm tc-variant">Hari ini, 09:15</span>
                            </div>
                            <div class="row g-2 pt-1">
                                <div class="col-6">
                                    <button type="button" class="btn-sm-edu btn-detail pressable">
                                        <span
                                            class="material-symbols-outlined icon-16">visibility</span><span>Pratinjau</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn-sm-edu btn-approve pressable">
                                        <span
                                            class="material-symbols-outlined icon-16">publish</span><span>Terbitkan</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pending 2 -->
                        <div class="approval-item d-flex flex-column gap-3">
                            <div>
                                <span class="btag text-label-sm mb-2">Pengumuman</span>
                                <h3 class="text-title-sm tc-on-surface mb-0">Libur Semester Ganjil</h3>
                                <p class="text-body-sm tc-variant mt-1 mb-0">Jadwal libur dan masuk kembali</p>
                            </div>
                            <div
                                class="d-flex align-items-center flex-wrap gap-2 text-body-sm tc-variant border-soft-t pt-2">
                                <span class="material-symbols-outlined icon-16 tc-outline">person</span>
                                <span>Penulis: Bpk. Suryadi, S.Pd</span>
                                <span class="tc-outline">&bull;</span>
                                <span class="text-label-sm tc-variant">Kemarin, 14:20</span>
                            </div>
                            <div class="row g-2 pt-1">
                                <div class="col-6">
                                    <button type="button" class="btn-sm-edu btn-detail pressable">
                                        <span
                                            class="material-symbols-outlined icon-16">visibility</span><span>Pratinjau</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn-sm-edu btn-approve pressable">
                                        <span
                                            class="material-symbols-outlined icon-16">publish</span><span>Terbitkan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
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
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">dashboard</span>
            <span class="text-label-sm mt-1">Beranda</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">school</span>
            <span class="text-label-sm mt-1">Akademik</span>
        </a>
        <a href="#" class="bottom-nav-item active" aria-current="page">
            <span class="material-symbols-outlined icon-fill" style="font-size:24px;">web</span>
            <span class="text-label-sm fw-bold mt-1">CMS</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">chat</span>
            <span class="text-label-sm mt-1">Pesan</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">person</span>
            <span class="text-label-sm mt-1">Profil</span>
        </a>
    </nav>
@endsection
