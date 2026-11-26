@extends('layouts.app')
@section('title', 'Penilaian & Rapor')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/assessment.css') }}">
@endpush
@section('content')
    <div class="raport">
        @include('Dashboard::partials.ep_subnav', [
            'title' => 'Modul Rapor & Asesmen',
            'badge' => 'Periode Penilaian Aktif',
            'subtitle' => 'Kurikulum Merdeka & E-Rapor Semester Genap 2024/2025',
            'metaIcon' => 'event',
            'metaLabel' => 'Tenggat',
            'metaValue' => '22 Juni 2025',
        ])
        <!-- ---------- MODULE HEADER ---------- -->
        <section class="rap-card p-4">
            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                <div>
                    <span class="tagpill bg-container tc-primary fw-bold mb-2">Akademik Genap</span>
                    <h1 class="fs-22 fw-bold tc-primary mt-2 mb-0 leading-tight">Modul Rapor &amp; Asesmen</h1>
                </div>
            </div>
            <p class="fs-12 tc-variant mb-3" style="line-height:1.5;">
                Kurikulum Merdeka &amp; E-Rapor Semester Genap 2024/2025
            </p>
            <div class="d-flex align-items-center gap-2 pt-2 border-soft-t flex-wrap">
                <span class="bpill bg-success tc-success">
                    <span class="bg-secondary-c rounded-circle me-1" style="width:6px;height:6px;"></span>
                    Periode Penilaian Aktif
                </span>
                <span class="fs-12 tc-variant ms-auto">Tenggat: 22 Juni 2025</span>
            </div>
        </section>

        <!-- ---------- KPI BENTO ---------- -->
        <section class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="fs-13 fw-bold tc-on-surface mb-0">Ringkasan Progres &amp; KPI</h2>
                <span class="fs-11 fw-bold tc-secondary">Sinkronisasi Realtime</span>
            </div>

            <div class="row g-3">

                <!-- KPI 1: Status Rapor Kelas -->
                <div class="col-6">
                    <div class="rap-card kpi-accent-primary p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between tc-variant mb-2">
                                <span class="fs-11 fw-bold tc-outline">Status Rapor Kelas</span>
                                <span class="material-symbols-outlined tc-primary"
                                    style="font-size:20px;">assignment_turned_in</span>
                            </div>
                            <div class="fs-22 fw-bold tc-primary leading-tight">
                                31<span class="fs-14 fw-normal tc-variant">/36</span>
                            </div>
                            <p class="fs-12 tc-variant mt-1 mb-0">Rombel Selesai diinput</p>
                        </div>
                        <div class="mt-3 pt-2 border-soft-t d-flex align-items-center justify-content-between">
                            <div class="progress-edu flex-grow-1 me-2">
                                <div class="bg-primary-c" style="width:86.1%;"></div>
                            </div>
                            <span class="fs-11 fw-bold tc-primary">86.1%</span>
                        </div>
                    </div>
                </div>

                <!-- KPI 2: Rata-Rata Angkatan -->
                <div class="col-6">
                    <div class="rap-card kpi-accent-secondary p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between tc-variant mb-2">
                                <span class="fs-11 fw-bold tc-outline">Rata-Rata Angkatan</span>
                                <span class="material-symbols-outlined tc-secondary"
                                    style="font-size:20px;">trending_up</span>
                            </div>
                            <div class="fs-22 fw-bold tc-on-surface leading-tight">85.2</div>
                            <p class="fs-12 tc-variant mt-1 mb-0">Skala Capaian 100</p>
                        </div>
                        <div class="mt-3 pt-2 border-soft-t d-flex align-items-center gap-2 tc-success">
                            <span class="material-symbols-outlined" style="font-size:16px;">arrow_upward</span>
                            <span class="fs-11 fw-bold">+1.4 poin</span>
                            <span class="fs-10 tc-outline">vs Ganjil</span>
                        </div>
                    </div>
                </div>

                <!-- KPI 3: Deskripsi CP -->
                <div class="col-6">
                    <div class="rap-card kpi-accent-teal p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between tc-variant mb-2">
                                <span class="fs-11 fw-bold tc-outline">Deskripsi CP</span>
                                <span class="material-symbols-outlined tc-secondary"
                                    style="font-size:20px;">fact_check</span>
                            </div>
                            <div class="fs-22 fw-bold tc-on-surface leading-tight">94%</div>
                            <p class="fs-12 tc-variant mt-1 mb-0">Kelengkapan Narasi</p>
                        </div>
                        <div class="mt-3 pt-2 border-soft-t">
                            <span class="bpill bg-success tc-success">Terverifikasi Wali Kelas</span>
                        </div>
                    </div>
                </div>

                <!-- KPI 4: Belum Finalisasi -->
                <div class="col-6">
                    <div class="rap-card kpi-accent-gold p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between tc-variant mb-2">
                                <span class="fs-11 fw-bold tc-outline">Belum Finalisasi</span>
                                <span class="material-symbols-outlined tc-on-tertiary-fixed-variant"
                                    style="font-size:20px;">pending_actions</span>
                            </div>
                            <div class="fs-22 fw-bold tc-error leading-tight">5 Kelas</div>
                            <p class="fs-12 tc-variant mt-1 mb-0">Perlu Tindak Lanjut</p>
                        </div>
                        <div class="mt-3 pt-2 border-soft-t">
                            <span class="bpill bg-warning tc-warning truncate-1">Menunggu Nilai Ekskul</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ---------- AKSI CEPAT ---------- -->
        <section class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="fs-13 fw-bold tc-on-surface mb-0">Aksi Cepat Modul</h2>
                <span class="fs-11 fw-bold tc-outline">Pintasan Dokumen</span>
            </div>

            <div class="row g-2">

                <!-- Action 1: Input Nilai -->
                <div class="col-6">
                    <button type="button" class="quick-card">
                        <span class="ibox-lg bg-primary-fixed tc-primary">
                            <span class="material-symbols-outlined" style="font-size:22px;">edit_note</span>
                        </span>
                        <span class="d-flex flex-column min-w-0">
                            <span class="fs-13 fw-bold tc-on-surface truncate-1 leading-tight">Input Nilai</span>
                            <span class="fs-12 tc-variant truncate-1">Formatif/Sumatif</span>
                        </span>
                    </button>
                </div>

                <!-- Action 2: Cetak Rapor -->
                <div class="col-6">
                    <button type="button" class="quick-card">
                        <span class="ibox-lg bg-secondary-fixed tc-secondary">
                            <span class="material-symbols-outlined" style="font-size:22px;">picture_as_pdf</span>
                        </span>
                        <span class="d-flex flex-column min-w-0">
                            <span class="fs-13 fw-bold tc-on-surface truncate-1 leading-tight">Cetak Rapor</span>
                            <span class="fs-12 tc-variant truncate-1">Berkas PDF Massal</span>
                        </span>
                    </button>
                </div>

                <!-- Action 3: Kunci Nilai -->
                <div class="col-6">
                    <button type="button" class="quick-card">
                        <span class="ibox-lg bg-tertiary-fixed tc-on-tertiary-fixed-variant">
                            <span class="material-symbols-outlined" style="font-size:22px;">lock</span>
                        </span>
                        <span class="d-flex flex-column min-w-0">
                            <span class="fs-13 fw-bold tc-on-surface truncate-1 leading-tight">Kunci Nilai</span>
                            <span class="fs-12 tc-variant truncate-1">Finalisasi Data</span>
                        </span>
                    </button>
                </div>

                <!-- Action 4: Ledger Nilai -->
                <div class="col-6">
                    <button type="button" class="quick-card">
                        <span class="ibox-lg bg-high tc-variant">
                            <span class="material-symbols-outlined" style="font-size:22px;">table_chart</span>
                        </span>
                        <span class="d-flex flex-column min-w-0">
                            <span class="fs-13 fw-bold tc-on-surface truncate-1 leading-tight">Ledger Nilai</span>
                            <span class="fs-12 tc-variant truncate-1">Rekap Semester</span>
                        </span>
                    </button>
                </div>
            </div>
        </section>

        <!-- ---------- DISTRIBUSI PREDIKAT ---------- -->
        <section class="rap-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="fs-13 fw-bold tc-on-surface mb-0">Distribusi Predikat Capaian</h2>
                    <p class="fs-12 tc-variant mb-0">Berdasarkan Kriteria Ketercapaian Tujuan (KKTP)</p>
                </div>
                <button type="button" class="ibox bg-transparent tc-outline border-0 pressable"
                    aria-label="Info Predikat">
                    <span class="material-symbols-outlined" style="font-size:20px;">help_outline</span>
                </button>
            </div>

            <div class="d-flex flex-column gap-3 pt-1">
                <!-- Segmented Bar -->
                <div class="progress-edu" style="height:12px;">
                    <div class="bg-primary-c" style="width:58%;" title="Sangat Baik 58%"></div>
                    <div class="bg-secondary-c" style="width:34%;" title="Baik 34%"></div>
                    <div class="bg-tertiary-fixed-dim" style="width:8%;" title="Cukup 8%"></div>
                </div>

                <!-- Predicate Badges -->
                <div class="row g-2 pt-2 text-center">
                    <!-- Sangat Baik -->
                    <div class="col-4">
                        <div class="bg-low rounded-3 p-2 border-soft d-flex flex-column align-items-center h-100">
                            <div class="d-flex align-items-center gap-1 mb-1">
                                <span class="bg-primary-c rounded-circle" style="width:10px;height:10px;"></span>
                                <span class="fs-11 fw-bold tc-on-surface">Sangat Baik</span>
                            </div>
                            <span class="fs-13 fw-bold tc-primary">58%</span>
                            <span class="fs-11 tc-outline">712 Siswa</span>
                        </div>
                    </div>
                    <!-- Baik -->
                    <div class="col-4">
                        <div class="bg-teal border-teal rounded-3 p-2 d-flex flex-column align-items-center h-100">
                            <div class="d-flex align-items-center gap-1 mb-1">
                                <span class="bg-secondary-c rounded-circle" style="width:10px;height:10px;"></span>
                                <span class="fs-11 fw-bold tc-teal">Baik</span>
                            </div>
                            <span class="fs-13 fw-bold tc-secondary">34%</span>
                            <span class="fs-11 tc-outline">418 Siswa</span>
                        </div>
                    </div>
                    <!-- Cukup -->
                    <div class="col-4">
                        <div class="bg-amber border-amber rounded-3 p-2 d-flex flex-column align-items-center h-100">
                            <div class="d-flex align-items-center gap-1 mb-1">
                                <span class="bg-tertiary-fixed-dim rounded-circle" style="width:10px;height:10px;"></span>
                                <span class="fs-11 fw-bold tc-amber">Cukup</span>
                            </div>
                            <span class="fs-13 fw-bold tc-amber">8%</span>
                            <span class="fs-11 tc-outline">98 Siswa</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ---------- VERIFIKASI RAPOR ---------- -->
        <section class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fs-13 fw-bold tc-on-surface mb-0">Verifikasi Rapor Rombel</h2>
                    <span class="fs-12 tc-variant">Status approval per rombongan belajar</span>
                </div>
                <button type="button" class="btn-link-edu">Lihat Semua</button>
            </div>

            <div class="d-flex flex-column gap-2">

                <!-- Item 1: X MIPA 1 -->
                <div class="verify-item d-flex align-items-center justify-content-between gap-2"
                    style="border-left:4px solid var(--c-primary-container);">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h3 class="fs-13 fw-bold tc-on-surface mb-0">Kelas X MIPA 1</h3>
                            <span class="bpill bg-info tc-info">Siap Cetak Rapor</span>
                        </div>
                        <p class="fs-12 tc-variant mb-0 mt-1 d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined tc-outline" style="font-size:16px;">person</span>
                            Wali Kelas: <span class="fw-semibold tc-on-surface">Bambang S.</span>
                        </p>
                    </div>
                    <button type="button" class="btn-circle" aria-label="Buka Rapor X MIPA 1">
                        <span class="material-symbols-outlined" style="font-size:20px;">chevron_right</span>
                    </button>
                </div>

                <!-- Item 2: XI IPS 2 -->
                <div class="verify-item d-flex align-items-center justify-content-between gap-2"
                    style="border-left:4px solid var(--c-tertiary-fixed-dim);">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h3 class="fs-13 fw-bold tc-on-surface mb-0">Kelas XI IPS 2</h3>
                            <span class="bpill bg-warning tc-warning">Menunggu Catatan Karakter</span>
                        </div>
                        <p class="fs-12 tc-variant mb-0 mt-1 d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined tc-outline" style="font-size:16px;">person</span>
                            Wali Kelas: <span class="fw-semibold tc-on-surface">Siti Rahmah</span>
                        </p>
                    </div>
                    <button type="button" class="btn-circle" aria-label="Buka Rapor XI IPS 2">
                        <span class="material-symbols-outlined" style="font-size:20px;">chevron_right</span>
                    </button>
                </div>

                <!-- Item 3: XII MIPA 3 -->
                <div class="verify-item d-flex align-items-center justify-content-between gap-2"
                    style="border-left:4px solid var(--c-teal-fg);">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h3 class="fs-13 fw-bold tc-on-surface mb-0">Kelas XII MIPA 3</h3>
                            <span class="bpill bg-success tc-success">100% Final &amp; Ditandatangani</span>
                        </div>
                        <p class="fs-12 tc-variant mb-0 mt-1 d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined tc-outline" style="font-size:16px;">person</span>
                            Wali Kelas: <span class="fw-semibold tc-on-surface">Dewi Sartika</span>
                        </p>
                    </div>
                    <button type="button" class="btn-circle" aria-label="Buka Rapor XII MIPA 3">
                        <span class="material-symbols-outlined" style="font-size:20px;">chevron_right</span>
                    </button>
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
    </div>
@endsection
