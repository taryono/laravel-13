<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EduPortal - Computer Based Test (CBT)</title>

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
            --on-primary-container: #8a95e9;
            --secondary: #006a61;
            --secondary-container: #86f2e4;
            --secondary-fixed: #89f5e7;
            --on-secondary: #ffffff;
            --on-secondary-fixed: #00201d;
            --on-secondary-container: #006f66;
            --tertiary: #291c00;
            --tertiary-fixed: #ffdea2;
            --error: #ba1a1a;
            --error-container: #ffdad6;
            --surface: #f9f9ff;
            --surface-container-lowest: #ffffff;
            --surface-container-low: #f0f3ff;
            --surface-container: #e7eeff;
            --surface-container-high: #dee9ff;
            --on-surface: #111c2c;
            --on-surface-variant: #454651;
            --outline: #767682;
            --outline-variant: #c6c5d3;
            --background: #f9f9ff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--on-surface);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            display: flex;
            justify-content: center;
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

        /* Typography helpers */
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

        .text-on-secondary-fixed {
            color: var(--on-secondary-fixed) !important;
        }

        .text-on-secondary-container {
            color: var(--on-secondary-container) !important;
        }

        /* Mobile shell */
        .mobile-shell {
            width: 100%;
            max-width: 448px;
            min-height: 100vh;
            background: var(--surface-container-low);
            display: flex;
            flex-direction: column;
            padding-bottom: 2rem;
            position: relative;
        }

        /* Header */
        .app-bar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(249, 249, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 12px 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--surface-container);
            color: var(--primary);
            border: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .1s;
        }

        .back-btn:active {
            transform: scale(.95);
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--surface-container-lowest);
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid rgba(0, 106, 97, .2);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
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
                opacity: .4;
            }
        }

        /* Cards */
        .card-soft {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, 0.3);
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .card-soft.border-left-secondary {
            border-left: 4px solid var(--secondary) !important;
        }

        /* Metric card */
        .metric-card {
            position: relative;
            overflow: hidden;
        }

        .metric-bg-icon {
            position: absolute;
            right: -8px;
            bottom: -8px;
            opacity: .05;
            color: var(--primary);
            pointer-events: none;
        }

        .metric-bg-icon .material-symbols-outlined {
            font-size: 64px;
        }

        .metric-icon-box {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--surface-container);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        /* Server status card */
        .server-card {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(0, 106, 97, .3);
            border-radius: 16px;
            padding: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .server-icon {
            width: 32px;
            height: 32px;
            border-radius: 12px;
            background: rgba(134, 242, 228, .4);
            color: var(--secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .latency-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(137, 245, 231, .5);
            color: var(--on-secondary-fixed);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        /* Quick action chips */
        .quick-actions {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 2px 0;
        }

        .quick-actions::-webkit-scrollbar {
            display: none;
        }

        .quick-actions {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .action-btn {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            border: 0;
            transition: transform .1s;
        }

        .action-btn:active {
            transform: scale(.95);
        }

        .action-btn.primary {
            background: var(--primary-container);
            color: var(--on-primary);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .action-btn.outline {
            background: var(--surface-container-lowest);
            color: var(--primary);
            border: 1px solid rgba(198, 197, 211, .4);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        /* Segmented tabs */
        .segmented {
            background: var(--surface-container);
            padding: 4px;
            border-radius: 12px;
            display: flex;
            gap: 4px;
        }

        .segmented button {
            flex: 1;
            padding: 6px 8px;
            border-radius: 8px;
            border: 0;
            background: transparent;
            color: var(--on-surface-variant);
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            text-align: center;
            transition: background .15s, color .15s;
        }

        .segmented button.active {
            background: var(--surface-container-lowest);
            color: var(--primary);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .segmented button:hover:not(.active) {
            background: var(--surface-container-high);
        }

        /* Status badges */
        .badge-running {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 999px;
            background: var(--secondary-fixed);
            color: var(--on-secondary-fixed);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        .ping-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--secondary);
            animation: ping 1.5s infinite;
        }

        @keyframes ping {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 106, 97, .6);
            }

            100% {
                box-shadow: 0 0 0 8px rgba(0, 106, 97, 0);
            }
        }

        .code-badge {
            padding: 4px 8px;
            border-radius: 8px;
            background: var(--surface-container);
            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            flex-shrink: 0;
        }

        /* Metadata box */
        .meta-box {
            background: var(--surface-container-low);
            padding: 10px;
            border-radius: 12px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            font-size: 12px;
            line-height: 16px;
            color: var(--on-surface-variant);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .meta-item.full {
            grid-column: span 2;
        }

        /* Progress */
        .progress-thin {
            height: 10px;
            border-radius: 999px;
            background: var(--surface-container);
            overflow: hidden;
        }

        .progress-thin .progress-bar {
            background-color: var(--secondary);
        }

        /* CTA button */
        .cta-primary {
            width: 100%;
            padding: 10px 16px;
            border-radius: 12px;
            background: var(--primary-container);
            color: var(--on-primary);
            border: 0;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            transition: transform .1s;
        }

        .cta-primary:active {
            transform: scale(.95);
        }

        /* Upcoming exam cards */
        .upcoming-card {
            transition: border-color .2s;
        }

        .upcoming-card:hover {
            border-color: rgba(0, 106, 97, .4) !important;
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            background: var(--surface-container);
            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        .more-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--surface-container-low);
            color: var(--outline);
            border: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: color .15s;
        }

        .more-btn:hover {
            color: var(--primary);
        }

        /* Security banner */
        .security-banner {
            background: var(--surface-container);
            padding: 14px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .security-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: var(--primary-container);
            color: var(--on-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
    </style>
</head>

<body>

    <!-- Mobile Shell -->
    <div class="mobile-shell">

        <!-- Header -->
        <header class="app-bar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="back-btn" aria-label="Kembali ke Beranda">
                    <span class="material-symbols-outlined">arrow_back</span>
                </button>
                <div>
                    <span class="text-body-sm text-on-surface-variant d-flex align-items-center gap-1">
                        Kembali ke Beranda
                    </span>
                    <h1 class="text-headline-md text-primary mb-0" style="letter-spacing:-.02em;">Computer Based Test
                        (CBT)</h1>
                </div>
            </div>
            <div class="live-badge">
                <span class="live-dot"></span>
                <span class="text-label-sm text-secondary">LIVE</span>
            </div>
        </header>

        <!-- Main -->
        <main class="px-3 py-3 d-flex flex-column gap-4">

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
                            <span class="material-symbols-outlined text-outline"
                                style="font-size:14px;">schedule</span>
                            <span>08:00 - 10:30 WIB</span>
                        </div>
                        <div class="meta-item">
                            <span class="material-symbols-outlined text-outline"
                                style="font-size:14px;">person_check</span>
                            <span>72 Peserta Terdaftar</span>
                        </div>
                        <div class="meta-item full">
                            <span class="material-symbols-outlined text-outline"
                                style="font-size:14px;">meeting_room</span>
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
                            <div class="progress-bar" role="progressbar" style="width: 84%;" aria-valuenow="84"
                                aria-valuemin="0" aria-valuemax="100"></div>
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
                    <button type="button"
                        class="btn btn-link p-0 text-label-md text-primary text-decoration-none">Lihat
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
                            <span class="material-symbols-outlined text-outline"
                                style="font-size:14px;">schedule</span>
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
                            <span class="material-symbols-outlined text-outline"
                                style="font-size:14px;">schedule</span>
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

        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
