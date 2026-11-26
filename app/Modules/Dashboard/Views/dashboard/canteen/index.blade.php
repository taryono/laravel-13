@extends('layouts.app')
@section('title', 'Kantin & Cashless')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/canteen.css') }}">
@endpush
@section('content')
    <div class="canteen">
        @include('Dashboard::partials.ep_subnav', [
            'title' => 'Modul Kantin & Smart Canteen',
            'badge' => '12 Tenant Aktif Buka',
            'subtitle' => 'Monitoring Transaksi Non-Tunai, Cashless & Gizi Siswa',
            'metaIcon' => 'contactless',
            'metaLabel' => 'Operasional',
            'metaValue' => 'Cashless Aktif',
        ])
        <!-- ===================== TOP APP BAR ===================== -->
        <header class="app-header d-flex justify-content-end align-items-center px-3">

            <div class="d-flex align-items-center gap-2">
                <span class="bpill bg-secondary-fixed tc-on-secondary-fixed d-none d-sm-inline-flex p-3">
                    <span class="bg-secondary-c rounded-circle me-1" style="width:6px;height:6px;"></span>
                    12 Tenant Aktif Buka
                </span>
                <button type="button" class="ibox-md bg-transparent tc-primary border-0 pressable" aria-label="Notifikasi">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
            </div>
        </header>

        <!-- ===================== SUBHEADER BANNER ===================== -->
        <section class="px-3 pt-3 pb-2">
            <div class="can-card p-4 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <span class="tagpill bg-secondary-fixed-30 tc-secondary mb-2">
                            <span class="material-symbols-outlined me-1" style="font-size:12px;">restaurant</span>
                            Sistem Kantin Cerdas
                        </span>
                        <h1 class="fs-22 fw-bold tc-primary mt-1 mb-1 leading-tight">Modul Kantin &amp; Smart Canteen</h1>
                        <p class="fs-12 tc-variant mb-0">Monitoring Transaksi Non-Tunai &amp; Gizi Siswa</p>
                    </div>
                    <span class="ibox-lg bg-container tc-primary">
                        <span class="material-symbols-outlined" style="font-size:24px;">contactless</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- ===================== KPI CARDS ===================== -->
        <section class="px-3 py-3 d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="fs-13 fw-bold tc-on-surface mb-0">Metrik Utama Hari Ini</h2>
                <span class="fs-11 fw-bold tc-secondary">Real-time RFID/QRIS</span>
            </div>

            <!-- 1. Omzet Harian (full width) -->
            <div class="can-card kpi-accent-primary p-4 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fs-13 fw-semibold tc-variant">Omzet Harian Kantin</span>
                    <span class="ibox bg-container tc-primary">
                        <span class="material-symbols-outlined" style="font-size:18px;">payments</span>
                    </span>
                </div>
                <div>
                    <div class="fs-22 fw-bold tc-primary">Rp 14.850.000</div>
                    <span class="bpill bg-secondary-fixed-40 tc-secondary mt-2">
                        <span class="material-symbols-outlined" style="font-size:13px;">check_circle</span>
                        1.420 Transaksi Cashless RFID/QRIS
                    </span>
                </div>
            </div>

            <!-- 2 & 3: Double grid KPI -->
            <div class="row g-3">
                <!-- 2. Saldo Tenant -->
                <div class="col-6">
                    <div class="can-card kpi-accent-secondary p-3 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fs-11 fw-bold tc-variant truncate-1">Saldo Tenant</span>
                                <span class="material-symbols-outlined tc-secondary"
                                    style="font-size:14px;">account_balance</span>
                            </div>
                            <div class="fs-13 fw-bold tc-primary">Rp 112,4 Jt</div>
                        </div>
                        <div class="mt-2 pt-2 border-soft-t d-flex align-items-center fs-11 tc-secondary">
                            <span class="material-symbols-outlined me-1" style="font-size:12px;">sync</span>
                            <span class="fs-10 leading-tight">Siap settlement harian</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Higienis & Halal -->
                <div class="col-6">
                    <div
                        class="can-card kpi-accent-secondary-container p-3 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fs-11 fw-bold tc-variant truncate-1">Higienis &amp; Halal</span>
                                <span class="material-symbols-outlined tc-secondary" style="font-size:14px;">verified</span>
                            </div>
                            <div class="fs-13 fw-bold tc-secondary">100% Valid</div>
                        </div>
                        <div class="mt-2 pt-2 border-soft-t d-flex align-items-center fs-11 tc-variant">
                            <span class="material-symbols-outlined me-1 tc-secondary"
                                style="font-size:12px;">health_and_safety</span>
                            <span class="fs-10 leading-tight">Tim UKS Sekolah</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Menu Paling Diminati -->
            <div class="can-card p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined"
                            style="font-size:18px;color:var(--c-tertiary-fixed-dim);">star</span>
                        <h3 class="fs-13 fw-bold tc-on-surface mb-0">Menu Paling Diminati</h3>
                    </div>
                    <span class="tagpill bg-low tc-secondary">Hari Ini</span>
                </div>
                <div class="d-flex flex-wrap gap-2 pt-1">
                    <span class="chip-menu">
                        <span class="material-symbols-outlined tc-secondary me-1" style="font-size:14px;">bento</span>
                        Paket Nasi Bento Sehat
                    </span>
                    <span class="chip-menu">
                        <span class="material-symbols-outlined tc-secondary me-1" style="font-size:14px;">local_bar</span>
                        Jus Buah Murni
                    </span>
                    <span class="chip-menu">
                        <span class="material-symbols-outlined tc-secondary me-1"
                            style="font-size:14px;">soup_kitchen</span>
                        Soto Ayam Madura
                    </span>
                </div>
            </div>
        </section>

        <!-- ===================== AKSI CEPAT ===================== -->
        <section class="px-3 py-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="fs-13 fw-bold tc-on-surface mb-0">Aksi Cepat Kantin</h2>
                <span class="fs-11 tc-variant">Kendali Cepat</span>
            </div>

            <div class="row g-2">

                <!-- Action 1: Rekap Settlement -->
                <div class="col-6">
                    <button type="button" class="quick-card">
                        <span class="ibox bg-primary-solid text-white">
                            <span class="material-symbols-outlined" style="font-size:18px;">receipt_long</span>
                        </span>
                        <span class="min-w-0">
                            <span class="fs-13 fw-bold tc-on-surface d-block leading-snug">Rekap Settlement</span>
                            <span class="fs-11 tc-variant d-block truncate-1">Stand Kantin</span>
                        </span>
                    </button>
                </div>

                <!-- Action 2: Top-Up Saldo -->
                <div class="col-6">
                    <button type="button" class="quick-card">
                        <span class="ibox bg-secondary-c text-white">
                            <span class="material-symbols-outlined" style="font-size:18px;">credit_card</span>
                        </span>
                        <span class="min-w-0">
                            <span class="fs-13 fw-bold tc-on-surface d-block leading-snug">Top-Up Saldo</span>
                            <span class="fs-11 tc-variant d-block truncate-1">Kartu Siswa</span>
                        </span>
                    </button>
                </div>

                <!-- Action 3: Pantau Gizi -->
                <div class="col-6">
                    <button type="button" class="quick-card">
                        <span class="ibox bg-high tc-primary">
                            <span class="material-symbols-outlined" style="font-size:18px;">monitoring</span>
                        </span>
                        <span class="min-w-0">
                            <span class="fs-13 fw-bold tc-on-surface d-block leading-snug">Pantau Gizi</span>
                            <span class="fs-11 tc-variant d-block truncate-1">Batas Kalori Siswa</span>
                        </span>
                    </button>
                </div>

                <!-- Action 4: Blokir Menu -->
                <div class="col-6">
                    <button type="button" class="quick-card">
                        <span class="ibox bg-error-container tc-error">
                            <span class="material-symbols-outlined" style="font-size:18px;">block</span>
                        </span>
                        <span class="min-w-0">
                            <span class="fs-13 fw-bold tc-on-surface d-block leading-snug">Blokir Menu</span>
                            <span class="fs-11 tc-variant d-block truncate-1">Tidak Sehat / MSG</span>
                        </span>
                    </button>
                </div>
            </div>
        </section>

        <!-- ===================== DAFTAR TENANT ===================== -->
        <section class="px-3 py-3 d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h2 class="fs-13 fw-bold tc-on-surface mb-0">Daftar Stand / Tenant Kantin</h2>
                    <p class="fs-12 tc-variant mb-0">Monitoring performa tenant terdaftar</p>
                </div>
                <button type="button" class="btn-link-edu">
                    Lihat Semua (12)
                    <span class="material-symbols-outlined" style="font-size:16px;">chevron_right</span>
                </button>
            </div>

            <div class="d-flex flex-column gap-2">

                <!-- Stand 01 -->
                <div class="tenant-item d-flex align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <div class="ibox-lg bg-container tc-primary fw-bold fs-13">01</div>
                        <div class="min-w-0">
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fs-13 fw-bold tc-on-surface mb-0 truncate-1">Dapur Nusantara Sehat</h3>
                                <span class="bg-secondary-c rounded-circle flex-shrink-0"
                                    style="width:8px;height:8px;"></span>
                            </div>
                            <p class="fs-12 tc-variant mb-0">185 Pesanan selesai</p>
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fs-13 fw-bold tc-primary">Rp 2.450.000</div>
                        <span class="tagpill bg-secondary-fixed-30 tc-secondary py-02">Omzet</span>
                    </div>
                </div>

                <!-- Stand 02 -->
                <div class="tenant-item d-flex align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <div class="ibox-lg bg-container tc-primary fw-bold fs-13">02</div>
                        <div class="min-w-0">
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fs-13 fw-bold tc-on-surface mb-0 truncate-1">Kedai Bu Ani_Jus &amp; Buah</h3>
                                <span class="bg-secondary-c rounded-circle flex-shrink-0"
                                    style="width:8px;height:8px;"></span>
                            </div>
                            <p class="fs-12 tc-variant mb-0">140 Pesanan selesai</p>
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fs-13 fw-bold tc-primary">Rp 1.620.000</div>
                        <span class="tagpill bg-secondary-fixed-30 tc-secondary py-02">Omzet</span>
                    </div>
                </div>

                <!-- Stand 03 -->
                <div class="tenant-item d-flex align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <div class="ibox-lg bg-container tc-primary fw-bold fs-13">03</div>
                        <div class="min-w-0">
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fs-13 fw-bold tc-on-surface mb-0 truncate-1">Bento Bento Ceria</h3>
                                <span class="bg-secondary-c rounded-circle flex-shrink-0"
                                    style="width:8px;height:8px;"></span>
                            </div>
                            <p class="fs-12 tc-variant mb-0">210 Pesanan selesai</p>
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fs-13 fw-bold tc-primary">Rp 2.900.000</div>
                        <span class="tagpill bg-secondary-fixed-30 tc-secondary py-02">Omzet</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== LOG TRANSAKSI ===================== -->
        <section class="px-3 py-3 d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined tc-secondary" style="font-size:18px;">tap_and_play</span>
                    <h2 class="fs-13 fw-bold tc-on-surface mb-0">Riwayat Transaksi Tap Kartu Pelajar</h2>
                </div>
                <span class="fs-11 tc-variant">Live Kasir</span>
            </div>

            <div class="can-card p-3 divide-soft">

                <!-- Log 1 -->
                <div class="d-flex align-items-center justify-content-between py-2 gap-2">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <div class="avatar bg-container tc-primary">AR</div>
                        <div class="min-w-0">
                            <div class="fs-13 fw-bold tc-on-surface truncate-1">Arya Pratama (X-IPA 1)</div>
                            <div class="fs-12 tc-variant d-flex align-items-center gap-1 flex-wrap">
                                <span>Stand 03 • Nasi Bento Teriyaki</span>
                                <span>•</span>
                                <span class="tc-secondary fs-11">10:14 WIB</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fs-13 fw-bold tc-primary">- Rp 18.000</div>
                        <span class="fs-10 fw-bold tc-secondary d-inline-flex align-items-center">
                            <span class="material-symbols-outlined me-1" style="font-size:12px;">nfc</span>
                            RFID Berhasil
                        </span>
                    </div>
                </div>

                <!-- Log 2 -->
                <div class="d-flex align-items-center justify-content-between py-2 gap-2">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <div class="avatar bg-container tc-primary">ND</div>
                        <div class="min-w-0">
                            <div class="fs-13 fw-bold tc-on-surface truncate-1">Nadia Dewi (XI-IPS 2)</div>
                            <div class="fs-12 tc-variant d-flex align-items-center gap-1 flex-wrap">
                                <span>Stand 02 • Jus Alpukat Murni</span>
                                <span>•</span>
                                <span class="tc-secondary fs-11">10:11 WIB</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fs-13 fw-bold tc-primary">- Rp 12.000</div>
                        <span class="fs-10 fw-bold tc-secondary d-inline-flex align-items-center">
                            <span class="material-symbols-outlined me-1" style="font-size:12px;">nfc</span>
                            RFID Berhasil
                        </span>
                    </div>
                </div>

                <!-- Log 3 -->
                <div class="d-flex align-items-center justify-content-between py-2 gap-2">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <div class="avatar bg-container tc-primary">RK</div>
                        <div class="min-w-0">
                            <div class="fs-13 fw-bold tc-on-surface truncate-1">Rizky Kurniawan (IX-C)</div>
                            <div class="fs-12 tc-variant d-flex align-items-center gap-1 flex-wrap">
                                <span>Stand 01 • Soto Ayam + Nasi</span>
                                <span>•</span>
                                <span class="tc-secondary fs-11">10:08 WIB</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fs-13 fw-bold tc-primary">- Rp 16.000</div>
                        <span class="fs-10 fw-bold tc-secondary d-inline-flex align-items-center">
                            <span class="material-symbols-outlined me-1" style="font-size:12px;">qr_code_2</span>
                            QRIS Siswa
                        </span>
                    </div>
                </div>
            </div>
        </section>
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
    </div>
@endsection
