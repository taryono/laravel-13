<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="color-scheme" content="light dark" />
    <title>Modul Data Akademik - EduPortal</title>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>

    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />

    <!-- Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" />

    <!-- Material Symbols Outlined -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" />

    <style>
        :root {
            --primary: #021064;
            --primary-container: #1e2a78;
            --on-primary: #ffffff;
            --on-primary-container: #8a95e9;
            --primary-fixed: #dfe0ff;
            --primary-fixed-dim: #bcc3ff;
            --secondary: #006a61;
            --secondary-container: #86f2e4;
            --secondary-fixed: #89f5e7;
            --on-secondary: #ffffff;
            --on-secondary-container: #006f66;
            --on-secondary-fixed: #00201d;
            --tertiary: #291c00;
            --tertiary-container: #443000;
            --tertiary-fixed: #ffdea2;
            --tertiary-fixed-dim: #f7bd38;
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
            --on-surface: #111c2c;
            --on-surface-variant: #454651;
            --outline: #767682;
            --outline-variant: #c6c5d3;
            --background: #f9f9ff;
            --inverse-surface: #263142;
            --inverse-primary: #bcc3ff;
        }

        :root[data-bs-theme="dark"],
        body.dark-mode {
            --primary: #bcc3ff;
            --primary-container: #4c58a6;
            --on-primary: #021064;
            --on-primary-container: #dfe0ff;
            --primary-fixed: #dfe0ff;
            --primary-fixed-dim: #bcc3ff;
            --secondary: #6bd8cb;
            --secondary-container: #005049;
            --secondary-fixed: #89f5e7;
            --on-secondary: #003732;
            --on-secondary-container: #89f5e7;
            --on-secondary-fixed: #00201d;
            --tertiary: #ffdea2;
            --tertiary-container: #443000;
            --tertiary-fixed: #ffdea2;
            --tertiary-fixed-dim: #f7bd38;
            --error: #ffb4ab;
            --error-container: #93000a;
            --on-error: #690005;
            --on-error-container: #ffdad6;
            --surface: #0f172a;
            --surface-container-lowest: #141d2c;
            --surface-container-low: #1e293b;
            --surface-container: #243247;
            --surface-container-high: #2d3d56;
            --surface-container-highest: #374966;
            --surface-variant: #334155;
            --on-surface: #f1f5f9;
            --on-surface-variant: #cbd5e1;
            --outline: #8f909c;
            --outline-variant: #475569;
            --background: #0b1120;
            --inverse-surface: #f1f5f9;
            --inverse-primary: #1e2a78;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--on-surface);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 20px;
            display: flex;
            justify-content: center;
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

        .text-on-surface-variant {
            color: var(--on-surface-variant) !important;
        }

        .text-on-secondary-container {
            color: var(--on-secondary-container) !important;
        }

        /* Mobile Shell */
        .mobile-shell {
            width: 100%;
            max-width: 448px;
            min-height: 100vh;
            background: var(--background);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Card base */
        .card-soft {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, .3);
            border-radius: 12px;
            box-shadow: 0 4px 20px -2px rgba(30, 42, 120, .05);
        }

        .card-soft.border-left-primary {
            border-left: 4px solid var(--primary-container) !important;
        }

        .card-soft.border-left-secondary {
            border-left: 4px solid var(--secondary) !important;
        }

        /* KPI cards */
        .kpi-icon-box {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .kpi-icon-primary {
            background: var(--surface-container);
            color: var(--primary-container);
        }

        .kpi-icon-secondary {
            background: rgba(134, 242, 228, .4);
            color: var(--secondary);
        }

        /* Quick action tiles */
        .action-tile {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 8px;
            border-radius: 12px;
            border: 0;
            background: transparent;
            transition: background .15s, transform .1s;
            width: 100%;
        }

        .action-tile:hover {
            background: var(--surface-container);
        }

        .action-tile:active {
            transform: scale(.95);
        }

        .action-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background .15s, color .15s;
        }

        .action-icon-primary {
            background: var(--surface-container);
            color: var(--primary-container);
        }

        .action-icon-secondary {
            background: rgba(134, 242, 228, .4);
            color: var(--secondary);
        }

        .action-tile:hover .action-icon-primary {
            background: var(--primary-container);
            color: var(--on-primary);
        }

        .action-tile:hover .action-icon-secondary {
            background: var(--secondary);
            color: var(--on-primary);
        }

        /* Progress bars */
        .progress-thin {
            height: 6px;
            border-radius: 999px;
            background: var(--surface-container);
            overflow: hidden;
        }

        .progress-medium {
            height: 10px;
            border-radius: 999px;
            background: var(--surface-container-high);
            overflow: hidden;
        }

        .progress-thin .progress-bar,
        .progress-medium .progress-bar {
            border-radius: 999px;
        }

        .progress-primary .progress-bar {
            background-color: var(--primary-container);
        }

        .progress-secondary .progress-bar {
            background-color: var(--secondary);
        }

        /* Pill badges */
        .pill-secondary {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(134, 242, 228, .4);
            color: var(--secondary);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        .pill-primary {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 999px;
            background: var(--surface-container);
            color: var(--primary-container);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        /* Mapel icon boxes */
        .mapel-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mapel-icon-primary {
            background: var(--surface-container);
            color: var(--primary-container);
        }

        .mapel-icon-secondary {
            background: rgba(134, 242, 228, .4);
            color: var(--secondary);
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

        /* Header */
        .app-bar {
            background: var(--surface);
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
            height: 64px;
        }

        .brand-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-container);
            color: var(--on-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            line-height: 18px;
        }

        .notif-btn {
            padding: 8px;
            border-radius: 12px;
            border: 0;
            background: transparent;
            color: var(--primary);
            transition: background .15s, transform .1s;
        }

        .notif-btn:hover {
            background: var(--surface-container);
        }

        .notif-btn:active {
            transform: scale(.95);
        }

        /* Section headers */
        .border-surface-variant {
            border-color: rgba(216, 227, 249, .4) !important;
        }

        .divide-surface> :not(caption)>*>* {
            border-bottom-width: 1px;
            border-color: rgba(231, 238, 255, .6);
        }

        .divide-surface> :not(:last-child) {
            border-bottom: 1px solid rgba(231, 238, 255, .6);
        }

        [data-bs-theme="dark"] .card-soft,
        body.dark-mode .card-soft {
            border-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.4);
        }

        [data-bs-theme="dark"] .app-bar,
        body.dark-mode .app-bar {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
        }

        [data-bs-theme="dark"] .bottom-nav,
        body.dark-mode .bottom-nav {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.4);
        }

        [data-bs-theme="dark"] .border-surface-variant,
        body.dark-mode .border-surface-variant {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        [data-bs-theme="dark"] .divide-surface> :not(caption)>*>*,
        [data-bs-theme="dark"] .divide-surface> :not(:last-child),
        body.dark-mode .divide-surface> :not(caption)>*>*,
        body.dark-mode .divide-surface> :not(:last-child) {
            border-color: rgba(255, 255, 255, 0.08);
        }
    </style>
</head>

<body>

    <div class="mobile-shell">

        <!-- Top App Bar -->
        <header class="app-bar d-flex align-items-center justify-content-between px-3">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-avatar">SMA</div>
                <div>
                    <span class="text-headline-lg-mobile fw-bold text-primary"
                        style="letter-spacing:-.02em;">EduPortal</span>
                    <p class="text-label-sm text-outline mb-0">SMA Negeri 1 Prestasi</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="notif-btn" id="themeToggleBtn" aria-label="Ganti Tema" title="Ganti Tema">
                    <span class="material-symbols-outlined" id="themeToggleIcon">dark_mode</span>
                </button>
                <button type="button" class="notif-btn" aria-label="Notifikasi">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
            </div>
        </header>

        <!-- Context & Back Bar -->
        <div class="px-3 pt-3 pb-1">
            <a href="#"
                class="link-secondary d-inline-flex align-items-center gap-1 text-decoration-none text-label-md py-1">
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
                <span>Kembali ke Beranda</span>
            </a>
            <div class="mt-2">
                <h1 class="text-headline-lg-mobile text-primary fw-bold mb-0" style="letter-spacing:-.02em;">Modul Data
                    Akademik</h1>
                <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Kurikulum Merdeka &amp; Manajemen Kelas</p>
            </div>
        </div>

        <!-- Main -->
        <main class="px-3 py-3 d-flex flex-column gap-3">

            <!-- KPI Bento -->
            <section class="row g-2">
                <!-- Siswa Aktif -->
                <div class="col-6">
                    <div class="card-soft border-left-primary p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-outline">Total Siswa Aktif</span>
                            <div class="kpi-icon-box kpi-icon-primary">
                                <span class="material-symbols-outlined" style="font-size:14px;">group</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="text-headline-md text-primary fw-bold">1.180</div>
                            <div class="text-body-sm text-on-surface-variant">36 Rombel / Kelas</div>
                        </div>
                    </div>
                </div>

                <!-- Guru -->
                <div class="col-6">
                    <div class="card-soft border-left-secondary p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-outline">Guru &amp; Pengajar</span>
                            <div class="kpi-icon-box kpi-icon-secondary">
                                <span class="material-symbols-outlined" style="font-size:14px;">person_celebrate</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="text-headline-md text-primary fw-bold">64</div>
                            <div class="text-body-sm text-on-surface-variant">Tenaga Pendidik</div>
                        </div>
                    </div>
                </div>

                <!-- Mapel -->
                <div class="col-6">
                    <div class="card-soft border-left-secondary p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-outline">Mata Pelajaran</span>
                            <div class="kpi-icon-box kpi-icon-secondary">
                                <span class="material-symbols-outlined" style="font-size:14px;">menu_book</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="text-headline-md text-primary fw-bold">28</div>
                            <div class="text-body-sm text-on-surface-variant">Mapel Aktif</div>
                        </div>
                    </div>
                </div>

                <!-- Nilai -->
                <div class="col-6">
                    <div class="card-soft border-left-primary p-3 h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-sm text-outline">Rata-rata Nilai</span>
                            <div class="kpi-icon-box kpi-icon-primary">
                                <span class="material-symbols-outlined" style="font-size:14px;">analytics</span>
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
            </section>

            <!-- Quick Actions -->
            <section class="card-soft p-3">
                <h2 class="text-title-sm text-primary mb-3">Aksi Cepat Akademik</h2>
                <div class="row g-1">
                    <div class="col-3">
                        <button type="button" class="action-tile">
                            <div class="action-icon action-icon-primary">
                                <span class="material-symbols-outlined">meeting_room</span>
                            </div>
                            <span class="text-label-sm text-on-surface-variant mt-2" style="line-height:1.15;">Kelola
                                Rombel</span>
                        </button>
                    </div>
                    <div class="col-3">
                        <button type="button" class="action-tile">
                            <div class="action-icon action-icon-secondary">
                                <span class="material-symbols-outlined">description</span>
                            </div>
                            <span class="text-label-sm text-on-surface-variant mt-2" style="line-height:1.15;">Input
                                E-Rapor</span>
                        </button>
                    </div>
                    <div class="col-3">
                        <button type="button" class="action-tile">
                            <div class="action-icon action-icon-primary">
                                <span class="material-symbols-outlined">calendar_month</span>
                            </div>
                            <span class="text-label-sm text-on-surface-variant mt-2" style="line-height:1.15;">Jadwal
                                Pelajaran</span>
                        </button>
                    </div>
                    <div class="col-3">
                        <button type="button" class="action-tile">
                            <div class="action-icon action-icon-primary">
                                <span class="material-symbols-outlined">event_note</span>
                            </div>
                            <span class="text-label-sm text-on-surface-variant mt-2"
                                style="line-height:1.15;">Kalender Akademik</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Verifikasi Nilai -->
            <section class="card-soft p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <h2 class="text-title-sm text-primary mb-0">Verifikasi Nilai Akhir</h2>
                        <p class="text-body-sm text-outline mb-0">Tahun Ajaran 2024/2025 Semester Genap</p>
                    </div>
                    <span class="pill-primary">85% Selesai</span>
                </div>
                <div class="progress progress-medium progress-secondary my-3">
                    <div class="progress-bar" role="progressbar" style="width:85%;" aria-valuenow="85"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div
                    class="d-flex align-items-center justify-content-between text-body-sm pt-2 border-top border-surface-variant">
                    <span class="text-on-surface-variant d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined text-secondary" style="font-size:14px;">verified</span>
                        31 dari 36 Rombel Terverifikasi
                    </span>
                    <button type="button"
                        class="btn btn-link p-0 text-decoration-none text-primary text-label-sm fw-semibold">Cek
                        Detail</button>
                </div>
            </section>

            <!-- Ringkasan Tingkat Kelas -->
            <section class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between px-1">
                    <h2 class="text-title-sm text-primary mb-0">Ringkasan Tingkat Kelas</h2>
                    <span class="text-label-sm text-secondary fw-semibold">Fase E &amp; Fase F</span>
                </div>

                <!-- Kelas X -->
                <div class="card-soft p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-title-sm text-primary">Kelas X (Fase E)</span>
                                <span class="pill-secondary">12 Kelas</span>
                            </div>
                            <p class="text-body-sm text-outline mt-1 mb-0">396 Siswa Terdaftar • Kurikulum Merdeka
                                Murni</p>
                        </div>
                        <span class="material-symbols-outlined text-outline">chevron_right</span>
                    </div>
                    <div class="mt-3 d-flex align-items-center justify-content-between text-label-sm">
                        <span class="text-on-surface-variant">Progres Capaian Pembelajaran</span>
                        <span class="text-primary fw-semibold">92%</span>
                    </div>
                    <div class="progress progress-thin progress-primary mt-1">
                        <div class="progress-bar" role="progressbar" style="width:92%;" aria-valuenow="92"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Kelas XI -->
                <div class="card-soft p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-title-sm text-primary">Kelas XI (Fase F)</span>
                                <span class="pill-secondary">12 Kelas</span>
                            </div>
                            <p class="text-body-sm text-outline mt-1 mb-0">392 Siswa Terdaftar • Peminatan Terpadu</p>
                        </div>
                        <span class="material-symbols-outlined text-outline">chevron_right</span>
                    </div>
                    <div class="mt-3 d-flex align-items-center justify-content-between text-label-sm">
                        <span class="text-on-surface-variant">Progres Capaian Pembelajaran</span>
                        <span class="text-primary fw-semibold">88%</span>
                    </div>
                    <div class="progress progress-thin progress-primary mt-1">
                        <div class="progress-bar" role="progressbar" style="width:88%;" aria-valuenow="88"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Kelas XII -->
                <div class="card-soft p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-title-sm text-primary">Kelas XII (Fase F Akhir)</span>
                                <span class="pill-secondary">12 Kelas</span>
                            </div>
                            <p class="text-body-sm text-outline mt-1 mb-0">392 Siswa Terdaftar • Persiapan UTBK &amp;
                                Portofolio</p>
                        </div>
                        <span class="material-symbols-outlined text-outline">chevron_right</span>
                    </div>
                    <div class="mt-3 d-flex align-items-center justify-content-between text-label-sm">
                        <span class="text-on-surface-variant">Progres Capaian Pembelajaran</span>
                        <span class="text-secondary fw-semibold">96%</span>
                    </div>
                    <div class="progress progress-thin progress-secondary mt-1">
                        <div class="progress-bar" role="progressbar" style="width:96%;" aria-valuenow="96"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </section>

            <!-- Mapel Unggulan -->
            <section class="card-soft p-3">
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

        </main>

        <!-- Bottom Nav -->
        <nav class="bottom-nav" aria-label="Navigasi Utama">
            <a href="#" class="bottom-nav-item">
                <span class="material-symbols-outlined" style="font-size:20px;">dashboard</span>
                <span class="text-label-sm mt-1">Beranda</span>
            </a>
            <a href="#" class="bottom-nav-item active" aria-current="page">
                <span class="material-symbols-outlined icon-fill" style="font-size:20px;">school</span>
                <span class="text-label-sm fw-bold mt-1">Akademik</span>
            </a>
            <a href="#" class="bottom-nav-item">
                <span class="material-symbols-outlined" style="font-size:20px;">account_balance_wallet</span>
                <span class="text-label-sm mt-1">Keuangan</span>
            </a>
            <a href="#" class="bottom-nav-item">
                <span class="material-symbols-outlined" style="font-size:20px;">chat</span>
                <span class="text-label-sm mt-1">Pesan</span>
            </a>
            <a href="#" class="bottom-nav-item">
                <span class="material-symbols-outlined" style="font-size:20px;">person</span>
                <span class="text-label-sm mt-1">Profil</span>
            </a>
        </nav>

    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const toggleBtn = document.getElementById('themeToggleBtn');
            const toggleIcon = document.getElementById('themeToggleIcon');
            function syncIcon(theme) {
                if (!toggleIcon) return;
                toggleIcon.textContent = theme === 'dark' ? 'light_mode' : 'dark_mode';
            }
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            syncIcon(currentTheme);
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                    const nextTheme = isDark ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-bs-theme', nextTheme);
                    if (nextTheme === 'dark') {
                        document.documentElement.classList.add('dark-mode');
                        document.body && document.body.classList.add('dark-mode');
                    } else {
                        document.documentElement.classList.remove('dark-mode');
                        document.body && document.body.classList.remove('dark-mode');
                    }
                    localStorage.setItem('theme', nextTheme);
                    syncIcon(nextTheme);
                });
            }
        })();
    </script>
</body>

</html>
