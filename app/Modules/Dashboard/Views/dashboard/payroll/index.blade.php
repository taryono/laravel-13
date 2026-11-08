@extends('layouts.app')
@section('title', 'Payroll')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/payroll.css') }}">
@endpush
@section('content')
    <!-- ---------- HERO / TITLE ---------- -->
    <section class="hero-card mb-4">
        <div class="ps-2 d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <span class="bpill bg-container tc-primary border-variant">
                    <span class="material-symbols-outlined" style="font-size:14px;">calendar_month</span>
                    <span>Periode Maret 2025</span>
                </span>
                <span class="bpill bg-secondary-container-50 tc-on-secondary-fixed-variant">
                    <span class="bg-secondary-c rounded-circle" style="width:6px;height:6px;"></span>
                    Aktif
                </span>
            </div>
            <h1 class="fs-22 fw-bold tc-primary mt-1 mb-0">Modul Payroll &amp; Gaji</h1>
            <p class="fs-12 tc-variant mb-0">Penggajian Guru, Honorarium &amp; Tunjangan Bulanan</p>
            <div class="d-flex align-items-center gap-2 mt-2 pt-2 border-soft-t fs-12 tc-variant">
                <span class="material-symbols-outlined tc-secondary" style="font-size:16px;">verified_user</span>
                <span>Buku Kas Utama Terintegrasi</span>
            </div>
        </div>
    </section>

    <!-- ---------- KPI BENTO GRID ---------- -->
    <section class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="fs-20 fw-bold tc-on-surface mb-0">Ringkasan Keuangan</h2>
            <span class="fs-11 fw-bold tc-variant bg-container px-2 py-1 rounded-2">Real-Time</span>
        </div>

        <div class="row g-3">

            <!-- KPI 1: Anggaran Gaji (full width) -->
            <div class="col-12">
                <div class="pay-card p-4 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <span class="fs-13 fw-semibold tc-variant d-block">Total Anggaran Gaji Bulan Ini</span>
                            <div class="fs-22 fw-bold tc-primary mt-1">Rp 428.600.000</div>
                            <div class="d-flex align-items-center gap-2 fs-12 tc-variant mt-2">
                                <span class="material-symbols-outlined tc-secondary" style="font-size:14px;">group</span>
                                <span>82 Pegawai &amp; Pengajar</span>
                            </div>
                        </div>
                        <span class="ibox-md bg-container tc-primary">
                            <span class="material-symbols-outlined">account_balance_wallet</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- KPI 2: Status Penggajian (full width di mobile, col-6 di sm+) -->
            <div class="col-12 col-sm-6">
                <div class="pay-card p-4 d-flex flex-column justify-content-between h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="fs-13 fw-semibold tc-variant">Status Penggajian</span>
                        <span class="ibox bg-secondary-container-40 tc-secondary">
                            <span class="material-symbols-outlined" style="font-size:18px;">task_alt</span>
                        </span>
                    </div>
                    <div class="mt-3">
                        <div class="fs-13 fw-bold tc-on-surface">76 Diproses</div>
                        <div class="fs-12 tc-variant">6 Verifikasi Final</div>
                        <div class="progress-edu mt-2">
                            <div class="bg-secondary-c" style="width:92%;"></div>
                        </div>
                        <div class="text-end fs-11 fw-bold tc-secondary mt-1">92% Tuntas</div>
                    </div>
                </div>
            </div>

            <!-- KPI 3: Tunjangan & Sertifikasi -->
            <div class="col-6 col-sm-3">
                <div class="pay-card p-4 d-flex flex-column justify-content-between h-100">
                    <div class="d-flex justify-content-between align-items-start gap-1">
                        <span class="fs-13 fw-semibold tc-variant" style="line-height:1.2;">Tunjangan &amp;
                            Sertifikasi</span>
                        <span class="ibox bg-container tc-primary">
                            <span class="material-symbols-outlined" style="font-size:18px;">workspace_premium</span>
                        </span>
                    </div>
                    <div class="mt-2">
                        <div class="fs-13 fw-bold tc-primary">Rp 124.5 Jt</div>
                        <span
                            class="d-inline-block mt-1 px-2 py-1 rounded-2 fs-11 fw-bold bg-container tc-variant">Terdistribusi</span>
                    </div>
                </div>
            </div>

            <!-- KPI 4: Potongan Otomatis -->
            <div class="col-6 col-sm-3">
                <div class="pay-card p-4 d-flex flex-column justify-content-between h-100">
                    <div class="d-flex justify-content-between align-items-start gap-1">
                        <span class="fs-13 fw-semibold tc-variant" style="line-height:1.2;">Potongan (BPJS &amp;
                            PPh)</span>
                        <span class="ibox bg-high tc-on-surface">
                            <span class="material-symbols-outlined" style="font-size:18px;">receipt_long</span>
                        </span>
                    </div>
                    <div class="mt-2">
                        <div class="fs-13 fw-bold tc-on-surface">Rp 38.2 Jt</div>
                        <span class="d-inline-block mt-1 px-2 py-1 rounded-2 fs-11 fw-bold bg-low tc-variant">Otomatis
                            Terhitung</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ---------- QUICK ACTIONS ---------- -->
    <section class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="fs-20 fw-bold tc-on-surface mb-0">Aksi Cepat</h2>
            <span class="fs-12 tc-variant">Operasional Batch</span>
        </div>

        <div class="row g-2 g-md-3">
            <!-- Action 1 -->
            <div class="col-6">
                <button type="button" class="quick-card">
                    <span class="ibox-md bg-primary-c text-white mb-2">
                        <span class="material-symbols-outlined">calculate</span>
                    </span>
                    <span class="fs-13 fw-bold tc-on-surface">Hitung Payroll</span>
                    <span class="fs-12 tc-variant mt-1">Otomatis Kalkulasi</span>
                </button>
            </div>
            <!-- Action 2 -->
            <div class="col-6">
                <button type="button" class="quick-card">
                    <span class="ibox-md bg-secondary-c text-white mb-2">
                        <span class="material-symbols-outlined">payments</span>
                    </span>
                    <span class="fs-13 fw-bold tc-on-surface">Transfer Masal</span>
                    <span class="fs-12 tc-variant mt-1">Payroll Batch Bank</span>
                </button>
            </div>
            <!-- Action 3 -->
            <div class="col-6">
                <button type="button" class="quick-card">
                    <span class="ibox-md bg-container tc-primary mb-2">
                        <span class="material-symbols-outlined">send</span>
                    </span>
                    <span class="fs-13 fw-bold tc-on-surface">Kirim Slip Gaji</span>
                    <span class="fs-12 tc-variant mt-1">WhatsApp / Email</span>
                </button>
            </div>
            <!-- Action 4 -->
            <div class="col-6">
                <button type="button" class="quick-card">
                    <span class="ibox-md bg-container tc-primary mb-2">
                        <span class="material-symbols-outlined">description</span>
                    </span>
                    <span class="fs-13 fw-bold tc-on-surface">Laporan SPT</span>
                    <span class="fs-12 tc-variant mt-1">Rekap Pajak &amp; PPh 21</span>
                </button>
            </div>
        </div>
    </section>

    <!-- ---------- RINCIAN GAJI ---------- -->
    <section class="mb-5 pt-1">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="fs-20 fw-bold tc-on-surface mb-0">Rincian Gaji Terkini</h2>
            <a href="#semua-pegawai"
                class="fs-13 fw-semibold tc-primary text-decoration-none d-flex align-items-center gap-1">
                <span>Lihat Semua (82)</span>
                <span class="material-symbols-outlined" style="font-size:16px;">chevron_right</span>
            </a>
        </div>

        <div class="d-flex flex-column gap-3">

            <!-- Pegawai 1 -->
            <div class="emp-item">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar bg-primary-soft tc-primary">BS</div>
                        <div>
                            <h3 class="fs-14 fw-bold tc-on-surface mb-0">Drs. H. Bambang Sudrajat, M.Pd.</h3>
                            <span class="fs-12 tc-variant">Guru Senior</span>
                        </div>
                    </div>
                    <span class="bpill bg-secondary-container-50 tc-on-secondary-fixed-variant flex-shrink-0">Siap
                        Transfer</span>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-2 mt-3 border-soft-t">
                    <div>
                        <span class="fs-12 tc-variant d-block">Gaji Bersih</span>
                        <span class="fs-20 fw-bold tc-primary">Rp 8.450.000</span>
                    </div>
                    <button type="button" class="btn-edu btn-edu-soft">
                        <span class="material-symbols-outlined" style="font-size:14px;">visibility</span>
                        Detail
                    </button>
                </div>
            </div>

            <!-- Pegawai 2 -->
            <div class="emp-item">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar bg-secondary-soft tc-secondary">SR</div>
                        <div>
                            <h3 class="fs-14 fw-bold tc-on-surface mb-0">Siti Rahmah, S.Si., M.Biotech.</h3>
                            <span class="fs-12 tc-variant">Guru Biologi</span>
                        </div>
                    </div>
                    <span class="bpill bg-secondary-container-50 tc-on-secondary-fixed-variant flex-shrink-0">Siap
                        Transfer</span>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-2 mt-3 border-soft-t">
                    <div>
                        <span class="fs-12 tc-variant d-block">Gaji Bersih</span>
                        <span class="fs-20 fw-bold tc-primary">Rp 7.820.000</span>
                    </div>
                    <button type="button" class="btn-edu btn-edu-soft">
                        <span class="material-symbols-outlined" style="font-size:14px;">visibility</span>
                        Detail
                    </button>
                </div>
            </div>

            <!-- Pegawai 3 -->
            <div class="emp-item">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar bg-high tc-on-surface">BS</div>
                        <div>
                            <h3 class="fs-14 fw-bold tc-on-surface mb-0">Budi Santoso, S.AP.</h3>
                            <span class="fs-12 tc-variant">Kepala Tata Usaha</span>
                        </div>
                    </div>
                    <span class="bpill bg-tertiary-fixed tc-on-tertiary-fixed-variant flex-shrink-0">Diverifikasi</span>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-2 mt-3 border-soft-t">
                    <div>
                        <span class="fs-12 tc-variant d-block">Gaji Bersih</span>
                        <span class="fs-20 fw-bold tc-primary">Rp 6.200.000</span>
                    </div>
                    <button type="button" class="btn-edu btn-edu-soft">
                        <span class="material-symbols-outlined" style="font-size:14px;">visibility</span>
                        Detail
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
