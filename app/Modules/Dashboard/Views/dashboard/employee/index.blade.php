@extends('layouts.app')
@section('title', 'Karyawan')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/employee.css') }}">
@endpush
@section('content')
    @include('Dashboard::partials.ep_subnav', [
        'title' => 'Modul Karyawan  & SDM',
        'badge' => 'Realisasi SPP 92%',
        'subtitle' => 'Tata Usaha, Penerimaan SPP & Ringkasan Arus Kas Sekolah',
        'metaIcon' => 'calendar_month',
        'metaLabel' => 'Periode',
        'metaValue' => 'Maret 2025',
    ])

    <!-- Header card -->
    <div class="card-soft p-4">
        <div class="d-flex flex-column flex-md-row align-items-md-end gap-3">

            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="status-pill-live">
                    <span class="live-dot"></span>
                    <span>Tahun Ajaran 2024/2025</span>
                </div>
                <button type="button" class="btn-soft-primary">
                    <span class="material-symbols-outlined" style="font-size:16px;">badge</span>
                    <span>Cetak ID Card SDM</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Cuti -->
    <div class="alert-cuti">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-start gap-3">
                <div class="alert-icon">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h3 class="text-title-sm text-primary mb-0">Notifikasi Pengajuan Cuti &amp; Izin</h3>
                        <span class="badge-pending">2 Tertunda</span>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mt-1 mb-0">
                        Terdapat 2 pengajuan izin cuti yang membutuhkan persetujuan dan verifikasi Kepala
                        Sekolah pekan ini.
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 align-self-sm-end align-self-md-center">
                <button type="button" class="btn-ghost">Tinjau Nanti</button>
                <button type="button" class="btn-primary-dark">Buka Antrean Cuti</button>
            </div>
        </div>
    </div>

    <!-- KPI Bento -->
    <div class="row g-3">

        <!-- Total Pegawai -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="kpi-card border-primary">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-label-md text-on-surface-variant">Total Pegawai</span>
                    <div class="kpi-icon-box kpi-icon-primary">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">82</span>
                        <span class="text-label-md text-outline">Jiwa</span>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Karyawan &amp; Guru Terdaftar</p>
                </div>
                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between text-body-sm"
                    style="border-color:var(--surface-container-high)!important;">
                    <span class="text-secondary fw-semibold">100% Aktif</span>
                    <span class="text-outline">Tahun Ajaran Aktif</span>
                </div>
            </div>
        </div>

        <!-- Guru -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="kpi-card border-secondary">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-label-md text-on-surface-variant">Guru Pengajar (Pendidik)</span>
                    <div class="kpi-icon-box kpi-icon-secondary">
                        <span class="material-symbols-outlined">cast_for_education</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">64</span>
                        <span class="text-label-md text-secondary fw-semibold">Orang</span>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Terbagi dalam 14 Mata Pelajaran
                    </p>
                </div>
                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between text-body-sm"
                    style="border-color:var(--surface-container-high)!important;">
                    <span class="text-on-surface-variant">Wali Kelas: <strong class="text-primary">24</strong></span>
                    <span class="text-outline">Guru BK: <strong class="text-primary">4</strong></span>
                </div>
            </div>
        </div>

        <!-- Tenaga Kependidikan -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="kpi-card border-primary-fixed">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-label-md text-on-surface-variant">Tenaga Kependidikan / TU</span>
                    <div class="kpi-icon-box kpi-icon-high">
                        <span class="material-symbols-outlined">corporate_fare</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">18</span>
                        <span class="text-label-md text-outline">Orang</span>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Administrasi, Lab &amp; Perpus
                    </p>
                </div>
                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between text-body-sm"
                    style="border-color:var(--surface-container-high)!important;">
                    <span class="text-on-surface-variant">PNS: <strong class="text-primary">11</strong></span>
                    <span class="text-outline">PPPK/Honorer: <strong class="text-primary">7</strong></span>
                </div>
            </div>
        </div>

        <!-- Sertifikasi -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="kpi-card border-tertiary">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-label-md text-on-surface-variant">Status Sertifikasi</span>
                    <div class="kpi-icon-box kpi-icon-tertiary">
                        <span class="material-symbols-outlined">verified</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">78%</span>
                        <span class="text-label-md text-secondary fw-semibold">+4% thn ini</span>
                    </div>
                    <p class="text-body-sm text-on-surface-variant mt-1 mb-0">50 dari 64 Guru Tersertifikasi
                    </p>
                </div>
                <div class="mt-3 pt-3 border-top" style="border-color:var(--surface-container-high)!important;">
                    <div class="progress progress-thin">
                        <div class="progress-bar" role="progressbar" style="width:78%;" aria-valuenow="78"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Aksi Cepat -->
    <div class="card-soft p-4">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <h2 class="text-title-sm text-primary mb-0">Aksi Cepat Manajemen SDM</h2>
                <p class="text-body-sm text-outline mb-0">Operasional administrasi harian tenaga kependidikan
                </p>
            </div>
            <span class="text-label-sm text-secondary fw-semibold px-2 py-1 rounded-pill"
                style="background:var(--surface-container-low);">Tindakan Cepat</span>
        </div>
        <div class="row g-3">

            <div class="col-6 col-md-3">
                <button type="button" class="action-tile">
                    <div class="action-icon action-icon-primary">
                        <span class="material-symbols-outlined">person_add</span>
                    </div>
                    <span class="text-title-sm text-primary">Tambah Pegawai Baru</span>
                    <span class="text-body-sm text-on-surface-variant mt-1">Registrasi NIP/NUPTK &amp;
                        berkas</span>
                </button>
            </div>

            <div class="col-6 col-md-3">
                <button type="button" class="action-tile">
                    <div class="action-icon action-icon-secondary">
                        <span class="material-symbols-outlined">event_busy</span>
                    </div>
                    <span class="text-title-sm text-primary">Pengajuan Cuti &amp; Izin</span>
                    <span class="text-body-sm text-on-surface-variant mt-1">Permohonan, surat &amp; rekap
                        absensi</span>
                </button>
            </div>

            <div class="col-6 col-md-3">
                <button type="button" class="action-tile">
                    <div class="action-icon action-icon-primary-solid">
                        <span class="material-symbols-outlined">assessment</span>
                    </div>
                    <span class="text-title-sm text-primary">Penilaian Kinerja (SKP)</span>
                    <span class="text-body-sm text-on-surface-variant mt-1">Evaluasi semester &amp; indikator
                        guru</span>
                </button>
            </div>

            <div class="col-6 col-md-3">
                <button type="button" class="action-tile">
                    <div class="action-icon action-icon-tertiary">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </div>
                    <span class="text-title-sm text-primary">Unduh Rekap Gaji</span>
                    <span class="text-body-sm text-on-surface-variant mt-1">Slip tunjangan fungsional &amp;
                        honor</span>
                </button>
            </div>

        </div>
    </div>

    <!-- Employee Directory -->
    <div class="d-flex flex-column gap-3">

        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div>
                <h2 class="text-headline-md text-primary mb-0">Daftar Tenaga Pendidik &amp; Staf</h2>
                <p class="text-body-sm text-on-surface-variant mb-0">Direktori pengajar aktif dan personil tata
                    usaha sekolah</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="search-wrap" style="min-width:256px;">
                    <span class="material-symbols-outlined search-icon">search</span>
                    <input type="text" class="search-input" placeholder="Cari nama, NIP atau mapel..." />
                </div>
                <button type="button" class="filter-btn" title="Filter Tambahan">
                    <span class="material-symbols-outlined" style="font-size:20px;">tune</span>
                </button>
            </div>
        </div>

        <!-- Filter tabs -->
        <div class="filter-tabs">
            <button type="button" class="active">Semua (82)</button>
            <button type="button">Guru MIPA (26)</button>
            <button type="button">Guru IPS/Bahasa (24)</button>
            <button type="button">Staf Administrasi/TU (18)</button>
            <button type="button">Guru PJOK &amp; Seni (14)</button>
        </div>

        <!-- Employee grid -->
        <div class="row g-3">

            <!-- Card 1 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="employee-card border-secondary">
                    <div>
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBATE0SBLJR8VeKUetmwORiC3cetbWKXplzeBPnjQ5qsv4v7K8gNB86BL34ECjH8SBBou6gLiYfXvHzkhPwjMhEliWpIUqwU7szxDuJDY3PsgBPL_8SjIPmQcwXdRLpFw7luaRV5eebQ1L97_XiPHQlJwiShkTmMyasWrTsQi1TUjU3PFDSJGEfN5OHTkHTv_D3lFp5rNnt6UCL7xaVDAgAoxUOucWVTMu8nAnS8en8"
                                    class="emp-avatar" alt="Bambang Sudrajat" />
                                <div>
                                    <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Drs.
                                        H. Bambang Sudrajat, M.Pd.</h3>
                                    <p class="text-body-sm text-secondary fw-semibold mb-0">Guru Fisika Senior
                                        • MIPA</p>
                                </div>
                            </div>
                            <span class="emp-status">
                                <span class="dot"></span>
                                Aktif Mengajar
                            </span>
                        </div>

                        <div class="emp-meta">
                            <div class="emp-meta-row">
                                <span class="text-outline">NIP / NUPTK:</span>
                                <span class="mono-tiny">19680412 199403 1 002</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Beban Ajar:</span>
                                <span class="text-primary fw-semibold">24 Jam / Minggu (Kelas XII MIPA)</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Sertifikasi:</span>
                                <span class="cert-badge cert-success">
                                    <span class="material-symbols-outlined" style="font-size:12px;">check_circle</span>
                                    Terverifikasi Kemdikbud
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-3">
                        <button type="button" class="btn-outline-soft">
                            <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                            <span>Jam Mengajar</span>
                        </button>
                        <button type="button" class="btn-fill-primary">
                            <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                            <span>Detail Profil</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="employee-card border-secondary">
                    <div>
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC8UuKR-v0E6W-tCZ1u-yLc2CobikJbu6_dgIbH9uX4CzMbDaUI363zpYk3Bf8G9CV6KA--lNfzzK3M1Tm34mWxUoOPmjmmeXljpBNRdZpZcALHDrWniO0tsTJwKSrKw-TeMJYhb-mmmtT_PImcxfZ--BibL6LtsE2YS66PrWLpUtMalWqowV6LlgM8Sirg1ubDnrYC84n6cZVtCKb0PvAp9io3fyVGWyPm9-phuUTH"
                                    class="emp-avatar" alt="Siti Rahmah" />
                                <div>
                                    <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Siti
                                        Rahmah, S.Si., M.Biotech.</h3>
                                    <p class="text-body-sm text-secondary fw-semibold mb-0">Guru Biologi &amp;
                                        Kepala Lab • MIPA</p>
                                </div>
                            </div>
                            <span class="emp-status">
                                <span class="dot"></span>
                                Aktif Mengajar
                            </span>
                        </div>
                        <div class="emp-meta">
                            <div class="emp-meta-row">
                                <span class="text-outline">NIP / NUPTK:</span>
                                <span class="mono-tiny">19820519 200801 2 015</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Beban Ajar:</span>
                                <span class="text-primary fw-semibold">22 Jam / Minggu (Kelas XI &amp;
                                    XII)</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Sertifikasi:</span>
                                <span class="cert-badge cert-success">
                                    <span class="material-symbols-outlined" style="font-size:12px;">check_circle</span>
                                    Pendidik Profesional
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <button type="button" class="btn-outline-soft">
                            <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                            <span>Jam Mengajar</span>
                        </button>
                        <button type="button" class="btn-fill-primary">
                            <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                            <span>Detail Profil</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="employee-card border-primary">
                    <div>
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDXy4Bdn_ejcoZeEekwIqN9vw5fY0UgY1lC1WOTMnJDsmvCf5pwtcKblA6hmhsLmlqkDdlTkrTO7oTj-9Xx1XJUBZrgUSWFAAVwEAWSWt2udVpdh09zeeThYehTJjwLFQnlPjM5nlY1CYBVc6s5859WFEtGeCe9Epo2Xf8-zebulEnMVvtUUORwG_Vha-kav7b5Y3zaStCy0NQRSsaoy-yN6Y63QdmMt-l684RyVdIB"
                                    class="emp-avatar" alt="Ahmad Fikri Pratama" />
                                <div>
                                    <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Ahmad
                                        Fikri Pratama, S.Pd.</h3>
                                    <p class="text-body-sm text-on-primary-fixed-variant fw-semibold mb-0">Guru
                                        Bahasa Inggris • Bahasa</p>
                                </div>
                            </div>
                            <span class="emp-status">
                                <span class="dot"></span>
                                Aktif Mengajar
                            </span>
                        </div>
                        <div class="emp-meta">
                            <div class="emp-meta-row">
                                <span class="text-outline">NIP / NUPTK:</span>
                                <span class="mono-tiny">19910903 201903 1 008</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Beban Ajar:</span>
                                <span class="text-primary fw-semibold">20 Jam / Minggu + Pembina OSIS</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Sertifikasi:</span>
                                <span class="cert-badge cert-warning">
                                    <span class="material-symbols-outlined" style="font-size:12px;">sync</span>
                                    PPG Dalam Jabatan
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <button type="button" class="btn-outline-soft">
                            <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                            <span>Jam Mengajar</span>
                        </button>
                        <button type="button" class="btn-fill-primary">
                            <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                            <span>Detail Profil</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="employee-card border-primary">
                    <div>
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBHe2u6yt-hMQilI7KF37rl8sILB_tRClkes3HX7cAGpwuNwG2NLctNbn0PH1nfootOZWH03GHt5vs8nMEFdZxd-dH0ytIqlUkSGJYgt89WDYJh-RyOMyEn47xhVOjd-s1LE7auRE7toQQ0LzDAU1tXgO1yYznbNdiSmOwehAPYgniPmC4NBe8GPTaryrpHHNieRFPm0P21EdDWvsyWHhiJKysyV-7V6SGePmTJMoNo"
                                    class="emp-avatar" alt="Nurul Hidayati" />
                                <div>
                                    <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Hj.
                                        Nurul Hidayati, M.Hum.</h3>
                                    <p class="text-body-sm text-on-primary-fixed-variant fw-semibold mb-0">Guru
                                        Sejarah Indonesia • IPS</p>
                                </div>
                            </div>
                            <span class="emp-status">
                                <span class="dot"></span>
                                Aktif Mengajar
                            </span>
                        </div>
                        <div class="emp-meta">
                            <div class="emp-meta-row">
                                <span class="text-outline">NIP / NUPTK:</span>
                                <span class="mono-tiny">19741120 199903 2 004</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Beban Ajar:</span>
                                <span class="text-primary fw-semibold">24 Jam / Minggu (Kelas X &amp; XI
                                    IPS)</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Sertifikasi:</span>
                                <span class="cert-badge cert-success">
                                    <span class="material-symbols-outlined" style="font-size:12px;">check_circle</span>
                                    Terverifikasi Kemdikbud
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <button type="button" class="btn-outline-soft">
                            <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                            <span>Jam Mengajar</span>
                        </button>
                        <button type="button" class="btn-fill-primary">
                            <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                            <span>Detail Profil</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="employee-card border-outline">
                    <div>
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDFiweBdNKQofpS4iAJjinmZAUINyKOIMS3aSRE1xJhCEKHpHsl0ezP50_2oUe7w8bbrHfqGE8C1n1gjqIjnAbswDzzSc6NGQP1jX0YzsFKPWlHwJWxMrxeV8B4CgATSPsK1ZOR6IPvUJQZmYzg3R80xtc1ZpwiFaidWY6W57Gp-F9p-P5Cb80F2D5T2bvqzgFoJmnd_qTCmfSV1tiC7KicpXhthNo8Y8OIDZUzyPFU"
                                    class="emp-avatar" alt="Budi Santoso" />
                                <div>
                                    <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Budi
                                        Santoso, S.AP.</h3>
                                    <p class="text-body-sm text-outline fw-semibold mb-0">Kepala Tata Usaha •
                                        Staf TU</p>
                                </div>
                            </div>
                            <span class="emp-status primary">
                                <span class="dot"></span>
                                Aktif Dinas
                            </span>
                        </div>
                        <div class="emp-meta">
                            <div class="emp-meta-row">
                                <span class="text-outline">NIP:</span>
                                <span class="mono-tiny">19790214 200501 1 003</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Bidang Tugas:</span>
                                <span class="text-primary fw-semibold">Kepegawaian &amp; Kesiswaan</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Status Kepegawaian:</span>
                                <span class="cert-badge cert-neutral">
                                    ASN / PNS Penata Tk. I
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <button type="button" class="btn-outline-soft">
                            <span class="material-symbols-outlined" style="font-size:16px;">assignment</span>
                            <span>Tupoksi Kerja</span>
                        </button>
                        <button type="button" class="btn-fill-primary">
                            <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                            <span>Detail Profil</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="employee-card border-secondary">
                    <div>
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC9CydZKXb4jMDqyNmxDORbwKCXSXWZRE95yJe2m1ZbNQKalBaua9SkAHJosajVUhvoViGjP3LzwAFuP-0QGSN1krUbDv9XXGpHLw-jafracLR3hOgejcA_KjVv0Sk2Se-zeDg4LyZZnPFWggACHgCOfvsgNE86anf86FomSFXZxkmadyl8pTfxMOBo0VEwYZL4HgCiHIgy87_gToxDSQRIOBej-h9nrY3cJY65C5sE"
                                    class="emp-avatar" alt="Ratna Dewi Kusuma" />
                                <div>
                                    <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Ratna
                                        Dewi Kusuma, M.Pd.</h3>
                                    <p class="text-body-sm text-secondary fw-semibold mb-0">Guru Matematika
                                        Peminatan • MIPA</p>
                                </div>
                            </div>
                            <span class="emp-status">
                                <span class="dot"></span>
                                Aktif Mengajar
                            </span>
                        </div>
                        <div class="emp-meta">
                            <div class="emp-meta-row">
                                <span class="text-outline">NIP / NUPTK:</span>
                                <span class="mono-tiny">19850311 201001 2 021</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Beban Ajar:</span>
                                <span class="text-primary fw-semibold">24 Jam / Minggu + Wali Kelas XII</span>
                            </div>
                            <div class="emp-meta-row">
                                <span class="text-outline">Sertifikasi:</span>
                                <span class="cert-badge cert-success">
                                    <span class="material-symbols-outlined" style="font-size:12px;">check_circle</span>
                                    Terverifikasi Kemdikbud
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <button type="button" class="btn-outline-soft">
                            <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                            <span>Jam Mengajar</span>
                        </button>
                        <button type="button" class="btn-fill-primary">
                            <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                            <span>Detail Profil</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Pagination -->
        <div class="card-soft p-3 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
            <p class="text-body-sm text-outline mb-0">
                Menampilkan <span class="fw-bold text-on-surface">6</span> dari <span
                    class="fw-bold text-on-surface">82</span> Karyawan &amp; Tenaga Pendidik
            </p>
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="page-btn bordered" disabled>
                    <span class="material-symbols-outlined" style="font-size:14px;">chevron_left</span>
                </button>
                <button type="button" class="page-btn active">1</button>
                <button type="button" class="page-btn">2</button>
                <button type="button" class="page-btn">3</button>
                <span class="px-1 text-outline">...</span>
                <button type="button" class="page-btn">14</button>
                <button type="button" class="page-btn bordered">
                    <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
                </button>
            </div>
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
    </div>
@endsection
