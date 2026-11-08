<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="color-scheme" content="light dark" />
    <title>Modul Akuntansi Sekolah - EduPortal SMA</title>

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
            --primary-fixed: #dfe0ff;
            --primary-fixed-dim: #bcc3ff;
            --secondary: #006a61;
            --secondary-container: #86f2e4;
            --secondary-fixed: #89f5e7;
            --secondary-fixed-dim: #6bd8cb;
            --on-secondary: #ffffff;
            --on-secondary-container: #006f66;
            --on-secondary-fixed: #00201d;
            --on-secondary-fixed-variant: #005049;
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
            --on-background: #111c2c;
            --on-surface-variant: #454651;
            --outline: #767682;
            --outline-variant: #c6c5d3;
            --background: #f9f9ff;
            --inverse-surface: #263142;
            --inverse-primary: #bcc3ff;
            --surface-tint: #4c58a6;
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
            --secondary-fixed-dim: #6bd8cb;
            --on-secondary: #003732;
            --on-secondary-container: #89f5e7;
            --on-secondary-fixed: #00201d;
            --on-secondary-fixed-variant: #6bd8cb;
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
            --on-background: #f1f5f9;
            --on-surface-variant: #cbd5e1;
            --outline: #8f909c;
            --outline-variant: #475569;
            --background: #0b1120;
            --inverse-surface: #f1f5f9;
            --inverse-primary: #1e2a78;
            --surface-tint: #bcc3ff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--on-surface);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 20px;
            padding-bottom: 112px;
        }

        @media (min-width: 768px) {
            body {
                padding-bottom: 48px;
            }
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

        .text-headline-lg {
            font-size: 28px;
            line-height: 36px;
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

        .text-on-background {
            color: var(--on-background) !important;
        }

        .text-on-surface-variant {
            color: var(--on-surface-variant) !important;
        }

        .text-on-secondary-fixed {
            color: var(--on-secondary-fixed) !important;
        }

        .text-on-secondary-fixed-variant {
            color: var(--on-secondary-fixed-variant) !important;
        }

        .text-on-secondary-container {
            color: var(--on-secondary-container) !important;
        }

        .text-on-primary-container {
            color: var(--on-primary-container) !important;
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
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-container);
            color: var(--on-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .unit-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            background: var(--surface-container-high);
            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        .notif-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 0;
            background: transparent;
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background .15s, transform .1s;
        }

        .notif-btn:hover {
            background: var(--surface-container);
        }

        .notif-btn:active {
            transform: scale(.95);
        }

        /* Web nav */
        .web-nav a {
            color: var(--on-surface-variant);
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            text-decoration: none;
            transition: color .15s;
        }

        .web-nav a:hover {
            color: var(--primary);
        }

        .web-nav a.active {
            color: var(--primary);
            font-weight: 700;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 4px;
        }

        /* Cards */
        .card-soft {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, .4);
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(30, 42, 120, .04);
        }

        .kpi-card {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, .4);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px -2px rgba(30, 42, 120, .04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .kpi-card.border-primary {
            border-left: 4px solid var(--primary-container) !important;
        }

        .kpi-card.border-secondary {
            border-left: 4px solid var(--secondary) !important;
        }

        .kpi-card.border-error {
            border-left: 4px solid #B91C1C !important;
        }

        .kpi-card.border-success {
            border-left: 4px solid var(--secondary-fixed-dim) !important;
        }

        .kpi-icon-box {
            width: 32px;
            height: 32px;
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

        .kpi-icon-error {
            background: #FEF2F2;
            color: #B91C1C;
        }

        .kpi-icon-high {
            background: var(--surface-container-high);
            color: var(--primary);
        }

        .badge-trend-success {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 8px;
            border-radius: 999px;
            background: #ECFDF5;
            color: #047857;
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        /* Status pill */
        .status-pill-lock {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            background: var(--secondary-fixed);
            color: var(--on-secondary-fixed);
            font-size: 11px;
            font-weight: 600;
            line-height: 14px;
            letter-spacing: .03em;
        }

        .fiscal-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 12px;
            background: var(--surface-container-low);
            color: var(--on-surface-variant);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        /* Quick action buttons */
        .action-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid rgba(198, 197, 211, .5);
            background: var(--surface-container-lowest);
            color: var(--on-surface);
            text-align: left;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            transition: background .15s, opacity .15s, transform .1s;
            width: 100%;
            height: 100%;
        }

        .action-btn:hover {
            background: var(--surface-container);
            color: inherit;
        }

        .action-btn:active {
            transform: scale(.95);
        }

        .action-btn.primary {
            background: var(--primary-container);
            color: var(--on-primary);
            border-color: transparent;
        }

        .action-btn.primary:hover {
            opacity: .95;
            background: var(--primary-container);
            color: var(--on-primary);
        }

        .action-btn.accent-border {
            border-color: rgba(0, 106, 97, .3);
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .action-icon-primary-ghost {
            background: rgba(255, 255, 255, .15);
            color: var(--on-primary);
        }

        .action-icon-secondary {
            background: rgba(134, 242, 228, .4);
            color: var(--secondary);
        }

        .action-icon-high {
            background: var(--surface-container-high);
            color: var(--primary-container);
        }

        .action-icon-warning {
            background: #FEF3C7;
            color: #B45309;
        }

        /* Journal filter */
        .journal-filters {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding: 12px 0;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .journal-filters::-webkit-scrollbar {
            display: none;
        }

        .journal-filters button {
            padding: 6px 14px;
            border-radius: 999px;
            border: 0;
            background: var(--surface-container);
            color: var(--on-surface-variant);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            white-space: nowrap;
            transition: background .15s, transform .1s;
        }

        .journal-filters button:hover {
            background: var(--surface-container-high);
        }

        .journal-filters button:active {
            transform: scale(.95);
        }

        .journal-filters button.active {
            background: var(--primary-container);
            color: var(--on-primary);
        }

        /* Journal item */
        .journal-item {
            padding: 16px;
            border-radius: 12px;
            border: 1px solid rgba(198, 197, 211, .4);
            background: var(--surface);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: border-color .15s;
        }

        .journal-item:hover {
            border-color: rgba(0, 106, 97, .4);
        }

        @media (min-width: 768px) {
            .journal-item {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .coa-badge {
            padding: 2px 8px;
            border-radius: 4px;
            background: var(--surface-container-high);
            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 999px;
            background: #ECFDF5;
            color: #047857;
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        .amount-success {
            color: #047857;
            font-weight: 700;
        }

        .amount-error {
            color: #B91C1C;
            font-weight: 700;
        }

        /* Select custom */
        .form-select-soft {
            padding: 4px 10px;
            background: var(--surface-container-low);
            border: 0;
            border-radius: 8px;
            font-size: 12px;
            line-height: 16px;
            color: var(--on-surface);
            outline: none;
        }

        .form-select-soft:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 106, 97, .25);
        }

        /* Balance panel */
        .balance-box {
            background: var(--surface-container-low);
            border-radius: 12px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .balance-box hr {
            border-color: rgba(198, 197, 211, .3);
            margin: 4px 0;
        }

        .balanced-pill {
            padding: 2px 10px;
            border-radius: 4px;
            background: #ECFDF5;
            color: #047857;
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        /* Compliance list */
        .compliance-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .compliance-icon-success {
            color: #047857;
        }

        .compliance-icon-warning {
            color: #B45309;
        }

        .auditor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: var(--on-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            line-height: 18px;
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

        @media (min-width: 768px) {
            .bottom-nav {
                display: none !important;
            }
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
        .link-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--secondary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            transition: color .15s;
        }

        .link-secondary:hover {
            color: var(--on-secondary-container);
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

        [data-bs-theme="dark"] .web-nav a,
        body.dark-mode .web-nav a {
            color: var(--on-surface-variant);
        }

        [data-bs-theme="dark"] .web-nav a:hover,
        body.dark-mode .web-nav a:hover {
            color: var(--primary);
        }

        [data-bs-theme="dark"] .badge-trend-success,
        body.dark-mode .badge-trend-success {
            background: rgba(4, 120, 87, 0.22);
            color: #6ee7b7;
        }

        [data-bs-theme="dark"] .kpi-icon-error,
        body.dark-mode .kpi-icon-error {
            background: rgba(185, 28, 28, 0.22);
            color: #fca5a5;
        }

        [data-bs-theme="dark"] .table,
        body.dark-mode .table {
            --bs-table-color: var(--on-surface);
            --bs-table-bg: transparent;
            --bs-table-border-color: rgba(255, 255, 255, 0.08);
            color: var(--on-surface);
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

    <!-- Top App Bar -->
    <header class="app-bar d-flex justify-content-between align-items-center px-3">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-icon">
                <span class="material-symbols-outlined" style="font-size:20px;">school</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-headline-md text-primary fw-bold mb-0" style="letter-spacing:-.02em;">EduPortal</span>
                <span class="unit-badge d-none d-md-inline-block">SMA Unit</span>
            </div>
        </div>

        <!-- Web Nav -->
        <nav class="web-nav d-none d-md-flex align-items-center gap-4">
            <a href="#">Beranda</a>
            <a href="#">Akademik</a>
            <a href="#" class="active">Keuangan</a>
            <a href="#">Pesan</a>
            <a href="#">Profil</a>
        </nav>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="notif-btn" id="themeToggleBtn" aria-label="Ganti Tema" title="Ganti Tema">
                <span class="material-symbols-outlined" id="themeToggleIcon">dark_mode</span>
            </button>
            <button type="button" class="notif-btn" aria-label="Notifikasi">
                <span class="material-symbols-outlined">notifications</span>
            </button>
        </div>
    </header>

    <!-- Main -->
    <main class="container-fluid py-4 mx-auto" style="max-width:1280px;">
        <div class="d-flex flex-column gap-4 px-3 px-sm-4 px-lg-5">

            <!-- Header card -->
            <section class="card-soft p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <a href="#" class="link-secondary">
                        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
                        <span>Kembali ke Beranda</span>
                    </a>
                    <span class="status-pill-lock">
                        <span class="material-symbols-outlined" style="font-size:14px;">lock_clock</span>
                        Tutup Buku Maret 2025
                    </span>
                </div>
                <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
                    <div>
                        <h1 class="text-headline-lg-mobile text-primary fw-bold mb-0" style="letter-spacing:-.02em;">
                            Modul Akuntansi Sekolah</h1>
                        <p class="text-on-surface-variant text-body-md mt-1 mb-0">
                            Buku Besar &amp; Laporan Keuangan Yayasan/Sekolah Terpadu
                        </p>
                    </div>
                    <div class="fiscal-badge">
                        <span class="material-symbols-outlined text-secondary" style="font-size:18px;">event_note</span>
                        <span>Tahun Fiskal: <strong class="text-on-surface">2024/2025 Genap</strong></span>
                    </div>
                </div>
            </section>

            <!-- KPI Bento -->
            <section>
                <div class="row g-3">

                    <!-- Saldo Kas & Bank -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="kpi-card border-primary">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-on-surface-variant text-label-md">Saldo Kas &amp; Bank</span>
                                <div class="kpi-icon-box kpi-icon-primary">
                                    <span class="material-symbols-outlined"
                                        style="font-size:20px;">account_balance</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-headline-md text-primary" style="letter-spacing:-.02em;">Rp 842.150.000
                                </div>
                                <div class="badge-trend-success mt-2">
                                    <span class="material-symbols-outlined" style="font-size:14px;">trending_up</span>
                                    <span>Surplus +8.4% MoM</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pendapatan -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="kpi-card border-secondary">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-on-surface-variant text-label-md">Total Pendapatan Bulan Ini</span>
                                <div class="kpi-icon-box kpi-icon-secondary">
                                    <span class="material-symbols-outlined"
                                        style="font-size:20px;">arrow_downward_alt</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-headline-md text-on-surface" style="letter-spacing:-.02em;">Rp
                                    512.000.000</div>
                                <p class="text-on-surface-variant text-body-sm mt-2 mb-0">SPP, Uang Gedung, &amp; Hibah
                                    BOS</p>
                            </div>
                        </div>
                    </div>

                    <!-- Beban -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="kpi-card border-error">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-on-surface-variant text-label-md">Total Beban / Pengeluaran</span>
                                <div class="kpi-icon-box kpi-icon-error">
                                    <span class="material-symbols-outlined"
                                        style="font-size:20px;">arrow_upward_alt</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-headline-md text-on-surface" style="letter-spacing:-.02em;">Rp
                                    268.450.000</div>
                                <p class="text-on-surface-variant text-body-sm mt-2 mb-0">Operasional, Gaji, &amp;
                                    Sarpras</p>
                            </div>
                        </div>
                    </div>

                    <!-- Laba Bersih -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="kpi-card border-success">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-on-surface-variant text-label-md">Laba/Surplus Bersih</span>
                                <div class="kpi-icon-box kpi-icon-high">
                                    <span class="material-symbols-outlined" style="font-size:20px;">savings</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-headline-md text-primary" style="letter-spacing:-.02em;">Rp 243.550.000
                                </div>
                                <div class="badge-trend-success mt-2">
                                    <span class="material-symbols-outlined" style="font-size:14px;">check_circle</span>
                                    <span>Rasio Likuiditas Sehat</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Aksi Cepat -->
            <section class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="text-title-sm text-primary mb-0" style="letter-spacing:-.02em;">Aksi Cepat Akuntan</h2>
                    <span class="text-label-sm text-on-surface-variant">4 Pintasan Tersedia</span>
                </div>
                <div class="row g-2">

                    <!-- Jurnal Umum -->
                    <div class="col-6 col-md-3">
                        <button type="button" class="action-btn primary">
                            <div class="action-icon action-icon-primary-ghost">
                                <span class="material-symbols-outlined" style="font-size:22px;">edit_note</span>
                            </div>
                            <div>
                                <div class="text-label-md" style="line-height:1.2;">Catat Jurnal Umum</div>
                                <div class="text-body-sm" style="font-size:11px;opacity:.8;">Entri Debit/Kredit Baru
                                </div>
                            </div>
                        </button>
                    </div>

                    <!-- Rekonsiliasi -->
                    <div class="col-6 col-md-3">
                        <button type="button" class="action-btn">
                            <div class="action-icon action-icon-secondary">
                                <span class="material-symbols-outlined" style="font-size:22px;">sync_alt</span>
                            </div>
                            <div>
                                <div class="text-label-md text-primary" style="line-height:1.2;">Rekonsiliasi Bank
                                </div>
                                <div class="text-body-sm text-on-surface-variant" style="font-size:11px;">Sinkron Kas
                                    Giro</div>
                            </div>
                        </button>
                    </div>

                    <!-- Neraca -->
                    <div class="col-6 col-md-3">
                        <button type="button" class="action-btn">
                            <div class="action-icon action-icon-high">
                                <span class="material-symbols-outlined" style="font-size:22px;">balance</span>
                            </div>
                            <div>
                                <div class="text-label-md text-primary" style="line-height:1.2;">Buat Neraca Saldo
                                </div>
                                <div class="text-body-sm text-on-surface-variant" style="font-size:11px;">Kompilasi
                                    Buku Besar</div>
                            </div>
                        </button>
                    </div>

                    <!-- Laba Rugi -->
                    <div class="col-6 col-md-3">
                        <button type="button" class="action-btn accent-border">
                            <div class="action-icon action-icon-warning">
                                <span class="material-symbols-outlined" style="font-size:22px;">picture_as_pdf</span>
                            </div>
                            <div>
                                <div class="text-label-md text-primary" style="line-height:1.2;">Laporan Laba Rugi
                                </div>
                                <div class="text-body-sm text-secondary" style="font-size:11px;">Unduh File PDF Resmi
                                </div>
                            </div>
                        </button>
                    </div>

                </div>
            </section>

            <!-- Content Grid -->
            <div class="row g-4">

                <!-- LEFT: Journal Entries -->
                <section class="col-12 col-lg-8">
                    <div class="card-soft p-4">

                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 pb-3 border-bottom"
                            style="border-color:var(--surface-container)!important;">
                            <div>
                                <h2 class="text-headline-md text-primary mb-0">Entri Jurnal Keuangan Terbaru</h2>
                                <p class="text-body-sm text-on-surface-variant mb-0">Pencatatan real-time arus kas SMA
                                    &amp; Yayasan</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-label-sm text-on-surface-variant">Urutkan:</span>
                                <select class="form-select-soft">
                                    <option>Terbaru</option>
                                    <option>Nominal Terbesar</option>
                                    <option>Belum Diverifikasi</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filter tabs -->
                        <div class="journal-filters">
                            <button type="button" class="active">Semua Transaksi</button>
                            <button type="button">Penerimaan Operasional</button>
                            <button type="button">Belanja Modal</button>
                            <button type="button">Beban Gaji &amp; Operasional</button>
                        </div>

                        <!-- Journal list -->
                        <div class="d-flex flex-column gap-3 pt-2">

                            <!-- Card 1: SPP -->
                            <div class="journal-item">
                                <div class="flex-grow-1 d-flex flex-column gap-2">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="coa-badge">COA 1101-02</span>
                                        <span class="text-body-sm text-on-surface-variant">25 Maret 2025 • 09:42
                                            WIB</span>
                                        <span class="verified-badge">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">verified</span>
                                            Terverifikasi Auditor
                                        </span>
                                    </div>
                                    <div class="text-title-sm text-on-surface">Penerimaan SPP Kas Masuk - Kelas X &amp;
                                        XI</div>
                                    <p class="text-body-sm text-on-surface-variant mb-0">Pembayaran berkala via Virtual
                                        Account Bank BNI (320 Siswa)</p>
                                </div>
                                <div class="d-flex flex-md-column align-items-end justify-content-between justify-content-md-center pt-2 pt-md-0 border-top border-md-0"
                                    style="border-color:var(--surface-container)!important;">
                                    <span class="text-label-sm text-on-surface-variant mb-md-1">Debit (Kas
                                        Masuk)</span>
                                    <span class="text-title-sm amount-success">+ Rp 192.000.000</span>
                                </div>
                            </div>

                            <!-- Card 2: Listrik -->
                            <div class="journal-item">
                                <div class="flex-grow-1 d-flex flex-column gap-2">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="coa-badge">COA 5202-04</span>
                                        <span class="text-body-sm text-on-surface-variant">24 Maret 2025 • 14:15
                                            WIB</span>
                                        <span class="verified-badge">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">verified</span>
                                            Terverifikasi Auditor
                                        </span>
                                    </div>
                                    <div class="text-title-sm text-on-surface">Pembayaran Listrik &amp; Internet Lab
                                        Komputer</div>
                                    <p class="text-body-sm text-on-surface-variant mb-0">Beban utilitas bulanan PLN
                                        Pascabayar dan Dedicated Fiber Optic 200 Mbps</p>
                                </div>
                                <div class="d-flex flex-md-column align-items-end justify-content-between justify-content-md-center pt-2 pt-md-0 border-top border-md-0"
                                    style="border-color:var(--surface-container)!important;">
                                    <span class="text-label-sm text-on-surface-variant mb-md-1">Kredit (Beban
                                        Kas)</span>
                                    <span class="text-title-sm amount-error">- Rp 14.850.000</span>
                                </div>
                            </div>

                            <!-- Card 3: ATK -->
                            <div class="journal-item">
                                <div class="flex-grow-1 d-flex flex-column gap-2">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="coa-badge">COA 5104-01</span>
                                        <span class="text-body-sm text-on-surface-variant">22 Maret 2025 • 11:30
                                            WIB</span>
                                        <span class="verified-badge">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">verified</span>
                                            Terverifikasi Auditor
                                        </span>
                                    </div>
                                    <div class="text-title-sm text-on-surface">Belanja ATK Sekolah &amp; Kertas Ujian
                                        Semester</div>
                                    <p class="text-body-sm text-on-surface-variant mb-0">Pengadaan perlengkapan
                                        administrasi kantor guru dan cetak bank soal PAS</p>
                                </div>
                                <div class="d-flex flex-md-column align-items-end justify-content-between justify-content-md-center pt-2 pt-md-0 border-top border-md-0"
                                    style="border-color:var(--surface-container)!important;">
                                    <span class="text-label-sm text-on-surface-variant mb-md-1">Kredit (Beban
                                        Kas)</span>
                                    <span class="text-title-sm amount-error">- Rp 8.600.000</span>
                                </div>
                            </div>

                            <!-- Card 4: BOS -->
                            <div class="journal-item">
                                <div class="flex-grow-1 d-flex flex-column gap-2">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="coa-badge">COA 1205-01</span>
                                        <span class="text-body-sm text-on-surface-variant">20 Maret 2025 • 16:00
                                            WIB</span>
                                        <span class="verified-badge">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">verified</span>
                                            Terverifikasi Auditor
                                        </span>
                                    </div>
                                    <div class="text-title-sm text-on-surface">Penerimaan Hibah Dana BOS Tahap I 2025
                                    </div>
                                    <p class="text-body-sm text-on-surface-variant mb-0">Penyaluran dari Kas Daerah
                                        Kemendikbudristek untuk sarana pembelajaran</p>
                                </div>
                                <div class="d-flex flex-md-column align-items-end justify-content-between justify-content-md-center pt-2 pt-md-0 border-top border-md-0"
                                    style="border-color:var(--surface-container)!important;">
                                    <span class="text-label-sm text-on-surface-variant mb-md-1">Debit (Kas
                                        Masuk)</span>
                                    <span class="text-title-sm amount-success">+ Rp 320.000.000</span>
                                </div>
                            </div>

                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 pt-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-2"
                            style="border-color:var(--surface-container)!important;">
                            <span class="text-body-sm text-on-surface-variant">Menampilkan 4 dari 142 entri
                                jurnal</span>
                            <button type="button"
                                class="btn btn-link p-0 text-decoration-none text-primary text-label-md d-flex align-items-center gap-1">
                                <span>Buka Buku Besar Lengkap</span>
                                <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
                            </button>
                        </div>

                    </div>
                </section>

                <!-- RIGHT: Status Neraca & Kepatuhan -->
                <aside class="col-12 col-lg-4 d-flex flex-column gap-3">

                    <!-- Status Neraca -->
                    <div class="card-soft p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="kpi-icon-box" style="background:rgba(134,242,228,.5);color:var(--secondary);">
                                <span class="material-symbols-outlined" style="font-size:20px;">check_box</span>
                            </div>
                            <div>
                                <h3 class="text-title-sm text-primary mb-0">Status Neraca Saldo</h3>
                                <p class="text-body-sm text-on-surface-variant mb-0">Keseimbangan Aktiva &amp; Pasiva
                                </p>
                            </div>
                        </div>

                        <div class="balance-box mb-3">
                            <div class="d-flex justify-content-between text-body-sm">
                                <span class="text-on-surface-variant">Total Debit:</span>
                                <span class="fw-bold text-primary">Rp 1.154.150.000</span>
                            </div>
                            <div class="d-flex justify-content-between text-body-sm">
                                <span class="text-on-surface-variant">Total Kredit:</span>
                                <span class="fw-bold text-primary">Rp 1.154.150.000</span>
                            </div>
                            <hr />
                            <div class="d-flex justify-content-between align-items-center text-label-md">
                                <span class="text-secondary fw-bold">Status Selisih:</span>
                                <span class="balanced-pill">Rp 0 (Balanced / Seimbang)</span>
                            </div>
                        </div>

                        <p class="text-body-sm text-on-surface-variant mb-0" style="line-height:1.6;">
                            Semua transaksi akun per 25 Maret 2025 telah terpetakan secara otomatis dan siap untuk
                            penutupan buku triwulanan.
                        </p>
                    </div>

                    <!-- Kepatuhan -->
                    <div class="card-soft p-4">
                        <h3 class="text-title-sm text-primary mb-3 d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">gavel</span>
                            Kepatuhan &amp; Tata Kelola
                        </h3>

                        <ul class="list-unstyled d-flex flex-column gap-3 mb-0">

                            <li class="compliance-item">
                                <span class="material-symbols-outlined compliance-icon-success"
                                    style="font-size:18px;margin-top:2px;">check_circle</span>
                                <div>
                                    <span class="text-label-md text-on-surface">Standar Akuntansi Pesantren/Sekolah
                                        (PSAK 45)</span>
                                    <p class="text-body-sm text-on-surface-variant mb-0">Penyajian laporan nirlaba
                                        sesuai regulasi yayasan pendidikan.</p>
                                </div>
                            </li>

                            <li class="compliance-item">
                                <span class="material-symbols-outlined compliance-icon-success"
                                    style="font-size:18px;margin-top:2px;">check_circle</span>
                                <div>
                                    <span class="text-label-md text-on-surface">Pajak Penghasilan (PPh 21 &amp; PPh
                                        23)</span>
                                    <p class="text-body-sm text-on-surface-variant mb-0">Pemotongan pajak honorer dan
                                        penyedia telah disetor via e-Billing.</p>
                                </div>
                            </li>

                            <li class="compliance-item">
                                <span class="material-symbols-outlined compliance-icon-warning"
                                    style="font-size:18px;margin-top:2px;">pending</span>
                                <div>
                                    <span class="text-label-md text-on-surface">Audit Internal Yayasan</span>
                                    <p class="text-body-sm text-on-surface-variant mb-0">Jadwal pemeriksaan triwulan 1
                                        dijadwalkan pada 28 Maret 2025.</p>
                                </div>
                            </li>

                        </ul>

                        <div class="mt-4 pt-3 border-top" style="border-color:var(--surface-container)!important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="auditor-avatar">AK</div>
                                <div>
                                    <div class="text-label-md text-on-surface">Ahmad Kurnia, S.E., Ak.</div>
                                    <div class="text-body-sm text-on-surface-variant">Kepala Bagian Keuangan SMA</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </aside>
            </div>

        </div>
    </main>

    <!-- Bottom Nav (Mobile only) -->
    <nav class="bottom-nav">
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:22px;">dashboard</span>
            <span class="text-label-sm mt-1">Beranda</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:22px;">school</span>
            <span class="text-label-sm mt-1">Akademik</span>
        </a>
        <a href="#" class="bottom-nav-item active" aria-current="page">
            <span class="material-symbols-outlined icon-fill" style="font-size:22px;">account_balance_wallet</span>
            <span class="text-label-sm fw-bold mt-1">Keuangan</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:22px;">chat</span>
            <span class="text-label-sm mt-1">Pesan</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:22px;">person</span>
            <span class="text-label-sm mt-1">Profil</span>
        </a>
    </nav>

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
