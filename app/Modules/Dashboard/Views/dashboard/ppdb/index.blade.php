@extends('layouts.app')
@section('title', 'PPDB_Penerimaan Peserta Didik Baru')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/ppdb.css') }}">
@endpush
@section('content')
    @include('Dashboard::partials.ep_subnav', [
        'title' => 'Penerimaan Peserta Didik Baru (PPDB)',
        'badge' => $activeAdmission ? $activeAdmission->name : 'PPDB 2026/2027 Aktif',
        'subtitle' => 'Panel Manajemen Pendaftaran, Verifikasi Berkas & Seleksi Calon Siswa Baru',
        'metaIcon' => 'how_to_reg',
        'metaLabel' => 'Periode',
        'metaValue' => $activeAdmission ? $activeAdmission->code : '2026/2027',
    ])
    <div class="d-flex flex-column gap-4 px-3 px-sm-4 px-lg-5">

        <!-- Header card -->
        <div class="header-card">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
                <div>
                    <h1 class="text-headline-md text-primary mb-1">
                        {{ $activeAdmission->name ?? 'Portal Penerimaan Peserta Didik Baru' }}
                    </h1>
                    <p class="text-body-sm text-on-surface-variant mb-0">
                        Pantau seluruh progres pendaftaran, verifikasi dokumen, dan pengaturan kuota penerimaan.
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ Route::has('admission_quota_setting.index') ? route('admission_quota_setting.index') : (Route::has('admission.index') ? route('admission.index') : '#') }}"
                        class="btn-soft text-decoration-none">
                        <span class="material-symbols-outlined">settings</span>
                        <span>Pengaturan Kuota</span>
                    </a>
                    <a href="{{ Route::has('applicant.create') ? route('applicant.create') : (Route::has('ppdb-admin.applicants.create') ? route('ppdb-admin.applicants.create') : '#') }}"
                        class="btn-primary-solid text-decoration-none">
                        <span class="material-symbols-outlined">person_add</span>
                        <span>Pendaftaran Offline</span>
                    </a>
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
                            <div class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">
                                {{ $totalApplicants }}
                            </div>
                            <div class="d-flex align-items-center gap-1 mt-1 text-body-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-secondary"
                                    style="font-size:14px;">trending_up</span>
                                <span class="text-secondary fw-bold">{{ $totalApplicants }} Pendaftar</span> terdata di
                                sistem
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
                                <span class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">
                                    {{ $verifiedApplicants }}
                                </span>
                                <span class="text-title-sm text-secondary fw-bold">{{ $verifiedPercentage }}%</span>
                            </div>
                            <div class="progress progress-thin mt-2">
                                <div class="progress-bar" role="progressbar" style="width:{{ $verifiedPercentage }}%;"
                                    aria-valuenow="{{ $verifiedPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                            <div class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">
                                {{ $pendingVerificationsCount }}
                            </div>
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
                                <span class="text-display-lg-mobile text-primary" style="letter-spacing:-.02em;">
                                    {{ $remainingQuota }}
                                </span>
                                <span class="text-title-sm text-on-surface-variant">/ {{ $totalQuota }} Kursi</span>
                            </div>
                            <div class="text-body-sm text-on-surface-variant mt-1">
                                {{ $acceptedCount }} Calon Siswa Diterima
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
                <span class="text-label-sm text-on-surface-variant">4 Modul Terintegrasi</span>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <a href="{{ Route::has('admission_document_verification.index') ? route('admission_document_verification.index') : (Route::has('application_document.index') ? route('application_document.index') : '#') }}"
                        class="action-tile text-decoration-none d-flex flex-column text-start">
                        <div class="action-icon action-icon-primary">
                            <span class="material-symbols-outlined" style="font-size:24px;">fact_check</span>
                        </div>
                        <span class="text-title-sm text-primary">Verifikasi Berkas</span>
                        <span class="text-body-sm text-on-surface-variant mt-1">{{ $pendingVerificationsCount }} berkas
                            antre</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ Route::has('admission_announcement.index') ? route('admission_announcement.index') : '#' }}"
                        class="action-tile text-decoration-none d-flex flex-column text-start">
                        <div class="action-icon action-icon-secondary">
                            <span class="material-symbols-outlined" style="font-size:24px;">campaign</span>
                        </div>
                        <span class="text-title-sm text-primary">Pengumuman Seleksi</span>
                        <span class="text-body-sm text-on-surface-variant mt-1">Rilis berkala web/portal</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ Route::has('admission_interview.index') ? route('admission_interview.index') : (Route::has('admission_test_schedule.index') ? route('admission_test_schedule.index') : '#') }}"
                        class="action-tile text-decoration-none d-flex flex-column text-start">
                        <div class="action-icon action-icon-high">
                            <span class="material-symbols-outlined" style="font-size:24px;">calendar_month</span>
                        </div>
                        <span class="text-title-sm text-primary">Jadwal Wawancara</span>
                        <span class="text-body-sm text-on-surface-variant mt-1">Atur slot &amp; penguji</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ Route::has('applicant.index') ? route('applicant.index') : '#' }}"
                        class="action-tile text-decoration-none d-flex flex-column text-start">
                        <div class="action-icon action-icon-low">
                            <span class="material-symbols-outlined" style="font-size:24px;">file_download</span>
                        </div>
                        <span class="text-title-sm text-primary">Data Pendaftar</span>
                        <span class="text-body-sm text-on-surface-variant mt-1">Format Excel / PDF / Data</span>
                    </a>
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
                            <p class="text-body-sm text-on-surface-variant mb-0">Update live sinkronisasi berkas dokumen
                                online</p>
                        </div>
                        <form method="GET" action="{{ url('/dashboard') }}" class="search-wrap"
                            style="min-width:224px;">
                            @if (request()->has('path'))
                                <input type="hidden" name="path" value="{{ request('path') }}">
                            @endif
                            <span class="material-symbols-outlined search-icon">search</span>
                            <input type="text" name="search" value="{{ $searchQuery }}" class="search-input"
                                placeholder="Cari NISN / Nama / No. Reg..." />
                        </form>
                    </div>

                    <!-- Filter tabs -->
                    <div class="filter-tabs">
                        <a href="{{ url('/dashboard' . (request('search') ? '?search=' . urlencode(request('search')) : '')) }}"
                            class="text-decoration-none {{ empty($selectedPath) || $selectedPath === 'all' ? 'active' : '' }}">
                            Semua ({{ $totalApplicants }})
                        </a>
                        @foreach ($paths as $path)
                            <a href="{{ url('/dashboard?path=' . $path->code . (request('search') ? '&search=' . urlencode(request('search')) : '')) }}"
                                class="text-decoration-none {{ $selectedPath == $path->code ? 'active' : '' }}">
                                {{ $path->name }} ({{ $pathCounts[$path->id] ?? 0 }})
                            </a>
                        @endforeach
                    </div>

                    <!-- Student list -->
                    <div class="d-flex flex-column gap-3 pt-1">
                        @forelse ($applicants as $appItem)
                            @php
                                $status = strtoupper($appItem->status ?? 'REGISTERED');
                                $avatarLetters = strtoupper(substr(trim($appItem->name), 0, 2));
                                $pathName = $appItem->path_name ?? 'Jalur Reguler';
                                $pathCode = strtoupper($appItem->path_code ?? 'REGULER');

                                $badgeClass = match ($status) {
                                    'VERIFIED', 'ACCEPTED' => 'badge-soft badge-success',
                                    'REJECTED', 'FAILED' => 'badge-soft badge-warning',
                                    'DRAFT' => 'badge-soft badge-neutral',
                                    default => 'badge-soft badge-neutral',
                                };

                                $statusLabel = match ($status) {
                                    'VERIFIED' => 'Terverifikasi',
                                    'ACCEPTED' => 'Diterima',
                                    'REJECTED' => 'Perlu Koreksi Dokumen',
                                    'FAILED' => 'Tidak Lolos',
                                    'DRAFT' => 'Draft',
                                    default => 'Menunggu Review',
                                };

                                $pathIcon = match ($pathCode) {
                                    'PRESTASI' => 'workspace_premium',
                                    'ZONASI' => 'location_on',
                                    'AFIRMASI' => 'volunteer_activism',
                                    'PERPINDAHAN' => 'transfer_within_a_station',
                                    default => 'school',
                                };
                            @endphp
                            <div class="student-card">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="avatar-circle avatar-secondary">{{ $avatarLetters }}</div>
                                    <div>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <h3 class="text-title-sm text-primary mb-0">{{ $appItem->name }}</h3>
                                            <span class="{{ $badgeClass }}">{{ $statusLabel }}</span>
                                        </div>
                                        <div class="text-body-sm text-on-surface-variant mt-1 d-flex flex-wrap gap-3">
                                            <span>No. Reg: <strong
                                                    class="text-on-surface">{{ $appItem->registration_number ?? '-' }}</strong></span>
                                            <span>SMP Asal: <strong
                                                    class="text-on-surface">{{ $appItem->previous_school ?? '-' }}</strong></span>
                                            <span class="d-inline-flex align-items-center gap-1 text-secondary fw-bold">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:12px;">{{ $pathIcon }}</span>
                                                {{ $pathName }}
                                            </span>
                                        </div>
                                        @if (!empty($appItem->notes))
                                            <div class="mt-1 text-body-sm text-error d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:14px;">info</span>
                                                <span>{{ $appItem->notes }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-end gap-2 pt-3 pt-md-0 border-top border-md-0"
                                    style="border-color:var(--surface-container)!important;">
                                    <a href="{{ Route::has('applicant.show') ? route('applicant.show', $appItem->id) : (Route::has('admission_document_verification.index') ? route('admission_document_verification.index') : '#') }}"
                                        class="btn-action primary text-decoration-none">
                                        <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                                        <span>Cek Berkas</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <span class="material-symbols-outlined fs-2 mb-2 d-block">folder_off</span>
                                <span>Tidak ditemukan calon peserta didik untuk kriteria ini.</span>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($applicants->hasPages())
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top"
                            style="border-color:var(--surface-container)!important;">
                            <span class="text-body-sm text-on-surface-variant">
                                Menampilkan {{ $applicants->firstItem() }}_{{ $applicants->lastItem() }} dari
                                {{ $applicants->total() }} data
                            </span>
                            <div>
                                {{ $applicants->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top"
                            style="border-color:var(--surface-container)!important;">
                            <span class="text-body-sm text-on-surface-variant">
                                Menampilkan {{ $applicants->count() }} dari {{ $totalApplicants }} data
                            </span>
                        </div>
                    @endif

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
                        <a href="{{ Route::has('admission_timeline.index') ? route('admission_timeline.index') : '#' }}"
                            class="text-decoration-none" title="Kelola Jadwal PPDB">
                            <span class="material-symbols-outlined text-primary" style="font-size:20px;">event</span>
                        </a>
                    </div>

                    <div class="timeline">
                        @forelse ($timelines as $timeline)
                            @php
                                $today = \Carbon\Carbon::now()->format('Y-m-d');
                                $isPast = $timeline->end_date && $timeline->end_date < $today;
                                $isActive =
                                    $timeline->start_date <= $today &&
                                    (!$timeline->end_date || $timeline->end_date >= $today);
                                $dotClass = $isPast ? 'done' : ($isActive ? 'active' : 'pending');
                                $startDateFormatted = \Carbon\Carbon::parse($timeline->start_date)->translatedFormat(
                                    'd M',
                                );
                                $endDateFormatted = $timeline->end_date
                                    ? \Carbon\Carbon::parse($timeline->end_date)->translatedFormat('d M Y')
                                    : '';
                            @endphp
                            <div class="timeline-item">
                                <div class="timeline-dot {{ $dotClass }}"></div>
                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        class="text-label-sm {{ $isPast ? 'text-secondary' : ($isActive ? 'text-primary' : 'text-on-surface-variant') }} fw-bold text-uppercase"
                                        style="letter-spacing:.05em;">
                                        {{ $isPast ? 'Selesai • ' : ($isActive ? 'Aktif • ' : '') }}{{ $startDateFormatted }}{{ $endDateFormatted ? '_' . $endDateFormatted : '' }}
                                    </span>
                                    @if ($isActive)
                                        <span class="badge-soft"
                                            style="background:var(--primary);color:var(--on-primary);border-color:transparent;font-size:10px;padding:2px 6px;">SEKARANG</span>
                                    @endif
                                </div>
                                <h4 class="text-title-sm text-primary mt-1 mb-0">{{ $timeline->phase_name }}</h4>
                                <p class="text-body-sm text-on-surface-variant mt-1 mb-0">{{ $timeline->description }}</p>
                            </div>
                        @empty
                            <p class="text-muted small">Jadwal pelaksanaan belum diatur.</p>
                        @endforelse
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
                        <span>Panduan Dokumen &amp; Juknis PPDB</span>
                        <a href="{{ Route::has('document.index') ? route('document.index') : '#' }}"
                            class="link-secondary fw-bold text-decoration-none d-inline-flex align-items-center gap-1">
                            <span>Buka Dokumen</span>
                            <span class="material-symbols-outlined" style="font-size:14px;">open_in_new</span>
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
        <a href="{{ route('dashboard.back-to-home') }}" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">dashboard</span>
            <span class="text-label-sm mt-1">Beranda</span>
        </a>
        <a href="{{ Route::has('admission_quota_setting.index') ? route('admission_quota_setting.index') : '#' }}"
            class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">settings</span>
            <span class="text-label-sm mt-1">Kuota</span>
        </a>
        <a href="{{ url('/dashboard') }}" class="bottom-nav-item active">
            <span class="material-symbols-outlined icon-fill" style="font-size:24px;">how_to_reg</span>
            <span class="text-label-sm fw-bold mt-1">PPDB</span>
        </a>
        <a href="{{ Route::has('admission_document_verification.index') ? route('admission_document_verification.index') : '#' }}"
            class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">fact_check</span>
            <span class="text-label-sm mt-1">Verifikasi</span>
        </a>
        <a href="{{ Route::has('document.index') ? route('document.index') : '#' }}" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">description</span>
            <span class="text-label-sm mt-1">Dokumen</span>
        </a>
    </nav>
@endsection
