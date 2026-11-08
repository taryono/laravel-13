<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EduPortal - Modul Absensi &amp; Presensi</title>

    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />

    <!-- Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" />

    <!-- Material Symbols Outlined -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" />

    <style>
        :root {
            --primary: #021064;
            --primary-container: #1e2a78;
            --on-primary: #ffffff;
            --primary-fixed: #dfe0ff;
            --primary-fixed-dim: #bcc3ff;
            --secondary: #006a61;
            --secondary-container: #86f2e4;
            --secondary-fixed: #89f5e7;
            --on-secondary: #ffffff;
            --on-secondary-container: #006f66;
            --tertiary: #291c00;
            --tertiary-container: #443000;
            --tertiary-fixed: #ffdea2;
            --tertiary-fixed-dim: #f7bd38;
            --on-tertiary-container: #c69300;
            --error: #ba1a1a;
            --error-container: #ffdad6;
            --on-error: #ffffff;
            --on-error-container: #93000a;
            --surface: #f9f9ff;
            --surface-container-lowest: #ffffff;
            --surface-container-low: #f0f3ff;
            --surface-container: #e7eeff;
            --surface-container-high: #dee9ff;
            --surface-container-highest: #d8e3f9;
            --surface-variant: #d8e3f9;
            --surface-dim: #cfdaf1;
            --on-surface: #111c2c;
            --on-background: #111c2c;
            --on-surface-variant: #454651;
            --outline: #767682;
            --outline-variant: #c6c5d3;
            --background: #f9f9ff;
            --inverse-surface: #263142;
            --inverse-primary: #bcc3ff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--on-background);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 20px;
            padding-bottom: 96px;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }

        .icon-fill {
            font-variation-settings: 'FILL' 1;
        }

        /* Typography */
        .text-label-sm {
            font-size: 11px;
            line-height: 14px;
            font-weight: 700;
        }

        .text-label-md {
            font-size: 13px;
            line-height: 18px;
            font-weight: 600;
        }

        .text-title-sm {
            font-size: 16px;
            line-height: 24px;
            font-weight: 600;
        }

        .text-body-sm {
            font-size: 12px;
            line-height: 16px;
            font-weight: 400;
        }

        .text-body-md {
            font-size: 14px;
            line-height: 20px;
            font-weight: 400;
        }

        .text-headline-md {
            font-size: 20px;
            line-height: 28px;
            font-weight: 600;
        }

        .text-headline-lg-mobile {
            font-size: 22px;
            line-height: 30px;
            font-weight: 600;
        }

        .text-display-lg-mobile {
            font-size: 30px;
            line-height: 38px;
            font-weight: 700;
        }

        .text-primary {
            color: var(--primary) !important;
        }

        .text-secondary {
            color: var(--secondary) !important;
        }

        .text-error {
            color: var(--error) !important;
        }

        .text-outline {
            color: var(--outline) !important;
        }

        .text-on-surface {
            color: var(--on-surface) !important;
        }

        .text-on-background {
            color: var(--on-background) !important;
        }

        .text-on-surface-variant {
            color: var(--on-surface-variant) !important;
        }

        /* App bar */
        .app-bar {
            background: var(--surface);
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--primary-container);
            color: var(--on-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .notif-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--surface-container);
            color: var(--on-surface-variant);
            border: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background .15s, transform .1s;
        }

        .notif-btn:hover {
            background: var(--surface-container-high);
        }

        .notif-btn:active {
            transform: scale(.95);
        }

        /* Cards */
        .card-soft {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, .4);
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(30, 42, 120, .04);
        }

        .card-soft.border-left-primary {
            border-left: 4px solid var(--primary-container) !important;
        }

        .card-soft.border-left-secondary {
            border-left: 4px solid var(--secondary) !important;
        }

        .card-soft.border-left-error {
            border-left: 4px solid var(--error) !important;
        }

        .card-soft.border-left-tertiary {
            border-left: 4px solid var(--on-tertiary-container) !important;
        }

        /* Status pill */
        .status-pill-live {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(0, 106, 97, .1);
            border: 1px solid rgba(0, 106, 97, .2);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            color: var(--secondary);
        }

        .live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--secondary);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        @keyframes ping {

            75%,
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .icon-box {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-box-lg {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-box-primary {
            background: var(--primary-fixed);
            color: var(--primary);
        }

        .icon-box-secondary {
            background: rgba(0, 106, 97, .1);
            color: var(--secondary);
        }

        .icon-box-tertiary {
            background: var(--tertiary-fixed);
            color: var(--tertiary-container);
        }

        .icon-box-error {
            background: var(--error-container);
            color: var(--error);
        }

        .icon-box-neutral {
            background: var(--surface-container);
            color: var(--on-surface-variant);
        }

        .icon-box-primary-solid {
            background: var(--primary-container);
            color: var(--on-primary);
        }

        .icon-box-on-primary-ghost {
            background: rgba(255, 255, 255, .2);
            color: var(--on-primary);
        }

        /* Progress */
        .progress-thin {
            height: 8px;
            border-radius: 999px;
            background: var(--surface-container);
            overflow: hidden;
        }

        .progress-thin .progress-bar {
            background-color: var(--secondary);
            border-radius: 999px;
        }

        /* Quick action cards */
        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid rgba(198, 197, 211, .5);
            background: var(--surface-container-lowest);
            text-align: left;
            box-shadow: 0 4px 20px -2px rgba(30, 42, 120, .04);
            transition: background .15s, transform .1s, opacity .15s;
            width: 100%;
        }

        .quick-action-btn:hover {
            background: var(--surface-container);
            color: inherit;
        }

        .quick-action-btn:active {
            transform: scale(.95);
        }

        .quick-action-btn.primary {
            background: var(--primary-container);
            color: var(--on-primary);
            border-color: transparent;
        }

        .quick-action-btn.primary:hover {
            opacity: .95;
            background: var(--primary-container);
            color: var(--on-primary);
        }

        /* Student confirmation card */
        .student-confirm-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px;
            border-radius: 16px;
        }

        .avatar-photo {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid rgba(198, 197, 211, .3);
            flex-shrink: 0;
        }

        .class-badge {
            padding: 2px 6px;
            border-radius: 4px;
            background: var(--surface-container);
            color: var(--on-surface-variant);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        .btn-contact-parent {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 12px;
            border: 0;
            background: var(--primary-container);
            color: var(--on-primary);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            flex-shrink: 0;
            transition: transform .1s;
        }

        .btn-contact-parent:active {
            transform: scale(.95);
        }

        /* Live feed items */
        .feed-item {
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .feed-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid rgba(198, 197, 211, .3);
        }

        .badge-tag-success {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 999px;
            background: rgba(0, 106, 97, .1);
            color: var(--secondary);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        .badge-tag-warning {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 999px;
            background: var(--tertiary-fixed);
            color: var(--tertiary-container);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        /* Bar chart */
        .bar-chart {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 4px 8px;
        }

        .bar-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            gap: 6px;
        }

        .bar-track {
            width: 100%;
            background: var(--surface-container);
            border-radius: 8px 8px 0 0;
            height: 96px;
            display: flex;
            align-items: flex-end;
        }

        .bar-track.today {
            background: rgba(0, 106, 97, .2);
        }

        .bar-fill {
            width: 100%;
            border-radius: 8px 8px 0 0;
            transition: height .3s;
            background: var(--primary-container);
        }

        .bar-fill.today {
            background: var(--secondary);
            position: relative;
        }

        .bar-fill.future {
            background: var(--outline-variant);
        }

        .bar-dot {
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid var(--secondary);
        }

        .bar-label {
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            color: var(--on-surface-variant);
        }

        .bar-label.today {
            color: var(--secondary);
            font-weight: 700;
        }

        .bar-pct {
            font-size: 10px;
            line-height: 14px;
            color: var(--on-surface-variant);
        }

        .bar-pct.today {
            color: var(--secondary);
            font-weight: 700;
        }

        .bar-col.future-col {
            opacity: .4;
        }

        /* Bottom nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: var(--surface);
            box-shadow: 0 -2px 6px rgba(0, 0, 0, .08);
            z-index: 1040;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 8px;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            color: var(--on-surface-variant);
            text-decoration: none;
            border-radius: 12px;
            transition: background .15s, transform .1s;
        }

        .bottom-nav-item:hover {
            background: var(--surface-container-high);
            color: var(--on-surface-variant);
        }

        .bottom-nav-item:active {
            transform: scale(.95);
        }

        .bottom-nav-item.active {
            background: var(--primary-container);
            color: var(--on-primary);
        }

        /* Misc */
        .divide-feed> :not(:first-child) {
            border-top: 1px solid rgba(231, 238, 255, 1);
        }

        .link-secondary {
            color: var(--on-surface-variant);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            transition: color .15s;
        }

        .link-secondary:hover {
            color: var(--primary);
        }
    </style>
</head>

<body>

    <!-- Top App Bar -->
    <header class="app-bar d-flex justify-content-between align-items-center px-3">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-icon">
                <span class="material-symbols-outlined" style="font-size:20px;">school</span>
            </div>
            <div>
                <span class="text-headline-md text-primary d-block" style="letter-spacing:-.02em;">EduPortal</span>
                <span class="text-label-sm text-on-surface-variant d-block" style="margin-top:-2px;">SMA Negeri 1
                    Prestasi</span>
            </div>
        </div>
        <button type="button" class="notif-btn" aria-label="Notifikasi">
            <span class="material-symbols-outlined" style="font-size:22px;">notifications</span>
        </button>
    </header>

    <!-- Sub-Header -->
    <section class="px-3 pt-3 pb-2">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2 flex-wrap">
            <a href="#" class="link-secondary d-inline-flex align-items-center gap-1">
                <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
                <span>Kembali ke Beranda</span>
            </a>
            <div class="status-pill-live">
                <span class="live-dot"></span>
                <span>Live Presensi Real-Time Hari Ini</span>
            </div>
        </div>
        <div class="d-flex flex-column">
            <h1 class="text-headline-lg-mobile text-primary fw-bold mb-0" style="letter-spacing:-.02em;">Modul Absensi
                &amp; Presensi</h1>
            <p class="text-body-sm text-on-surface-variant mb-0">Rabu, 24 Oktober 2024 • Gerbang Utama &amp; Kelas
                Terhubung</p>
        </div>
    </section>

    <!-- Main -->
    <main class="px-3 mt-2 d-flex flex-column gap-4 mx-auto" style="max-width:896px;">

        <!-- KPI Bento -->
        <section>
            <div class="row g-3">

                <!-- Hadir -->
                <div class="col-12">
                    <div class="card-soft border-left-secondary p-3 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-label-md text-on-surface-variant">Persentase Hadir Siswa</span>
                                <div class="d-flex align-items-baseline gap-2 mt-1">
                                    <span class="text-display-lg-mobile fw-bold text-primary">96.8%</span>
                                    <span class="text-label-sm text-secondary fw-semibold px-2 py-1 rounded-pill"
                                        style="background:rgba(0,106,97,.1);">+0.4% vs kemarin</span>
                                </div>
                                <p class="text-body-sm text-on-surface-variant mt-1 mb-0">1.180 Hadir dari 1.216 total
                                    siswa</p>
                            </div>
                            <div class="icon-box-lg"
                                style="width:48px;height:48px;background:rgba(0,106,97,.1);color:var(--secondary);border-radius:16px;">
                                <span class="material-symbols-outlined" style="font-size:26px;">how_to_reg</span>
                            </div>
                        </div>
                        <div class="progress progress-thin mt-3">
                            <div class="progress-bar" role="progressbar" style="width:96.8%;" aria-valuenow="96.8"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- Sakit / Izin -->
                <div class="col-6">
                    <div class="card-soft border-left-tertiary p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-on-surface-variant fw-semibold">Sakit / Izin</span>
                            <div class="icon-box icon-box-tertiary">
                                <span class="material-symbols-outlined" style="font-size:16px;">medical_services</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-headline-md fw-bold text-on-background">24 Siswa</span>
                            <p class="text-body-sm text-on-surface-variant d-flex align-items-center gap-1 mt-1 mb-0">
                                <span class="material-symbols-outlined text-secondary"
                                    style="font-size:14px;">verified</span>
                                <span>Dokter terverifikasi</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Alpha -->
                <div class="col-6">
                    <div class="card-soft border-left-error p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-on-surface-variant fw-semibold">Tanpa Keterangan</span>
                            <div class="icon-box icon-box-error">
                                <span class="material-symbols-outlined" style="font-size:16px;">warning</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-headline-md fw-bold text-error">12 Siswa</span>
                            <p class="text-body-sm text-error fw-medium mt-1 mb-0">Alpha / Perlu Tindakan</p>
                        </div>
                    </div>
                </div>

                <!-- Guru -->
                <div class="col-12">
                    <div class="card-soft border-left-primary p-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box-lg icon-box-primary">
                                <span class="material-symbols-outlined" style="font-size:22px;">badge</span>
                            </div>
                            <div>
                                <span class="text-label-sm text-on-surface-variant fw-semibold">Kehadiran Guru &amp;
                                    Staf</span>
                                <div class="d-flex align-items-baseline gap-2">
                                    <span class="text-title-sm fw-bold text-primary">100% Sempurna</span>
                                    <span class="text-body-sm text-on-surface-variant">(64 Guru Hadir)</span>
                                </div>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-lg text-secondary text-label-sm fw-bold"
                            style="background:rgba(0,106,97,.1);">Lengkap</span>
                    </div>
                </div>

            </div>
        </section>

        <!-- Quick Actions -->
        <section>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h2 class="text-title-sm text-on-background fw-bold mb-0" style="letter-spacing:-.02em;">Aksi Cepat</h2>
                <span class="text-label-sm text-on-surface-variant">Operasional Gerbang</span>
            </div>
            <div class="row g-2">

                <!-- Scan QR -->
                <div class="col-12 col-sm-6">
                    <button type="button" class="quick-action-btn primary">
                        <div class="icon-box-lg icon-box-on-primary-ghost">
                            <span class="material-symbols-outlined" style="font-size:24px;">qr_code_scanner</span>
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-label-md d-block fw-bold" style="line-height:1.15;">Scan QR
                                Gerbang</span>
                            <span class="text-body-sm d-block text-truncate"
                                style="font-size:11px;opacity:.7;">Terminal Tap RFID/QR</span>
                        </div>
                    </button>
                </div>

                <!-- Buat Izin -->
                <div class="col-12 col-sm-6">
                    <button type="button" class="quick-action-btn">
                        <div class="icon-box-lg icon-box-neutral">
                            <span class="material-symbols-outlined text-primary"
                                style="font-size:22px;">edit_note</span>
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-label-md d-block fw-bold text-on-background"
                                style="line-height:1.15;">Buat Izin Manual</span>
                            <span class="text-body-sm d-block text-on-surface-variant text-truncate"
                                style="font-size:11px;">Input surat dokter</span>
                        </div>
                    </button>
                </div>

                <!-- Notif WhatsApp -->
                <div class="col-12 col-sm-6">
                    <button type="button" class="quick-action-btn">
                        <div class="icon-box-lg icon-box-secondary">
                            <span class="material-symbols-outlined" style="font-size:22px;">send</span>
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-label-md d-block fw-bold text-on-background"
                                style="line-height:1.15;">Notif WhatsApp</span>
                            <span class="text-body-sm d-block text-on-surface-variant text-truncate"
                                style="font-size:11px;">Kirim ke Orang Tua</span>
                        </div>
                    </button>
                </div>

                <!-- Unduh Rekap -->
                <div class="col-12 col-sm-6">
                    <button type="button" class="quick-action-btn">
                        <div class="icon-box-lg icon-box-neutral">
                            <span class="material-symbols-outlined" style="font-size:22px;">download</span>
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-label-md d-block fw-bold text-on-background"
                                style="line-height:1.15;">Unduh Rekap</span>
                            <span class="text-body-sm d-block text-on-surface-variant text-truncate"
                                style="font-size:11px;">Format Excel (.xlsx)</span>
                        </div>
                    </button>
                </div>

            </div>
        </section>

        <!-- Perlu Konfirmasi -->
        <section>
            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="position-relative d-inline-block" style="width:10px;height:10px;">
                        <span class="position-absolute d-inline-block rounded-circle"
                            style="width:10px;height:10px;background:var(--error);opacity:.7;animation:ping 1.5s infinite;"></span>
                        <span class="position-relative d-inline-block rounded-circle"
                            style="width:10px;height:10px;background:var(--error);"></span>
                    </span>
                    <h2 class="text-title-sm text-on-background fw-bold mb-0" style="letter-spacing:-.02em;">Perlu
                        Konfirmasi Segera</h2>
                </div>
                <span class="px-2 py-1 rounded-pill text-error text-label-sm fw-bold"
                    style="background:var(--error-container);">12 Belum Masuk</span>
            </div>
            <p class="text-body-sm text-on-surface-variant mb-2">Siswa belum hadir melebihi jam toleransi (07.15 WIB)
            </p>

            <div class="d-flex flex-column gap-2">

                <!-- Siswa 1 -->
                <div class="student-confirm-card card-soft">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDYQ6375yLiR7_7Xwx0-7jEI56f26-gss_43a4bgYI7rK8iuYNjaI9rdxYavmqVhiOp08c2YTnnjhDJJJotDrt8005XmvZA2JBwstUoq6m-yhkq4QpjQwiLjvzZ2UKvi3LYW-Liqj5r9oS4MaG71gudod6khUDYJ2mz74-BHzov2OA7S9DTzEaov1H6p_b9BXvLGFWx1EU9dNGxESBiHnIMJD0TknxqLGVpWNRbgjmH"
                            class="avatar-photo" alt="Farhan Arya Pratama" />
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-label-md fw-bold text-on-background">Farhan Arya Pratama</span>
                                <span class="class-badge">XII MIPA 1</span>
                            </div>
                            <p class="text-body-sm text-error fw-medium mt-1 mb-0">Terlambat 35 menit • Tanpa
                                Keterangan</p>
                        </div>
                    </div>
                    <button type="button" class="btn-contact-parent">
                        <span class="material-symbols-outlined" style="font-size:16px;">phone_forwarded</span>
                        <span>Hubungi Ortu</span>
                    </button>
                </div>

                <!-- Siswa 2 -->
                <div class="student-confirm-card card-soft">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTeTt7Ns3x7D4y75bkREMUc470WHn1wN77XKL_vXP3USMLQ7s9n0rfvi2m8EXLJK3llPvjrAUOxZ7vfGzAvG1G2t__2GZU21rDpMp96KxwmOx2vqEjWqmv1zGgeKzLzGAWg6faqTdvxdRs1ldu_hukVqd0aUHyFTcgBsA0fVYsE-eT9uA2PGvUxlzHEDgTz-Ijucc2HDYlXHRv6weYIwOpN4gobsP_6-bH8Vt-riun"
                            class="avatar-photo" alt="Nadia Kirana Dewi" />
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-label-md fw-bold text-on-background">Nadia Kirana Dewi</span>
                                <span class="class-badge">XI IPS 2</span>
                            </div>
                            <p class="text-body-sm text-error fw-medium mt-1 mb-0">Terlambat 30 menit • Tanpa
                                Keterangan</p>
                        </div>
                    </div>
                    <button type="button" class="btn-contact-parent">
                        <span class="material-symbols-outlined" style="font-size:16px;">phone_forwarded</span>
                        <span>Hubungi Ortu</span>
                    </button>
                </div>

            </div>
        </section>

        <!-- Live Feed -->
        <section>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <h2 class="text-title-sm text-on-background fw-bold mb-0" style="letter-spacing:-.02em;">Live Tap
                        RFID / QR Gerbang</h2>
                    <span class="text-body-sm text-on-surface-variant">Pintu Utama Barat &amp; Selatan</span>
                </div>
                <span class="text-label-sm text-secondary d-flex align-items-center gap-1">
                    <span class="rounded-circle" style="width:8px;height:8px;background:var(--secondary);"></span>
                    Aktif
                </span>
            </div>

            <div class="card-soft p-3 divide-feed">

                <!-- Feed 1 -->
                <div class="feed-item">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_i6OlMdW8Yqbqd0cBrsKkgXRdKkbwmXvD9h2EuIIzxKqu9B8fmtCfOA7ingKLrBI9BrLW1vzixzYahWBDrHKau23Ht7K1fNy1thXHixKPDF3e66TbS-AkQNjafRRHg8xP7c-LH3UwLfGYw9837SY4wEOU-2cGI_RVqG4Guu-XDthFCP5ZKY1XJYZEaXN8OfNNzneVMCex0tGCQJ-OQ06BWGh0qSxf6nrkJaKW9jmJ"
                            class="feed-avatar" alt="Rizky Maulana" />
                        <div>
                            <span class="text-label-md fw-bold text-on-background d-block">Rizky Maulana</span>
                            <span class="text-body-sm text-on-surface-variant d-block">X MIPA 3 • Pintu Barat
                                (RFID)</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge-tag-success">Tepat Waktu</span>
                        <span class="text-body-sm text-on-surface-variant d-block mt-1">06.48 WIB</span>
                    </div>
                </div>

                <!-- Feed 2 -->
                <div class="feed-item">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDIBeeRcZ9LVyhrPwzhwj7zuX7mEBi-29Z5YpGSIb55pxkrY-wTK5oNBMctRP3vg0chEM-k6wQoDNKBfk_qrup5bZyF2FMv9ebj6GlwKaN8mrc1bjYI4PxtW2FcGAGjgaENQOl4RVxVm0gTROEC8H1lO3jswm_Ofo89EBIAVRTdzil6kte4zGH1AofApKAD74shDk6p_qTiJ32BCMcPlGAWEMWRQFFQn03BiYg0iS6F"
                            class="feed-avatar" alt="Alya Safitri" />
                        <div>
                            <span class="text-label-md fw-bold text-on-background d-block">Alya Safitri</span>
                            <span class="text-body-sm text-on-surface-variant d-block">XII Bahasa • Pintu Selatan
                                (QR)</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge-tag-success">Tepat Waktu</span>
                        <span class="text-body-sm text-on-surface-variant d-block mt-1">06.52 WIB</span>
                    </div>
                </div>

                <!-- Feed 3 (Terlambat) -->
                <div class="feed-item">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCEAZ8d-U1TKQxnQZInO5KvKK1j_BlyDJe8e4qNM3MAAIYNyJupV1k7KjmxuRO4tZSJMlud2EDA3CHGmg7Z_1TkEtGLa18iM-xkfQ3sKzwlNamkTQo9SjMLtSrWKw81YlN0oDupNXiWxFa4nL2rjF9ipdjUStNi1pojHRv_NqB0yYQWg9SdjuceuazhMEMm3XWwj6CoXGZmAI2ndeXB0krEHy8jkCfxqUj0TB1Dpron"
                            class="feed-avatar" alt="Bima Satria Wicaksono" />
                        <div>
                            <span class="text-label-md fw-bold text-on-background d-block">Bima Satria Wicaksono</span>
                            <span class="text-body-sm text-on-surface-variant d-block">XI MIPA 4 • Pintu Barat
                                (RFID)</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge-tag-warning">Terlambat (5 mnt)</span>
                        <span class="text-body-sm text-on-surface-variant d-block mt-1">07.20 WIB</span>
                    </div>
                </div>

            </div>
        </section>

        <!-- Bar Chart -->
        <section class="card-soft p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="text-title-sm text-on-background fw-bold mb-0" style="letter-spacing:-.02em;">Tren
                        Kehadiran Mingguan</h2>
                    <span class="text-body-sm text-on-surface-variant">Senin - Jumat Pekan Berjalan</span>
                </div>
                <div class="text-end">
                    <span class="text-label-md fw-bold text-primary">Rata-rata 97.2%</span>
                </div>
            </div>

            <div class="bar-chart">
                <!-- Senin -->
                <div class="bar-col">
                    <span class="bar-pct">98.2%</span>
                    <div class="bar-track">
                        <div class="bar-fill" style="height:98%;"></div>
                    </div>
                    <span class="bar-label">Sen</span>
                </div>
                <!-- Selasa -->
                <div class="bar-col">
                    <span class="bar-pct">97.5%</span>
                    <div class="bar-track">
                        <div class="bar-fill" style="height:97%;"></div>
                    </div>
                    <span class="bar-label">Sel</span>
                </div>
                <!-- Rabu (Today) -->
                <div class="bar-col">
                    <span class="bar-pct today">96.8%</span>
                    <div class="bar-track today">
                        <div class="bar-fill today" style="height:96%;">
                            <span class="bar-dot"></span>
                        </div>
                    </div>
                    <span class="bar-label today">Rab</span>
                </div>
                <!-- Kamis (future) -->
                <div class="bar-col future-col">
                    <span class="bar-pct">-</span>
                    <div class="bar-track">
                        <div class="bar-fill future" style="height:10%;"></div>
                    </div>
                    <span class="bar-label">Kam</span>
                </div>
                <!-- Jumat (future) -->
                <div class="bar-col future-col">
                    <span class="bar-pct">-</span>
                    <div class="bar-track">
                        <div class="bar-fill future" style="height:10%;"></div>
                    </div>
                    <span class="bar-label">Jum</span>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-1"
                style="font-size:11px;color:var(--on-surface-variant);border-color:var(--surface-container)!important;">
                <span class="d-flex align-items-center gap-2">
                    <span class="d-inline-block rounded"
                        style="width:10px;height:10px;background:var(--secondary);"></span>
                    <span>Hari Ini</span>
                </span>
                <span>Target Sekolah: Min. 95.0%</span>
            </div>
        </section>

    </main>

    <!-- Bottom Nav -->
    <nav class="bottom-nav">
        <a href="#" class="bottom-nav-item">
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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
