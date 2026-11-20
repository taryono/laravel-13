@extends('layouts.app')
@section('title', 'Shuttle Service & Transportasi')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/shuttle-service.css') }}">
@endpush
@section('content')
<div class="shuttle-service">
    <!-- Sub navigation -->
    @include('Dashboard::partials.ep_subnav', [
        'title' => 'Modul Antar Jemput',
        'badge' => '4 Rute Armada Aktif',
        'subtitle' => 'Sistem Monitoring Transportasi Siswa & Operasional Bus Harian',
        'metaIcon' => 'schedule',
        'metaLabel' => 'Sesi Sore',
        'metaValue' => '14:30 WIB',
    ])
    <!-- KPI -->
    <section aria-label="KPI Real-Time Transportasi" class="mb-4">
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="ep-card ep-kpi ep-accent h-100" style="--accent:var(--ep-sec)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="t-label-sm text-uppercase text-ep-outline" style="letter-spacing:.05em">Armada
                            Bus</span>
                        <div class="ep-box ep-b36 rounded-3 bg-ep-sec10 text-ep-sec"><span
                                class="material-symbols-outlined ms-20">directions_bus</span></div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="t-hl-lg text-ep-primary">4 / 4</span>
                        <span class="t-label-md text-ep-sec fw-bold">Unit Aktif</span>
                    </div>
                    <p class="t-body-sm text-ep-var mt-2 mb-0 d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-14 text-ep-sec">check_circle</span>100% Siap
                        Beroperasi
                    </p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="ep-card ep-kpi ep-accent h-100" style="--accent:var(--ep-pc)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="t-label-sm text-uppercase text-ep-outline" style="letter-spacing:.05em">Total
                            Siswa</span>
                        <div class="ep-box ep-b36 rounded-3 bg-ep-pf text-ep-primary"><span
                                class="material-symbols-outlined ms-20">badge</span></div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="t-hl-lg text-ep-primary">168</span>
                        <span class="t-label-md text-ep-var fw-bold">Siswa</span>
                    </div>
                    <p class="t-body-sm text-ep-var mt-2 mb-0 d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-14 text-ep-primary">group</span>Terdaftar di 4
                        Koridor Rute
                    </p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="ep-card ep-kpi ep-accent h-100" style="--accent:var(--ep-sec)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="t-label-sm text-uppercase text-ep-outline" style="letter-spacing:.05em">Penjemputan
                            Pagi</span>
                        <div class="ep-box ep-b36 rounded-3 bg-ep-sec15 text-ep-sec"><span
                                class="material-symbols-outlined ms-20">task_alt</span></div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="t-hl-lg text-ep-sec">100%</span>
                        <span class="t-label-md text-ep-sec fw-bold">Tuntas</span>
                    </div>
                    <p class="t-body-sm text-ep-var mt-2 mb-0 d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-14 text-ep-sec">verified</span>Semua tiba aman di
                        sekolah
                    </p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="ep-card ep-kpi ep-accent h-100" style="--accent:var(--ep-tfd)">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="t-label-sm text-uppercase text-ep-outline" style="letter-spacing:.05em">Keberangkatan
                            Sore</span>
                        <div class="ep-box ep-b36 rounded-3 bg-ep-tf text-ep-tc"><span
                                class="material-symbols-outlined ms-20">local_parking</span></div>
                    </div>
                    <span class="t-hl-md text-ep-primary">Parkir Timur</span>
                    <p class="t-body-sm text-ep-var mt-2 mb-0 d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-14 text-ep-otc">alarm</span>Standby (Jadwal 14:30
                        WIB)
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Aksi Cepat -->
    <section aria-label="Aksi Cepat Operasional" class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h3 class="m-0 t-title-sm text-ep-primary d-flex align-items-center gap-2">
                <span class="material-symbols-outlined ms-20 text-ep-sec">bolt</span>Aksi Cepat Dispatcher &amp;
                Guru Piket
            </h3>
            <span class="t-label-sm text-ep-outline">Pintasan Darurat &amp; Notifikasi</span>
        </div>
        <div class="row row-cols-2 row-cols-md-4 g-3">
            <div class="col"><button class="ep-action ep-press shadow-sm d-flex flex-column align-items-start">
                    <div class="ep-box ep-b40 rounded-3 bg-ep-pc text-white mb-3"><span
                            class="material-symbols-outlined ms-20">my_location</span></div>
                    <span class="t-title-sm text-ep-primary">Pantau GPS</span><span class="t-body-sm text-ep-var">Live
                        Tracking Rute</span>
                </button></div>
            <div class="col"><button class="ep-action ep-press shadow-sm d-flex flex-column align-items-start">
                    <div class="ep-box ep-b40 rounded-3 bg-ep-sec text-white mb-3"><span
                            class="material-symbols-outlined ms-20">campaign</span></div>
                    <span class="t-title-sm text-ep-primary">Broadcast Info</span><span class="t-body-sm text-ep-var">Kirim
                        ke Orang Tua</span>
                </button></div>
            <div class="col"><button class="ep-action danger ep-press shadow-sm d-flex flex-column align-items-start">
                    <div class="ep-box ep-b40 rounded-3 bg-ep-errc text-ep-err mb-3"><span
                            class="material-symbols-outlined ms-20">alt_route</span></div>
                    <span class="t-title-sm text-ep-primary">Rute Darurat</span><span
                        class="t-body-sm text-ep-var">Penyesuaian Macet</span>
                </button></div>
            <div class="col"><button class="ep-action ep-press shadow-sm d-flex flex-column align-items-start">
                    <div class="ep-box ep-b40 rounded-3 bg-ep-highest text-ep-primary mb-3"><span
                            class="material-symbols-outlined ms-20">checklist</span></div>
                    <span class="t-title-sm text-ep-primary">Log Checklist</span><span
                        class="t-body-sm text-ep-var">Inspeksi Harian</span>
                </button></div>
        </div>
    </section>

    <div class="row g-4 align-items-start">

        <!-- Daftar Rute -->
        <section aria-label="Daftar Rute Bus Sekolah" class="col-12 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined ms-22 text-ep-primary">route</span>
                    <h3 class="m-0 t-hl-md text-ep-primary">Daftar Rute Bus Sekolah</h3>
                </div>
                <span class="t-body-sm text-ep-var">Update: 14:15 WIB</span>
            </div>

            <div class="d-flex flex-column gap-3">

                <!-- Rute 1 -->
                <article class="ep-card ep-accent ep-route">
                    <div class="head d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="px-2 py-1 rounded-2 bg-ep-pc text-white t-label-sm">Rute 01</span>
                                <h4 class="m-0 t-title-sm text-ep-primary">Rute Timur - Buah Batu &amp;
                                    Kiaracondong</h4>
                            </div>
                            <div class="d-flex align-items-center gap-3 t-body-sm text-ep-var mt-1">
                                <span class="d-flex align-items-center gap-1"><span
                                        class="material-symbols-outlined ms-15 text-ep-outline">person</span>Driver:
                                    <strong>Pak Asep</strong></span>
                                <span>•</span>
                                <span class="d-flex align-items-center gap-1 font-monospace fw-bold text-ep-on"><span
                                        class="material-symbols-outlined ms-15 text-ep-outline">pin</span>D 1928
                                    AB</span>
                            </div>
                        </div>
                        <div class="align-self-start align-self-sm-auto">
                            <span class="ep-pill ep-pill-sec px-3 py-1 bg-ep-sec10 text-ep-sec t-label-md"><span
                                    class="ep-dot bg-ep-sec"></span>Siap
                                Jalan Pukul 14:30</span>
                        </div>
                    </div>
                    <div class="pt-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="flex-fill" style="min-width:200px">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="t-label-sm text-ep-var">Kapasitas Kursi Siswa</span>
                                <span class="t-label-md fw-bold text-ep-primary">42 / 42 Siswa (Penuh)</span>
                            </div>
                            <div class="progress ep-progress">
                                <div class="progress-bar" style="width:100%"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="ep-btn ep-btn-low ep-press t-label-md"><span
                                    class="material-symbols-outlined ms-16">fact_check</span>Presensi Bus</button>
                            <button class="ep-btn ep-btn-pc ep-press t-label-md"><span
                                    class="material-symbols-outlined ms-16">navigation</span>Live Track</button>
                        </div>
                    </div>
                </article>

                <!-- Rute 2 -->
                <article class="ep-card ep-accent ep-route">
                    <div class="head d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="px-2 py-1 rounded-2 bg-ep-pc text-white t-label-sm">Rute 02</span>
                                <h4 class="m-0 t-title-sm text-ep-primary">Rute Barat - Cimahi &amp; Pasteur</h4>
                            </div>
                            <div class="d-flex align-items-center gap-3 t-body-sm text-ep-var mt-1">
                                <span class="d-flex align-items-center gap-1"><span
                                        class="material-symbols-outlined ms-15 text-ep-outline">person</span>Driver:
                                    <strong>Pak Joko</strong></span>
                                <span>•</span>
                                <span class="d-flex align-items-center gap-1 font-monospace fw-bold text-ep-on"><span
                                        class="material-symbols-outlined ms-15 text-ep-outline">pin</span>D 1844
                                    ZL</span>
                            </div>
                        </div>
                        <div class="align-self-start align-self-sm-auto">
                            <span class="ep-pill px-3 py-1 bg-ep-cont text-ep-primary t-label-md"><span
                                    class="ep-dot bg-ep-pfd"></span>On Standby</span>
                        </div>
                    </div>
                    <div class="pt-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="flex-fill" style="min-width:200px">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="t-label-sm text-ep-var">Kapasitas Kursi Siswa</span>
                                <span class="t-label-md fw-bold text-ep-primary">38 / 40 Siswa</span>
                            </div>
                            <div class="progress ep-progress">
                                <div class="progress-bar" style="width:95%"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="ep-btn ep-btn-low ep-press t-label-md"><span
                                    class="material-symbols-outlined ms-16">fact_check</span>Presensi Bus</button>
                            <button class="ep-btn ep-btn-pc ep-press t-label-md"><span
                                    class="material-symbols-outlined ms-16">navigation</span>Live Track</button>
                        </div>
                    </div>
                </article>

                <!-- Rute 3 -->
                <article class="ep-card ep-accent ep-route">
                    <div class="head d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="px-2 py-1 rounded-2 bg-ep-pc text-white t-label-sm">Rute 03</span>
                                <h4 class="m-0 t-title-sm text-ep-primary">Rute Selatan - Dago &amp; Setiabudhi
                                </h4>
                            </div>
                            <div class="d-flex align-items-center gap-3 t-body-sm text-ep-var mt-1">
                                <span class="d-flex align-items-center gap-1"><span
                                        class="material-symbols-outlined ms-15 text-ep-outline">person</span>Driver:
                                    <strong>Pak Dadang</strong></span>
                                <span>•</span>
                                <span class="d-flex align-items-center gap-1 font-monospace fw-bold text-ep-on"><span
                                        class="material-symbols-outlined ms-15 text-ep-outline">pin</span>D 1901
                                    BC</span>
                            </div>
                        </div>
                        <div class="align-self-start align-self-sm-auto">
                            <span class="ep-pill ep-pill-sec px-3 py-1 bg-ep-sec10 text-ep-sec t-label-md"><span
                                    class="ep-dot bg-ep-sec"></span>Siap
                                Jalan Pukul 14:30</span>
                        </div>
                    </div>
                    <div class="pt-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="flex-fill" style="min-width:200px">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="t-label-sm text-ep-var">Kapasitas Kursi Siswa</span>
                                <span class="t-label-md fw-bold text-ep-primary">40 / 40 Siswa (Penuh)</span>
                            </div>
                            <div class="progress ep-progress">
                                <div class="progress-bar" style="width:100%"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="ep-btn ep-btn-low ep-press t-label-md"><span
                                    class="material-symbols-outlined ms-16">fact_check</span>Presensi Bus</button>
                            <button class="ep-btn ep-btn-pc ep-press t-label-md"><span
                                    class="material-symbols-outlined ms-16">navigation</span>Live Track</button>
                        </div>
                    </div>
                </article>

                <!-- Rute 4 -->
                <article class="ep-card ep-accent ep-route">
                    <div class="head d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="px-2 py-1 rounded-2 bg-ep-pc text-white t-label-sm">Rute 04</span>
                                <h4 class="m-0 t-title-sm text-ep-primary">Rute Pusat Kota (Asia Afrika - Merdeka)
                                </h4>
                            </div>
                            <div class="d-flex align-items-center gap-3 t-body-sm text-ep-var mt-1">
                                <span class="d-flex align-items-center gap-1"><span
                                        class="material-symbols-outlined ms-15 text-ep-outline">person</span>Driver:
                                    <strong>Pak Hendra</strong></span>
                                <span>•</span>
                                <span class="d-flex align-items-center gap-1 font-monospace fw-bold text-ep-on"><span
                                        class="material-symbols-outlined ms-15 text-ep-outline">pin</span>D 1992
                                    XY</span>
                            </div>
                        </div>
                        <div class="align-self-start align-self-sm-auto">
                            <span class="ep-pill px-3 py-1 bg-ep-cont text-ep-primary t-label-md"><span
                                    class="ep-dot bg-ep-pfd"></span>On Standby</span>
                        </div>
                    </div>
                    <div class="pt-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="flex-fill" style="min-width:200px">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="t-label-sm text-ep-var">Kapasitas Kursi Siswa</span>
                                <span class="t-label-md fw-bold text-ep-primary">35 / 40 Siswa</span>
                            </div>
                            <div class="progress ep-progress">
                                <div class="progress-bar" style="width:87.5%"></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="ep-btn ep-btn-low ep-press t-label-md"><span
                                    class="material-symbols-outlined ms-16">fact_check</span>Presensi Bus</button>
                            <button class="ep-btn ep-btn-pc ep-press t-label-md"><span
                                    class="material-symbols-outlined ms-16">navigation</span>Live Track</button>
                        </div>
                    </div>
                </article>

            </div>
        </section>

        <!-- Sidebar Keamanan -->
        <aside aria-label="Status Keamanan dan Inspeksi Kendaraan" class="col-12 col-lg-4 d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined ms-22 text-ep-sec">verified_user</span>
                    <h3 class="m-0 t-title-sm text-ep-primary">Status Keamanan &amp; Inspeksi</h3>
                </div>
                <span class="t-label-sm text-ep-sec">Terverifikasi</span>
            </div>

            <div class="ep-card d-flex flex-column gap-3">
                <div class="d-flex align-items-center justify-content-between pb-3"
                    style="border-bottom:1px solid var(--ep-cont)">
                    <div>
                        <p class="m-0 t-label-md fw-bold text-ep-primary">Inspeksi Pra-Keberangkatan</p>
                        <p class="m-0 t-body-sm text-ep-var">Pemeriksaan teknis oleh Koordinator Sarpras</p>
                    </div>
                    <div class="ep-box ep-b32 rounded-circle bg-ep-sec15 text-ep-sec"><span
                            class="material-symbols-outlined ms-18">build</span></div>
                </div>

                <div class="ep-check d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="ep-box ep-b32 rounded-2 bg-ep-lowest text-ep-primary"><span
                                class="material-symbols-outlined ms-18">toys</span></div>
                        <div>
                            <p class="m-0 t-label-md fw-bold text-ep-on">Sistem Pengereman</p>
                            <p class="m-0 t-body-sm text-ep-outline">Tekanan oli &amp; kampas rem</p>
                        </div>
                    </div>
                    <span class="ep-pill bg-ep-sec10 text-ep-sec t-label-sm"><span
                            class="material-symbols-outlined ms-14">check</span>Aman</span>
                </div>

                <div class="ep-check d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="ep-box ep-b32 rounded-2 bg-ep-lowest text-ep-primary"><span
                                class="material-symbols-outlined ms-18">tire_repair</span></div>
                        <div>
                            <p class="m-0 t-label-md fw-bold text-ep-on">Kondisi Ban &amp; Tekanan</p>
                            <p class="m-0 t-body-sm text-ep-outline">Tekanan 42 psi seragam</p>
                        </div>
                    </div>
                    <span class="ep-pill bg-ep-sec10 text-ep-sec t-label-sm"><span
                            class="material-symbols-outlined ms-14">check</span>Aman</span>
                </div>

                <div class="ep-check d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="ep-box ep-b32 rounded-2 bg-ep-lowest text-ep-primary"><span
                                class="material-symbols-outlined ms-18">local_gas_station</span></div>
                        <div>
                            <p class="m-0 t-label-md fw-bold text-ep-on">BBM &amp; Pelumas Mesin</p>
                            <p class="m-0 t-body-sm text-ep-outline">Tangki &gt; 80% per unit</p>
                        </div>
                    </div>
                    <span class="ep-pill bg-ep-sec10 text-ep-sec t-label-sm"><span
                            class="material-symbols-outlined ms-14">check</span>Aman</span>
                </div>

                <div class="ep-check d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="ep-box ep-b32 rounded-2 bg-ep-lowest text-ep-primary"><span
                                class="material-symbols-outlined ms-18">medical_services</span></div>
                        <div>
                            <p class="m-0 t-label-md fw-bold text-ep-on">Kotak P3K &amp; APAR</p>
                            <p class="m-0 t-body-sm text-ep-outline">Lengkap &amp; masa aktif valid</p>
                        </div>
                    </div>
                    <span class="ep-pill bg-ep-sec10 text-ep-sec t-label-sm"><span
                            class="material-symbols-outlined ms-14">check</span>Aman</span>
                </div>

                <div class="pt-3 d-flex align-items-center justify-content-between t-body-sm text-ep-outline"
                    style="border-top:1px solid var(--ep-cont)">
                    <span>Petugas: Bambang S. (Sarpras)</span>
                    <span class="font-monospace t-label-sm text-ep-on">13:50 WIB</span>
                </div>
            </div>

            <!-- Kontak Darurat -->
            <div class="ep-emergency-box d-flex align-items-center gap-3 p-3 rounded-4">
                <div class="ep-box ep-b40 rounded-3 bg-ep-pc text-white"><span
                        class="material-symbols-outlined ms-20">phone_in_talk</span></div>
                <div>
                    <h4 class="m-0 t-label-md fw-bold text-ep-primary">Kontak Darurat Transportasi</h4>
                    <p class="m-0 t-body-sm text-ep-var">Posko Dispatcher: 0812-3456-7890</p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@section('bottom_navigation')
    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ route('dashboard.back-to-home') }}" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">dashboard</span>
            <span class="text-label-sm mt-1">Beranda</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">school</span>
            <span class="text-label-sm mt-1">Akademik</span>
        </a>
        <a href="#" class="bottom-nav-item active">
            <span class="material-symbols-outlined icon-fill" style="font-size:24px;">directions_bus</span>
            <span class="text-label-sm fw-bold mt-1">Antar Jemput</span>
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
