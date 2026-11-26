@extends('layouts.app')
@section('title', 'Data Akademik')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/academic.css') }}">
@endpush
@section('content')
    <div class="academic-dashboard">
        <!-- Context & Back Bar -->
        <div class="mb-4">
            @include('Dashboard::partials.ep_subnav', [
                'title' => 'Modul Data Akademik',
                'badge' => '36 Rombel Aktif',
                'subtitle' => 'Kurikulum Merdeka, Manajemen Kelas & Verifikasi Nilai Akhir',
                'metaIcon' => 'school',
                'metaLabel' => 'Siswa Aktif',
                'metaValue' => '1.180 Siswa',
            ])
        </div>

        <!-- KPI Bento -->
        <section aria-label="Ringkasan Statistik Akademik" class="mb-4">
            <div class="row g-3">
                <!-- Siswa Aktif -->
                <div class="col-6 col-md-3">
                    <div class="card-soft border-left-primary p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-outline">Total Siswa Aktif</span>
                            <div class="kpi-icon-box kpi-icon-primary">
                                <span class="material-symbols-outlined" style="font-size:16px;">group</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="text-headline-md text-primary fw-bold">1.180</div>
                            <div class="text-body-sm text-on-surface-variant">36 Rombel / Kelas</div>
                        </div>
                    </div>
                </div>

                <!-- Guru -->
                <div class="col-6 col-md-3">
                    <div class="card-soft border-left-secondary p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-outline">Guru &amp; Pengajar</span>
                            <div class="kpi-icon-box kpi-icon-secondary">
                                <span class="material-symbols-outlined" style="font-size:16px;">person_celebrate</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="text-headline-md text-primary fw-bold">64</div>
                            <div class="text-body-sm text-on-surface-variant">Tenaga Pendidik</div>
                        </div>
                    </div>
                </div>

                <!-- Mapel -->
                <div class="col-6 col-md-3">
                    <div class="card-soft border-left-secondary p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-outline">Mata Pelajaran</span>
                            <div class="kpi-icon-box kpi-icon-secondary">
                                <span class="material-symbols-outlined" style="font-size:16px;">menu_book</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="text-headline-md text-primary fw-bold">28</div>
                            <div class="text-body-sm text-on-surface-variant">Mapel Aktif</div>
                        </div>
                    </div>
                </div>

                <!-- Nilai -->
                <div class="col-6 col-md-3">
                    <div class="card-soft border-left-primary p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-outline">Rata-rata Nilai</span>
                            <div class="kpi-icon-box kpi-icon-primary">
                                <span class="material-symbols-outlined" style="font-size:16px;">analytics</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="d-flex align-items-baseline gap-1">
                                <span class="text-headline-md text-primary fw-bold">84.5</span>
                                <span class="text-label-sm text-secondary fw-semibold">+3.2%</span>
                            </div>
                            <div class="text-body-sm text-on-surface-variant">Semester Berjalan</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content 2-Column Responsive Layout -->
        <div class="row g-4 align-items-start">
            <!-- Left / Main Column (Desktop 8 cols) -->
            <div class="col-12 col-xl-8 d-flex flex-column gap-4">
                <!-- Verifikasi Nilai -->
                <section class="card-soft p-3 p-md-4" aria-label="Status Verifikasi Nilai Akhir">
                    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                        <div>
                            <h2 class="text-title-sm text-primary mb-0">Verifikasi Nilai Akhir</h2>
                            <p class="text-body-sm text-outline mb-0">Tahun Ajaran 2024/2025 Semester Genap</p>
                        </div>
                        <span class="pill-primary">85% Selesai</span>
                    </div>
                    <div class="progress progress-medium progress-secondary my-3">
                        <div class="progress-bar" role="progressbar" style="width:85%;" aria-valuenow="85" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                    <div
                        class="d-flex align-items-center justify-content-between text-body-sm pt-2 border-top border-surface-variant flex-wrap gap-2">
                        <span class="text-on-surface-variant d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined text-secondary" style="font-size:16px;">verified</span>
                            31 dari 36 Rombel Terverifikasi
                        </span>
                        <button type="button"
                            class="btn btn-link p-0 text-decoration-none text-primary text-label-sm fw-semibold">Cek
                            Detail</button>
                    </div>
                </section>

                <!-- Ringkasan Tingkat Kelas -->
                <section class="d-flex flex-column gap-3" aria-label="Ringkasan Tingkat Kelas">
                    <div class="d-flex align-items-center justify-content-between px-1">
                        <h2 class="text-title-sm text-primary mb-0">Ringkasan Tingkat Kelas</h2>
                        <span class="text-label-sm text-secondary fw-semibold">Fase E &amp; Fase F</span>
                    </div>

                    <div class="row g-3">
                        <!-- Kelas X -->
                        <div class="col-12 col-md-4 col-xl-12">
                            <div class="card-soft p-3 h-100 d-flex flex-column justify-content-between">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="text-title-sm text-primary">Kelas X (Fase E)</span>
                                            <span class="pill-secondary">12 Kelas</span>
                                        </div>
                                        <p class="text-body-sm text-outline mt-1 mb-0">396 Siswa Terdaftar • Kurikulum
                                            Merdeka Murni</p>
                                    </div>
                                    <span class="material-symbols-outlined text-outline">chevron_right</span>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex align-items-center justify-content-between text-label-sm mb-1">
                                        <span class="text-on-surface-variant">Progres Capaian Pembelajaran</span>
                                        <span class="text-primary fw-semibold">92%</span>
                                    </div>
                                    <div class="progress progress-thin progress-primary">
                                        <div class="progress-bar" role="progressbar" style="width:92%;" aria-valuenow="92"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kelas XI -->
                        <div class="col-12 col-md-4 col-xl-12">
                            <div class="card-soft p-3 h-100 d-flex flex-column justify-content-between">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="text-title-sm text-primary">Kelas XI (Fase F)</span>
                                            <span class="pill-secondary">12 Kelas</span>
                                        </div>
                                        <p class="text-body-sm text-outline mt-1 mb-0">392 Siswa Terdaftar • Peminatan
                                            Terpadu</p>
                                    </div>
                                    <span class="material-symbols-outlined text-outline">chevron_right</span>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex align-items-center justify-content-between text-label-sm mb-1">
                                        <span class="text-on-surface-variant">Progres Capaian Pembelajaran</span>
                                        <span class="text-primary fw-semibold">88%</span>
                                    </div>
                                    <div class="progress progress-thin progress-primary">
                                        <div class="progress-bar" role="progressbar" style="width:88%;"
                                            aria-valuenow="88" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kelas XII -->
                        <div class="col-12 col-md-4 col-xl-12">
                            <div class="card-soft p-3 h-100 d-flex flex-column justify-content-between">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="text-title-sm text-primary">Kelas XII (Fase F Akhir)</span>
                                            <span class="pill-secondary">12 Kelas</span>
                                        </div>
                                        <p class="text-body-sm text-outline mt-1 mb-0">392 Siswa Terdaftar • Persiapan UTBK
                                            &amp; Portofolio</p>
                                    </div>
                                    <span class="material-symbols-outlined text-outline">chevron_right</span>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex align-items-center justify-content-between text-label-sm mb-1">
                                        <span class="text-on-surface-variant">Progres Capaian Pembelajaran</span>
                                        <span class="text-secondary fw-semibold">96%</span>
                                    </div>
                                    <div class="progress progress-thin progress-secondary">
                                        <div class="progress-bar" role="progressbar" style="width:96%;"
                                            aria-valuenow="96" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right / Sidebar Column (Desktop 4 cols) -->
            <div class="col-12 col-xl-4 d-flex flex-column gap-4">
                <!-- Quick Actions -->
                <section class="card-soft p-3 p-md-4" aria-label="Aksi Cepat Akademik">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="text-title-sm text-primary mb-0">Aksi Cepat Akademik</h2>
                        <span class="text-label-sm text-secondary fw-semibold">Pintasan</span>
                    </div>
                    <div class="row row-cols-2 row-cols-sm-4 row-cols-xl-2 g-2">
                        <div class="col">
                            <button type="button" class="action-tile h-100">
                                <div class="action-icon action-icon-primary">
                                    <span class="material-symbols-outlined">meeting_room</span>
                                </div>
                                <span class="text-label-sm text-on-surface-variant mt-2">Kelola Rombel</span>
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="action-tile h-100">
                                <div class="action-icon action-icon-secondary">
                                    <span class="material-symbols-outlined">description</span>
                                </div>
                                <span class="text-label-sm text-on-surface-variant mt-2">Input E-Rapor</span>
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="action-tile h-100">
                                <div class="action-icon action-icon-primary">
                                    <span class="material-symbols-outlined">calendar_month</span>
                                </div>
                                <span class="text-label-sm text-on-surface-variant mt-2">Jadwal Pelajaran</span>
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="action-tile h-100">
                                <div class="action-icon action-icon-primary">
                                    <span class="material-symbols-outlined">event_note</span>
                                </div>
                                <span class="text-label-sm text-on-surface-variant mt-2">Kalender Akademik</span>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Mapel Unggulan -->
                <section class="card-soft p-3 p-md-4" aria-label="Mata Pelajaran Unggulan">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="text-title-sm text-primary mb-0">Mata Pelajaran Unggulan</h2>
                        <button type="button"
                            class="btn btn-link p-0 text-decoration-none text-secondary text-label-sm fw-semibold">Semua
                            Mapel</button>
                    </div>
                    <div class="divide-surface">
                        <!-- Matematika -->
                        <div class="py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="mapel-icon mapel-icon-primary">
                                    <span class="material-symbols-outlined" style="font-size:18px;">calculate</span>
                                </div>
                                <div>
                                    <p class="text-label-md text-primary fw-bold mb-0">Matematika Peminatan</p>
                                    <p class="text-body-sm text-outline mb-0">Drs. H. Bambang Sudrajat, M.Pd.</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="text-label-sm text-secondary fw-semibold d-block">88.4 Rata-rata</span>
                                <span class="text-body-sm text-outline">6 Rombel</span>
                            </div>
                        </div>

                        <!-- Fisika -->
                        <div class="py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="mapel-icon mapel-icon-secondary">
                                    <span class="material-symbols-outlined" style="font-size:18px;">science</span>
                                </div>
                                <div>
                                    <p class="text-label-md text-primary fw-bold mb-0">Fisika</p>
                                    <p class="text-body-sm text-outline mb-0">Siti Rahmah, S.Si., M.Sc.</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="text-label-sm text-secondary fw-semibold d-block">86.1 Rata-rata</span>
                                <span class="text-body-sm text-outline">8 Rombel</span>
                            </div>
                        </div>

                        <!-- Bahasa Indonesia -->
                        <div class="py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="mapel-icon mapel-icon-primary">
                                    <span class="material-symbols-outlined" style="font-size:18px;">auto_stories</span>
                                </div>
                                <div>
                                    <p class="text-label-md text-primary fw-bold mb-0">Bahasa Indonesia</p>
                                    <p class="text-body-sm text-outline mb-0">Dewi Sartika, S.Pd., M.Hum.</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="text-label-sm text-secondary fw-semibold d-block">89.0 Rata-rata</span>
                                <span class="text-body-sm text-outline">12 Rombel</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('bottom_navigation')
    <!-- Bottom Navigation (Mobile Only) -->
    <nav class="bottom-nav d-md-none" aria-label="Navigasi Bawah">
        <a href="{{ route('dashboard.back-to-home') }}" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">dashboard</span>
            <span class="text-label-sm mt-1">Beranda</span>
        </a>
        <a href="#" class="bottom-nav-item active" aria-current="page">
            <span class="material-symbols-outlined icon-fill" style="font-size:24px;">school</span>
            <span class="text-label-sm fw-bold mt-1">Akademik</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">account_balance_wallet</span>
            <span class="text-label-sm mt-1">Keuangan</span>
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
