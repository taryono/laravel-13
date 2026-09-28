<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Manajemen Panitia PPDB 2025/2026 - SMAN 3 Bandung</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Material Symbols Outlined -->
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    <!-- Bootstrap Icons (optional, for extra icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bs-primary: #00288e;
            --bs-primary-rgb: 0, 40, 142;
            --bs-primary-container: #1e40af;
            --bs-secondary: #006398;
            --bs-secondary-rgb: 0, 99, 152;
            --bs-surface: #f8f9ff;
            --bs-on-surface: #0d1c2e;
            --bs-surface-container-lowest: #ffffff;
            --bs-surface-container-low: #eff4ff;
            --bs-surface-container: #e6eeff;
            --bs-surface-container-high: #dce9ff;
            --bs-surface-container-highest: #d5e3fc;
            --bs-outline: #757684;
            --bs-outline-variant: #c4c5d5;
            --bs-on-surface-variant: #444653;
            --bs-tertiary: #4c2e00;
            --bs-amber-50: #fffbeb;
            --bs-amber-100: #fef3c7;
            --bs-amber-200: #fde68a;
            --bs-amber-800: #92400e;
            --bs-amber-900: #78350f;
            --bs-emerald-50: #ecfdf5;
            --bs-emerald-600: #059669;
            --bs-emerald-700: #047857;
            --bs-emerald-800: #065f46;
            --bs-blue-50: #eff6ff;
            --bs-slate-200: #e2e8f0;
            --bs-slate-300: #cbd5e1;
            --bs-slate-600: #475569;
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bs-surface);
            color: var(--bs-on-surface);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem;
            z-index: 1030;
            background: var(--bs-surface-container-lowest);
            border-right: 1px solid var(--bs-outline-variant);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            padding: 1rem;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar .drawer-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0.5rem;
            border-bottom: 1px solid var(--bs-surface-container);
            margin-bottom: 1rem;
        }

        .sidebar .drawer-header .logo-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: var(--bs-primary-container);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .sidebar .drawer-header h1 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--bs-primary);
            line-height: 1.2;
            margin: 0;
        }

        .sidebar .drawer-header p {
            font-size: 0.65rem;
            color: var(--bs-on-surface-variant);
            margin: 0;
        }

        .sidebar .section-label {
            padding: 0 0.75rem;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--bs-outline);
            margin-bottom: 0.5rem;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--bs-on-surface-variant);
            transition: all 0.15s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .sidebar .nav-link:hover {
            background: var(--bs-surface-container-low);
            color: var(--bs-on-surface);
        }

        .sidebar .nav-link.active {
            background: var(--bs-surface-container-low);
            color: var(--bs-primary);
        }

        .sidebar .nav-link .material-symbols-outlined {
            font-size: 1.25rem;
        }

        .sidebar .badge-nav {
            margin-left: auto;
            padding: 0.1rem 0.45rem;
            font-size: 0.6rem;
            font-weight: 700;
            border-radius: 999px;
            background: var(--bs-amber-100);
            color: var(--bs-amber-800);
            border: 1px solid var(--bs-amber-300, #fcd34d);
        }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper {
            margin-left: 16rem;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOP NAVBAR ===== */
        .top-navbar {
            position: sticky;
            top: 0;
            right: 0;
            width: 100%;
            z-index: 1020;
            background: var(--bs-surface-container-lowest);
            border-bottom: 1px solid var(--bs-outline-variant);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 0.75rem 1.5rem;
        }

        .top-navbar .institution-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--bs-primary);
        }

        .top-navbar .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.1rem 0.5rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 600;
            background: var(--bs-emerald-50);
            color: var(--bs-emerald-700);
            border: 1px solid #a7f3d0;
        }

        .top-navbar .status-dot {
            width: 0.35rem;
            height: 0.35rem;
            border-radius: 999px;
            background: var(--bs-emerald-600);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .top-navbar .search-box {
            position: relative;
            width: 18rem;
        }

        .top-navbar .search-box input {
            width: 100%;
            height: 2.25rem;
            padding-left: 2.25rem;
            font-size: 0.75rem;
            background: var(--bs-surface-container-low);
            border: 1px solid var(--bs-outline-variant);
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .top-navbar .search-box input:focus {
            outline: none;
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 2px rgba(0, 40, 142, 0.15);
            background: var(--bs-surface-container-lowest);
        }

        .top-navbar .search-box .material-symbols-outlined {
            position: absolute;
            left: 0.6rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.1rem;
            color: var(--bs-outline);
            pointer-events: none;
        }

        /* ===== CARDS & PANELS ===== */
        .panel {
            background: var(--bs-surface-container-lowest);
            border: 1px solid var(--bs-outline-variant);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--bs-surface-container);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .panel-header .icon-box {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .panel-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--bs-on-surface);
            margin: 0;
        }

        .panel-header p {
            font-size: 0.75rem;
            color: var(--bs-on-surface-variant);
            margin: 0;
        }

        /* ===== KPI CARDS ===== */
        .kpi-card {
            background: var(--bs-surface-container-lowest);
            border: 1px solid var(--bs-outline-variant);
            border-radius: 0.75rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: border-color 0.2s;
            height: 100%;
        }

        .kpi-card:hover {
            border-color: var(--bs-primary);
        }

        .kpi-card.kpi-amber {
            border-color: var(--bs-amber-200);
            background: rgba(255, 251, 235, 0.2);
        }

        .kpi-card.kpi-amber:hover {
            border-color: #fbbf24;
        }

        .kpi-card .kpi-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kpi-card .kpi-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 1rem;
        }

        .kpi-card .kpi-value small {
            font-size: 0.75rem;
            font-weight: 400;
            color: var(--bs-on-surface-variant);
        }

        .kpi-card .kpi-sub {
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.5rem;
        }

        /* ===== TIMELINE / STEPPER ===== */
        .timeline-step {
            display: flex;
            gap: 1rem;
            padding: 0.85rem;
            border-radius: 0.5rem;
            border: 1px solid var(--bs-surface-container-high);
            background: #f8faff;
            align-items: flex-start;
        }

        .timeline-step.step-active {
            border: 2px solid var(--bs-primary-container);
            background: rgba(239, 244, 255, 0.5);
        }

        .timeline-step .step-marker {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }

        .timeline-step .step-circle {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .timeline-step .step-line {
            width: 2px;
            height: 2.5rem;
            background: var(--bs-outline-variant);
            margin: 0.25rem 0;
        }

        .timeline-step .step-line.line-done {
            background: var(--bs-emerald-600);
        }

        /* ===== PROGRESS BARS ===== */
        .progress-thin {
            height: 0.5rem;
            border-radius: 999px;
            background: var(--bs-surface-container-high);
            overflow: hidden;
        }

        .progress-thin .progress-bar {
            border-radius: 999px;
        }

        /* ===== TABLES ===== */
        .table-verifikasi {
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        .table-verifikasi thead th {
            background: var(--bs-surface-container-low);
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--bs-on-surface);
            border-bottom: 1px solid var(--bs-surface-container);
            padding: 0.75rem 1rem;
            white-space: nowrap;
        }

        .table-verifikasi tbody td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--bs-surface-container);
        }

        .table-verifikasi tbody tr:hover {
            background: rgba(248, 249, 255, 0.8);
        }

        .table-verifikasi tbody tr.row-focus {
            background: rgba(239, 246, 255, 0.2);
        }

        /* ===== BADGES ===== */
        .badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-soft.amber {
            background: var(--bs-amber-50);
            color: var(--bs-amber-800);
            border: 1px solid #fcd34d;
        }

        .badge-soft.emerald {
            background: var(--bs-emerald-50);
            color: var(--bs-emerald-800);
            border: 1px solid #6ee7b7;
        }

        .badge-soft.red {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .badge-soft.blue {
            background: var(--bs-blue-50);
            color: var(--bs-primary);
            border: 1px solid #bfdbfe;
        }

        /* ===== BUTTONS ===== */
        .btn-primary-custom {
            background: var(--bs-primary-container);
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 0.8rem;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.15s;
        }

        .btn-primary-custom:hover {
            background: var(--bs-primary);
            color: #fff;
        }

        .btn-outline-custom {
            border: 1px solid var(--bs-outline-variant);
            color: var(--bs-on-surface-variant);
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: transparent;
            transition: all 0.15s;
        }

        .btn-outline-custom:hover {
            background: var(--bs-surface-container-low);
            color: var(--bs-on-surface);
        }

        /* ===== DUAL COLUMN GRID ===== */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }
        }

        /* ===== SCROLLBAR ===== */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* ===== RESPONSIVE TABLE ===== */
        @media (max-width: 767.98px) {
            .table-responsive-custom {
                overflow-x: auto;
            }

            .top-navbar .search-box {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar" id="sidebar">
        <div class="drawer-header">
            <div class="logo-icon">
                <span class="material-symbols-outlined" style="font-size:1.5rem;">account_balance</span>
            </div>
            <div>
                <h1>PPDB Admin Portal</h1>
                <p>Dinas Pendidikan &amp; Kebudayaan</p>
            </div>
        </div>

        <p class="section-label">Alur Kerja Utama</p>

        <nav class="nav flex-column gap-1">
            <a class="nav-link active" href="#overview">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard Overview</span>
            </a>
            <a class="nav-link" href="#jadwal">
                <span class="material-symbols-outlined">calendar_month</span>
                <span>Jadwal &amp; Gelombang</span>
            </a>
            <a class="nav-link" href="#kuota">
                <span class="material-symbols-outlined">tune</span>
                <span>Jalur &amp; Kuota</span>
            </a>
            <a class="nav-link" href="#verifikasi">
                <span class="material-symbols-outlined">fact_check</span>
                <span>Verifikasi Berkas</span>
                <span class="badge-nav">42</span>
            </a>
            <a class="nav-link" href="#seleksi">
                <span class="material-symbols-outlined">leaderboard</span>
                <span>Seleksi &amp; Peringkat</span>
            </a>
            <a class="nav-link" href="#kelulusan">
                <span class="material-symbols-outlined">campaign</span>
                <span>Kelulusan &amp; Hasil</span>
            </a>
            <a class="nav-link" href="#laporan">
                <span class="material-symbols-outlined">folder_shared</span>
                <span>Laporan &amp; Arsip</span>
            </a>
        </nav>

        <div class="mt-auto pt-3 border-top" style="border-color: var(--bs-surface-container) !important;">
            <a class="nav-link justify-content-center mb-2" href="#bantuan"
                style="background: var(--bs-surface-container); color: var(--bs-primary); font-weight: 700;">
                <span class="material-symbols-outlined" style="font-size:1.1rem;">support_agent</span>
                <span>Bantuan Teknis</span>
            </a>
            <div class="d-flex flex-column gap-1">
                <a class="nav-link" href="#pengaturan">
                    <span class="material-symbols-outlined" style="font-size:1.1rem;">settings</span>
                    <span>Pengaturan Sistem</span>
                </a>
                <a class="nav-link" href="#panduan">
                    <span class="material-symbols-outlined" style="font-size:1.1rem;">help</span>
                    <span>Pusat Panduan</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- ==================== MAIN WRAPPER ==================== -->
    <div class="main-wrapper">

        <!-- ==================== TOP NAVBAR ==================== -->
        <header class="top-navbar">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <!-- Left -->
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <div>
                        <span class="institution-name">Portal Panitia PPDB</span>
                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                            <span class="status-badge">
                                <span class="status-dot"></span>
                                Dapodik Terhubung
                            </span>
                            <span class="text-muted" style="font-size:0.65rem;">•</span>
                            <span style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Tahun Ajaran
                                2024/2025</span>
                            <span class="text-muted" style="font-size:0.65rem;">•</span>
                            <span style="font-size:0.7rem; font-weight:600; color:var(--bs-primary);">SMAN 1 Garut
                                (Pelaksana PPDB Wilayah)</span>
                        </div>
                    </div>
                    <!-- Search -->
                    <div class="search-box d-none d-xl-block">
                        <span class="material-symbols-outlined">search</span>
                        <input type="text" placeholder="Cari NISN, NIK, atau Nama...">
                    </div>
                </div>

                <!-- Right -->
                <div class="d-flex align-items-center gap-3">
                    <div class="d-none d-lg-flex align-items-center gap-2 px-3 py-1 rounded-2"
                        style="background:var(--bs-surface-container-low); border:1px solid var(--bs-surface-container-high);">
                        <span class="material-symbols-outlined"
                            style="font-size:1.1rem; color:var(--bs-secondary);">schedule</span>
                        <span style="font-size:0.7rem; color:var(--bs-on-surface-variant); font-weight:500;">Senin, 16
                            Juni 2025 | 10:42 WIB</span>
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <button class="btn btn-link position-relative p-2 text-decoration-none"
                            style="color:var(--bs-on-surface-variant);" title="Notifikasi Aktivitas">
                            <span class="material-symbols-outlined" style="font-size:1.4rem;">notifications</span>
                            <span class="position-absolute top-0 end-0 translate-middle p-1 rounded-circle"
                                style="width:0.5rem;height:0.5rem;background:var(--bs-error,#ba1a1a);border:2px solid #fff;"></span>
                        </button>
                        <button class="btn btn-link p-2 text-decoration-none"
                            style="color:var(--bs-on-surface-variant);" title="Bantuan">
                            <span class="material-symbols-outlined" style="font-size:1.4rem;">help</span>
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-3 ps-3 border-start"
                        style="border-color:var(--bs-outline-variant) !important;">
                        <div class="rounded-circle overflow-hidden border"
                            style="width:2.5rem;height:2.5rem;background:var(--bs-surface-container);">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAmg5J44HalDBs2HOUvSTvyTdom38w-E0rM6E3CAdkGGGVOx67cJOauB84IrAY79__5YoSLGVjzgp-SttX8r_AEZ4BM3TVXHX5RCQam5KOH9eAjoLt5eBjhFP3MaAx6IK0lUJS7x-o9DzmCH4EQsJrDW372azEqy5d2MX1ttw_F7Xc041AArwZqPjh-zYsS-jf8jI0Gp6wOYApnKCLWNgkNb9N7VUseTTxbzEJ62-8T6NOuF0JJrXV1"
                                alt="Profile" class="w-100 h-100" style="object-fit:cover;">
                        </div>
                        <div class="d-none d-sm-block text-start">
                            <p style="font-size:0.75rem; font-weight:700; margin:0; line-height:1.2;">Drs. H. Maman
                                Suryaman, M.Pd</p>
                            <p style="font-size:0.65rem; color:var(--bs-on-surface-variant); margin:0;">Ketua Panitia
                                Pelaksana</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ==================== MAIN CONTENT ==================== -->
        <main class="flex-grow-1 p-3 p-lg-4" style="max-width:1440px; margin:0 auto; width:100%;">

            <!-- ===== TOP BANNER ===== -->
            <section class="rounded-3 p-4 text-white mb-4 position-relative overflow-hidden"
                style="background: linear-gradient(90deg, var(--bs-primary), var(--bs-primary-container), var(--bs-secondary));">
                <div class="position-absolute end-0 bottom-0 opacity-10 pe-none">
                    <span class="material-symbols-outlined" style="font-size:12rem;">school</span>
                </div>
                <div
                    class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2"
                            style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.2); font-size:0.7rem; font-weight:600;">
                            <span class="rounded-circle bg-success"
                                style="width:0.5rem;height:0.5rem; animation:pulse 1.5s infinite;"></span>
                            TAHAP 1: JALUR ZONASI &amp; AFIRMASI KETUA
                        </div>
                        <h2 class="fw-bold mb-2" style="font-size:1.35rem; color:#fff;">SMAN 3 Bandung — Masa
                            Pendaftaran &amp; Verifikasi Dokumen Fisik/Online</h2>
                        <p class="mb-0"
                            style="font-size:0.8rem; color:var(--bs-surface-container-highest); max-width:42rem;">
                            Proses verifikasi faktual berkas pendaftar jalur Zonasi dan KIP/Afirmasi sedang berlangsung
                            aktif. Seluruh panitia verifikator wajib menuntaskan validasi sebelum rapat pleno penetapan
                            kuota.
                        </p>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center gap-4 flex-shrink-0"
                        style="background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.2);">
                        <div class="text-center">
                            <span class="d-block mb-1"
                                style="font-size:0.65rem; text-transform:uppercase; letter-spacing:0.05em; color:#b8c4ff;">Sisa
                                Waktu Pendaftaran</span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-2 px-2 py-1" style="background:rgba(0,0,0,0.2);">
                                    <span class="d-block fw-bold" style="font-size:1.1rem;">02</span>
                                    <span
                                        style="font-size:0.55rem; color:var(--bs-surface-container-highest);">Hari</span>
                                </div>
                                <span class="fw-bold">:</span>
                                <div class="rounded-2 px-2 py-1" style="background:rgba(0,0,0,0.2);">
                                    <span class="d-block fw-bold" style="font-size:1.1rem;">14</span>
                                    <span
                                        style="font-size:0.55rem; color:var(--bs-surface-container-highest);">Jam</span>
                                </div>
                                <span class="fw-bold">:</span>
                                <div class="rounded-2 px-2 py-1" style="background:rgba(0,0,0,0.2);">
                                    <span class="d-block fw-bold" style="font-size:1.1rem;">35</span>
                                    <span
                                        style="font-size:0.55rem; color:var(--bs-surface-container-highest);">Menit</span>
                                </div>
                            </div>
                        </div>
                        <button class="btn fw-bold d-flex align-items-center gap-2 flex-shrink-0"
                            style="background:var(--bs-surface-container-lowest); color:var(--bs-primary); font-size:0.8rem; border-radius:0.5rem; padding:0.65rem 1rem;">
                            <span class="material-symbols-outlined" style="font-size:1.1rem;">sync</span>
                            Tarik Data Dapodik
                        </button>
                    </div>
                </div>
            </section>

            <!-- ===== KPI CARDS ===== -->
            <section class="row g-3 mb-4">
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="kpi-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <span style="font-size:0.75rem; color:var(--bs-on-surface-variant); font-weight:600;">Total
                                Pendaftar Masuk</span>
                            <div class="kpi-icon"
                                style="background:var(--bs-surface-container-low); color:var(--bs-primary);">
                                <span class="material-symbols-outlined" style="font-size:1.2rem;">group_add</span>
                            </div>
                        </div>
                        <div>
                            <div class="kpi-value">1.248 <small>siswa</small></div>
                            <div class="kpi-sub" style="color:var(--bs-emerald-700);">
                                <span class="material-symbols-outlined" style="font-size:1rem;">trending_up</span>
                                <span>+18.4% dibandingkan tahun lalu</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="kpi-card kpi-amber">
                        <div class="d-flex align-items-center justify-content-between">
                            <span style="font-size:0.75rem; color:var(--bs-amber-900); font-weight:700;">Menunggu
                                Verifikasi</span>
                            <div class="kpi-icon" style="background:var(--bs-amber-100); color:var(--bs-amber-800);">
                                <span class="material-symbols-outlined"
                                    style="font-size:1.2rem;">pending_actions</span>
                            </div>
                        </div>
                        <div>
                            <div class="kpi-value" style="color:var(--bs-amber-900);">42 <small
                                    style="color:var(--bs-amber-800);">berkas</small></div>
                            <div class="kpi-sub" style="color:var(--bs-amber-800); font-weight:600;">
                                <span class="rounded-circle bg-warning"
                                    style="width:0.5rem;height:0.5rem; animation:pulse 1.5s infinite;"></span>
                                <span>Perlu tindakan segera verifikator</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="kpi-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <span
                                style="font-size:0.75rem; color:var(--bs-on-surface-variant); font-weight:600;">Pendaftar
                                Terverifikasi Sah</span>
                            <div class="kpi-icon"
                                style="background:var(--bs-emerald-50); color:var(--bs-emerald-700);">
                                <span class="material-symbols-outlined" style="font-size:1.2rem;">verified</span>
                            </div>
                        </div>
                        <div>
                            <div class="kpi-value" style="color:var(--bs-emerald-800);">1.156 <small>siswa</small>
                            </div>
                            <div class="kpi-sub" style="color:var(--bs-on-surface-variant);">
                                <span>92.6% kelengkapan akta &amp; KK valid</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="kpi-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <span
                                style="font-size:0.75rem; color:var(--bs-on-surface-variant); font-weight:600;">Kapasitas
                                Pagu Terisi</span>
                            <div class="kpi-icon" style="background:#cce5ff; color:#004b73;">
                                <span class="material-symbols-outlined" style="font-size:1.2rem;">pie_chart</span>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-baseline justify-content-between">
                                <div class="kpi-value" style="color:var(--bs-primary);">384 <small>/ 400 Kursi</small>
                                </div>
                                <span style="font-size:0.75rem; font-weight:700; color:var(--bs-primary);">96%</span>
                            </div>
                            <div class="progress-thin mt-2">
                                <div class="progress-bar" style="width:96%; background:var(--bs-primary);"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===== DUAL COLUMN: JADWAL + KUOTA ===== -->
            <div class="row g-4 mb-4">
                <!-- PANEL 1: JADWAL -->
                <div class="col-12 col-lg-7" id="jadwal">
                    <div class="panel h-100">
                        <div class="panel-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box"
                                    style="background:var(--bs-surface-container-low); color:var(--bs-primary);">
                                    <span class="material-symbols-outlined"
                                        style="font-size:1.4rem;">event_note</span>
                                </div>
                                <div>
                                    <h3>Manajemen Jadwal &amp; Gelombang Seleksi</h3>
                                    <p>Konfigurasi batas waktu registrasi, seleksi berkas, dan sidang kelulusan</p>
                                </div>
                            </div>
                            <button class="btn btn-outline-custom d-flex align-items-center gap-2"
                                style="font-size:0.7rem;">
                                <span class="material-symbols-outlined" style="font-size:1.1rem;">add_circle</span>
                                Tambah Agenda
                            </button>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <!-- Step 1 -->
                            <div class="timeline-step" style="background:#f8faff;">
                                <div class="step-marker">
                                    <div class="step-circle" style="background:var(--bs-emerald-600); color:#fff;">
                                        <span class="material-symbols-outlined" style="font-size:1.1rem;">check</span>
                                    </div>
                                    <div class="step-line line-done"></div>
                                </div>
                                <div class="flex-grow-1 row g-2 align-items-start">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span style="font-size:0.8rem; font-weight:700;">Sosialisasi &amp; Uji Coba
                                                Akun Siswa</span>
                                            <span class="badge-soft emerald" style="font-size:0.55rem;">Selesai</span>
                                        </div>
                                        <p class="mb-0 mt-1"
                                            style="font-size:0.75rem; color:var(--bs-on-surface-variant);">Pembuatan
                                            akun mandiri orang tua dan integrasi NIK Disdukcapil.</p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <span style="font-size:0.7rem; color:var(--bs-outline);">01 Jun - 08 Jun
                                            2025</span>
                                        <span class="d-block"
                                            style="font-size:0.7rem; color:var(--bs-emerald-700); font-weight:600;">100%
                                            Tercapai</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 (Active) -->
                            <div class="timeline-step step-active">
                                <div class="step-marker">
                                    <div class="step-circle"
                                        style="background:var(--bs-primary); color:#fff; box-shadow:0 0 0 4px #dde1ff;">
                                        2</div>
                                    <div class="step-line"></div>
                                </div>
                                <div class="flex-grow-1 row g-2 align-items-start">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span
                                                style="font-size:0.8rem; font-weight:700; color:var(--bs-primary);">Pendaftaran
                                                Tahap 1 &amp; Verifikasi Fisik</span>
                                            <span class="badge-soft blue"
                                                style="font-size:0.55rem; animation:pulse 1.5s infinite;">Sedang
                                                Berjalan</span>
                                        </div>
                                        <p class="mb-0 mt-1"
                                            style="font-size:0.75rem; color:var(--bs-on-surface-variant);">Zonasi murni
                                            50% &amp; Afirmasi KIP/Disabilitas 20%. Penyerahan berkas verifikasi.</p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <span class="d-block" style="font-size:0.7rem; font-weight:700;">10 Jun - 18
                                            Jun 2025</span>
                                        <span class="badge-soft amber d-inline-block mt-1"
                                            style="font-size:0.6rem;">Sisa 2 Hari</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="timeline-step" style="background:var(--bs-surface-container-lowest);">
                                <div class="step-marker">
                                    <div class="step-circle"
                                        style="background:var(--bs-surface-container-high); color:var(--bs-outline);">3
                                    </div>
                                    <div class="step-line"></div>
                                </div>
                                <div class="flex-grow-1 row g-2 align-items-start">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span style="font-size:0.8rem; font-weight:700;">Sidang Pleno Dewan Guru
                                                &amp; Penetapan</span>
                                            <span class="badge-soft"
                                                style="background:var(--bs-surface-container); color:var(--bs-outline); border:1px solid var(--bs-outline-variant); font-size:0.55rem;">Mendatang</span>
                                        </div>
                                        <p class="mb-0 mt-1"
                                            style="font-size:0.75rem; color:var(--bs-on-surface-variant);">Penguncian
                                            nilai akhir ranking zonasi dan verifikasi dokumen sanggahan.</p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <span class="d-block" style="font-size:0.7rem; color:var(--bs-outline);">20
                                            Juni 2025</span>
                                        <span class="d-block" style="font-size:0.7rem; color:var(--bs-outline);">Pukul
                                            13:00 WIB</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="timeline-step" style="background:var(--bs-surface-container-lowest);">
                                <div class="step-marker">
                                    <div class="step-circle"
                                        style="background:var(--bs-surface-container-high); color:var(--bs-outline);">4
                                    </div>
                                </div>
                                <div class="flex-grow-1 row g-2 align-items-start">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span style="font-size:0.8rem; font-weight:700;">Pengumuman Kelulusan &amp;
                                                Daftar Ulang</span>
                                            <span class="badge-soft"
                                                style="background:var(--bs-surface-container); color:var(--bs-outline); border:1px solid var(--bs-outline-variant); font-size:0.55rem;">Terjadwal</span>
                                        </div>
                                        <p class="mb-0 mt-1"
                                            style="font-size:0.75rem; color:var(--bs-on-surface-variant);">Penayangan
                                            hasil di web portal terbuka &amp; notifikasi otomatis calon murid.</p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <span class="d-block" style="font-size:0.7rem; color:var(--bs-outline);">23
                                            Jun - 25 Jun 2025</span>
                                        <span class="d-block"
                                            style="font-size:0.7rem; color:var(--bs-outline);">Online &amp;
                                            Loket</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top"
                            style="border-color:var(--bs-surface-container) !important;">
                            <button class="btn-outline-custom">Reset Jadwal Standar Jabar</button>
                            <button class="btn-primary-custom">Simpan Perubahan Jadwal</button>
                        </div>
                    </div>
                </div>

                <!-- PANEL 2: KUOTA -->
                <div class="col-12 col-lg-5" id="kuota">
                    <div class="panel h-100">
                        <div class="panel-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box" style="background:#cce5ff; color:#004b73;">
                                    <span class="material-symbols-outlined" style="font-size:1.4rem;">pie_chart</span>
                                </div>
                                <div>
                                    <h3>Daya Tampung &amp; Kuota Jalur</h3>
                                    <p>Kapasitas: 10 Rombongan Belajar (400 Siswa)</p>
                                </div>
                            </div>
                            <span class="badge-soft"
                                style="background:#dde1ff; color:#001453; border:none; font-size:0.65rem;">SK Gub
                                2025</span>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <!-- Zonasi -->
                            <div class="p-3 rounded-2"
                                style="border:1px solid var(--bs-outline-variant); background:#f8faff;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle"
                                            style="width:0.75rem;height:0.75rem;background:var(--bs-primary);"></span>
                                        <span style="font-size:0.75rem; font-weight:700;">Jalur Zonasi Domisili
                                            (50%)</span>
                                    </div>
                                    <span style="font-size:0.75rem; font-weight:700; color:var(--bs-primary);">200
                                        Kursi</span>
                                </div>
                                <div class="progress-thin mb-1">
                                    <div class="progress-bar" style="width:100%; background:var(--bs-primary);"></div>
                                </div>
                                <div class="d-flex justify-content-between"
                                    style="font-size:0.65rem; color:var(--bs-on-surface-variant);">
                                    <span>Pendaftar Masuk: 780 siswa</span>
                                    <span style="color:var(--bs-emerald-700); font-weight:700;">Kapasitas Penuh
                                        (Terseleksi)</span>
                                </div>
                            </div>

                            <!-- Afirmasi -->
                            <div class="p-3 rounded-2"
                                style="border:1px solid var(--bs-outline-variant); background:#f8faff;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle"
                                            style="width:0.75rem;height:0.75rem;background:var(--bs-secondary);"></span>
                                        <span style="font-size:0.75rem; font-weight:700;">Jalur Afirmasi / KETM / ABK
                                            (20%)</span>
                                    </div>
                                    <span style="font-size:0.75rem; font-weight:700; color:var(--bs-secondary);">80
                                        Kursi</span>
                                </div>
                                <div class="progress-thin mb-1">
                                    <div class="progress-bar" style="width:90%; background:var(--bs-secondary);">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between"
                                    style="font-size:0.65rem; color:var(--bs-on-surface-variant);">
                                    <span>Pendaftar Masuk: 124 siswa</span>
                                    <span style="color:var(--bs-secondary); font-weight:700;">72 / 80
                                        Terverifikasi</span>
                                </div>
                            </div>

                            <!-- Prestasi -->
                            <div class="p-3 rounded-2"
                                style="border:1px solid var(--bs-outline-variant); background:#f8faff;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle"
                                            style="width:0.75rem;height:0.75rem;background:#d97706;"></span>
                                        <span style="font-size:0.75rem; font-weight:700;">Jalur Prestasi Nilai &amp;
                                            Kejuaraan (25%)</span>
                                    </div>
                                    <span style="font-size:0.75rem; font-weight:700; color:#b45309;">100 Kursi</span>
                                </div>
                                <div class="progress-thin mb-1">
                                    <div class="progress-bar" style="width:94%; background:#f59e0b;"></div>
                                </div>
                                <div class="d-flex justify-content-between"
                                    style="font-size:0.65rem; color:var(--bs-on-surface-variant);">
                                    <span>Pendaftar Masuk: 280 siswa</span>
                                    <span style="color:var(--bs-amber-800); font-weight:700;">94 / 100 Terpilih</span>
                                </div>
                            </div>

                            <!-- Perpindahan -->
                            <div class="p-3 rounded-2"
                                style="border:1px solid var(--bs-outline-variant); background:#f8faff;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle"
                                            style="width:0.75rem;height:0.75rem;background:var(--bs-slate-600);"></span>
                                        <span style="font-size:0.75rem; font-weight:700;">Perpindahan Tugas Orang Tua
                                            (5%)</span>
                                    </div>
                                    <span style="font-size:0.75rem; font-weight:700;">20 Kursi</span>
                                </div>
                                <div class="progress-thin mb-1">
                                    <div class="progress-bar" style="width:90%; background:var(--bs-slate-600);">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between"
                                    style="font-size:0.65rem; color:var(--bs-on-surface-variant);">
                                    <span>Pendaftar Masuk: 64 siswa</span>
                                    <span style="font-weight:700;">18 / 20 Kursi</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-2 mt-3"
                            style="background:var(--bs-surface-container-low); font-size:0.7rem;">
                            <span style="color:var(--bs-on-surface-variant);">Total Alokasi Rombel: <strong>10 Kelas x
                                    40 Siswa</strong></span>
                            <span class="fw-bold" style="color:var(--bs-primary);">Total: 400 Kursi (100%)</span>
                        </div>
                        <button class="btn w-100 mt-3 fw-bold"
                            style="background:var(--bs-secondary); color:#fff; font-size:0.8rem; padding:0.65rem; border-radius:0.5rem;">
                            Kunci &amp; Sinkronisasi Kuota ke Provinsi
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===== PANEL 3: VERIFIKASI BERKAS ===== -->
            <section class="panel mb-4" id="verifikasi">
                <div class="panel-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box"
                            style="background:var(--bs-amber-50); color:var(--bs-amber-700); border:1px solid var(--bs-amber-200);">
                            <span class="material-symbols-outlined" style="font-size:1.4rem;">fact_check</span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h3>Antrean Verifikasi Berkas &amp; Faktual</h3>
                                <span class="badge-soft amber" style="font-size:0.65rem;">42 Menunggu</span>
                            </div>
                            <p>Pemeriksaan keaslian Kartu Keluarga, Titik Koordinat Rumah, Akta Kelahiran, dan Surat
                                Tanggung Jawab Mutlak (SPTJM)</p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button class="btn btn-sm fw-semibold text-white"
                            style="background:var(--bs-primary); font-size:0.7rem;">Semua Berkas (42)</button>
                        <button class="btn btn-sm fw-semibold"
                            style="background:var(--bs-surface-container-low); color:var(--bs-on-surface-variant); border:1px solid var(--bs-outline-variant); font-size:0.7rem;">Zonasi
                            (24)</button>
                        <button class="btn btn-sm fw-semibold"
                            style="background:var(--bs-surface-container-low); color:var(--bs-on-surface-variant); border:1px solid var(--bs-outline-variant); font-size:0.7rem;">Afirmasi
                            KIP (11)</button>
                        <button class="btn btn-sm fw-semibold"
                            style="background:var(--bs-surface-container-low); color:var(--bs-on-surface-variant); border:1px solid var(--bs-outline-variant); font-size:0.7rem;">Prestasi
                            (7)</button>
                    </div>
                </div>

                <div class="table-responsive-custom">
                    <table class="table table-verifikasi table-hover">
                        <thead>
                            <tr>
                                <th>No. Pendaftaran</th>
                                <th>Calon Siswa &amp; NISN</th>
                                <th>Asal Sekolah</th>
                                <th>Jalur &amp; Parameter Jarak</th>
                                <th class="text-center">Dokumen Unggahan</th>
                                <th>Status Verifikasi</th>
                                <th class="text-center">Tindakan Panitia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Row 1 -->
                            <tr class="row-focus">
                                <td style="font-family:monospace; font-weight:700; color:var(--bs-primary);">
                                    PPDB25-300184
                                    <span class="d-block fw-normal"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Tgl: 16/06
                                        08:30</span>
                                </td>
                                <td>
                                    <div class="fw-bold" style="font-size:0.8rem;">Muhammad Farhan Al-Hafiz</div>
                                    <div style="font-size:0.65rem; color:var(--bs-on-surface-variant);">NISN:
                                        0089283719 • NIK: 3273102901080002</div>
                                </td>
                                <td>
                                    <span class="fw-medium">SMP Negeri 5 Bandung</span>
                                    <span class="d-block" style="font-size:0.65rem; color:var(--bs-outline);">Kel.
                                        Merdeka, Kec. Sumur Bandung</span>
                                </td>
                                <td>
                                    <div class="badge-soft blue">
                                        <span class="material-symbols-outlined"
                                            style="font-size:0.85rem;">near_me</span>
                                        Zonasi: 840 meter
                                    </div>
                                    <span class="d-block mt-1"
                                        style="font-size:0.65rem; color:var(--bs-emerald-700); font-weight:600;">Sesuai
                                        Titik Rumah &amp; KK</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        <button class="btn btn-sm d-flex align-items-center gap-1"
                                            style="font-size:0.65rem; padding:0.2rem 0.5rem; border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-low); color:var(--bs-primary); border-radius:0.35rem;">
                                            <span class="material-symbols-outlined"
                                                style="font-size:0.85rem;">description</span> KK
                                        </button>
                                        <button class="btn btn-sm d-flex align-items-center gap-1"
                                            style="font-size:0.65rem; padding:0.2rem 0.5rem; border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-low); color:var(--bs-primary); border-radius:0.35rem;">
                                            <span class="material-symbols-outlined"
                                                style="font-size:0.85rem;">badge</span> Akta
                                        </button>
                                        <button class="btn btn-sm d-flex align-items-center gap-1"
                                            style="font-size:0.65rem; padding:0.2rem 0.5rem; border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-low); color:var(--bs-primary); border-radius:0.35rem;">
                                            <span class="material-symbols-outlined"
                                                style="font-size:0.85rem;">task</span> SPTJM
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-soft amber" style="font-size:0.65rem;">
                                        <span class="rounded-circle bg-warning"
                                            style="width:0.35rem;height:0.35rem; animation:pulse 1.5s infinite;"></span>
                                        Menunggu Validasi
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm p-1"
                                            style="background:var(--bs-emerald-600); color:#fff; border-radius:0.5rem;"
                                            title="Setujui dan Sahkan Berkas">
                                            <span class="material-symbols-outlined"
                                                style="font-size:1.1rem;">check_circle</span>
                                        </button>
                                        <button class="btn btn-sm p-1"
                                            style="background:#ba1a1a; color:#fff; border-radius:0.5rem;"
                                            title="Tolak / Berkas Tidak Sah">
                                            <span class="material-symbols-outlined"
                                                style="font-size:1.1rem;">cancel</span>
                                        </button>
                                        <button class="btn btn-sm p-1"
                                            style="background:var(--bs-surface-container); color:var(--bs-on-surface-variant); border-radius:0.5rem;"
                                            title="Minta Perbaikan Berkas">
                                            <span class="material-symbols-outlined"
                                                style="font-size:1.1rem;">history_edu</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 2 -->
                            <tr>
                                <td style="font-family:monospace; font-weight:700;">
                                    PPDB25-300185
                                    <span class="d-block fw-normal"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Tgl: 16/06
                                        08:45</span>
                                </td>
                                <td>
                                    <div class="fw-bold" style="font-size:0.8rem;">Annisa Rahmawati Putri</div>
                                    <div style="font-size:0.65rem; color:var(--bs-on-surface-variant);">NISN:
                                        0091823901 • NIK: 3273114502090001</div>
                                </td>
                                <td>
                                    <span class="fw-medium">SMP Negeri 2 Bandung</span>
                                    <span class="d-block" style="font-size:0.65rem; color:var(--bs-outline);">Kel.
                                        Citarum, Kec. Bandung Wetan</span>
                                </td>
                                <td>
                                    <div class="badge-soft"
                                        style="background:#f0f9ff; color:var(--bs-secondary); border:1px solid #bae6fd;">
                                        <span class="material-symbols-outlined"
                                            style="font-size:0.85rem;">verified_user</span>
                                        Afirmasi KIP: KETM
                                    </div>
                                    <span class="d-block mt-1"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">ID KIP:
                                        8941094012 (Valid)</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm d-flex align-items-center gap-1"
                                            style="font-size:0.65rem; padding:0.2rem 0.5rem; border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-low); color:var(--bs-primary); border-radius:0.35rem;">
                                            <span class="material-symbols-outlined"
                                                style="font-size:0.85rem;">description</span> KK
                                        </button>
                                        <button class="btn btn-sm d-flex align-items-center gap-1"
                                            style="font-size:0.65rem; padding:0.2rem 0.5rem; border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-low); color:var(--bs-primary); border-radius:0.35rem;">
                                            <span class="material-symbols-outlined"
                                                style="font-size:0.85rem;">card_membership</span> KIP
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-soft emerald" style="font-size:0.65rem;">
                                        <span class="rounded-circle"
                                            style="width:0.35rem;height:0.35rem;background:var(--bs-emerald-600);"></span>
                                        Terverifikasi Sah
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <span class="badge-soft emerald" style="font-size:0.65rem;">Sah
                                            Disetujui</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 3 -->
                            <tr>
                                <td style="font-family:monospace; font-weight:700;">
                                    PPDB25-300186
                                    <span class="d-block fw-normal"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Tgl: 16/06
                                        09:12</span>
                                </td>
                                <td>
                                    <div class="fw-bold" style="font-size:0.8rem;">Bagas Adityo Nugroho</div>
                                    <div style="font-size:0.65rem; color:var(--bs-on-surface-variant);">NISN:
                                        0081293847 • NIK: 3273181208080004</div>
                                </td>
                                <td>
                                    <span class="fw-medium">SMP Taruna Bakti</span>
                                    <span class="d-block" style="font-size:0.65rem; color:var(--bs-outline);">Kel.
                                        Cihapit, Kec. Bandung Wetan</span>
                                </td>
                                <td>
                                    <div class="badge-soft amber" style="font-size:0.65rem;">
                                        <span class="material-symbols-outlined"
                                            style="font-size:0.85rem;">emoji_events</span>
                                        Prestasi: Rapor 93.85
                                    </div>
                                    <span class="d-block mt-1"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Sertifikat:
                                        Juara 1 O2SN Renang</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm d-flex align-items-center gap-1"
                                            style="font-size:0.65rem; padding:0.2rem 0.5rem; border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-low); color:var(--bs-primary); border-radius:0.35rem;">
                                            <span class="material-symbols-outlined"
                                                style="font-size:0.85rem;">description</span> KK
                                        </button>
                                        <button class="btn btn-sm d-flex align-items-center gap-1"
                                            style="font-size:0.65rem; padding:0.2rem 0.5rem; border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-low); color:var(--bs-primary); border-radius:0.35rem;">
                                            <span class="material-symbols-outlined"
                                                style="font-size:0.85rem;">workspace_premium</span> Piagam
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-soft red" style="font-size:0.65rem;">
                                        <span class="rounded-circle bg-danger"
                                            style="width:0.35rem;height:0.35rem;"></span>
                                        Perbaikan Dokumen
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-sm"
                                            style="font-size:0.65rem; background:var(--bs-surface-container-low); color:var(--bs-on-surface-variant); border:1px solid var(--bs-outline-variant); border-radius:0.5rem; padding:0.3rem 0.6rem;">
                                            Catatan Terkirim
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-3"
                    style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                    <span>Menampilkan 1-3 dari 42 antrean verifikasi berkas tahap 1</span>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm"
                            style="border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-lowest); font-size:0.7rem;"
                            disabled>Sebelumnya</button>
                        <button class="btn btn-sm fw-bold text-white"
                            style="background:var(--bs-primary-container); font-size:0.7rem;">1</button>
                        <button class="btn btn-sm"
                            style="border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-lowest); font-size:0.7rem;">2</button>
                        <button class="btn btn-sm"
                            style="border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-lowest); font-size:0.7rem;">3</button>
                        <button class="btn btn-sm"
                            style="border:1px solid var(--bs-outline-variant); background:var(--bs-surface-container-lowest); font-size:0.7rem;">Selanjutnya</button>
                    </div>
                </div>
            </section>

            <!-- ===== DUAL COLUMN: SELEKSI + KELULUSAN ===== -->
            <div class="row g-4 mb-4">
                <!-- PANEL 4: SIMULASI SELEKSI -->
                <div class="col-12 col-lg-7" id="seleksi">
                    <div class="panel h-100">
                        <div class="panel-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box"
                                    style="background:var(--bs-surface-container-low); color:var(--bs-primary);">
                                    <span class="material-symbols-outlined"
                                        style="font-size:1.4rem;">leaderboard</span>
                                </div>
                                <div>
                                    <h3>Simulasi Pemeringkatan &amp; Batas Aman (Cut-off)</h3>
                                    <p>Algoritma otomatis berbasis Geodesic Radius meter &amp; skor prestasi terstandar
                                    </p>
                                </div>
                            </div>
                            <button class="btn btn-primary-custom d-flex align-items-center gap-2"
                                style="font-size:0.7rem;">
                                <span class="material-symbols-outlined" style="font-size:1.1rem;">play_circle</span>
                                Jalankan Algoritma Seleksi
                            </button>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-12 col-sm-4">
                                <div class="p-3 rounded-2"
                                    style="background:var(--bs-surface-container-low); border:1px solid var(--bs-surface-container-high);">
                                    <span class="d-block"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Passing Radius
                                        Zonasi</span>
                                    <span class="d-block fw-bold"
                                        style="font-size:1rem; color:var(--bs-primary);">1.240 Meter</span>
                                    <span class="d-block mt-1"
                                        style="font-size:0.65rem; color:var(--bs-emerald-700);">Peringkat 200: Sdr.
                                        Rifki (1.238m)</span>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="p-3 rounded-2"
                                    style="background:var(--bs-surface-container-low); border:1px solid var(--bs-surface-container-high);">
                                    <span class="d-block"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Passing Grade
                                        Prestasi</span>
                                    <span class="d-block fw-bold"
                                        style="font-size:1rem; color:var(--bs-secondary);">89.42 Poin</span>
                                    <span class="d-block mt-1"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Peringkat 100:
                                        Sdri. Naila</span>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="p-3 rounded-2"
                                    style="background:var(--bs-surface-container-low); border:1px solid var(--bs-surface-container-high);">
                                    <span class="d-block"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Status Validasi
                                        Pleno</span>
                                    <span class="d-block fw-bold"
                                        style="font-size:1rem; color:var(--bs-emerald-800);">Tersinkronisasi</span>
                                    <span class="d-block mt-1"
                                        style="font-size:0.65rem; color:var(--bs-outline);">Hash SHA-256
                                        Terverifikasi</span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2 overflow-hidden" style="border:1px solid var(--bs-surface-container);">
                            <div class="d-flex justify-content-between align-items-center px-3 py-2"
                                style="background:var(--bs-surface-container-low); font-size:0.7rem; font-weight:700;">
                                <span>Pratinjau Leaderboard Teratas (Zonasi Domisili)</span>
                                <span style="color:var(--bs-primary);">Batas Kuota: 200 Siswa</span>
                            </div>
                            <div class="divide-y" style="border-color:var(--bs-surface-container);">
                                <div class="d-flex align-items-center justify-content-between px-3 py-2"
                                    style="background:#f8faff; border-bottom:1px solid var(--bs-surface-container);">
                                    <div class="d-flex align-items-center gap-3">
                                        <span
                                            class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                            style="width:1.5rem;height:1.5rem;background:#f59e0b; font-size:0.65rem;">1</span>
                                        <div>
                                            <span class="d-block fw-bold" style="font-size:0.8rem;">Siti
                                                Nurhaliza</span>
                                            <span style="font-size:0.65rem; color:var(--bs-outline);">SMPN 5 Bandung •
                                                NIK: 3273...1081</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="d-block fw-bold"
                                            style="font-family:monospace; color:var(--bs-primary);">142 meter</span>
                                        <span class="badge-soft emerald" style="font-size:0.55rem;">Sangat Aman</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between px-3 py-2"
                                    style="border-bottom:1px solid var(--bs-surface-container);">
                                    <div class="d-flex align-items-center gap-3">
                                        <span
                                            class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            style="width:1.5rem;height:1.5rem;background:var(--bs-slate-300); font-size:0.65rem;">2</span>
                                        <div>
                                            <span class="d-block fw-bold" style="font-size:0.8rem;">Muhammad Farhan
                                                Al-Hafiz</span>
                                            <span style="font-size:0.65rem; color:var(--bs-outline);">SMPN 5 Bandung •
                                                NIK: 3273...0002</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="d-block fw-bold"
                                            style="font-family:monospace; color:var(--bs-primary);">840 meter</span>
                                        <span class="badge-soft emerald" style="font-size:0.55rem;">Aman
                                            Terpilih</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between px-3 py-2"
                                    style="background:#f8faff;">
                                    <div class="d-flex align-items-center gap-3">
                                        <span
                                            class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            style="width:1.5rem;height:1.5rem;background:var(--bs-slate-200); font-size:0.65rem;">200</span>
                                        <div>
                                            <span class="d-block fw-bold" style="font-size:0.8rem;">Rifki Kurniawan
                                                (Garis Batas)</span>
                                            <span style="font-size:0.65rem; color:var(--bs-outline);">SMPN 7 Bandung •
                                                NIK: 3273...9912</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="d-block fw-bold"
                                            style="font-family:monospace; color:var(--bs-amber-800);">1.238
                                            meter</span>
                                        <span class="badge-soft amber" style="font-size:0.55rem;">Ambang Batas</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-3 pt-2">
                            <span style="font-size:0.7rem; color:var(--bs-outline); font-style:italic;">*Perhitungan
                                diperbarui otomatis setiap kali verifikator mengesahkan titik koordinat baru.</span>
                            <button class="btn d-flex align-items-center gap-2"
                                style="background:var(--bs-surface-container); color:var(--bs-on-surface); font-weight:700; font-size:0.7rem; border-radius:0.5rem; padding:0.4rem 1rem;">
                                <span class="material-symbols-outlined" style="font-size:1rem;">lock</span>
                                Kunci Hasil Pleno Sementara
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL 5: KELULUSAN -->
                <div class="col-12 col-lg-5" id="kelulusan">
                    <div class="panel h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="panel-header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box" style="background:var(--bs-primary); color:#fff;">
                                        <span class="material-symbols-outlined"
                                            style="font-size:1.4rem;">campaign</span>
                                    </div>
                                    <div>
                                        <h3>Penetapan Kelulusan &amp; Notifikasi</h3>
                                        <p>Tahap finalisasi SK &amp; broadcast resmi</p>
                                    </div>
                                </div>
                                <span class="badge-soft amber" style="font-size:0.6rem;">Siap Pleno</span>
                            </div>

                            <div class="p-4 rounded-3 mb-3"
                                style="background:var(--bs-surface-container-low); border:1px solid var(--bs-surface-container-high);">
                                <span class="d-block fw-bold mb-3" style="font-size:0.75rem;">Lembar Cek Pleno
                                    Kelulusan:</span>
                                <div class="d-flex flex-column gap-2" style="font-size:0.8rem;">
                                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                                        <input type="checkbox" checked class="form-check-input mt-0"
                                            style="accent-color:var(--bs-primary);">
                                        <span>Seluruh berkas 384 siswa telah berstatus Sah</span>
                                    </label>
                                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                                        <input type="checkbox" checked class="form-check-input mt-0"
                                            style="accent-color:var(--bs-primary);">
                                        <span>Kordinat zonasi diverifikasi tim survei lapangan</span>
                                    </label>
                                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                                        <input type="checkbox" checked class="form-check-input mt-0"
                                            style="accent-color:var(--bs-primary);">
                                        <span>Berita Acara Rapat Pleno Dewan Guru ditandatangani</span>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <button
                                    class="btn w-100 d-flex align-items-center justify-content-center gap-2 fw-bold"
                                    style="background:var(--bs-primary); color:#fff; font-size:0.8rem; padding:0.75rem; border-radius:0.75rem;">
                                    <span class="material-symbols-outlined" style="font-size:1.2rem;">send</span>
                                    Kirim Notifikasi Kelulusan WA / SMS (384 Murid)
                                </button>
                                <button
                                    class="btn w-100 d-flex align-items-center justify-content-center gap-2 fw-bold"
                                    style="background:var(--bs-surface-container-lowest); color:var(--bs-primary); border:2px solid var(--bs-primary); font-size:0.8rem; padding:0.65rem; border-radius:0.75rem;">
                                    <span class="material-symbols-outlined"
                                        style="font-size:1.2rem;">picture_as_pdf</span>
                                    Generate &amp; Cetak SK Kelulusan Resmi (PDF)
                                </button>
                            </div>
                        </div>

                        <div class="pt-3 mt-3 border-top d-flex align-items-center gap-3"
                            style="border-color:var(--bs-surface-container) !important;">
                            <span class="material-symbols-outlined"
                                style="font-size:1.8rem; color:var(--bs-secondary);">verified</span>
                            <div>
                                <p class="mb-0 fw-bold" style="font-size:0.7rem;">Tanda Tangan Elektronik
                                    Tersertifikasi BSrE</p>
                                <p class="mb-0" style="font-size:0.65rem; color:var(--bs-outline);">Dokumen digital
                                    dilengkapi QR-Code legalitas resmi Disdik Jabar.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== PANEL 6: LAPORAN ===== -->
            <section class="panel mb-4" id="laporan">
                <div class="panel-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box"
                            style="background:var(--bs-surface-container); color:var(--bs-on-surface);">
                            <span class="material-symbols-outlined" style="font-size:1.4rem;">folder_shared</span>
                        </div>
                        <div>
                            <h3>Laporan &amp; Ekspor Buku Induk Pendaftar PPDB</h3>
                            <p>Pengarsipan komprehensif untuk pelaporan ke Cabang Dinas Pendidikan Wilayah VII</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button class="btn d-flex align-items-center gap-2 fw-bold"
                            style="background:var(--bs-emerald-50); color:var(--bs-emerald-800); border:1px solid #6ee7b7; font-size:0.75rem;">
                            <span class="material-symbols-outlined" style="font-size:1.1rem;">table_view</span>
                            Export Excel (.XLSX)
                        </button>
                        <button class="btn d-flex align-items-center gap-2 fw-bold"
                            style="background:var(--bs-surface-container); color:var(--bs-on-surface); font-size:0.75rem;">
                            <span class="material-symbols-outlined" style="font-size:1.1rem;">print</span>
                            Cetak Buku Induk
                        </button>
                    </div>
                </div>

                <div class="p-3 rounded-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3"
                    style="background:#f8faff; border:1px solid var(--bs-surface-container-high); font-size:0.8rem;">
                    <div class="d-flex align-items-center gap-3">
                        <span class="rounded-circle"
                            style="width:0.5rem;height:0.5rem;background:var(--bs-emerald-600);"></span>
                        <span class="fw-medium">Log Sistem Terakhir:</span>
                        <span style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Verifikator <strong>Budi
                                Santoso, S.Pd</strong> memvalidasi berkas pendaftar <strong>PPDB25-300185</strong> (SMPN
                            2 Bandung)</span>
                    </div>
                    <span style="font-size:0.7rem; color:var(--bs-outline); white-space:nowrap;">16 Jun 2025 - 10:38
                        WIB</span>
                </div>
            </section>

        </main>

        <!-- ==================== FOOTER ==================== -->
        <footer class="py-3 px-4 text-center"
            style="background:var(--bs-surface-container-lowest); border-top:1px solid var(--bs-outline-variant); margin-top:auto;">
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2"
                style="max-width:1440px; margin:0 auto; font-size:0.7rem; color:var(--bs-on-surface-variant);">
                <p class="mb-0">© 2025 Panitia Penerimaan Peserta Didik Baru (PPDB) SMAN 3 Bandung. Seluruh Hak Cipta
                    Dilindungi Undang-Undang.</p>
                <p class="mb-0" style="color:var(--bs-outline);">Didukung oleh Sistem Basis Data Terintegrasi
                    Dapodikdasmen &amp; Kemendikbudristek RI</p>
            </div>
        </footer>

    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Simple sidebar toggle logic (if needed)
            const sidebar = document.getElementById('sidebar');
            // Add a toggle button dynamically if desired

            // Highlight active nav link on click
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.getAttribute('href') && this.getAttribute('href').startsWith('#')) {
                        navLinks.forEach(l => l.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
