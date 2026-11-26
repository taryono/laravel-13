@extends('layouts.app')
@section('title', 'Budgeting & RAPBS')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/budgeting.css') }}">
@endpush
@section('content')
    <div class="bgt d-flex flex-column gap-4">
        @include('Dashboard::partials.ep_subnav', [
            'title' => 'Modul Budgeting & RAPBS',
            'badge' => 'Realisasi 72.8%',
            'subtitle' => 'Rencana Anggaran Pendapatan & Belanja Sekolah TA 2024/2025',
            'metaIcon' => 'account_balance',
            'metaLabel' => 'Tahun Anggaran',
            'metaValue' => '2024/2025',
        ])
        <!-- Page Header -->
        <section
            class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center gap-3 bg-lowest p-3 p-sm-4 r-2xl border-soft shadow-sm">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button type="button" class="btn-edu btn-edu-outline pressable">
                    <span class="material-symbols-outlined tc-secondary">file_download</span>
                    <span>Unduh Laporan</span>
                </button>
                <button type="button" class="btn-edu btn-edu-solid pressable">
                    <span class="material-symbols-outlined icon-18">add_circle</span>
                    <span>Ajukan Rencana (RKA)</span>
                </button>
            </div>
        </section>

        <!-- KPI -->
        <section class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
            <!-- Total Pagu -->
            <div class="col">
                <div class="kpi-card p-3 p-sm-4" style="--kpi-accent: var(--c-primary-container)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-label-md tc-variant">Total Pagu Anggaran</span>
                        <span class="ibox bg-high tc-primary-container">
                            <span class="material-symbols-outlined icon-20">account_balance_wallet</span>
                        </span>
                    </div>
                    <div class="text-headline-md tc-primary">Rp 1.850.000.000</div>
                    <div class="d-flex align-items-center gap-1 mt-2 text-body-sm tc-variant">
                        <span class="material-symbols-outlined tc-secondary icon-16">check_circle</span>
                        <span>RAPBS Disetujui Tim Anggaran</span>
                    </div>
                </div>
            </div>

            <!-- Realisasi -->
            <div class="col">
                <div class="kpi-card p-3 p-sm-4" style="--kpi-accent: var(--c-secondary)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-label-md tc-variant">Realisasi Pengeluaran</span>
                        <span class="ibox bg-secondary-container-40 tc-secondary">
                            <span class="material-symbols-outlined icon-20">payments</span>
                        </span>
                    </div>
                    <div class="text-headline-md tc-primary">Rp 1.346.800.000</div>
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <span class="bpill text-label-sm bg-secondary-container"
                            style="color:var(--c-on-secondary-container)">72.8% Terpakai</span>
                        <span class="text-body-sm tc-variant">Q3 Berjalan</span>
                    </div>
                </div>
            </div>

            <!-- Sisa -->
            <div class="col">
                <div class="kpi-card p-3 p-sm-4" style="--kpi-accent: var(--c-tertiary-fixed-dim)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-label-md tc-variant">Sisa Alokasi Dana</span>
                        <span class="ibox bg-high tc-gold">
                            <span class="material-symbols-outlined icon-20">savings</span>
                        </span>
                    </div>
                    <div class="text-headline-md tc-primary">Rp 503.200.000</div>
                    <div class="d-flex align-items-center gap-1 mt-2 text-body-sm tc-variant">
                        <span class="material-symbols-outlined tc-secondary icon-16">schedule</span>
                        <span>Tersedia untuk Term IV</span>
                    </div>
                </div>
            </div>

            <!-- Efisiensi -->
            <div class="col">
                <div class="kpi-card p-3 p-sm-4" style="--kpi-accent: var(--c-secondary-container)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-label-md tc-variant">Efisiensi Anggaran</span>
                        <span class="ibox bg-high tc-secondary">
                            <span class="material-symbols-outlined icon-20">trending_up</span>
                        </span>
                    </div>
                    <div class="text-headline-md tc-secondary">+4.2% Hemat</div>
                    <div class="d-flex align-items-center gap-1 mt-2 text-body-sm tc-on-secondary-fixed-variant">
                        <span class="material-symbols-outlined icon-16">energy_savings_leaf</span>
                        <span>Lebih hemat dari target Q3</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Aksi Cepat -->
        <section class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between px-1">
                <h2 class="text-title-sm tc-primary d-flex align-items-center gap-2 mb-0">
                    <span class="material-symbols-outlined tc-secondary">bolt</span>
                    Aksi Cepat Anggaran
                </h2>
                <span class="text-body-sm tc-variant">Pintasan Pengelolaan Keuangan</span>
            </div>

            <div class="row row-cols-2 row-cols-md-4 g-3">
                <div class="col">
                    <button type="button" class="quick-card">
                        <div class="ibox-lg bg-primary-container text-white mb-3">
                            <span class="material-symbols-outlined">post_add</span>
                        </div>
                        <div>
                            <div class="text-title-sm tc-on-surface quick-title">Ajukan Rencana (RKA)</div>
                            <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">Form usulan pos anggaran baru</p>
                        </div>
                    </button>
                </div>
                <div class="col">
                    <button type="button" class="quick-card">
                        <div class="ibox-lg bg-high tc-primary mb-3">
                            <span class="material-symbols-outlined">edit_calendar</span>
                        </div>
                        <div>
                            <div class="text-title-sm tc-on-surface quick-title">Revisi Anggaran Pos</div>
                            <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">Penyesuaian pergeseran pagu</p>
                        </div>
                    </button>
                </div>
                <div class="col">
                    <button type="button" class="quick-card">
                        <div class="ibox-lg bg-high tc-secondary mb-3">
                            <span class="material-symbols-outlined">how_to_reg</span>
                        </div>
                        <div>
                            <div class="text-title-sm tc-on-surface quick-title">Persetujuan Komite</div>
                            <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">Verifikasi rapat pleno komite</p>
                        </div>
                    </button>
                </div>
                <div class="col">
                    <button type="button" class="quick-card">
                        <div class="ibox-lg bg-secondary-container mb-3" style="color:var(--c-on-secondary-container)">
                            <span class="material-symbols-outlined">receipt_long</span>
                        </div>
                        <div>
                            <div class="text-title-sm tc-on-surface quick-title">Rekap Realisasi BOS</div>
                            <p class="text-body-sm tc-variant mt-1 mb-0 truncate-1">Sinkronisasi SiLPA &amp; Kemenkeu</p>
                        </div>
                    </button>
                </div>
            </div>
        </section>

        <!-- Distribusi vs Approval -->
        <div class="row g-4 align-items-start">

            <!-- Distribusi Pos -->
            <section class="col-lg-7">
                <div class="bg-lowest p-3 p-sm-4 r-2xl border-soft shadow-sm d-flex flex-column gap-4">
                    <div class="d-flex align-items-center justify-content-between border-soft-b pb-3">
                        <div>
                            <h2 class="text-title-sm tc-primary d-flex align-items-center gap-2 mb-0">
                                <span class="material-symbols-outlined tc-secondary">pie_chart</span>
                                Distribusi Pos Anggaran &amp; Realisasi
                            </h2>
                            <p class="text-body-sm tc-variant mt-1 mb-0">Pantauan serapan alokasi pos utama TA 2024/2025
                            </p>
                        </div>
                        <span class="bpill text-label-sm bg-low tc-primary-container py-1 ms-2 text-nowrap">4 Pos
                            Utama</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- Item 1 -->
                        <div class="pos-item d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="text-title-sm tc-on-surface d-flex align-items-center gap-2">
                                        <span class="dot bg-secondary-c"></span>
                                        Pos Sarana &amp; Prasarana Lab
                                    </div>
                                    <div class="text-body-sm tc-variant mt-1">Pagu: Rp 500.000.000</div>
                                </div>
                                <div class="text-end">
                                    <span class="text-label-md tc-primary">Rp 420.000.000</span>
                                    <div class="text-label-sm tc-secondary">84% Terserap</div>
                                </div>
                            </div>
                            <div class="progress-edu">
                                <div class="bg-secondary-c" style="width: 84%"></div>
                            </div>
                            <div class="d-flex justify-content-between text-body-sm tc-variant pt-1">
                                <span>Sisa kuota: Rp 80 Juta</span>
                                <span class="tc-secondary d-flex align-items-center">
                                    <span class="material-symbols-outlined icon-14 me-1">lock_clock</span>Term 3 Final
                                </span>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="pos-item d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="text-title-sm tc-on-surface d-flex align-items-center gap-2">
                                        <span class="dot bg-primary-container"></span>
                                        Pos Pembelajaran &amp; Ujian Siswa
                                    </div>
                                    <div class="text-body-sm tc-variant mt-1">Pagu: Rp 400.000.000</div>
                                </div>
                                <div class="text-end">
                                    <span class="text-label-md tc-primary">Rp 310.000.000</span>
                                    <div class="text-label-sm tc-primary">77.5% Terserap</div>
                                </div>
                            </div>
                            <div class="progress-edu">
                                <div class="bg-primary-container" style="width: 77.5%"></div>
                            </div>
                            <div class="d-flex justify-content-between text-body-sm tc-variant pt-1">
                                <span>Sisa kuota: Rp 90 Juta</span>
                                <span>Ujian Asesmen Semester</span>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="pos-item d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="text-title-sm tc-on-surface d-flex align-items-center gap-2">
                                        <span class="dot bg-on-primary-fixed-variant"></span>
                                        Pos Gaji &amp; Honorarium Pendidik
                                    </div>
                                    <div class="text-body-sm tc-variant mt-1">Pagu: Rp 650.000.000</div>
                                </div>
                                <div class="text-end">
                                    <span class="text-label-md tc-primary">Rp 480.000.000</span>
                                    <div class="text-label-sm tc-on-primary-fixed-variant">73.8% Terserap</div>
                                </div>
                            </div>
                            <div class="progress-edu">
                                <div class="bg-on-primary-fixed-variant" style="width: 73.8%"></div>
                            </div>
                            <div class="d-flex justify-content-between text-body-sm tc-variant pt-1">
                                <span>Sisa kuota: Rp 170 Juta</span>
                                <span>Rutin GTT/PTT</span>
                            </div>
                        </div>

                        <!-- Item 4 -->
                        <div class="pos-item d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="text-title-sm tc-on-surface d-flex align-items-center gap-2">
                                        <span class="dot bg-gold"></span>
                                        Pos Ekstrakurikuler &amp; Lomba
                                    </div>
                                    <div class="text-body-sm tc-variant mt-1">Pagu: Rp 300.000.000</div>
                                </div>
                                <div class="text-end">
                                    <span class="text-label-md tc-primary">Rp 136.800.000</span>
                                    <div class="text-label-sm tc-on-tertiary-fixed-variant">45.6% Terserap</div>
                                </div>
                            </div>
                            <div class="progress-edu">
                                <div class="bg-gold" style="width: 45.6%"></div>
                            </div>
                            <div class="d-flex justify-content-between text-body-sm tc-variant pt-1">
                                <span>Sisa kuota: Rp 163.2 Juta</span>
                                <span class="tc-on-secondary-fixed-variant">Alokasi O2SN &amp; FLS2N</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Menunggu Approval -->
            <section class="col-lg-5">
                <div class="bg-lowest p-3 p-sm-4 r-2xl border-soft shadow-sm d-flex flex-column gap-4">
                    <div class="d-flex align-items-center justify-content-between border-soft-b pb-3">
                        <div>
                            <h2 class="text-title-sm tc-primary d-flex align-items-center gap-2 mb-0">
                                <span class="material-symbols-outlined"
                                    style="color:var(--c-tertiary-fixed-dim)">pending_actions</span>
                                Menunggu Approval
                            </h2>
                            <p class="text-body-sm tc-variant mt-1 mb-0">Pengajuan dana belanja belum diverifikasi</p>
                        </div>
                        <span class="bpill text-label-sm bg-error-container ms-2 text-nowrap"
                            style="color:var(--c-on-error-container)">2 Pending</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- Pending 1 -->
                        <div class="approval-item d-flex flex-column gap-3">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <div>
                                    <span class="btag text-label-sm mb-2">Lab Komputer &amp; TI</span>
                                    <h3 class="text-title-sm tc-on-surface mb-0">Pengadaan Software CBT Lab</h3>
                                    <p class="text-body-sm tc-variant mt-1 mb-0">Lisensi server tryout UTBK 120 client</p>
                                </div>
                                <span class="text-label-md tc-primary text-nowrap">Rp 24.500.000</span>
                            </div>
                            <div
                                class="d-flex align-items-center flex-wrap gap-2 text-body-sm tc-variant border-soft-t pt-2">
                                <span class="material-symbols-outlined icon-16 tc-outline">person</span>
                                <span>Pemohon: Hendra, M.Kom</span>
                                <span class="tc-outline">&bull;</span>
                                <span class="text-label-sm tc-variant">Hari ini, 09:15</span>
                            </div>
                            <div class="row g-2 pt-1">
                                <div class="col-6">
                                    <button type="button" class="btn-sm-edu btn-detail pressable">
                                        <span
                                            class="material-symbols-outlined icon-16">visibility</span><span>Detail</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn-sm-edu btn-approve pressable">
                                        <span class="material-symbols-outlined icon-16">check</span><span>Setujui</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pending 2 -->
                        <div class="approval-item d-flex flex-column gap-3">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <div>
                                    <span class="btag text-label-sm mb-2">Sarana Olahraga</span>
                                    <h3 class="text-title-sm tc-on-surface mb-0">Perbaikan Lapangan Futsal</h3>
                                    <p class="text-body-sm tc-variant mt-1 mb-0">Pengecatan ulang interlock &amp; jaring
                                        keliling</p>
                                </div>
                                <span class="text-label-md tc-primary text-nowrap">Rp 18.250.000</span>
                            </div>
                            <div
                                class="d-flex align-items-center flex-wrap gap-2 text-body-sm tc-variant border-soft-t pt-2">
                                <span class="material-symbols-outlined icon-16 tc-outline">person</span>
                                <span>Pemohon: Bpk. Suryadi, S.Pd</span>
                                <span class="tc-outline">&bull;</span>
                                <span class="text-label-sm tc-variant">Kemarin, 14:20</span>
                            </div>
                            <div class="row g-2 pt-1">
                                <div class="col-6">
                                    <button type="button" class="btn-sm-edu btn-detail pressable">
                                        <span
                                            class="material-symbols-outlined icon-16">visibility</span><span>Detail</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn-sm-edu btn-approve pressable">
                                        <span class="material-symbols-outlined icon-16">check</span><span>Setujui</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Komite -->
                    <div class="bg-container p-3 r-xl d-flex align-items-center gap-3"
                        style="border:1px solid rgba(0,106,97,.2)">
                        <div class="bg-secondary-10 tc-secondary d-flex align-items-center justify-content-center flex-shrink-0 rounded-circle"
                            style="width:32px;height:32px">
                            <span class="material-symbols-outlined icon-18">info</span>
                        </div>
                        <p class="text-body-sm tc-on-surface mb-0">
                            Persetujuan di atas Rp 20.000.000 memerlukan tanda tangan digital Komite Sekolah.
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
