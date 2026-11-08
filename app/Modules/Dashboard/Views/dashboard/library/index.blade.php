@extends('layouts.app')
@section('title', 'Budgeting & RAPBS')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/library.css') }}">
@endpush
@section('content')

    <!-- ===================== TOP APP BAR ===================== -->
    <header class="app-header d-flex justify-content-between align-items-center px-3">
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="ibox-sm bg-low tc-primary border-0 pressable" aria-label="Kembali ke Beranda">
                <span class="material-symbols-outlined">arrow_back</span>
            </button>
            <div class="d-flex flex-column">
                <span class="fs-20 fw-bold tc-primary">EduPortal</span>
                <span class="fs-11 fw-semibold tc-variant">Sistem Perpustakaan</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="theme-toggle" onclick="toggleTheme()" aria-label="Ganti Tema">
                <span class="material-symbols-outlined" id="themeIcon">dark_mode</span>
            </button>
            <button type="button" class="ibox-sm bg-low tc-primary border-0 pressable position-relative"
                aria-label="Notifikasi">
                <span class="material-symbols-outlined">notifications</span>
                <span class="position-absolute bg-error-c rounded-circle"
                    style="width:8px;height:8px;top:9px;right:9px;border:2px solid var(--c-surface);"></span>
            </button>
        </div>
    </header>

    <!-- ===================== BREADCRUMB + HERO ===================== -->
    <section class="px-3 pt-3 pb-2">
        <div class="d-flex align-items-center gap-1 tc-variant fs-11 fw-semibold mb-2">
            <span class="material-symbols-outlined" style="font-size:14px;">dashboard</span>
            <span>Beranda</span>
            <span class="material-symbols-outlined" style="font-size:12px;">chevron_right</span>
            <span>Akademik</span>
            <span class="material-symbols-outlined" style="font-size:12px;">chevron_right</span>
            <span class="tc-primary fw-bold">Modul Perpustakaan</span>
        </div>

        <div class="lib-card p-4 position-relative overflow-hidden">
            <div class="position-absolute bg-secondary-container-40 rounded-circle"
                style="width:128px;height:128px;top:-48px;right:-48px;filter:blur(40px);pointer-events:none;"></div>

            <div class="d-flex align-items-start gap-2 mb-2 position-relative">
                <div class="ibox-lg bg-primary-c text-white">
                    <span class="material-symbols-outlined" style="font-size:24px;">local_library</span>
                </div>
                <div>
                    <h1 class="fs-22 fw-bold tc-primary mb-0">Modul Perpustakaan &amp; E-Library</h1>
                    <p class="fs-12 tc-variant mb-0">Sirkulasi Buku Fisik, E-Book &amp; Pojok Baca Digital</p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 mt-2 border-soft-t flex-wrap gap-2">
                <span class="bpill bg-secondary-container-40 tc-on-secondary-fixed-variant border-soft">
                    <span class="bg-secondary-c rounded-circle" style="width:8px;height:8px;"></span>
                    <span>Perpustakaan Buka</span>
                    <span class="tc-outline">• Jam 07:30 - 16:30</span>
                </span>
                <span class="tc-primary fw-bold d-flex align-items-center fs-11">
                    <span class="material-symbols-outlined me-1" style="font-size:16px;">verified</span>
                    Layanan Terpadu
                </span>
            </div>
        </div>
    </section>

    <!-- ===================== KPI CARDS ===================== -->
    <section class="px-3 py-2">
        <div class="row g-3">

            <!-- KPI 1: Koleksi Judul Buku (full width) -->
            <div class="col-12">
                <div class="lib-card kpi-accent-primary p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center gap-2">
                            <span class="ibox bg-container tc-primary">
                                <span class="material-symbols-outlined">menu_book</span>
                            </span>
                            <span class="fs-13 fw-semibold tc-variant">Koleksi Judul Buku</span>
                        </div>
                        <span class="bpill bg-container tc-primary">Total 17.780</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mt-3 flex-wrap">
                        <span class="fs-22 fw-bold tc-primary">14.280</span>
                        <span class="fs-12 fw-semibold tc-on-surface">Buku Fisik</span>
                        <span class="fs-11 tc-outline">&amp;</span>
                        <span class="fs-20 fw-bold tc-secondary">3.500</span>
                        <span class="fs-12 fw-semibold tc-on-surface">E-Book Digital</span>
                    </div>
                    <div class="progress-edu mt-3">
                        <div class="bg-primary-c" style="width:80%;"></div>
                        <div class="bg-secondary-c" style="width:20%;"></div>
                    </div>
                    <div class="d-flex justify-content-between fs-11 tc-outline mt-2">
                        <span>80.3% Fisik di Rak</span>
                        <span>19.7% E-Library Cloud</span>
                    </div>
                </div>
            </div>

            <!-- KPI 2: Sedang Dipinjam -->
            <div class="col-6">
                <div class="lib-card kpi-accent-secondary p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="ibox bg-secondary-container-50 tc-on-secondary-fixed-variant">
                                <span class="material-symbols-outlined">sync_alt</span>
                            </span>
                            <span class="bpill bg-secondary-container-50 tc-on-secondary-fixed-variant">
                                <span class="material-symbols-outlined" style="font-size:13px;">check_circle</span>
                                92%
                            </span>
                        </div>
                        <span class="fs-11 fw-semibold tc-variant d-block">Buku Sedang Dipinjam</span>
                        <p class="fs-20 fw-bold tc-primary mt-1 mb-0">
                            418 <span class="fs-12 fw-normal tc-variant">Eksemplar</span>
                        </p>
                    </div>
                    <p class="fs-11 fw-semibold tc-secondary mt-2 mb-0">92% Pengembalian Tepat Waktu</p>
                </div>
            </div>

            <!-- KPI 3: Jatuh Tempo / Terlambat -->
            <div class="col-6">
                <div class="lib-card kpi-accent-error p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="ibox bg-error-container tc-error">
                                <span class="material-symbols-outlined">alarm</span>
                            </span>
                            <span class="bpill bg-error-container" style="color:var(--c-on-error-container);">Kritis</span>
                        </div>
                        <span class="fs-11 fw-semibold tc-variant d-block">Jatuh Tempo / Denda</span>
                        <p class="fs-20 fw-bold tc-error mt-1 mb-0">
                            24 <span class="fs-12 fw-normal tc-variant">Buku</span>
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-1 fs-11 tc-variant mt-2">
                        <span class="material-symbols-outlined tc-secondary" style="font-size:14px;">mark_chat_read</span>
                        <span>Notifikasi WA Terkirim</span>
                    </div>
                </div>
            </div>

            <!-- KPI 4: Kunjungan Hari Ini -->
            <div class="col-12">
                <div
                    class="lib-card kpi-accent-primary p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <span class="ibox-lg bg-high tc-primary">
                            <span class="material-symbols-outlined">badge</span>
                        </span>
                        <div>
                            <div class="fs-11 fw-semibold tc-variant">Kunjungan Hari Ini</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="fs-20 fw-bold tc-primary">342</span>
                                <span class="fs-12 tc-variant">Siswa &amp; Guru</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="bpill bg-low tc-primary border-soft">
                            <span class="material-symbols-outlined tc-secondary"
                                style="font-size:14px;">contactless</span>
                            Tap ID Card Masuk
                        </span>
                        <div class="fs-11 tc-outline mt-1">Gate Otomatis 1 &amp; 2 Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== QUICK ACTIONS ===================== -->
    <section class="px-3 py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
                <span class="material-symbols-outlined tc-secondary">bolt</span>
                Aksi Cepat Perpustakaan
            </h2>
            <span class="fs-11 fw-semibold tc-primary">4 Fitur Utama</span>
        </div>

        <div class="row g-2 g-md-3">
            <!-- 1. Scan Barcode -->
            <div class="col-6">
                <button type="button" class="btn-edu btn-edu-primary w-100 flex-column align-items-start p-3 text-start"
                    style="height:100%;">
                    <span class="ibox bg-white bg-opacity-10 text-white mb-2">
                        <span class="material-symbols-outlined">qr_code_scanner</span>
                    </span>
                    <span class="fw-bold text-white fs-13">Scan Barcode Sirkulasi</span>
                    <span class="fs-11 fw-normal" style="color:rgba(255,255,255,.7);">Pinjam / Kembali Cepat</span>
                </button>
            </div>
            <!-- 2. Tambah Katalog -->
            <div class="col-6">
                <button type="button"
                    class="lib-card p-3 d-flex flex-column align-items-start w-100 text-start pressable"
                    style="border-radius:1rem;">
                    <span class="ibox bg-secondary-container-50 tc-on-secondary-fixed-variant mb-2">
                        <span class="material-symbols-outlined">library_add</span>
                    </span>
                    <span class="fs-13 fw-bold tc-primary">Tambah Katalog Buku</span>
                    <span class="fs-11 tc-variant">Pindai / Input ISBN Baru</span>
                </button>
            </div>
            <!-- 3. Kirim Reminder -->
            <div class="col-6">
                <button type="button"
                    class="lib-card p-3 d-flex flex-column align-items-start w-100 text-start pressable"
                    style="border-radius:1rem;">
                    <span class="ibox bg-error-container mb-2" style="color:var(--c-on-error-container);">
                        <span class="material-symbols-outlined">forward_to_inbox</span>
                    </span>
                    <span class="fs-13 fw-bold tc-primary">Kirim Reminder Buku</span>
                    <span class="fs-11 tc-variant">Broadcast WA Jatuh Tempo</span>
                </button>
            </div>
            <!-- 4. Reservasi Ruang -->
            <div class="col-6">
                <button type="button"
                    class="lib-card p-3 d-flex flex-column align-items-start w-100 text-start pressable"
                    style="border-radius:1rem;">
                    <span class="ibox bg-container tc-primary mb-2">
                        <span class="material-symbols-outlined">meeting_room</span>
                    </span>
                    <span class="fs-13 fw-bold tc-primary">Reservasi Ruang Baca</span>
                    <span class="fs-11 tc-variant">Ruang Diskusi &amp; Pojok E-Book</span>
                </button>
            </div>
        </div>
    </section>

    <!-- ===================== BUKU POPULER ===================== -->
    <section class="px-3 py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
                    <span class="material-symbols-outlined tc-primary">trending_up</span>
                    Buku Populer &amp; Tren Peminjaman
                </h2>
                <p class="fs-11 tc-variant mb-0">Judul dengan sirkulasi tertinggi bulan ini</p>
            </div>
            <button type="button" class="btn-chip bg-transparent tc-primary fw-bold">Lihat Semua</button>
        </div>

        <div class="d-flex flex-column gap-2">

            <!-- Item 1 -->
            <div class="book-row d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-container rounded-3 d-flex align-items-center justify-content-center tc-primary position-relative flex-shrink-0 border-soft"
                        style="width:48px;height:64px;">
                        <span class="material-symbols-outlined" style="font-size:28px;">science</span>
                        <span class="position-absolute bg-primary-c text-white rounded px-1 fw-bold"
                            style="top:4px;left:4px;font-size:9px;">TOP 1</span>
                    </div>
                    <div>
                        <h3 class="fs-13 fw-bold tc-primary mb-0" style="line-height:1.3;">
                            Ensiklopedia Sains &amp; Teknologi Modern
                        </h3>
                        <p class="fs-12 tc-variant mb-1 mt-1">Prof. Dr. Suryadi • Rak A-04</p>
                        <span class="bpill bg-secondary-container-40 tc-on-secondary-fixed-variant">
                            <span class="material-symbols-outlined" style="font-size:13px;">local_fire_department</span>
                            Dipinjam 45x bulan ini
                        </span>
                    </div>
                </div>
                <button type="button" class="ibox bg-low tc-primary border-0 pressable flex-shrink-0"
                    aria-label="Lihat Detail Buku">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <!-- Item 2 -->
            <div class="book-row d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-high rounded-3 d-flex align-items-center justify-content-center tc-secondary position-relative flex-shrink-0 border-soft"
                        style="width:48px;height:64px;">
                        <span class="material-symbols-outlined" style="font-size:28px;">functions</span>
                        <span class="position-absolute bg-secondary-c text-white rounded px-1 fw-bold"
                            style="top:4px;left:4px;font-size:9px;">TOP 2</span>
                    </div>
                    <div>
                        <h3 class="fs-13 fw-bold tc-primary mb-0" style="line-height:1.3;">
                            Seri Pengayaan Matematika Diskrit
                        </h3>
                        <p class="fs-12 tc-variant mb-1 mt-1">Drs. Handoko, M.Pd • Rak M-12</p>
                        <span class="bpill bg-tertiary-fixed tc-on-tertiary-fixed-variant">
                            <span class="material-symbols-outlined" style="font-size:13px;">inventory_2</span>
                            Tersisa 3 dari 10 eksemplar
                        </span>
                    </div>
                </div>
                <button type="button" class="ibox bg-low tc-primary border-0 pressable flex-shrink-0"
                    aria-label="Lihat Detail Buku">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <!-- Item 3 -->
            <div class="book-row d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-low rounded-3 d-flex align-items-center justify-content-center tc-primary position-relative flex-shrink-0 border-soft"
                        style="width:48px;height:64px;">
                        <span class="material-symbols-outlined" style="font-size:28px;">auto_stories</span>
                        <span class="position-absolute bg-primary-c text-white rounded px-1 fw-bold"
                            style="top:4px;left:4px;font-size:9px;">TOP 3</span>
                    </div>
                    <div>
                        <h3 class="fs-13 fw-bold tc-primary mb-0" style="line-height:1.3;">
                            Novel Sastra Nusantara Klasik
                        </h3>
                        <p class="fs-12 tc-variant mb-1 mt-1">Balai Pustaka • Rak S-02</p>
                        <span class="bpill bg-secondary-container-40 tc-on-secondary-fixed-variant">
                            <span class="material-symbols-outlined" style="font-size:13px;">check_circle</span>
                            Tersedia (Siap Dipinjam)
                        </span>
                    </div>
                </div>
                <button type="button" class="ibox bg-low tc-primary border-0 pressable flex-shrink-0"
                    aria-label="Lihat Detail Buku">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
    </section>

    <!-- ===================== SIRKULASI AKTIF ===================== -->
    <section class="px-3 py-3 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h2 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
                    <span class="material-symbols-outlined tc-secondary">assignment_ind</span>
                    Sirkulasi Aktif Siswa
                </h2>
                <p class="fs-11 tc-variant mb-0">Tenggat pengembalian real-time</p>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn-chip bg-container tc-primary">Semua (418)</button>
                <button type="button" class="btn-chip bg-error-container"
                    style="color:var(--c-on-error-container);">Terlambat (24)</button>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">

            <!-- Siswa 1: On-track -->
            <div class="circ-item kpi-accent-secondary">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary-c text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-13 flex-shrink-0"
                            style="width:40px;height:40px;">AN</div>
                        <div>
                            <div class="fs-13 fw-bold tc-primary">Ahmad Naufal Pratama</div>
                            <div class="fs-12 tc-variant">Kelas XII MIPA 1 • NISN 006482910</div>
                        </div>
                    </div>
                    <span class="bpill bg-secondary-container-40 tc-on-secondary-fixed-variant">
                        <span class="material-symbols-outlined" style="font-size:13px;">schedule</span>
                        Aktif
                    </span>
                </div>

                <div class="bg-low rounded-3 p-3 d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined tc-primary" style="font-size:18px;">book_2</span>
                        <div>
                            <div class="fs-12 fw-semibold tc-primary">Biologi Molekuler SMA Kelas 12</div>
                            <div class="fs-11 tc-outline">Kode: BIO-XII-0442</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fs-11 tc-outline d-block">Tenggat Kembali</span>
                        <span class="fs-13 fw-bold tc-secondary">28 Okt 2024</span>
                    </div>
                </div>

                <div
                    class="d-flex justify-content-between align-items-center fs-11 border-soft-t pt-2 mt-3 flex-wrap gap-2">
                    <span class="tc-outline">Dipinjam: 21 Okt 2024 (7 Hari)</span>
                    <button type="button"
                        class="btn-chip bg-transparent tc-primary fw-bold d-flex align-items-center gap-1">
                        Proses Kembali
                        <span class="material-symbols-outlined" style="font-size:14px;">keyboard_return</span>
                    </button>
                </div>
            </div>

            <!-- Siswa 2: Terlambat -->
            <div class="circ-item kpi-accent-error">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-error-container rounded-circle d-flex align-items-center justify-content-center fw-bold fs-13 flex-shrink-0"
                            style="width:40px;height:40px;color:var(--c-on-error-container);">SR</div>
                        <div>
                            <div class="fs-13 fw-bold tc-primary">Siti Rahmawati</div>
                            <div class="fs-12 tc-variant">Kelas XI IPS 3 • NISN 007194012</div>
                        </div>
                    </div>
                    <span class="bpill bg-error-container" style="color:var(--c-on-error-container);">
                        <span class="material-symbols-outlined" style="font-size:13px;">warning</span>
                        Terlambat 3 Hari
                    </span>
                </div>

                <div class="bg-low rounded-3 p-3 d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined tc-error" style="font-size:18px;">book_2</span>
                        <div>
                            <div class="fs-12 fw-semibold tc-primary">Sejarah Peradaban Dunia Modern</div>
                            <div class="fs-11 tc-outline">Kode: SEJ-XI-0198</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fs-11 tc-error fw-medium d-block">Jatuh Tempo: 21 Okt</span>
                        <span class="fs-13 fw-bold tc-error">Denda: Rp 3.000</span>
                    </div>
                </div>

                <div
                    class="d-flex justify-content-between align-items-center fs-11 border-soft-t pt-2 mt-3 flex-wrap gap-2">
                    <span class="tc-error fw-medium d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size:13px;">sms</span>
                        Reminder Otomatis Terkirim
                    </span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn-chip bg-high tc-primary">Kirim Ulang WA</button>
                        <button type="button" class="btn-chip bg-primary-c text-white">Bayar Denda &amp; Kembali</button>
                    </div>
                </div>
            </div>

            <!-- Siswa 3: Jatuh Tempo Hari Ini -->
            <div class="circ-item kpi-accent-gold">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-container tc-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-13 flex-shrink-0"
                            style="width:40px;height:40px;">DA</div>
                        <div>
                            <div class="fs-13 fw-bold tc-primary">Dimas Anggoro</div>
                            <div class="fs-12 tc-variant">Kelas X-B • NISN 008102394</div>
                        </div>
                    </div>
                    <span class="bpill bg-tertiary-fixed tc-on-tertiary-fixed-variant">
                        <span class="material-symbols-outlined" style="font-size:13px;">notification_important</span>
                        Jatuh Tempo Hari Ini
                    </span>
                </div>

                <div class="bg-low rounded-3 p-3 d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined tc-primary" style="font-size:18px;">book_2</span>
                        <div>
                            <div class="fs-12 fw-semibold tc-primary">Fisika Dasar: Gelombang &amp; Optik</div>
                            <div class="fs-11 tc-outline">Kode: FIS-X-0311</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fs-11 tc-outline d-block">Tenggat Waktu</span>
                        <span class="fs-13 fw-bold tc-primary">Hari Ini, 16:00</span>
                    </div>
                </div>

                <div
                    class="d-flex justify-content-between align-items-center fs-11 border-soft-t pt-2 mt-3 flex-wrap gap-2">
                    <span class="tc-outline">Dapat diperpanjang 1x lagi</span>
                    <button type="button" class="btn-chip bg-transparent tc-secondary fw-bold">
                        Perpanjang Pinjaman (+7 Hari)
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('bottom_navigation')
    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">dashboard</span>
            <span class="text-label-sm mt-1">Beranda</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">school</span>
            <span class="text-label-sm mt-1">Akademik</span>
        </a>
        <a href="#" class="bottom-nav-item active">
            <span class="material-symbols-outlined icon-fill" style="font-size:24px;">account_balance_wallet</span>
            <span class="text-label-sm fw-bold mt-1">Keuangan</span>
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
