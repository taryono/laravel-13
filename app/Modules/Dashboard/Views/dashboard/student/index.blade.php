@extends('layouts.app')
@section('title', 'Manajemen Siswa')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/student.css') }}">
@endpush
@section('content')
    <div class="students">
        @include('Dashboard::partials.ep_subnav', [
            'title' => 'Modul Manajemen Siswa',
            'badge' => '1.180 Siswa Aktif',
            'subtitle' => 'Buku Induk, Konseling BK & Poin Kedisiplinan Siswa',
            'metaIcon' => 'school',
            'metaLabel' => 'Periode',
            'metaValue' => 'T.A. 2024/2025 Genap',
        ])
        <!-- ---------- HERO MODULE HEADER ---------- -->
        <section class="hero-card">
            <div class="position-absolute bg-high rounded-circle"
                style="width:112px;height:112px;right:-24px;bottom:-24px;opacity:.4;filter:blur(30px);pointer-events:none;">
            </div>

            <div class="position-relative d-flex flex-column gap-3">
                <div>
                    <span class="tagpill bg-container tc-primary text-uppercase" style="letter-spacing:.05em;">
                        <span class="material-symbols-outlined me-1" style="font-size:13px;">school</span>
                        Modul Utama
                    </span>
                    <h1 class="fs-22 fw-bold tc-primary mt-2 mb-1 leading-tight">Modul Manajemen Siswa</h1>
                    <p class="fs-12 tc-variant mb-0 leading-snug">
                        Buku Induk, Konseling BK &amp; Poin Kedisiplinan Siswa
                    </p>
                </div>

                <div class="d-flex align-items-center justify-content-between border-soft-t pt-2 flex-wrap gap-2">
                    <span class="bpill bg-secondary-container tc-on-secondary-fixed-variant">
                        <span class="material-symbols-outlined" style="font-size:15px;">verified_user</span>
                        <span>1.180 Siswa Aktif</span>
                    </span>
                    <span class="fs-12 tc-variant">T.A. 2024/2025 Genap</span>
                </div>
            </div>
        </section>

        <!-- ---------- KPI CARDS (BENTO) ---------- -->
        <section class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between px-1">
                <h2 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
                    <span class="material-symbols-outlined tc-secondary">analytics</span>
                    Ringkasan Metrik
                </h2>
                <span class="fs-11 fw-bold tc-outline">Real-time Data</span>
            </div>

            <div class="row g-2">

                <!-- KPI 1: Siswa Aktif -->
                <div class="col-6">
                    <div class="stu-card kpi-accent-primary p-3 d-flex flex-column justify-content-between h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fs-13 fw-semibold tc-variant">Total Siswa</span>
                            <span class="ibox bg-low tc-primary">
                                <span class="material-symbols-outlined" style="font-size:18px;">groups</span>
                            </span>
                        </div>
                        <div class="mt-2">
                            <div class="fs-20 fw-bold tc-primary">1.180</div>
                            <p class="fs-12 tc-variant mt-1 mb-0 leading-tight">Tingkat X, XI, XII</p>
                        </div>
                    </div>
                </div>

                <!-- KPI 2: Prestasi -->
                <div class="col-6">
                    <div class="stu-card kpi-accent-secondary p-3 d-flex flex-column justify-content-between h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fs-13 fw-semibold tc-variant">Prestasi Siswa</span>
                            <span class="ibox bg-secondary-container tc-on-secondary-fixed-variant">
                                <span class="material-symbols-outlined" style="font-size:18px;">emoji_events</span>
                            </span>
                        </div>
                        <div class="mt-2">
                            <div class="fs-20 fw-bold tc-secondary">84 Prestasi</div>
                            <p class="fs-12 tc-variant mt-1 mb-0 leading-tight">Nasional &amp; Daerah</p>
                        </div>
                    </div>
                </div>

                <!-- KPI 3: Poin Disiplin -->
                <div class="col-6">
                    <div class="stu-card kpi-accent-error p-3 d-flex flex-column justify-content-between h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fs-13 fw-semibold tc-variant">Poin Disiplin</span>
                            <span class="ibox bg-error-container tc-error">
                                <span class="material-symbols-outlined" style="font-size:18px;">gavel</span>
                            </span>
                        </div>
                        <div class="mt-2">
                            <div class="fs-20 fw-bold tc-error">18 Kasus</div>
                            <p class="fs-12 tc-variant mt-1 mb-0 leading-tight">Ditangani Guru BK</p>
                        </div>
                    </div>
                </div>

                <!-- KPI 4: Konseling BK -->
                <div class="col-6">
                    <div class="stu-card kpi-accent-gold p-3 d-flex flex-column justify-content-between h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fs-13 fw-semibold tc-variant">Layanan BK</span>
                            <span class="ibox bg-tertiary-fixed tc-on-tertiary-fixed-variant">
                                <span class="material-symbols-outlined" style="font-size:18px;">forum</span>
                            </span>
                        </div>
                        <div class="mt-2">
                            <div class="fs-20 fw-bold tc-tertiary">12 Sesi</div>
                            <p class="fs-12 tc-variant mt-1 mb-0 leading-tight">Terjadwal Minggu Ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ---------- AKSI CEPAT ---------- -->
        <section class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between px-1">
                <h2 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
                    <span class="material-symbols-outlined tc-primary">bolt</span>
                    Aksi Cepat
                </h2>
                <span class="fs-11 fw-bold tc-primary">Pintasan Menu</span>
            </div>

            <div class="row g-2">

                <!-- Action 1: Input Prestasi -->
                <div class="col-6">
                    <button type="button" class="quick-card">
                        <span class="ibox bg-secondary-container tc-on-secondary-fixed-variant">
                            <span class="material-symbols-outlined">military_tech</span>
                        </span>
                        <span class="d-flex flex-column">
                            <span class="fs-13 fw-bold tc-on-surface">Input Prestasi</span>
                            <span class="fs-11 tc-variant mt-1 leading-tight">Tambah reward baru</span>
                        </span>
                    </button>
                </div>

                <!-- Action 2: Catat Poin -->
                <div class="col-6">
                    <button type="button" class="quick-card accent-error">
                        <span class="ibox bg-error-container tc-error">
                            <span class="material-symbols-outlined">report_problem</span>
                        </span>
                        <span class="d-flex flex-column">
                            <span class="fs-13 fw-bold tc-on-surface">Catat Poin</span>
                            <span class="fs-11 tc-variant mt-1 leading-tight">Pelanggaran siswa</span>
                        </span>
                    </button>
                </div>

                <!-- Action 3: Jadwal BK -->
                <div class="col-6">
                    <button type="button" class="quick-card accent-primary">
                        <span class="ibox bg-high tc-primary">
                            <span class="material-symbols-outlined">calendar_month</span>
                        </span>
                        <span class="d-flex flex-column">
                            <span class="fs-13 fw-bold tc-on-surface">Jadwal BK</span>
                            <span class="fs-11 tc-variant mt-1 leading-tight">Konseling mandiri</span>
                        </span>
                    </button>
                </div>

                <!-- Action 4: Cetak Surat -->
                <div class="col-6">
                    <button type="button" class="quick-card accent-primary">
                        <span class="ibox bg-low tc-primary">
                            <span class="material-symbols-outlined">print</span>
                        </span>
                        <span class="d-flex flex-column">
                            <span class="fs-13 fw-bold tc-on-surface">Cetak Surat</span>
                            <span class="fs-11 tc-variant mt-1 leading-tight">Mutasi / Keterangan</span>
                        </span>
                    </button>
                </div>
            </div>
        </section>

        <!-- ---------- PENANGANAN & PRESTASI TERKINI ---------- -->
        <section class="d-flex flex-column gap-2 mb-2">
            <div class="d-flex align-items-center justify-content-between px-1">
                <h2 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
                    <span class="material-symbols-outlined tc-secondary">history_edu</span>
                    Penanganan &amp; Prestasi Terkini
                </h2>
                <button type="button" class="btn-link-edu">Lihat Semua</button>
            </div>

            <div class="d-flex flex-column gap-3">

                <!-- Item 1: Prestasi OSN -->
                <article class="timeline-item" style="--timeline-accent: var(--c-secondary);">
                    <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar bg-secondary-container tc-secondary">MF</div>
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <h3 class="fs-14 fw-bold tc-on-surface mb-0">Muhammad Farhan</h3>
                                    <span class="tagpill bg-container tc-variant">XII MIPA 1</span>
                                </div>
                                <p class="fs-12 tc-variant mb-0">NISN: 0064928172</p>
                            </div>
                        </div>
                        <span class="bpill bg-secondary-container tc-on-secondary-fixed-variant flex-shrink-0">
                            <span class="material-symbols-outlined" style="font-size:14px;">verified</span>
                            +100 Poin
                        </span>
                    </div>

                    <div class="mt-3 pt-2 border-soft-t d-flex flex-column gap-1">
                        <div class="d-flex align-items-center gap-2 tc-secondary">
                            <span class="material-symbols-outlined" style="font-size:16px;">trophy</span>
                            <span class="fs-13 fw-semibold">Juara 1 OSN Fisika Nasional</span>
                        </div>
                        <p class="fs-12 tc-variant mb-0 d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">schedule</span>
                            Diverifikasi Kemarin • Surat Keputusan No. 421/OSN/2025
                        </p>
                    </div>
                </article>

                <!-- Item 2: Konseling BK -->
                <article class="timeline-item" style="--timeline-accent: var(--c-primary-container);">
                    <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar bg-high tc-primary">AP</div>
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <h3 class="fs-14 fw-bold tc-on-surface mb-0">Anindya Putri</h3>
                                    <span class="tagpill bg-container tc-variant">XI IPS 2</span>
                                </div>
                                <p class="fs-12 tc-variant mb-0">NISN: 0071839201</p>
                            </div>
                        </div>
                        <span class="bpill bg-high tc-primary flex-shrink-0">
                            <span class="material-symbols-outlined" style="font-size:14px;">event</span>
                            Konseling
                        </span>
                    </div>

                    <div class="mt-3 pt-2 border-soft-t d-flex flex-column gap-1">
                        <div class="d-flex align-items-center gap-2 tc-primary">
                            <span class="material-symbols-outlined" style="font-size:16px;">psychology</span>
                            <span class="fs-13 fw-semibold">Bimbingan Karir &amp; SNMPTN</span>
                        </div>
                        <p class="fs-12 tc-variant mb-0 d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">alarm</span>
                            Jadwal: Besok, 09:00 WIB • Ruang BK 2 (Ibu Rahma, M.Pd)
                        </p>
                    </div>
                </article>

                <!-- Item 3: Pelanggaran Disiplin -->
                <article class="timeline-item" style="--timeline-accent: var(--c-error);">
                    <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar bg-error-container tc-error">KS</div>
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <h3 class="fs-14 fw-bold tc-on-surface mb-0">Kevin Sanjaya</h3>
                                    <span class="tagpill bg-container tc-variant">X-C</span>
                                </div>
                                <p class="fs-12 tc-variant mb-0">NISN: 0089201948</p>
                            </div>
                        </div>
                        <span class="bpill bg-error-container tc-error flex-shrink-0">
                            <span class="material-symbols-outlined" style="font-size:14px;">warning</span>
                            -15 Poin
                        </span>
                    </div>

                    <div class="mt-3 pt-2 border-soft-t d-flex flex-column gap-1">
                        <div class="d-flex align-items-center gap-2 tc-error">
                            <span class="material-symbols-outlined" style="font-size:16px;">alarm_off</span>
                            <span class="fs-13 fw-semibold">Terlambat 3x Berturut-turut</span>
                        </div>
                        <p class="fs-12 tc-variant mb-0 d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:14px;">mail</span>
                            Tindakan: Peringatan Surat Wali Murid • Ditindak Tim Tatib
                        </p>
                    </div>
                </article>

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
    </div>
@endsection
