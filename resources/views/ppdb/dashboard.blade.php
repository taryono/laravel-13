<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pendaftar PPDB — {{ $applicant->name ?? 'Calon Siswa' }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        :root {
            --bs-primary: #00288e;
            --bs-primary-dark: #001c66;
            --bs-primary-light: #e8eeff;
            --bs-secondary: #0284c7;
            --bs-surface: #f8fafc;
            --bs-card-bg: #ffffff;
            --bs-border-color: #e2e8f0;
            --bs-text-main: #0f172a;
            --bs-text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bs-surface);
            color: var(--bs-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .portal-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--bs-border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .brand-logo {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.6rem;
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        /* Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #00288e 0%, #0369a1 100%);
            border-radius: 1rem;
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 40, 142, 0.2);
        }

        .welcome-banner .bg-deco {
            position: absolute;
            right: -20px;
            bottom: -30px;
            opacity: 0.12;
            pointer-events: none;
            font-size: 14rem;
        }

        /* Stepper */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-top: 1.5rem;
        }

        .stepper-wrapper::before {
            content: '';
            position: absolute;
            top: 18px;
            left: 30px;
            right: 30px;
            height: 3px;
            background: rgba(255, 255, 255, 0.25);
            z-index: 1;
        }

        .step-item {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: white;
            margin-bottom: 0.4rem;
            transition: all 0.2s ease;
        }

        .step-item.active .step-circle {
            background: #ffffff;
            color: var(--bs-primary);
            border-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3);
        }

        .step-item.completed .step-circle {
            background: #10b981;
            color: white;
            border-color: #10b981;
        }

        .step-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Tabs */
        .portal-nav-tabs {
            border-bottom: 2px solid var(--bs-border-color);
            gap: 0.5rem;
        }

        .portal-nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: var(--bs-text-muted);
            font-weight: 600;
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .portal-nav-tabs .nav-link:hover {
            color: var(--bs-primary);
        }

        .portal-nav-tabs .nav-link.active {
            color: var(--bs-primary);
            border-bottom-color: var(--bs-primary);
            background: transparent;
        }

        /* Cards */
        .portal-card {
            background: var(--bs-card-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 0.85rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .portal-card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--bs-border-color);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .portal-card-body {
            padding: 1.25rem;
        }

        /* Data list */
        .detail-row {
            display: flex;
            padding: 0.65rem 0;
            border-bottom: 1px dashed var(--bs-border-color);
            font-size: 0.875rem;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            width: 35%;
            color: var(--bs-text-muted);
            font-weight: 500;
        }

        .detail-value {
            width: 65%;
            font-weight: 600;
            color: var(--bs-text-main);
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .status-verified {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-pending {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .status-revision {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-rejected {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
    </style>
</head>

<body>

    <!-- TOP NAVBAR -->
    <header class="portal-navbar py-2">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('ppdb.index') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                    <div class="brand-logo">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.2;">PPDB Online</h6>
                        <span class="text-muted" style="font-size: 0.7rem;">Portal Peserta Didik Baru</span>
                    </div>
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light border">
                    <i class="bi bi-calendar2-check text-primary"></i>
                    <span style="font-size: 0.78rem; font-weight: 600;" class="text-secondary">
                        {{ $applicant?->admission?->name ?? 'Tahun Ajaran 2025/2026' }}
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2 ps-2 border-start">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                        style="width: 2.2rem; height: 2.2rem; font-size: 0.85rem;">
                        {{ substr($applicant?->name ?? 'Pendaftar', 0, 2) }}
                    </div>
                    <div class="d-none d-sm-block text-start me-2">
                        <div class="fw-bold" style="font-size: 0.82rem; line-height: 1.2;">
                            {{ $applicant?->name ?? 'Calon Siswa' }}
                        </div>
                        <div class="text-muted" style="font-size: 0.7rem;">
                            {{ $applicant?->registration_number ?? '-' }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('ppdb.logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 py-1 px-2 rounded-2" title="Keluar">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="d-none d-md-inline" style="font-size: 0.75rem; font-weight: 600;">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTAINER -->
    <main class="container my-4 flex-grow-1">

        <!-- WELCOME BANNER -->
        <section class="welcome-banner mb-4">
            <span class="material-symbols-outlined bg-deco">school</span>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge bg-white text-primary fw-bold px-2 py-1" style="font-size: 0.72rem;">
                            {{ $applicant?->registration_number ?? 'REG-PPDB' }}
                        </span>
                        <span class="badge bg-white bg-opacity-25 text-white fw-bold px-2 py-1" style="font-size: 0.72rem;">
                            Jalur: {{ $applicant?->path?->name ?? 'Zonasi Reguler' }}
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1">Halo, {{ $applicant?->name ?? 'Calon Siswa' }}! 👋</h3>
                    <p class="mb-0 text-white-50" style="font-size: 0.85rem;">
                        Pilihan Sekolah: <strong>{{ $applicant?->school?->name ?? 'SMAN 3 Bandung' }}</strong> • Asal Sekolah: <strong>{{ $applicant?->previous_school ?? 'SMP/MTs' }}</strong>
                    </p>
                </div>

                <div class="text-md-end">
                    @php
                        $st = strtoupper($applicant?->status ?? 'REGISTERED');
                        $badgeClass = match($st) {
                            'VERIFIED' => 'status-verified',
                            'REVISION_REQUIRED' => 'status-revision',
                            'REJECTED' => 'status-rejected',
                            'ACCEPTED', 'LULUS' => 'status-verified',
                            default => 'status-pending'
                        };
                        $statusLabel = match($st) {
                            'VERIFIED' => 'Berkas Terverifikasi',
                            'REVISION_REQUIRED' => 'Perlu Perbaikan Berkas',
                            'REJECTED' => 'Tidak Memenuhi Syarat',
                            'ACCEPTED', 'LULUS' => 'Diterima / Lulus Seleksi',
                            default => 'Pendaftaran Diterima (Menunggu Verifikasi)'
                        };
                    @endphp
                    <span class="status-badge {{ $badgeClass }} shadow-sm">
                        <i class="bi bi-shield-check"></i> {{ $statusLabel }}
                    </span>
                </div>
            </div>

            <!-- STEPPER PROGRESS -->
            <div class="stepper-wrapper d-none d-md-flex">
                <div class="step-item completed">
                    <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                    <span class="step-label">1. Formulir</span>
                </div>
                <div class="step-item {{ ($applicant?->documents && $applicant->documents->count() > 0) ? 'completed' : 'active' }}">
                    <div class="step-circle">
                        @if ($applicant?->documents && $applicant->documents->count() > 0)
                            <i class="bi bi-check-lg"></i>
                        @else
                            2
                        @endif
                    </div>
                    <span class="step-label">2. Unggah Dokumen</span>
                </div>
                <div class="step-item {{ $st === 'VERIFIED' ? 'completed' : ($st === 'REVISION_REQUIRED' ? 'active' : '') }}">
                    <div class="step-circle">
                        @if ($st === 'VERIFIED')
                            <i class="bi bi-check-lg"></i>
                        @else
                            3
                        @endif
                    </div>
                    <span class="step-label">3. Verifikasi Panitia</span>
                </div>
                <div class="step-item {{ in_array($st, ['ACCEPTED', 'LULUS']) ? 'completed' : '' }}">
                    <div class="step-circle">
                        @if (in_array($st, ['ACCEPTED', 'LULUS']))
                            <i class="bi bi-check-lg"></i>
                        @else
                            4
                        @endif
                    </div>
                    <span class="step-label">4. Seleksi & Ranking</span>
                </div>
                <div class="step-item {{ in_array($st, ['ACCEPTED', 'LULUS']) ? 'active' : '' }}">
                    <div class="step-circle">5</div>
                    <span class="step-label">5. Pengumuman</span>
                </div>
            </div>
        </section>

        <!-- NAVIGATION TABS -->
        <ul class="nav portal-nav-tabs mb-4 flex-nowrap overflow-auto" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'overview') === 'overview' ? 'active' : '' }}" href="{{ route('ppdb.dashboard') }}">
                    <i class="bi bi-grid-fill"></i> Ringkasan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'profile' ? 'active' : '' }}" href="{{ route('ppdb.profile') }}">
                    <i class="bi bi-person-lines-fill"></i> Biodata Lengkap
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'documents' ? 'active' : '' }}" href="{{ route('ppdb.documents') }}">
                    <i class="bi bi-file-earmark-check-fill"></i> Berkas Persyaratan
                    @if ($applicant?->documents && $applicant->documents->count() > 0)
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.7rem;">{{ $applicant->documents->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'payments' ? 'active' : '' }}" href="{{ route('ppdb.payments') }}">
                    <i class="bi bi-credit-card-2-front-fill"></i> Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'announcements' ? 'active' : '' }}" href="{{ route('ppdb.announcements') }}">
                    <i class="bi bi-megaphone-fill"></i> Pengumuman Hasil
                </a>
            </li>
        </ul>

        <!-- TAB CONTENT SECTION -->
        @if (($tab ?? 'overview') === 'overview')
            <!-- ===== OVERVIEW TAB ===== -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Ringkasan Pendaftaran -->
                    <div class="portal-card">
                        <div class="portal-card-header">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle text-primary me-2"></i>Rincian Pendaftaran</h6>
                            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                                <i class="bi bi-printer"></i> Cetak Bukti Pendaftaran
                            </button>
                        </div>
                        <div class="portal-card-body">
                            <div class="detail-row">
                                <div class="detail-label">Nomor Registrasi</div>
                                <div class="detail-value text-primary font-monospace">{{ $applicant?->registration_number ?? '-' }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Nama Lengkap</div>
                                <div class="detail-value">{{ $applicant?->name ?? '-' }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">NIK / NISN</div>
                                <div class="detail-value">{{ $applicant?->nik ?? '-' }} / {{ $applicant?->nisn ?? '-' }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Sekolah Pilihan</div>
                                <div class="detail-value">{{ $applicant?->school?->name ?? 'SMAN 3 Bandung' }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Jalur Pendaftaran</div>
                                <div class="detail-value">
                                    <span class="badge bg-primary text-white">{{ $applicant?->path?->name ?? 'Zonasi Reguler' }}</span>
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Asal Sekolah</div>
                                <div class="detail-value">{{ $applicant?->previous_school ?? '-' }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Jarak Geodesik ke Sekolah</div>
                                <div class="detail-value">
                                    {{ $applicant?->domicile && $applicant->domicile->distance_to_school_m ? number_format($applicant->domicile->distance_to_school_m, 0, ',', '.') . ' Meter' : 'Dalam proses kalkulasi' }}
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Waktu Registrasi</div>
                                <div class="detail-value">
                                    {{ $applicant?->registered_at ? \Carbon\Carbon::parse($applicant->registered_at)->translatedFormat('l, d F Y - H:i') . ' WIB' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pemberitahuan Status -->
                    <div class="alert alert-info border-info d-flex align-items-start gap-3 p-3 rounded-3">
                        <i class="bi bi-info-circle-fill fs-4 text-info flex-shrink-0"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Informasi Verifikasi Dokumen</h6>
                            <p class="mb-0" style="font-size: 0.85rem;">
                                Panitia PPDB sedang memvalidasi kesesuaian berkas Anda. Pastikan berkas akta kelahiran, kartu keluarga, dan rapor sudah diunggah dengan jelas dan dapat terbaca. Jika ada perbaikan, panitia akan memberikan catatan pada menu <strong>Berkas Persyaratan</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="portal-card">
                        <div class="portal-card-header">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-lightning-charge text-warning me-2"></i>Aksi Cepat</h6>
                        </div>
                        <div class="portal-card-body d-flex flex-column gap-2">
                            <a href="{{ route('ppdb.documents') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-between p-2 rounded-2 text-start">
                                <span class="d-flex align-items-center gap-2" style="font-size: 0.85rem; font-weight: 600;">
                                    <i class="bi bi-upload"></i> Unggah / Periksa Berkas
                                </span>
                                <i class="bi bi-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                            </a>
                            <a href="{{ route('ppdb.profile') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-between p-2 rounded-2 text-start">
                                <span class="d-flex align-items-center gap-2" style="font-size: 0.85rem; font-weight: 600;">
                                    <i class="bi bi-person-vcard"></i> Cek Biodata Calon Siswa
                                </span>
                                <i class="bi bi-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                            </a>
                            <a href="{{ route('ppdb.announcements') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-between p-2 rounded-2 text-start">
                                <span class="d-flex align-items-center gap-2" style="font-size: 0.85rem; font-weight: 600;">
                                    <i class="bi bi-trophy"></i> Hasil Pemeringkatan Seleksi
                                </span>
                                <i class="bi bi-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Jadwal Penting Gelombang -->
                    <div class="portal-card">
                        <div class="portal-card-header">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-calendar3 text-primary me-2"></i>Linimasa PPDB</h6>
                        </div>
                        <div class="portal-card-body p-0">
                            <ul class="list-group list-group-flush" style="font-size: 0.83rem;">
                                @if ($applicant?->admission && $applicant->admission->timelines && $applicant->admission->timelines->isNotEmpty())
                                    @foreach ($applicant->admission->timelines->sortBy('order_number') as $t)
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                            <div>
                                                <div class="fw-bold">{{ $t->phase_name }}</div>
                                                <span class="text-muted" style="font-size: 0.72rem;">{{ $t->description }}</span>
                                            </div>
                                            <span class="badge bg-light text-dark border">
                                                {{ $t->start_date ? \Carbon\Carbon::parse($t->start_date)->format('d M') : '' }}
                                            </span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item py-2 px-3 text-muted">Jadwal resmi dapat dilihat pada beranda PPDB.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        @elseif (($tab ?? '') === 'profile')
            <!-- ===== PROFILE TAB ===== -->
            <div class="portal-card">
                <div class="portal-card-header">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill text-primary me-2"></i>Biodata Calon Siswa Lengkap</h6>
                </div>
                <div class="portal-card-body">
                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3">1. Data Pribadi</h6>
                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Nama Lengkap</div>
                                <div class="detail-value">{{ $applicant?->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">NIK</div>
                                <div class="detail-value">{{ $applicant?->nik ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">NISN</div>
                                <div class="detail-value">{{ $applicant?->nisn ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Tempat, Tanggal Lahir</div>
                                <div class="detail-value">
                                    {{ $applicant?->birth_place ?? '-' }}, {{ $applicant?->birth_date ? \Carbon\Carbon::parse($applicant->birth_date)->translatedFormat('d F Y') : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Jenis Kelamin</div>
                                <div class="detail-value">{{ ($applicant?->gender == 'L' || $applicant?->gender == 'male') ? 'Laki-laki' : ($applicant?->gender ? 'Perempuan' : '-') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">No. Telepon / WhatsApp</div>
                                <div class="detail-value">{{ $applicant?->phone ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-row">
                                <div class="detail-label" style="width: 17.5%;">Alamat Lengkap</div>
                                <div class="detail-value" style="width: 82.5%;">{{ $applicant?->address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3">2. Data Orang Tua / Wali</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Nama Orang Tua / Wali</div>
                                <div class="detail-value">{{ $applicant?->parent_name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">No. Telepon Orang Tua</div>
                                <div class="detail-value">{{ $applicant?->parent_phone ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Email Orang Tua</div>
                                <div class="detail-value">{{ $applicant?->parent_email ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-row">
                                <div class="detail-label">Asal Sekolah</div>
                                <div class="detail-value">{{ $applicant?->previous_school ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif (($tab ?? '') === 'documents')
            <!-- ===== DOCUMENTS TAB ===== -->
            <div class="portal-card">
                <div class="portal-card-header">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-check text-primary me-2"></i>Status Berkas &amp; Dokumen Persyaratan</h6>
                    <span class="badge bg-primary text-white" style="font-size: 0.75rem;">Format file: PDF, JPG, PNG (Maks 2MB)</span>
                </div>
                <div class="portal-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Jenis Dokumen</th>
                                    <th>Status Dokumen</th>
                                    <th>Status Verifikasi</th>
                                    <th>Catatan Panitia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $requiredDocs = [
                                        'kartu_keluarga' => 'Kartu Keluarga (KK)',
                                        'akta_kelahiran' => 'Akta Kelahiran',
                                        'rapor' => 'Buku Rapor Semester 1 - 5',
                                        'surat_domisili' => 'Surat Keterangan Domisili / Bukti Tempat Tinggal',
                                        'sertifikat' => 'Sertifikat / Piagam Prestasi (Opsional)',
                                    ];
                                @endphp
                                @foreach ($requiredDocs as $key => $title)
                                    @php
                                        $uploaded = $applicant?->documents ? $applicant->documents->firstWhere('document_type', $key) : null;
                                        $verif = $applicant?->verifications ? $applicant->verifications->firstWhere('document_type', $key) : null;
                                        $vStatus = $verif ? $verif->verification_status : ($uploaded ? 'pending' : 'not_uploaded');
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $title }}</div>
                                            @if ($uploaded)
                                                <span class="text-muted" style="font-size: 0.72rem;">File: {{ $uploaded->file_name ?? $uploaded->original_name ?? 'Dokumen.pdf' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($uploaded)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                    <i class="bi bi-check-circle me-1"></i> Terunggah
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                                    <i class="bi bi-clock me-1"></i> Belum Diunggah
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($vStatus === 'verified')
                                                <span class="badge bg-success text-white px-2 py-1">Valid / Diterima</span>
                                            @elseif ($vStatus === 'rejected' || $vStatus === 'revision_required')
                                                <span class="badge bg-danger text-white px-2 py-1">Perlu Revisi</span>
                                            @elseif ($uploaded)
                                                <span class="badge bg-secondary text-white px-2 py-1">Dalam Antrean</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted" style="font-size: 0.78rem;">
                                                {{ $verif && $verif->revision_notes ? $verif->revision_notes : ($uploaded ? 'Menunggu pemeriksaan petugas loket' : 'Mohon segera diunggah') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @elseif (($tab ?? '') === 'payments')
            <!-- ===== PAYMENTS TAB ===== -->
            @php $latestInvoice = $applicant?->invoices?->sortByDesc('id')?->first(); @endphp
            <div class="portal-card">
                <div class="portal-card-header">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-receipt text-primary me-2"></i>Tagihan &amp; Pembayaran Pendaftaran</h6>
                    @if ($latestInvoice && $applicant)
                        <a href="{{ route('ppdb.invoice', $applicant->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="bi bi-file-earmark-text me-1"></i> Lihat Invoice
                        </a>
                    @endif
                </div>
                <div class="portal-card-body">
                    @if ($latestInvoice && ($latestInvoice->final_amount ?? $latestInvoice->total_amount ?? 0) > 0)
                        <div class="detail-row">
                            <div class="detail-label">Nomor Invoice</div>
                            <div class="detail-value text-primary font-monospace">{{ $latestInvoice->invoice_number }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Total Tagihan</div>
                            <div class="detail-value fw-bold">Rp {{ number_format($latestInvoice->final_amount ?? $latestInvoice->total_amount ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status Pembayaran</div>
                            <div class="detail-value">
                                @php $invStat = strtolower($latestInvoice->status ?? 'unpaid'); @endphp
                                <span class="badge {{ $invStat === 'paid' ? 'bg-success' : ($invStat === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ strtoupper($invStat) }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success d-flex align-items-center gap-3 p-3 rounded-3 mb-4">
                            <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Biaya Formulir Pendaftaran PPDB: GRATIS / LUNAS</h6>
                                <span style="font-size: 0.83rem;">Seluruh alur pendaftaran dan seleksi PPDB dibiayai oleh BOS dan Anggaran Daerah.</span>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Nomor Invoice</div>
                            <div class="detail-value text-primary font-monospace">INV-PPDB-{{ $applicant?->registration_number ?? '2025' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Biaya Seleksi &amp; Ujian</div>
                            <div class="detail-value">Rp 0 (Bebas Biaya)</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status Pembayaran</div>
                            <div class="detail-value"><span class="badge bg-success">LUNAS / BEBAS BIAYA</span></div>
                        </div>
                    @endif
                </div>
            </div>

        @elseif (($tab ?? '') === 'announcements')
            <!-- ===== ANNOUNCEMENTS TAB ===== -->
            <div class="portal-card">
                <div class="portal-card-header">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-megaphone text-primary me-2"></i>Hasil Pengumuman Seleksi PPDB</h6>
                </div>
                <div class="portal-card-body text-center py-5">
                    @php
                        $st = strtoupper($applicant?->status ?? 'REGISTERED');
                    @endphp
                    @if (in_array($st, ['ACCEPTED', 'LULUS']))
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 4.5rem; height: 4.5rem;">
                            <i class="bi bi-trophy-fill fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-success mb-2">SELAMAT! ANDA DINYATAKAN LULUS SELEKSI PPDB</h4>
                        <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
                            Berdasarkan hasil seleksi pemeringkatan geodesik dan verifikasi berkas, Anda diterima pada sekolah <strong>{{ $applicant?->school?->name ?? 'SMAN 3 Bandung' }}</strong>.
                        </p>
                        <button onclick="window.print()" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Unduh Surat Keterangan Lulus (SKL)
                        </button>
                    @else
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 4.5rem; height: 4.5rem;">
                            <i class="bi bi-hourglass-split fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Proses Seleksi Sedang Berjalan</h5>
                        <p class="text-muted mx-auto mb-3" style="max-width: 500px; font-size: 0.88rem;">
                            Pemeringkatan hasil seleksi PPDB Jalur {{ $applicant?->path?->name ?? 'Zonasi' }} akan diumumkan secara resmi pada tanggal yang tertera pada linimasa gelombang.
                        </p>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 bg-light border text-muted" style="font-size: 0.82rem;">
                            <i class="bi bi-geo-alt-fill text-danger"></i>
                            Peringkat Sementara Anda: <strong>#{{ $applicant?->ranking ?? '1' }}</strong> (Skor/Jarak: {{ $applicant?->domicile && $applicant->domicile->distance_to_school_m ? number_format($applicant->domicile->distance_to_school_m, 0, ',', '.') . ' m' : 'Dalam kalkulasi' }})
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-top py-3 mt-auto text-center text-muted" style="font-size: 0.78rem;">
        <div class="container">
            &copy; {{ date('Y') }} Panitia Penerimaan Peserta Didik Baru (PPDB) • Hak Cipta Dilindungi
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
