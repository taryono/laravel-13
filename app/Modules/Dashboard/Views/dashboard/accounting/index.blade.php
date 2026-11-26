@extends('layouts.app')
@section('title', 'Alumni & Tracer Study')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/accounting.css') }}">
@endpush
@section('content')
    @include('Dashboard::partials.ep_subnav', [
        'title' => 'Modul Akuntansi Sekolah',
        'badge' => 'Tutup Buku Maret 2025',
        'subtitle' => 'Buku Besar, Jurnal Keuangan & Laporan Yayasan Terpadu',
        'metaIcon' => 'event_note',
        'metaLabel' => 'Tahun Fiskal',
        'metaValue' => '2024/2025 Genap',
    ])

    <!-- Header card -->
    <section class="card-soft p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <a href="{{ route('dashboard.back-to-home') }}" class="link-secondary back-to-home">
                <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
                <span>Kembali ke Beranda</span>
            </a>
            <span class="status-pill-lock">
                <span class="material-symbols-outlined" style="font-size:14px;">lock_clock</span>
                Tutup Buku Maret 2025
            </span>
        </div>
        <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
            <div>
                <h1 class="text-headline-lg-mobile text-primary fw-bold mb-0" style="letter-spacing:-.02em;">
                    Modul Akuntansi Sekolah</h1>
                <p class="text-on-surface-variant text-body-md mt-1 mb-0">
                    Buku Besar &amp; Laporan Keuangan Yayasan/Sekolah Terpadu
                </p>
            </div>
            <div class="fiscal-badge">
                <span class="material-symbols-outlined text-secondary" style="font-size:18px;">event_note</span>
                <span>Tahun Fiskal: <strong class="text-on-surface">2024/2025 Genap</strong></span>
            </div>
        </div>
    </section>

    <!-- KPI Bento -->
    <section>
        <div class="row g-3 mt-3">

            <!-- Saldo Kas & Bank -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="kpi-card border-primary">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-on-surface-variant text-label-md">Saldo Kas &amp; Bank</span>
                        <div class="kpi-icon-box kpi-icon-primary">
                            <span class="material-symbols-outlined" style="font-size:20px;">account_balance</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-headline-md text-primary" style="letter-spacing:-.02em;">Rp 842.150.000
                        </div>
                        <div class="badge-trend-success mt-2">
                            <span class="material-symbols-outlined" style="font-size:14px;">trending_up</span>
                            <span>Surplus +8.4% MoM</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendapatan -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="kpi-card border-secondary">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-on-surface-variant text-label-md">Total Pendapatan Bulan Ini</span>
                        <div class="kpi-icon-box kpi-icon-secondary">
                            <span class="material-symbols-outlined" style="font-size:20px;">arrow_downward_alt</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-headline-md text-on-surface" style="letter-spacing:-.02em;">Rp
                            512.000.000</div>
                        <p class="text-on-surface-variant text-body-sm mt-2 mb-0">SPP, Uang Gedung, &amp; Hibah
                            BOS</p>
                    </div>
                </div>
            </div>

            <!-- Beban -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="kpi-card border-error">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-on-surface-variant text-label-md">Total Beban / Pengeluaran</span>
                        <div class="kpi-icon-box kpi-icon-error">
                            <span class="material-symbols-outlined" style="font-size:20px;">arrow_upward_alt</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-headline-md text-on-surface" style="letter-spacing:-.02em;">Rp
                            268.450.000</div>
                        <p class="text-on-surface-variant text-body-sm mt-2 mb-0">Operasional, Gaji, &amp;
                            Sarpras</p>
                    </div>
                </div>
            </div>

            <!-- Laba Bersih -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="kpi-card border-success">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-on-surface-variant text-label-md">Laba/Surplus Bersih</span>
                        <div class="kpi-icon-box kpi-icon-high">
                            <span class="material-symbols-outlined" style="font-size:20px;">savings</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-headline-md text-primary" style="letter-spacing:-.02em;">Rp 243.550.000
                        </div>
                        <div class="badge-trend-success mt-2">
                            <span class="material-symbols-outlined" style="font-size:14px;">check_circle</span>
                            <span>Rasio Likuiditas Sehat</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Aksi Cepat -->
    <section class="d-flex flex-column gap-2">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="text-title-sm text-primary mb-0" style="letter-spacing:-.02em;">Aksi Cepat Akuntan</h2>
            <span class="text-label-sm text-on-surface-variant">4 Pintasan Tersedia</span>
        </div>
        <div class="row g-2">

            <!-- Jurnal Umum -->
            <div class="col-6 col-md-3">
                <button type="button" class="action-btn primary">
                    <div class="action-icon action-icon-primary-ghost">
                        <span class="material-symbols-outlined" style="font-size:22px;">edit_note</span>
                    </div>
                    <div>
                        <div class="text-label-md" style="line-height:1.2;">Catat Jurnal Umum</div>
                        <div class="text-body-sm" style="font-size:11px;opacity:.8;">Entri Debit/Kredit Baru
                        </div>
                    </div>
                </button>
            </div>

            <!-- Rekonsiliasi -->
            <div class="col-6 col-md-3">
                <button type="button" class="action-btn">
                    <div class="action-icon action-icon-secondary">
                        <span class="material-symbols-outlined" style="font-size:22px;">sync_alt</span>
                    </div>
                    <div>
                        <div class="text-label-md text-primary" style="line-height:1.2;">Rekonsiliasi Bank
                        </div>
                        <div class="text-body-sm text-on-surface-variant" style="font-size:11px;">Sinkron Kas
                            Giro</div>
                    </div>
                </button>
            </div>

            <!-- Neraca -->
            <div class="col-6 col-md-3">
                <button type="button" class="action-btn">
                    <div class="action-icon action-icon-high">
                        <span class="material-symbols-outlined" style="font-size:22px;">balance</span>
                    </div>
                    <div>
                        <div class="text-label-md text-primary" style="line-height:1.2;">Buat Neraca Saldo
                        </div>
                        <div class="text-body-sm text-on-surface-variant" style="font-size:11px;">Kompilasi
                            Buku Besar</div>
                    </div>
                </button>
            </div>

            <!-- Laba Rugi -->
            <div class="col-6 col-md-3">
                <button type="button" class="action-btn accent-border">
                    <div class="action-icon action-icon-warning">
                        <span class="material-symbols-outlined" style="font-size:22px;">picture_as_pdf</span>
                    </div>
                    <div>
                        <div class="text-label-md text-primary" style="line-height:1.2;">Laporan Laba Rugi
                        </div>
                        <div class="text-body-sm text-secondary" style="font-size:11px;">Unduh File PDF Resmi
                        </div>
                    </div>
                </button>
            </div>

        </div>
    </section>

    <!-- Content Grid -->
    <div class="row mt-3 g-4">

        <!-- LEFT: Journal Entries -->
        <section class="col-12 col-lg-8">
            <div class="card-soft p-4">

                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 pb-3 border-bottom "
                    style="border-color:var(--surface-container)!important;">
                    <div>
                        <h2 class="text-headline-md text-primary mb-0">Entri Jurnal Keuangan Terbaru</h2>
                        <p class="text-body-sm text-on-surface-variant mb-0">Pencatatan real-time arus kas SMA
                            &amp; Yayasan</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-label-sm text-on-surface-variant">Urutkan:</span>
                        <select class="form-select-soft">
                            <option>Terbaru</option>
                            <option>Nominal Terbesar</option>
                            <option>Belum Diverifikasi</option>
                        </select>
                    </div>
                </div>

                <!-- Filter tabs -->
                <div class="journal-filters">
                    <button type="button" class="active">Semua Transaksi</button>
                    <button type="button">Penerimaan Operasional</button>
                    <button type="button">Belanja Modal</button>
                    <button type="button">Beban Gaji &amp; Operasional</button>
                </div>

                <!-- Journal list -->
                <div class="d-flex flex-column gap-3 pt-2">

                    <!-- Card 1: SPP -->
                    <div class="journal-item">
                        <div class="flex-grow-1 d-flex flex-column gap-2">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="coa-badge">COA 1101-02</span>
                                <span class="text-body-sm text-on-surface-variant">25 Maret 2025 • 09:42
                                    WIB</span>
                                <span class="verified-badge">
                                    <span class="material-symbols-outlined" style="font-size:12px;">verified</span>
                                    Terverifikasi Auditor
                                </span>
                            </div>
                            <div class="text-title-sm text-on-surface">Penerimaan SPP Kas Masuk_Kelas X &amp;
                                XI</div>
                            <p class="text-body-sm text-on-surface-variant mb-0">Pembayaran berkala via Virtual
                                Account Bank BNI (320 Siswa)</p>
                        </div>
                        <div class="d-flex flex-md-column align-items-end justify-content-between justify-content-md-center pt-2 pt-md-0 border-top border-md-0"
                            style="border-color:var(--surface-container)!important;">
                            <span class="text-label-sm text-on-surface-variant mb-md-1">Debit (Kas
                                Masuk)</span>
                            <span class="text-title-sm amount-success">+ Rp 192.000.000</span>
                        </div>
                    </div>

                    <!-- Card 2: Listrik -->
                    <div class="journal-item">
                        <div class="flex-grow-1 d-flex flex-column gap-2">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="coa-badge">COA 5202-04</span>
                                <span class="text-body-sm text-on-surface-variant">24 Maret 2025 • 14:15
                                    WIB</span>
                                <span class="verified-badge">
                                    <span class="material-symbols-outlined" style="font-size:12px;">verified</span>
                                    Terverifikasi Auditor
                                </span>
                            </div>
                            <div class="text-title-sm text-on-surface">Pembayaran Listrik &amp; Internet Lab
                                Komputer</div>
                            <p class="text-body-sm text-on-surface-variant mb-0">Beban utilitas bulanan PLN
                                Pascabayar dan Dedicated Fiber Optic 200 Mbps</p>
                        </div>
                        <div class="d-flex flex-md-column align-items-end justify-content-between justify-content-md-center pt-2 pt-md-0 border-top border-md-0"
                            style="border-color:var(--surface-container)!important;">
                            <span class="text-label-sm text-on-surface-variant mb-md-1">Kredit (Beban
                                Kas)</span>
                            <span class="text-title-sm amount-error">- Rp 14.850.000</span>
                        </div>
                    </div>

                    <!-- Card 3: ATK -->
                    <div class="journal-item">
                        <div class="flex-grow-1 d-flex flex-column gap-2">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="coa-badge">COA 5104-01</span>
                                <span class="text-body-sm text-on-surface-variant">22 Maret 2025 • 11:30
                                    WIB</span>
                                <span class="verified-badge">
                                    <span class="material-symbols-outlined" style="font-size:12px;">verified</span>
                                    Terverifikasi Auditor
                                </span>
                            </div>
                            <div class="text-title-sm text-on-surface">Belanja ATK Sekolah &amp; Kertas Ujian
                                Semester</div>
                            <p class="text-body-sm text-on-surface-variant mb-0">Pengadaan perlengkapan
                                administrasi kantor guru dan cetak bank soal PAS</p>
                        </div>
                        <div class="d-flex flex-md-column align-items-end justify-content-between justify-content-md-center pt-2 pt-md-0 border-top border-md-0"
                            style="border-color:var(--surface-container)!important;">
                            <span class="text-label-sm text-on-surface-variant mb-md-1">Kredit (Beban
                                Kas)</span>
                            <span class="text-title-sm amount-error">- Rp 8.600.000</span>
                        </div>
                    </div>

                    <!-- Card 4: BOS -->
                    <div class="journal-item">
                        <div class="flex-grow-1 d-flex flex-column gap-2">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="coa-badge">COA 1205-01</span>
                                <span class="text-body-sm text-on-surface-variant">20 Maret 2025 • 16:00
                                    WIB</span>
                                <span class="verified-badge">
                                    <span class="material-symbols-outlined" style="font-size:12px;">verified</span>
                                    Terverifikasi Auditor
                                </span>
                            </div>
                            <div class="text-title-sm text-on-surface">Penerimaan Hibah Dana BOS Tahap I 2025
                            </div>
                            <p class="text-body-sm text-on-surface-variant mb-0">Penyaluran dari Kas Daerah
                                Kemendikbudristek untuk sarana pembelajaran</p>
                        </div>
                        <div class="d-flex flex-md-column align-items-end justify-content-between justify-content-md-center pt-2 pt-md-0 border-top border-md-0"
                            style="border-color:var(--surface-container)!important;">
                            <span class="text-label-sm text-on-surface-variant mb-md-1">Debit (Kas
                                Masuk)</span>
                            <span class="text-title-sm amount-success">+ Rp 320.000.000</span>
                        </div>
                    </div>

                </div>

                <!-- Pagination -->
                <div class="mt-4 pt-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-2"
                    style="border-color:var(--surface-container)!important;">
                    <span class="text-body-sm text-on-surface-variant">Menampilkan 4 dari 142 entri
                        jurnal</span>
                    <button type="button"
                        class="btn btn-link p-0 text-decoration-none text-primary text-label-md d-flex align-items-center gap-1">
                        <span>Buka Buku Besar Lengkap</span>
                        <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
                    </button>
                </div>

            </div>
        </section>

        <!-- RIGHT: Status Neraca & Kepatuhan -->
        <aside class="col-12 col-lg-4 d-flex flex-column gap-3">

            <!-- Status Neraca -->
            <div class="card-soft p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="kpi-icon-box" style="background:rgba(134,242,228,.5);color:var(--secondary);">
                        <span class="material-symbols-outlined" style="font-size:20px;">check_box</span>
                    </div>
                    <div>
                        <h3 class="text-title-sm text-primary mb-0">Status Neraca Saldo</h3>
                        <p class="text-body-sm text-on-surface-variant mb-0">Keseimbangan Aktiva &amp; Pasiva
                        </p>
                    </div>
                </div>

                <div class="balance-box mb-3">
                    <div class="d-flex justify-content-between text-body-sm">
                        <span class="text-on-surface-variant">Total Debit:</span>
                        <span class="fw-bold text-primary">Rp 1.154.150.000</span>
                    </div>
                    <div class="d-flex justify-content-between text-body-sm">
                        <span class="text-on-surface-variant">Total Kredit:</span>
                        <span class="fw-bold text-primary">Rp 1.154.150.000</span>
                    </div>
                    <hr />
                    <div class="d-flex justify-content-between align-items-center text-label-md">
                        <span class="text-secondary fw-bold">Status Selisih:</span>
                        <span class="balanced-pill">Rp 0 (Balanced / Seimbang)</span>
                    </div>
                </div>

                <p class="text-body-sm text-on-surface-variant mb-0" style="line-height:1.6;">
                    Semua transaksi akun per 25 Maret 2025 telah terpetakan secara otomatis dan siap untuk
                    penutupan buku triwulanan.
                </p>
            </div>

            <!-- Kepatuhan -->
            <div class="card-soft p-4">
                <h3 class="text-title-sm text-primary mb-3 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">gavel</span>
                    Kepatuhan &amp; Tata Kelola
                </h3>

                <ul class="list-unstyled d-flex flex-column gap-3 mb-0">

                    <li class="compliance-item">
                        <span class="material-symbols-outlined compliance-icon-success"
                            style="font-size:18px;margin-top:2px;">check_circle</span>
                        <div>
                            <span class="text-label-md text-on-surface">Standar Akuntansi Pesantren/Sekolah
                                (PSAK 45)</span>
                            <p class="text-body-sm text-on-surface-variant mb-0">Penyajian laporan nirlaba
                                sesuai regulasi yayasan pendidikan.</p>
                        </div>
                    </li>

                    <li class="compliance-item">
                        <span class="material-symbols-outlined compliance-icon-success"
                            style="font-size:18px;margin-top:2px;">check_circle</span>
                        <div>
                            <span class="text-label-md text-on-surface">Pajak Penghasilan (PPh 21 &amp; PPh
                                23)</span>
                            <p class="text-body-sm text-on-surface-variant mb-0">Pemotongan pajak honorer dan
                                penyedia telah disetor via e-Billing.</p>
                        </div>
                    </li>

                    <li class="compliance-item">
                        <span class="material-symbols-outlined compliance-icon-warning"
                            style="font-size:18px;margin-top:2px;">pending</span>
                        <div>
                            <span class="text-label-md text-on-surface">Audit Internal Yayasan</span>
                            <p class="text-body-sm text-on-surface-variant mb-0">Jadwal pemeriksaan triwulan 1
                                dijadwalkan pada 28 Maret 2025.</p>
                        </div>
                    </li>

                </ul>

                <div class="mt-4 pt-3 border-top" style="border-color:var(--surface-container)!important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="auditor-avatar">AK</div>
                        <div>
                            <div class="text-label-md text-on-surface">Ahmad Kurnia, S.E., Ak.</div>
                            <div class="text-body-sm text-on-surface-variant">Kepala Bagian Keuangan SMA</div>
                        </div>
                    </div>
                </div>
            </div>

        </aside>
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
