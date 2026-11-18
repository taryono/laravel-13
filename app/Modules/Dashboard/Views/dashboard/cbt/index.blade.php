@extends('layouts.app')
@section('title', 'CBT & Ujian Online')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cbt.css') }}">
@endpush
@section('content')
    @include('Dashboard::partials.ep_subnav', [
        'title' => 'Modul Ujian CBT',
        'badge' => '2 Sesi Aktif',
        'subtitle' => 'Manajemen Ujian Berbasis Komputer, Peserta & Status Server',
        'metaIcon' => 'groups',
        'metaLabel' => 'Peserta',
        'metaValue' => '284 Siswa',
    ])
    <!-- KPI Bento -->
    <section class="row g-2">
        <!-- Ujian Berlangsung -->
        <div class="col-6">
            <div class="card-soft metric-card p-3 h-100">
                <div class="metric-bg-icon">
                    <span class="material-symbols-outlined">alarm</span>
                </div>
                <div class="metric-icon-box">
                    <span class="material-symbols-outlined" style="font-size:16px;">play_circle</span>
                </div>
                <p class="text-label-sm text-on-surface-variant mb-0">Ujian Berlangsung</p>
                <div class="d-flex align-items-baseline gap-1 mt-1">
                    <span class="text-headline-lg-mobile text-primary fw-bold">2</span>
                    <span class="text-label-md text-secondary">Sesi Aktif</span>
                </div>
            </div>
        </div>
        <!-- Total Peserta -->
        <div class="col-6">
            <div class="card-soft metric-card p-3 h-100">
                <div class="metric-bg-icon">
                    <span class="material-symbols-outlined">groups</span>
                </div>
                <div class="metric-icon-box">
                    <span class="material-symbols-outlined" style="font-size:16px;">groups</span>
                </div>
                <p class="text-label-sm text-on-surface-variant mb-0">Total Peserta Ujian</p>
                <div class="d-flex align-items-baseline gap-1 mt-1">
                    <span class="text-headline-lg-mobile text-primary fw-bold">284</span>
                    <span class="text-body-sm text-on-surface-variant">Siswa</span>
                </div>
            </div>
        </div>
        <!-- Server Status -->
        <div class="col-12">
            <div class="server-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="server-icon">
                        <span class="material-symbols-outlined" style="font-size:18px;">dns</span>
                    </div>
                    <div>
                        <p class="text-label-md text-on-surface d-flex align-items-center gap-1 mb-0">
                            Server CBT Utama Online
                            <span class="d-inline-block rounded-circle"
                                style="width:6px;height:6px;background:var(--secondary);"></span>
                        </p>
                        <p class="text-body-sm text-on-surface-variant mb-0">Koneksi Stabil &amp; Terenkripsi
                        </p>
                    </div>
                </div>
                <div class="text-end">
                    <span class="latency-badge">
                        <span class="material-symbols-outlined" style="font-size:12px;">speed</span>
                        14ms Latency
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="quick-actions">
        <button type="button" class="action-btn primary">
            <span class="material-symbols-outlined" style="font-size:16px;">add_circle</span>
            Buat Ujian Baru
        </button>
        <button type="button" class="action-btn outline">
            <span class="material-symbols-outlined text-secondary" style="font-size:16px;">key</span>
            Token Ujian Harian
        </button>
        <button type="button" class="action-btn outline">
            <span class="material-symbols-outlined" style="font-size:16px;">upload_file</span>
            Unggah Soal Excel
        </button>
    </section>

    <!-- Segmented Tab Filter -->
    <nav class="segmented" aria-label="Kategori CBT">
        <button type="button" class="active">Sedang Berlangsung</button>
        <button type="button">Jadwal Mendatang</button>
        <button type="button">Bank Soal</button>
        <button type="button">Rekap Nilai</button>
    </nav>

    <!-- Active Monitor Card -->
    <section class="d-flex flex-column gap-2">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="text-title-sm text-primary d-flex align-items-center gap-1 mb-0">
                <span class="material-symbols-outlined text-secondary" style="font-size:18px;">radar</span>
                Sesi Sedang Berlangsung
            </h2>
            <span class="text-body-sm text-on-surface-variant">Update: 09:42 WIB</span>
        </div>

        <article class="card-soft border-left-secondary p-3 d-flex flex-column gap-3">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div class="flex-grow-1">
                    <span class="badge-running">
                        <span class="ping-dot"></span>
                        Berlangsung
                    </span>
                    <h3 class="text-title-sm text-on-surface mt-2 mb-0">
                        Simulasi Ujian Matematika Peminatan XII MIPA
                    </h3>
                </div>
                <span class="code-badge">CBT-MIPA-12</span>
            </div>

            <!-- Metadata -->
            <div class="meta-box">
                <div class="meta-item">
                    <span class="material-symbols-outlined text-outline" style="font-size:14px;">schedule</span>
                    <span>08:00 - 10:30 WIB</span>
                </div>
                <div class="meta-item">
                    <span class="material-symbols-outlined text-outline" style="font-size:14px;">person_check</span>
                    <span>72 Peserta Terdaftar</span>
                </div>
                <div class="meta-item full">
                    <span class="material-symbols-outlined text-outline" style="font-size:14px;">meeting_room</span>
                    <span>Lab Multimedia 1 &amp; Lab Multimedia 2</span>
                </div>
            </div>

            <!-- Progress -->
            <div class="d-flex flex-column gap-1">
                <div class="d-flex align-items-center justify-content-between text-label-sm">
                    <span class="text-on-surface-variant">Progress Pengerjaan Peserta</span>
                    <span class="text-secondary fw-bold">84% Selesai</span>
                </div>
                <div class="progress progress-thin">
                    <div class="progress-bar" role="progressbar" style="width: 84%;" aria-valuenow="84" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <div class="d-flex align-items-center justify-content-between text-body-sm text-outline pt-1">
                    <span>61 Selesai / Meninjau</span>
                    <span class="text-on-secondary-container">11 Sedang Menjawab</span>
                </div>
            </div>

            <!-- CTA -->
            <div class="pt-1">
                <button type="button" class="cta-primary">
                    <span class="material-symbols-outlined" style="font-size:18px;">visibility</span>
                    Pantau Live Proctor / Monitoring Peserta
                </button>
            </div>
        </article>
    </section>

    <!-- Upcoming Exams -->
    <section class="d-flex flex-column gap-3">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="text-title-sm text-primary d-flex align-items-center gap-1 mb-0">
                <span class="material-symbols-outlined"
                    style="color:var(--primary-container);font-size:18px;">event_note</span>
                Jadwal Ujian Mendatang
            </h2>
            <button type="button" class="btn btn-link p-0 text-label-md text-primary text-decoration-none">Lihat
                Kalender</button>
        </div>

        <!-- Upcoming Card 1 -->
        <article class="card-soft upcoming-card p-3 d-flex flex-column gap-2">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <span class="date-badge">Besok, 25 Maret 2025</span>
                    <h3 class="text-title-sm text-on-surface mt-2 mb-0">
                        Penilaian Tengah Semester (PTS) Bahasa Inggris XI
                    </h3>
                </div>
                <button type="button" class="more-btn" aria-label="Opsi Ujian">
                    <span class="material-symbols-outlined" style="font-size:16px;">more_vert</span>
                </button>
            </div>
            <div class="d-flex align-items-center gap-3 text-body-sm text-on-surface-variant pt-2 border-top"
                style="border-color:var(--surface-container)!important;">
                <div class="d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined text-outline" style="font-size:14px;">schedule</span>
                    <span>08:00 WIB</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined text-outline" style="font-size:14px;">group</span>
                    <span>140 Peserta (4 Kelas)</span>
                </div>
                <div class="ms-auto">
                    <span class="text-label-sm text-secondary">Token Siap</span>
                </div>
            </div>
        </article>

        <!-- Upcoming Card 2 -->
        <article class="card-soft upcoming-card p-3 d-flex flex-column gap-2">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <span class="date-badge">Rabu, 26 Maret 2025</span>
                    <h3 class="text-title-sm text-on-surface mt-2 mb-0">
                        Kuis Harian Fisika Gelombang Magnetik X MIPA
                    </h3>
                </div>
                <button type="button" class="more-btn" aria-label="Opsi Ujian">
                    <span class="material-symbols-outlined" style="font-size:16px;">more_vert</span>
                </button>
            </div>
            <div class="d-flex align-items-center gap-3 text-body-sm text-on-surface-variant pt-2 border-top"
                style="border-color:var(--surface-container)!important;">
                <div class="d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined text-outline" style="font-size:14px;">schedule</span>
                    <span>10:00 WIB</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined text-outline" style="font-size:14px;">group</span>
                    <span>72 Peserta (2 Kelas)</span>
                </div>
                <div class="ms-auto">
                    <span class="text-label-sm text-outline">Draf Soal Selesai</span>
                </div>
            </div>
        </article>
    </section>

    <!-- Security Banner -->
    <section class="security-banner">
        <div class="security-icon">
            <span class="material-symbols-outlined" style="font-size:18px;">security</span>
        </div>
        <div class="flex-grow-1">
            <p class="text-label-md text-primary mb-0">Proctoring AI &amp; Lock-down Browser Aktif</p>
            <p class="text-body-sm text-on-surface-variant mb-0">Deteksi tab switching dan gawai sekunder
                berjalan otomatis.</p>
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
