@extends('layouts.app')
@section('title', 'Keuangan & Kas')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/finance.css') }}">
@endpush
@section('content')
    @include('Dashboard::partials.ep_subnav', [
        'title' => 'Modul Keuangan & Kas',
        'badge' => 'Realisasi SPP 92%',
        'subtitle' => 'Tata Usaha, Penerimaan SPP & Ringkasan Arus Kas Sekolah',
        'metaIcon' => 'calendar_month',
        'metaLabel' => 'Periode',
        'metaValue' => 'Maret 2025',
    ])
    <!-- Breadcrumb -->
    <section class="px-3 pt-1 pb-1">
        <a href="{{ route('dashboard.back-to-home') }}"
            class="back-to-home d-inline-flex align-items-center gap-1 text-secondary text-label-md text-decoration-none">
            <span class="material-symbols-outlined" style="font-size:14px;">home</span>
            <span>Kembali ke Beranda</span>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <span class="text-on-surface-variant fw-normal">Tata Usaha &amp; Keuangan</span>
        </a>
    </section>

    <div class="d-flex flex-column gap-4">

        <!-- Period Banner -->
        <div class="card-soft p-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-3"
                    style="width:40px;height:40px;background:rgba(30,42,120,.1);color:var(--primary-container);">
                    <span class="material-symbols-outlined">calendar_month</span>
                </div>
                <div>
                    <p class="text-label-sm text-on-surface-variant mb-0">Tahun Ajaran 2024/2025</p>
                    <p class="text-title-sm text-primary fw-bold mb-0">Periode Maret 2025</p>
                </div>
            </div>
            <span class="badge-soft" style="background:rgba(137,245,231,.4);color:var(--secondary);">Aktif</span>
        </div>

        <!-- Financial Stats -->
        <div class="d-flex flex-column gap-3">

            <!-- Main Metric -->
            <div class="card-soft border-left-primary p-3 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-label-md text-on-surface-variant">Total Penerimaan SPP Bulan Ini</span>
                    <span class="badge-soft d-inline-flex align-items-center gap-1"
                        style="background:rgba(137,245,231,.3);color:var(--secondary);">
                        <span class="material-symbols-outlined" style="font-size:12px;">trending_up</span> 92%
                    </span>
                </div>
                <h2 class="text-display-lg-mobile text-primary fw-bold mb-2" style="letter-spacing:-.02em;">Rp
                    482.500.000</h2>
                <div class="progress progress-thin mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 92%;" aria-valuenow="92" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between text-on-surface-variant text-body-sm">
                    <span>Target: Rp 525.000.000</span>
                    <span class="fw-medium text-secondary">Tercapai 92%</span>
                </div>
            </div>

            <!-- Secondary Metrics -->
            <div class="row g-3">
                <!-- Tunggakan -->
                <div class="col-6">
                    <div class="card-soft border-left-error p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-label-sm text-on-surface-variant">Tunggakan</span>
                                <span class="material-symbols-outlined text-error" style="font-size:18px;">warning</span>
                            </div>
                            <p class="text-headline-md text-error fw-bold lh-1 mb-0">Rp 42.000.000</p>
                        </div>
                        <div class="mt-2 pt-2 border-top d-flex align-items-center gap-1 text-on-surface-variant text-body-sm"
                            style="border-color:rgba(222,233,255,.6)!important;">
                            <span class="material-symbols-outlined text-error" style="font-size:12px;">group</span>
                            <span><strong>84</strong> Siswa Belum Lunas</span>
                        </div>
                    </div>
                </div>
                <!-- Masuk Hari Ini -->
                <div class="col-6">
                    <div class="card-soft border-left-secondary p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-label-sm text-on-surface-variant">Masuk Hari Ini</span>
                                <span class="material-symbols-outlined text-secondary"
                                    style="font-size:18px;">payments</span>
                            </div>
                            <p class="text-headline-md text-secondary fw-bold lh-1 mb-0">Rp 18.500.000</p>
                        </div>
                        <div class="mt-2 pt-2 border-top d-flex align-items-center gap-1 text-on-surface-variant text-body-sm"
                            style="border-color:rgba(222,233,255,.6)!important;">
                            <span class="material-symbols-outlined text-secondary" style="font-size:12px;">done_all</span>
                            <span><strong>37</strong> Transaksi Berhasil</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card-soft p-3">
            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                <h3 class="text-title-sm text-on-surface fw-bold mb-0">Aksi Cepat Keuangan</h3>
                <span class="text-label-sm text-on-surface-variant">Shortcut Staf TU</span>
            </div>
            <div class="row g-2">
                <div class="col-4">
                    <button type="button" class="action-tile">
                        <div class="action-icon" style="background:var(--primary-container);color:var(--on-primary);">
                            <span class="material-symbols-outlined" style="font-size:18px;">receipt_long</span>
                        </div>
                        <span class="text-label-sm fw-semibold lh-sm" style="color:var(--primary);">Buat Tagihan
                            Masal</span>
                    </button>
                </div>
                <div class="col-4">
                    <button type="button" class="action-tile">
                        <div class="action-icon" style="background:var(--secondary);color:var(--on-secondary);">
                            <span class="material-symbols-outlined" style="font-size:18px;">chat</span>
                        </div>
                        <span class="text-label-sm fw-semibold lh-sm" style="color:var(--secondary);">Kirim Notif
                            WhatsApp</span>
                    </button>
                </div>
                <div class="col-4">
                    <button type="button" class="action-tile">
                        <div class="action-icon" style="background:var(--surface-container-high);color:var(--primary);">
                            <span class="material-symbols-outlined" style="font-size:18px;">download</span>
                        </div>
                        <span class="text-label-sm fw-semibold lh-sm text-on-surface">Unduh Rekap PDF</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabs & Filter -->
        <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="text-title-sm text-on-surface fw-bold mb-0">Rincian Transaksi &amp; Tagihan</h3>
                <span class="text-label-md text-secondary fw-bold" style="cursor:pointer;">Lihat Semua</span>
            </div>
            <div class="d-flex gap-2 overflow-auto pb-1 no-scrollbar"
                style="margin-left:-1rem;margin-right:-1rem;padding-left:1rem;padding-right:1rem;">
                <button type="button" class="chip active">Semua Transaksi</button>
                <button type="button" class="chip">Tagihan SPP</button>
                <button type="button" class="chip">Uang Gedung/DSP</button>
                <button type="button" class="chip">Kegiatan &amp; Ujian</button>
            </div>
        </div>

        <!-- Search -->
        <div class="search-wrap">
            <span class="material-symbols-outlined search-icon">search</span>
            <input type="text" class="form-control search-input" placeholder="Cari nama siswa, NISN, atau kelas..." />
            <span class="material-symbols-outlined filter-icon">filter_list</span>
        </div>

        <!-- Transaction List -->
        <div class="d-flex flex-column gap-3">

            <!-- Item 1: Lunas via VA -->
            <div class="card-soft border-left-secondary p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-secondary">AF</div>
                        <div>
                            <h4 class="text-title-sm text-on-surface fw-bold lh-sm mb-0">Ahmad Fauzi</h4>
                            <p class="text-body-sm text-on-surface-variant mb-0">Kelas XII MIPA 1 • NISN 00642198
                            </p>
                        </div>
                    </div>
                    <span class="badge-soft badge-success">Lunas</span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center"
                    style="border-color:rgba(222,233,255,.6)!important;">
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-0">SPP Maret 2025</p>
                        <p class="text-label-sm text-secondary fw-medium d-flex align-items-center gap-1 mt-1 mb-0">
                            <span class="material-symbols-outlined" style="font-size:12px;">credit_card</span> VA
                            BCA (Otomatis)
                        </p>
                    </div>
                    <div class="text-end">
                        <p class="text-title-sm text-on-surface fw-bold mb-0">Rp 500.000</p>
                        <p class="text-body-sm text-on-surface-variant mb-0" style="font-size:11px;">08:14 WIB •
                            Hari Ini</p>
                    </div>
                </div>
            </div>

            <!-- Item 2: Jatuh Tempo -->
            <div class="card-soft border-left-error p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-error">CP</div>
                        <div>
                            <h4 class="text-title-sm text-on-surface fw-bold lh-sm mb-0">Clarissa Putri</h4>
                            <p class="text-body-sm text-on-surface-variant mb-0">Kelas X IPS 2 • NISN 00812974</p>
                        </div>
                    </div>
                    <span class="badge-soft badge-danger">Jatuh Tempo</span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center"
                    style="border-color:rgba(222,233,255,.6)!important;">
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-0">SPP Maret 2025</p>
                        <p class="text-label-sm text-error fw-medium d-flex align-items-center gap-1 mt-1 mb-0">
                            <span class="material-symbols-outlined" style="font-size:12px;">schedule</span>
                            Terlambat 3 Hari
                        </p>
                    </div>
                    <div class="text-end">
                        <p class="text-title-sm text-error fw-bold mb-0">Rp 500.000</p>
                        <button type="button"
                            class="btn btn-link p-0 mt-1 text-secondary fw-bold text-decoration-none text-label-sm d-inline-flex align-items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:12px;">send</span> Tagih via
                            WA
                        </button>
                    </div>
                </div>
            </div>

            <!-- Item 3: Lunas via QRIS -->
            <div class="card-soft border-left-primary p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-primary">BP</div>
                        <div>
                            <h4 class="text-title-sm text-on-surface fw-bold lh-sm mb-0">Bima Perkasa</h4>
                            <p class="text-body-sm text-on-surface-variant mb-0">Kelas XI MIPA 3 • NISN 00729481
                            </p>
                        </div>
                    </div>
                    <span class="badge-soft badge-success">Lunas</span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center"
                    style="border-color:rgba(222,233,255,.6)!important;">
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-0">Uang Praktikum Fisika</p>
                        <p class="text-label-sm text-secondary fw-medium d-flex align-items-center gap-1 mt-1 mb-0">
                            <span class="material-symbols-outlined" style="font-size:12px;">qr_code_scanner</span>
                            QRIS Dinamis
                        </p>
                    </div>
                    <div class="text-end">
                        <p class="text-title-sm text-on-surface fw-bold mb-0">Rp 250.000</p>
                        <p class="text-body-sm text-on-surface-variant mb-0" style="font-size:11px;">Kemarin •
                            14:20 WIB</p>
                    </div>
                </div>
            </div>

            <!-- Item 4: Menunggu Verifikasi -->
            <div class="card-soft border-left-tertiary p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-tertiary">SR</div>
                        <div>
                            <h4 class="text-title-sm text-on-surface fw-bold lh-sm mb-0">Siti Rahma</h4>
                            <p class="text-body-sm text-on-surface-variant mb-0">Kelas XII IPS 1 • NISN 00688319
                            </p>
                        </div>
                    </div>
                    <span class="badge-soft badge-warning">Menunggu Verifikasi</span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center"
                    style="border-color:rgba(222,233,255,.6)!important;">
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-0">SPP Maret 2025</p>
                        <p class="text-label-sm fw-medium d-flex align-items-center gap-1 mt-1 mb-0"
                            style="color:#B45309;">
                            <span class="material-symbols-outlined" style="font-size:12px;">receipt</span> Bukti
                            Transfer Bank Diunggah
                        </p>
                    </div>
                    <div class="text-end">
                        <p class="text-title-sm text-on-surface fw-bold mb-0">Rp 500.000</p>
                        <button type="button"
                            class="btn btn-link p-0 mt-1 text-primary fw-bold text-decoration-none text-label-sm">
                            Periksa Bukti
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reconciliation Notice -->
        <div class="reconciliation-box p-3 d-flex align-items-start gap-2">
            <span class="material-symbols-outlined text-secondary flex-shrink-0"
                style="margin-top:2px;">verified_user</span>
            <div>
                <p class="text-title-sm text-primary fw-bold mb-0">Rekonsiliasi Otomatis Aktif</p>
                <p class="text-body-sm text-on-surface-variant mt-1 mb-0" style="line-height:1.6;">
                    Semua transaksi VA Bank dan QRIS diverifikasi langsung secara real-time oleh gerbang pembayaran
                    resmi sekolah.
                </p>
            </div>
        </div>

    </div>
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
