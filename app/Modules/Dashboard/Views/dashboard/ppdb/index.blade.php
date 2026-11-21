@extends('layouts.app')
@section('title', 'Keuangan & Kas')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ppdb.css') }}">
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
    <div class="d-flex flex-column gap-4 px-3 px-sm-4 px-lg-5">

        <!-- Header card -->
        <div class="header-card">
            <div class="d-flex flex-column flex-md-row align-items-md-end gap-3">

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn-soft">
                        <span class="material-symbols-outlined">settings</span>
                        <span>Pengaturan Kuota</span>
                    </button>
                    <button type="button" class="btn-primary-solid">
                        <span class="material-symbols-outlined">person_add</span>
                        <span>Pendaftaran Offline</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- KPI Bento -->
        <section>
            <div class="row g-3">

                <!-- Total Pendaftar -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="kpi-card border-primary">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-md text-on-surface-variant">Total Pendaftar</span>
                            <span class="kpi-icon-box kpi-icon-primary">
                                <span class="material-symbols-outlined">groups</span>
                            </span>
                        </div>
                        <div class="mt-3">
                            <div class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">485
                            </div>
                            <div class="d-flex align-items-center gap-1 mt-1 text-body-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-secondary"
                                    style="font-size:14px;">trending_up</span>
                                <span class="text-secondary fw-bold">+18%</span> dari Gelombang 1
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lolos Verifikasi -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="kpi-card border-secondary">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-md text-on-surface-variant">Lolos Verifikasi Berkas</span>
                            <span class="kpi-icon-box kpi-icon-secondary">
                                <span class="material-symbols-outlined">verified</span>
                            </span>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">390</span>
                                <span class="text-title-sm text-secondary fw-bold">80.4%</span>
                            </div>
                            <div class="progress progress-thin mt-2">
                                <div class="progress-bar" role="progressbar" style="width:80.4%;" aria-valuenow="80.4"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Review -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="kpi-card border-tertiary">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-md text-on-surface-variant">Menunggu Review</span>
                            <span class="kpi-icon-box kpi-icon-tertiary">
                                <span class="material-symbols-outlined">pending_actions</span>
                            </span>
                        </div>
                        <div class="mt-3">
                            <div class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">65</div>
                            <div class="text-body-sm text-on-tertiary-fixed-variant fw-bold mt-1">
                                Berkas butuh tindak lanjut segera
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kuota Tersedia -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="kpi-card border-tint">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-md text-on-surface-variant">Sisa Kuota Tersedia</span>
                            <span class="kpi-icon-box kpi-icon-primary">
                                <span class="material-symbols-outlined">event_seat</span>
                            </span>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-baseline gap-1">
                                <span class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">120</span>
                                <span class="text-title-sm text-on-surface-variant">/ 360 Kursi</span>
                            </div>
                            <div class="text-body-sm text-on-surface-variant mt-1">
                                240 Calon Siswa Diterima
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Aksi Cepat -->
        <section class="card-soft p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="accent-bar-secondary"></span>
                    <h2 class="text-title-sm text-primary mb-0">Aksi Cepat Panitia PPDB</h2>
                </div>
                <span class="text-label-sm text-on-surface-variant">4 Opsi Prioritas</span>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <button type="button" class="action-tile">
                        <div class="action-icon action-icon-primary">
                            <span class="material-symbols-outlined" style="font-size:24px;">fact_check</span>
                        </div>
                        <span class="text-title-sm text-primary">Verifikasi Berkas</span>
                        <span class="text-body-sm text-on-surface-variant mt-1">65 berkas antre</span>
                    </button>
                </div>
                <div class="col-6 col-md-3">
                    <button type="button" class="action-tile">
                        <div class="action-icon action-icon-secondary">
                            <span class="material-symbols-outlined" style="font-size:24px;">campaign</span>
                        </div>
                        <span class="text-title-sm text-primary">Pengumuman Seleksi</span>
                        <span class="text-body-sm text-on-surface-variant mt-1">Rilis berkala web/portal</span>
                    </button>
                </div>
                <div class="col-6 col-md-3">
                    <button type="button" class="action-tile">
                        <div class="action-icon action-icon-high">
                            <span class="material-symbols-outlined" style="font-size:24px;">calendar_month</span>
                        </div>
                        <span class="text-title-sm text-primary">Jadwal Wawancara</span>
                        <span class="text-body-sm text-on-surface-variant mt-1">Atur slot &amp; penguji</span>
                    </button>
                </div>
                <div class="col-6 col-md-3">
                    <button type="button" class="action-tile">
                        <div class="action-icon action-icon-low">
                            <span class="material-symbols-outlined" style="font-size:24px;">file_download</span>
                        </div>
                        <span class="text-title-sm text-primary">Ekspor Data</span>
                        <span class="text-body-sm text-on-surface-variant mt-1">Format Excel / PDF</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Content Grid -->
        <div class="row g-4">

            <!-- Left: Daftar Calon Peserta -->
            <section class="col-12 col-lg-8">
                <div class="card-soft p-4 d-flex flex-column gap-3">

                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                        <div>
                            <h2 class="text-title-sm text-primary mb-0">Daftar Calon Peserta Didik Terbaru</h2>
                            <p class="text-body-sm text-on-surface-variant mb-0">Update live sinkronisasi berkas
                                dokumen online</p>
                        </div>
                        <div class="search-wrap" style="min-width:224px;">
                            <span class="material-symbols-outlined search-icon">search</span>
                            <input type="text" class="search-input" placeholder="Cari NISN / Nama..." />
                        </div>
                    </div>

                    <!-- Filter tabs -->
                    <div class="filter-tabs">
                        <button type="button" class="active">Semua (485)</button>
                        <button type="button">Jalur Prestasi (190)</button>
                        <button type="button">Jalur Zonasi (220)</button>
                        <button type="button">Jalur Afirmasi (75)</button>
                    </div>

                    <!-- Student list -->
                    <div class="d-flex flex-column gap-3 pt-1">

                        <!-- Card 1 -->
                        <div class="student-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-circle avatar-secondary">AR</div>
                                <div>
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <h3 class="text-title-sm text-primary mb-0">Alifia Rahma Azzahra</h3>
                                        <span class="badge-soft badge-success">Terverifikasi</span>
                                    </div>
                                    <div class="text-body-sm text-on-surface-variant mt-1 d-flex flex-wrap gap-3">
                                        <span>No. Reg: <strong class="text-on-surface">PPDB-2025-0891</strong></span>
                                        <span>SMP Asal: <strong class="text-on-surface">SMPN 1
                                                Surabaya</strong></span>
                                        <span class="d-inline-flex align-items-center gap-1 text-secondary fw-bold">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">workspace_premium</span>
                                            Jalur Prestasi
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2 pt-3 pt-md-0 border-top border-md-0"
                                style="border-color:var(--surface-container)!important;">
                                <button type="button" class="btn-action primary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                                    <span>Cek Berkas</span>
                                </button>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="student-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-circle avatar-high">MR</div>
                                <div>
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <h3 class="text-title-sm text-primary mb-0">M. Rizky Pratama</h3>
                                        <span class="badge-soft badge-warning">Perlu Koreksi Dokumen</span>
                                    </div>
                                    <div class="text-body-sm text-on-surface-variant mt-1 d-flex flex-wrap gap-3">
                                        <span>No. Reg: <strong class="text-on-surface">PPDB-2025-0912</strong></span>
                                        <span>SMP Asal: <strong class="text-on-surface">SMP Muhammadiyah
                                                2</strong></span>
                                        <span
                                            class="d-inline-flex align-items-center gap-1 text-primary-fixed-dim fw-bold">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">location_on</span>
                                            Jalur Zonasi
                                        </span>
                                    </div>
                                    <div class="mt-1 text-body-sm text-error d-flex align-items-center gap-1">
                                        <span class="material-symbols-outlined" style="font-size:14px;">info</span>
                                        <span>Kartu Keluarga kurang jelas / resolusi rendah</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2 pt-3 pt-md-0 border-top border-md-0"
                                style="border-color:var(--surface-container)!important;">
                                <button type="button" class="btn-action secondary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">edit_note</span>
                                    <span>Cek Berkas</span>
                                </button>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="student-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-circle avatar-low">DA</div>
                                <div>
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <h3 class="text-title-sm text-primary mb-0">Dinda Ayu Lestari</h3>
                                        <span class="badge-soft badge-neutral">Menunggu</span>
                                    </div>
                                    <div class="text-body-sm text-on-surface-variant mt-1 d-flex flex-wrap gap-3">
                                        <span>No. Reg: <strong class="text-on-surface">PPDB-2025-0955</strong></span>
                                        <span>SMP Asal: <strong class="text-on-surface">SMP Negeri
                                                4</strong></span>
                                        <span
                                            class="d-inline-flex align-items-center gap-1 text-on-secondary-fixed-variant fw-bold">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">volunteer_activism</span>
                                            Jalur Afirmasi
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2 pt-3 pt-md-0 border-top border-md-0"
                                style="border-color:var(--surface-container)!important;">
                                <button type="button" class="btn-action primary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">rate_review</span>
                                    <span>Cek Berkas</span>
                                </button>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="student-card">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-circle avatar-secondary">FN</div>
                                <div>
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <h3 class="text-title-sm text-primary mb-0">Farhan Nabiel Al-Ghifari</h3>
                                        <span class="badge-soft badge-success">Terverifikasi</span>
                                    </div>
                                    <div class="text-body-sm text-on-surface-variant mt-1 d-flex flex-wrap gap-3">
                                        <span>No. Reg: <strong class="text-on-surface">PPDB-2025-0968</strong></span>
                                        <span>SMP Asal: <strong class="text-on-surface">SMP
                                                Al-Hikmah</strong></span>
                                        <span class="d-inline-flex align-items-center gap-1 text-secondary fw-bold">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">workspace_premium</span>
                                            Jalur Prestasi
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2 pt-3 pt-md-0 border-top border-md-0"
                                style="border-color:var(--surface-container)!important;">
                                <button type="button" class="btn-action primary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                                    <span>Cek Berkas</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Pagination -->
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top"
                        style="border-color:var(--surface-container)!important;">
                        <span class="text-body-sm text-on-surface-variant">Menampilkan 4 dari 485 data</span>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="page-btn" disabled>
                                <span class="material-symbols-outlined" style="font-size:14px;">chevron_left</span>
                            </button>
                            <button type="button" class="page-btn active">1</button>
                            <button type="button" class="page-btn">2</button>
                            <button type="button" class="page-btn">3</button>
                            <button type="button" class="page-btn">
                                <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
                            </button>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Right: Jadwal & Alur -->
            <aside class="col-12 col-lg-4 d-flex flex-column gap-4">

                <!-- Jadwal Pelaksanaan -->
                <div class="card-soft p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="accent-bar-primary"></span>
                            <h2 class="text-title-sm text-primary mb-0">Jadwal Pelaksanaan PPDB</h2>
                        </div>
                        <span class="material-symbols-outlined text-primary" style="font-size:20px;">event</span>
                    </div>

                    <div class="timeline">
                        <!-- Step 1 -->
                        <div class="timeline-item">
                            <div class="timeline-dot done"></div>
                            <span class="text-label-sm text-secondary fw-bold text-uppercase"
                                style="letter-spacing:.05em;">Selesai • 10 - 20 Mei</span>
                            <h4 class="text-title-sm text-primary mt-1 mb-0">Pendaftaran Gelombang 1</h4>
                            <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Penutupan berkas &amp;
                                validasi nilai rapor.</p>
                        </div>
                        <!-- Step 2 -->
                        <div class="timeline-item">
                            <div class="timeline-dot active"></div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-label-sm text-primary fw-bold text-uppercase"
                                    style="letter-spacing:.05em;">Aktif • 22 Mei - 5 Juni</span>
                                <span class="badge-soft"
                                    style="background:var(--primary);color:var(--on-primary);border-color:transparent;font-size:10px;padding:2px 6px;">SEKARANG</span>
                            </div>
                            <h4 class="text-title-sm text-primary mt-1 mb-0">Pendaftaran Gelombang 2 &amp;
                                Verifikasi</h4>
                            <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Pemeriksaan fisik sertifikat
                                dan kartu zonasi aktif.</p>
                        </div>
                        <!-- Step 3 -->
                        <div class="timeline-item">
                            <div class="timeline-dot pending"></div>
                            <span class="text-label-sm text-on-surface-variant fw-bold text-uppercase"
                                style="letter-spacing:.05em;">8 - 12 Juni 2025</span>
                            <h4 class="text-title-sm text-on-surface mt-1 mb-0">Tes Akademik &amp; Wawancara</h4>
                            <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Sesi luring di Gedung
                                Laboratorium SMA.</p>
                        </div>
                        <!-- Step 4 -->
                        <div class="timeline-item">
                            <div class="timeline-dot pending"></div>
                            <span class="text-label-sm text-on-surface-variant fw-bold text-uppercase"
                                style="letter-spacing:.05em;">18 Juni 2025</span>
                            <h4 class="text-title-sm text-on-surface mt-1 mb-0">Pengumuman Final Hasil Seleksi</h4>
                            <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Publikasi serentak melalui
                                portal pendaftaran.</p>
                        </div>
                    </div>
                </div>

                <!-- Alur Tahapan Seleksi -->
                <div class="card-soft p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="accent-bar-secondary-container"></span>
                            <h2 class="text-title-sm text-primary mb-0">Alur Tahapan Seleksi</h2>
                        </div>
                        <span class="material-symbols-outlined text-secondary" style="font-size:20px;">account_tree</span>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <!-- Step 1 -->
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3"
                            style="background:var(--surface-container-low);border:1px solid var(--surface-container);">
                            <div class="step-num neutral">1</div>
                            <div>
                                <h4 class="text-title-sm text-primary mb-0">Submit Berkas Digital</h4>
                                <p class="text-body-sm text-on-surface-variant mb-0">Calon siswa mengunggah KK,
                                    NISN, Akta, dan Piagam Prestasi.</p>
                            </div>
                        </div>
                        <!-- Step 2 -->
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3"
                            style="background:var(--surface-container-low);border:1px solid var(--surface-container);">
                            <div class="step-num secondary">2</div>
                            <div>
                                <h4 class="text-title-sm text-primary mb-0">Verifikasi Panitia</h4>
                                <p class="text-body-sm text-on-surface-variant mb-0">Pengecekan keaslian data dan
                                    jarak koordinat tempat tinggal.</p>
                            </div>
                        </div>
                        <!-- Step 3 -->
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3"
                            style="background:var(--surface-container-low);border:1px solid var(--surface-container);">
                            <div class="step-num neutral">3</div>
                            <div>
                                <h4 class="text-title-sm text-primary mb-0">Wawancara &amp; Uji Potensi</h4>
                                <p class="text-body-sm text-on-surface-variant mb-0">Wawancara minat bakat serta
                                    verifikasi wali murid.</p>
                            </div>
                        </div>
                        <!-- Step 4 -->
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3"
                            style="background:var(--surface-container-low);border:1px solid var(--surface-container);">
                            <div class="step-num primary">4</div>
                            <div>
                                <h4 class="text-title-sm text-primary mb-0">Daftar Ulang &amp; Orientasi</h4>
                                <p class="text-body-sm text-on-surface-variant mb-0">Konfirmasi penerimaan dan
                                    pengisian biodata induk siswa.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between text-body-sm text-on-surface-variant"
                        style="border-color:var(--surface-container)!important;">
                        <span>Panduan Teknis Juknis 2025</span>
                        <a href="#" class="link-secondary fw-bold">
                            <span>Unduh PDF</span>
                            <span class="material-symbols-outlined" style="font-size:14px;">download</span>
                        </a>
                    </div>
                </div>

            </aside>
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
