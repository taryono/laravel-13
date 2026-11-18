<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="color-scheme" content="light dark" />
    <title>EduPortal - Modul Data Karyawan &amp; SDM</title>

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
            --secondary-fixed-dim: #6bd8cb;
            --on-secondary: #ffffff;
            --on-secondary-container: #006f66;
            --on-secondary-fixed: #00201d;
            --on-secondary-fixed-variant: #005049;
            --tertiary: #291c00;
            --tertiary-container: #443000;
            --tertiary-fixed: #ffdea2;
            --tertiary-fixed-dim: #f7bd38;
            --on-tertiary: #ffffff;
            --on-tertiary-fixed: #261900;
            --on-tertiary-fixed-variant: #5c4200;
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

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--on-surface);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 20px;
            padding-bottom: 96px;
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

        .text-on-primary-fixed {
            color: var(--primary) !important;
        }

        .text-on-primary-fixed-variant {
            color: var(--primary-container) !important;
        }

        .text-on-secondary-container {
            color: var(--on-secondary-container) !important;
        }

        .text-on-tertiary-fixed {
            color: var(--on-tertiary-fixed) !important;
        }

        .text-on-tertiary-fixed-variant {
            color: var(--on-tertiary-fixed-variant) !important;
        }

        .text-tertiary {
            color: var(--tertiary) !important;
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

        .brand-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-container);
            color: var(--secondary-container);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
        }

        .notif-btn {
            position: relative;
            padding: 8px;
            border-radius: 12px;
            border: 0;
            background: transparent;
            color: var(--on-surface-variant);
            transition: background .15s, transform .1s;
        }

        .notif-btn:hover {
            background: var(--surface-container);
        }

        .notif-btn:active {
            transform: scale(.95);
        }

        .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--error);
            box-shadow: 0 0 0 2px var(--surface);
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
            border-bottom: 2px solid var(--primary-container);
            padding-bottom: 4px;
        }

        /* Cards */
        .card-soft {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, .3);
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(30, 42, 120, .04);
        }

        .kpi-card {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, .3);
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

        .kpi-card.border-primary-fixed {
            border-left: 4px solid var(--primary-fixed-dim) !important;
        }

        .kpi-card.border-tertiary {
            border-left: 4px solid var(--tertiary-fixed-dim) !important;
        }

        .kpi-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .kpi-icon-primary {
            background: var(--surface-container);
            color: var(--primary-container);
        }

        .kpi-icon-secondary {
            background: rgba(134, 242, 228, .3);
            color: var(--secondary);
        }

        .kpi-icon-high {
            background: var(--surface-container-high);
            color: var(--primary);
        }

        .kpi-icon-tertiary {
            background: rgba(255, 222, 162, .3);
            color: var(--on-tertiary-fixed-variant);
        }

        /* Header card */
        .status-pill-live {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 12px;
            background: var(--surface-container-low);
            border: 1px solid rgba(198, 197, 211, .4);
            font-size: 12px;
            font-weight: 600;
            line-height: 16px;
            color: var(--on-surface);
        }

        .live-dot {
            width: 10px;
            height: 10px;
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

        .btn-soft-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            border: 0;
            background: var(--primary-container);
            color: var(--on-primary);
            font-size: 13px;
            font-weight: 700;
            line-height: 18px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            transition: opacity .15s, transform .1s;
        }

        .btn-soft-primary:hover {
            color: var(--on-primary);
            opacity: .95;
        }

        .btn-soft-primary:active {
            transform: scale(.95);
        }

        /* Alert cuti */
        .alert-cuti {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, .3);
            border-left: 4px solid var(--tertiary-fixed-dim);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px -2px rgba(30, 42, 120, .04);
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 222, 162, .3);
            color: var(--on-tertiary-fixed-variant);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .badge-pending {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            background: var(--tertiary-fixed);
            color: var(--on-tertiary-fixed);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        /* Buttons */
        .btn-ghost {
            padding: 8px 14px;
            border-radius: 12px;
            border: 0;
            background: transparent;
            color: var(--on-surface-variant);
            font-size: 13px;
            font-weight: 700;
            line-height: 18px;
            transition: background .15s;
        }

        .btn-ghost:hover {
            background: var(--surface-container);
            color: var(--on-surface-variant);
        }

        .btn-primary-dark {
            padding: 8px 16px;
            border-radius: 12px;
            border: 0;
            background: var(--primary);
            color: var(--on-primary);
            font-size: 13px;
            font-weight: 700;
            line-height: 18px;
            transition: opacity .15s, transform .1s;
        }

        .btn-primary-dark:hover {
            color: var(--on-primary);
            opacity: .9;
        }

        .btn-primary-dark:active {
            transform: scale(.95);
        }

        /* Quick action tiles */
        .action-tile {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid rgba(198, 197, 211, .4);
            background: var(--surface);
            text-align: left;
            transition: background .15s, border-color .15s, transform .1s;
            width: 100%;
            height: 100%;
        }

        .action-tile:hover {
            background: var(--surface-container-low);
            border-color: rgba(0, 106, 97, .3);
        }

        .action-tile:active {
            transform: scale(.95);
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
            transition: transform .15s;
        }

        .action-tile:hover .action-icon {
            transform: scale(1.05);
        }

        .action-icon-primary {
            background: var(--primary-container);
            color: var(--on-primary);
        }

        .action-icon-secondary {
            background: var(--secondary);
            color: var(--on-secondary);
        }

        .action-icon-primary-solid {
            background: var(--primary);
            color: var(--on-primary);
        }

        .action-icon-tertiary {
            background: var(--tertiary);
            color: var(--on-tertiary);
        }

        /* Search */
        .search-wrap {
            position: relative;
        }

        .search-wrap .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--outline);
            font-size: 18px;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px 8px 36px !important;
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, .6);
            border-radius: 12px;
            font-size: 12px;
            line-height: 16px;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .search-input:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(0, 106, 97, .2);
        }

        .filter-btn {
            padding: 8px;
            border: 1px solid rgba(198, 197, 211, .5);
            background: var(--surface-container-lowest);
            border-radius: 12px;
            color: var(--on-surface-variant);
            transition: background .15s;
        }

        .filter-btn:hover {
            background: var(--surface-container);
            color: var(--on-surface-variant);
        }

        /* Filter tabs */
        .filter-tabs {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .filter-tabs::-webkit-scrollbar {
            display: none;
        }

        .filter-tabs button {
            padding: 8px 16px;
            border-radius: 12px;
            border: 1px solid rgba(198, 197, 211, .4);
            background: var(--surface-container-lowest);
            color: var(--on-surface-variant);
            font-size: 13px;
            font-weight: 700;
            line-height: 18px;
            white-space: nowrap;
            transition: background .15s, color .15s;
        }

        .filter-tabs button:hover {
            background: var(--surface-container);
            color: var(--primary);
        }

        .filter-tabs button.active {
            background: var(--primary-container);
            color: var(--on-primary);
            border-color: transparent;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        /* Employee cards */
        .employee-card {
            background: var(--surface-container-lowest);
            border: 1px solid rgba(198, 197, 211, .3);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px -2px rgba(30, 42, 120, .04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            transition: border-color .2s, box-shadow .2s;
        }

        .employee-card:hover {
            border-color: rgba(0, 106, 97, .4);
            box-shadow: 0 10px 25px -4px rgba(30, 42, 120, .08);
        }

        .employee-card.border-secondary {
            border-left: 4px solid var(--secondary) !important;
        }

        .employee-card.border-primary {
            border-left: 4px solid var(--primary-container) !important;
        }

        .employee-card.border-outline {
            border-left: 4px solid var(--outline) !important;
        }

        .emp-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 0 2px var(--surface-container);
            flex-shrink: 0;
        }

        .emp-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: var(--surface-container-low);
            color: var(--secondary);
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            flex-shrink: 0;
        }

        .emp-status .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--secondary);
        }

        .emp-status.primary {
            color: var(--primary);
        }

        .emp-status.primary .dot {
            background: var(--primary);
        }

        .emp-meta {
            margin-top: 16px;
            padding: 12px 0;
            border-top: 1px solid rgba(222, 233, 255, .6);
            border-bottom: 1px solid rgba(222, 233, 255, .6);
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 12px;
            line-height: 16px;
        }

        .emp-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .mono-tiny {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 12px;
            font-weight: 600;
            color: var(--on-surface);
        }

        .cert-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        .cert-success {
            background: rgba(134, 242, 228, .4);
            color: var(--on-secondary-container);
        }

        .cert-warning {
            background: rgba(255, 222, 162, .4);
            color: var(--on-tertiary-fixed-variant);
        }

        .cert-neutral {
            background: var(--primary-fixed);
            color: var(--on-primary-fixed);
        }

        .btn-outline-soft {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 12px;
            border: 1px solid rgba(198, 197, 211, .6);
            background: transparent;
            color: var(--on-surface-variant);
            font-size: 13px;
            font-weight: 700;
            line-height: 18px;
            transition: background .15s;
        }

        .btn-outline-soft:hover {
            background: var(--surface-container);
            color: var(--on-surface-variant);
        }

        .btn-fill-primary {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 12px;
            border: 0;
            background: var(--primary-container);
            color: var(--on-primary);
            font-size: 13px;
            font-weight: 700;
            line-height: 18px;
            transition: opacity .15s;
        }

        .btn-fill-primary:hover {
            color: var(--on-primary);
            opacity: .95;
        }

        /* Pagination */
        .page-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 0;
            background: transparent;
            color: var(--on-surface);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            transition: background .15s;
        }

        .page-btn:hover {
            background: var(--surface-container);
        }

        .page-btn.bordered {
            border: 1px solid rgba(198, 197, 211, .5);
            color: var(--outline);
        }

        .page-btn.bordered:hover {
            color: var(--on-surface);
        }

        .page-btn.active {
            background: var(--primary-container);
            color: var(--on-primary);
        }

        .page-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
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
            box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
        }

        /* Misc */
        .progress-thin {
            height: 8px;
            border-radius: 999px;
            background: var(--surface-container-highest);
            overflow: hidden;
        }

        .progress-thin .progress-bar {
            background-color: var(--secondary);
            border-radius: 999px;
        }

        .link-secondary-bold {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--secondary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            line-height: 18px;
            transition: color .15s;
        }

        .link-secondary-bold:hover {
            color: var(--on-secondary-container);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}" />
</head>

<body>

    <!-- Top App Bar -->
    <header class="app-bar d-flex justify-content-between align-items-center px-3">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-avatar">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div>
                <span class="text-headline-md text-primary d-block fw-bold"
                    style="letter-spacing:-.02em;line-height:1.1;">EduPortal</span>
                <span class="text-label-sm text-outline d-block">SMA Negeri 1 Edukasi</span>
            </div>
        </div>

        <!-- Web Nav -->
        <nav class="web-nav d-none d-md-flex align-items-center gap-4">
            <a href="#">Beranda</a>
            <a href="#" class="active">Akademik &amp; SDM</a>
            <a href="#">Keuangan</a>
            <a href="#">Pesan</a>
            <a href="#">Profil</a>
        </nav>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="notif-btn" aria-label="Notifikasi">
                <span class="material-symbols-outlined">notifications</span>
                <span class="notif-dot"></span>
            </button>
            <div class="d-none d-sm-flex align-items-center gap-2 ps-2 border-start"
                style="border-color:var(--outline-variant)!important;">
                <div class="text-end">
                    <p class="text-label-md text-on-surface mb-0" style="line-height:1.1;">Dr. Ir. Hendra M.</p>
                    <p class="text-label-sm text-outline mb-0">Kepala Sekolah</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="container-fluid py-4 mx-auto" style="max-width:1280px;">
        <div class="d-flex flex-column gap-4 px-3 px-sm-4 px-lg-5">

            @include('Dashboard::partials.ep_subnav', [
                'title' => 'Modul Karyawan & SDM',
                'badge' => 'Tahun Ajaran 2024/2025',
                'subtitle' => 'Manajemen Guru, Pendidik & Tenaga Kependidikan Terpadu',
                'metaIcon' => 'badge',
                'metaLabel' => 'Unit',
                'metaValue' => 'Tenaga Pendidik & Staf',
            ])

            <!-- Header card -->
            <div class="card-soft p-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <a href="{{ route('dashboard.back-to-home') }}" class="link-secondary-bold back-to-home mb-2">
                            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
                            <span>Kembali ke Beranda</span>
                        </a>
                        <h1 class="text-headline-lg-mobile text-primary fw-bold mt-2 mb-0"
                            style="letter-spacing:-.02em;">Modul Karyawan &amp; SDM</h1>
                        <p class="text-body-md text-on-surface-variant mb-0">Manajemen Guru, Pendidik &amp; Tenaga
                            Kependidikan Terpadu</p>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="status-pill-live">
                            <span class="live-dot"></span>
                            <span>Tahun Ajaran 2024/2025</span>
                        </div>
                        <button type="button" class="btn-soft-primary">
                            <span class="material-symbols-outlined" style="font-size:16px;">badge</span>
                            <span>Cetak ID Card SDM</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Alert Cuti -->
            <div class="alert-cuti">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="alert-icon">
                            <span class="material-symbols-outlined">pending_actions</span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h3 class="text-title-sm text-primary mb-0">Notifikasi Pengajuan Cuti &amp; Izin</h3>
                                <span class="badge-pending">2 Tertunda</span>
                            </div>
                            <p class="text-body-sm text-on-surface-variant mt-1 mb-0">
                                Terdapat 2 pengajuan izin cuti yang membutuhkan persetujuan dan verifikasi Kepala
                                Sekolah pekan ini.
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 align-self-sm-end align-self-md-center">
                        <button type="button" class="btn-ghost">Tinjau Nanti</button>
                        <button type="button" class="btn-primary-dark">Buka Antrean Cuti</button>
                    </div>
                </div>
            </div>

            <!-- KPI Bento -->
            <div class="row g-3">

                <!-- Total Pegawai -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="kpi-card border-primary">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-md text-on-surface-variant">Total Pegawai</span>
                            <div class="kpi-icon-box kpi-icon-primary">
                                <span class="material-symbols-outlined">groups</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="text-display-lg-mobile text-primary"
                                    style="letter-spacing:-.02em;">82</span>
                                <span class="text-label-md text-outline">Jiwa</span>
                            </div>
                            <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Karyawan &amp; Guru Terdaftar</p>
                        </div>
                        <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between text-body-sm"
                            style="border-color:var(--surface-container-high)!important;">
                            <span class="text-secondary fw-semibold">100% Aktif</span>
                            <span class="text-outline">Tahun Ajaran Aktif</span>
                        </div>
                    </div>
                </div>

                <!-- Guru -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="kpi-card border-secondary">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-md text-on-surface-variant">Guru Pengajar (Pendidik)</span>
                            <div class="kpi-icon-box kpi-icon-secondary">
                                <span class="material-symbols-outlined">cast_for_education</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="text-display-lg-mobile text-primary"
                                    style="letter-spacing:-.02em;">64</span>
                                <span class="text-label-md text-secondary fw-semibold">Orang</span>
                            </div>
                            <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Terbagi dalam 14 Mata Pelajaran
                            </p>
                        </div>
                        <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between text-body-sm"
                            style="border-color:var(--surface-container-high)!important;">
                            <span class="text-on-surface-variant">Wali Kelas: <strong
                                    class="text-primary">24</strong></span>
                            <span class="text-outline">Guru BK: <strong class="text-primary">4</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Tenaga Kependidikan -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="kpi-card border-primary-fixed">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-md text-on-surface-variant">Tenaga Kependidikan / TU</span>
                            <div class="kpi-icon-box kpi-icon-high">
                                <span class="material-symbols-outlined">corporate_fare</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="text-display-lg-mobile text-primary"
                                    style="letter-spacing:-.02em;">18</span>
                                <span class="text-label-md text-outline">Orang</span>
                            </div>
                            <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Administrasi, Lab &amp; Perpus
                            </p>
                        </div>
                        <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between text-body-sm"
                            style="border-color:var(--surface-container-high)!important;">
                            <span class="text-on-surface-variant">PNS: <strong class="text-primary">11</strong></span>
                            <span class="text-outline">PPPK/Honorer: <strong class="text-primary">7</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Sertifikasi -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="kpi-card border-tertiary">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-label-md text-on-surface-variant">Status Sertifikasi</span>
                            <div class="kpi-icon-box kpi-icon-tertiary">
                                <span class="material-symbols-outlined">verified</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="text-display-lg-mobile text-primary"
                                    style="letter-spacing:-.02em;">78%</span>
                                <span class="text-label-md text-secondary fw-semibold">+4% thn ini</span>
                            </div>
                            <p class="text-body-sm text-on-surface-variant mt-1 mb-0">50 dari 64 Guru Tersertifikasi
                            </p>
                        </div>
                        <div class="mt-3 pt-3 border-top"
                            style="border-color:var(--surface-container-high)!important;">
                            <div class="progress progress-thin">
                                <div class="progress-bar" role="progressbar" style="width:78%;" aria-valuenow="78"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Aksi Cepat -->
            <div class="card-soft p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <h2 class="text-title-sm text-primary mb-0">Aksi Cepat Manajemen SDM</h2>
                        <p class="text-body-sm text-outline mb-0">Operasional administrasi harian tenaga kependidikan
                        </p>
                    </div>
                    <span class="text-label-sm text-secondary fw-semibold px-2 py-1 rounded-pill"
                        style="background:var(--surface-container-low);">Tindakan Cepat</span>
                </div>
                <div class="row g-3">

                    <div class="col-6 col-md-3">
                        <button type="button" class="action-tile">
                            <div class="action-icon action-icon-primary">
                                <span class="material-symbols-outlined">person_add</span>
                            </div>
                            <span class="text-title-sm text-primary">Tambah Pegawai Baru</span>
                            <span class="text-body-sm text-on-surface-variant mt-1">Registrasi NIP/NUPTK &amp;
                                berkas</span>
                        </button>
                    </div>

                    <div class="col-6 col-md-3">
                        <button type="button" class="action-tile">
                            <div class="action-icon action-icon-secondary">
                                <span class="material-symbols-outlined">event_busy</span>
                            </div>
                            <span class="text-title-sm text-primary">Pengajuan Cuti &amp; Izin</span>
                            <span class="text-body-sm text-on-surface-variant mt-1">Permohonan, surat &amp; rekap
                                absensi</span>
                        </button>
                    </div>

                    <div class="col-6 col-md-3">
                        <button type="button" class="action-tile">
                            <div class="action-icon action-icon-primary-solid">
                                <span class="material-symbols-outlined">assessment</span>
                            </div>
                            <span class="text-title-sm text-primary">Penilaian Kinerja (SKP)</span>
                            <span class="text-body-sm text-on-surface-variant mt-1">Evaluasi semester &amp; indikator
                                guru</span>
                        </button>
                    </div>

                    <div class="col-6 col-md-3">
                        <button type="button" class="action-tile">
                            <div class="action-icon action-icon-tertiary">
                                <span class="material-symbols-outlined">receipt_long</span>
                            </div>
                            <span class="text-title-sm text-primary">Unduh Rekap Gaji</span>
                            <span class="text-body-sm text-on-surface-variant mt-1">Slip tunjangan fungsional &amp;
                                honor</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Employee Directory -->
            <div class="d-flex flex-column gap-3">

                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div>
                        <h2 class="text-headline-md text-primary mb-0">Daftar Tenaga Pendidik &amp; Staf</h2>
                        <p class="text-body-sm text-on-surface-variant mb-0">Direktori pengajar aktif dan personil tata
                            usaha sekolah</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="search-wrap" style="min-width:256px;">
                            <span class="material-symbols-outlined search-icon">search</span>
                            <input type="text" class="search-input" placeholder="Cari nama, NIP atau mapel..." />
                        </div>
                        <button type="button" class="filter-btn" title="Filter Tambahan">
                            <span class="material-symbols-outlined" style="font-size:20px;">tune</span>
                        </button>
                    </div>
                </div>

                <!-- Filter tabs -->
                <div class="filter-tabs">
                    <button type="button" class="active">Semua (82)</button>
                    <button type="button">Guru MIPA (26)</button>
                    <button type="button">Guru IPS/Bahasa (24)</button>
                    <button type="button">Staf Administrasi/TU (18)</button>
                    <button type="button">Guru PJOK &amp; Seni (14)</button>
                </div>

                <!-- Employee grid -->
                <div class="row g-3">

                    <!-- Card 1 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="employee-card border-secondary">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBATE0SBLJR8VeKUetmwORiC3cetbWKXplzeBPnjQ5qsv4v7K8gNB86BL34ECjH8SBBou6gLiYfXvHzkhPwjMhEliWpIUqwU7szxDuJDY3PsgBPL_8SjIPmQcwXdRLpFw7luaRV5eebQ1L97_XiPHQlJwiShkTmMyasWrTsQi1TUjU3PFDSJGEfN5OHTkHTv_D3lFp5rNnt6UCL7xaVDAgAoxUOucWVTMu8nAnS8en8"
                                            class="emp-avatar" alt="Bambang Sudrajat" />
                                        <div>
                                            <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Drs.
                                                H. Bambang Sudrajat, M.Pd.</h3>
                                            <p class="text-body-sm text-secondary fw-semibold mb-0">Guru Fisika Senior
                                                • MIPA</p>
                                        </div>
                                    </div>
                                    <span class="emp-status">
                                        <span class="dot"></span>
                                        Aktif Mengajar
                                    </span>
                                </div>

                                <div class="emp-meta">
                                    <div class="emp-meta-row">
                                        <span class="text-outline">NIP / NUPTK:</span>
                                        <span class="mono-tiny">19680412 199403 1 002</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Beban Ajar:</span>
                                        <span class="text-primary fw-semibold">24 Jam / Minggu (Kelas XII MIPA)</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Sertifikasi:</span>
                                        <span class="cert-badge cert-success">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">check_circle</span>
                                            Terverifikasi Kemdikbud
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 mt-3">
                                <button type="button" class="btn-outline-soft">
                                    <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                                    <span>Jam Mengajar</span>
                                </button>
                                <button type="button" class="btn-fill-primary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                                    <span>Detail Profil</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="employee-card border-secondary">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC8UuKR-v0E6W-tCZ1u-yLc2CobikJbu6_dgIbH9uX4CzMbDaUI363zpYk3Bf8G9CV6KA--lNfzzK3M1Tm34mWxUoOPmjmmeXljpBNRdZpZcALHDrWniO0tsTJwKSrKw-TeMJYhb-mmmtT_PImcxfZ--BibL6LtsE2YS66PrWLpUtMalWqowV6LlgM8Sirg1ubDnrYC84n6cZVtCKb0PvAp9io3fyVGWyPm9-phuUTH"
                                            class="emp-avatar" alt="Siti Rahmah" />
                                        <div>
                                            <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Siti
                                                Rahmah, S.Si., M.Biotech.</h3>
                                            <p class="text-body-sm text-secondary fw-semibold mb-0">Guru Biologi &amp;
                                                Kepala Lab • MIPA</p>
                                        </div>
                                    </div>
                                    <span class="emp-status">
                                        <span class="dot"></span>
                                        Aktif Mengajar
                                    </span>
                                </div>
                                <div class="emp-meta">
                                    <div class="emp-meta-row">
                                        <span class="text-outline">NIP / NUPTK:</span>
                                        <span class="mono-tiny">19820519 200801 2 015</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Beban Ajar:</span>
                                        <span class="text-primary fw-semibold">22 Jam / Minggu (Kelas XI &amp;
                                            XII)</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Sertifikasi:</span>
                                        <span class="cert-badge cert-success">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">check_circle</span>
                                            Pendidik Profesional
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <button type="button" class="btn-outline-soft">
                                    <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                                    <span>Jam Mengajar</span>
                                </button>
                                <button type="button" class="btn-fill-primary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                                    <span>Detail Profil</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="employee-card border-primary">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDXy4Bdn_ejcoZeEekwIqN9vw5fY0UgY1lC1WOTMnJDsmvCf5pwtcKblA6hmhsLmlqkDdlTkrTO7oTj-9Xx1XJUBZrgUSWFAAVwEAWSWt2udVpdh09zeeThYehTJjwLFQnlPjM5nlY1CYBVc6s5859WFEtGeCe9Epo2Xf8-zebulEnMVvtUUORwG_Vha-kav7b5Y3zaStCy0NQRSsaoy-yN6Y63QdmMt-l684RyVdIB"
                                            class="emp-avatar" alt="Ahmad Fikri Pratama" />
                                        <div>
                                            <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Ahmad
                                                Fikri Pratama, S.Pd.</h3>
                                            <p class="text-body-sm text-on-primary-fixed-variant fw-semibold mb-0">Guru
                                                Bahasa Inggris • Bahasa</p>
                                        </div>
                                    </div>
                                    <span class="emp-status">
                                        <span class="dot"></span>
                                        Aktif Mengajar
                                    </span>
                                </div>
                                <div class="emp-meta">
                                    <div class="emp-meta-row">
                                        <span class="text-outline">NIP / NUPTK:</span>
                                        <span class="mono-tiny">19910903 201903 1 008</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Beban Ajar:</span>
                                        <span class="text-primary fw-semibold">20 Jam / Minggu + Pembina OSIS</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Sertifikasi:</span>
                                        <span class="cert-badge cert-warning">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">sync</span>
                                            PPG Dalam Jabatan
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <button type="button" class="btn-outline-soft">
                                    <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                                    <span>Jam Mengajar</span>
                                </button>
                                <button type="button" class="btn-fill-primary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                                    <span>Detail Profil</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="employee-card border-primary">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBHe2u6yt-hMQilI7KF37rl8sILB_tRClkes3HX7cAGpwuNwG2NLctNbn0PH1nfootOZWH03GHt5vs8nMEFdZxd-dH0ytIqlUkSGJYgt89WDYJh-RyOMyEn47xhVOjd-s1LE7auRE7toQQ0LzDAU1tXgO1yYznbNdiSmOwehAPYgniPmC4NBe8GPTaryrpHHNieRFPm0P21EdDWvsyWHhiJKysyV-7V6SGePmTJMoNo"
                                            class="emp-avatar" alt="Nurul Hidayati" />
                                        <div>
                                            <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Hj.
                                                Nurul Hidayati, M.Hum.</h3>
                                            <p class="text-body-sm text-on-primary-fixed-variant fw-semibold mb-0">Guru
                                                Sejarah Indonesia • IPS</p>
                                        </div>
                                    </div>
                                    <span class="emp-status">
                                        <span class="dot"></span>
                                        Aktif Mengajar
                                    </span>
                                </div>
                                <div class="emp-meta">
                                    <div class="emp-meta-row">
                                        <span class="text-outline">NIP / NUPTK:</span>
                                        <span class="mono-tiny">19741120 199903 2 004</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Beban Ajar:</span>
                                        <span class="text-primary fw-semibold">24 Jam / Minggu (Kelas X &amp; XI
                                            IPS)</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Sertifikasi:</span>
                                        <span class="cert-badge cert-success">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">check_circle</span>
                                            Terverifikasi Kemdikbud
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <button type="button" class="btn-outline-soft">
                                    <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                                    <span>Jam Mengajar</span>
                                </button>
                                <button type="button" class="btn-fill-primary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                                    <span>Detail Profil</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="employee-card border-outline">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDFiweBdNKQofpS4iAJjinmZAUINyKOIMS3aSRE1xJhCEKHpHsl0ezP50_2oUe7w8bbrHfqGE8C1n1gjqIjnAbswDzzSc6NGQP1jX0YzsFKPWlHwJWxMrxeV8B4CgATSPsK1ZOR6IPvUJQZmYzg3R80xtc1ZpwiFaidWY6W57Gp-F9p-P5Cb80F2D5T2bvqzgFoJmnd_qTCmfSV1tiC7KicpXhthNo8Y8OIDZUzyPFU"
                                            class="emp-avatar" alt="Budi Santoso" />
                                        <div>
                                            <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Budi
                                                Santoso, S.AP.</h3>
                                            <p class="text-body-sm text-outline fw-semibold mb-0">Kepala Tata Usaha •
                                                Staf TU</p>
                                        </div>
                                    </div>
                                    <span class="emp-status primary">
                                        <span class="dot"></span>
                                        Aktif Dinas
                                    </span>
                                </div>
                                <div class="emp-meta">
                                    <div class="emp-meta-row">
                                        <span class="text-outline">NIP:</span>
                                        <span class="mono-tiny">19790214 200501 1 003</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Bidang Tugas:</span>
                                        <span class="text-primary fw-semibold">Kepegawaian &amp; Kesiswaan</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Status Kepegawaian:</span>
                                        <span class="cert-badge cert-neutral">
                                            ASN / PNS Penata Tk. I
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <button type="button" class="btn-outline-soft">
                                    <span class="material-symbols-outlined" style="font-size:16px;">assignment</span>
                                    <span>Tupoksi Kerja</span>
                                </button>
                                <button type="button" class="btn-fill-primary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                                    <span>Detail Profil</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="employee-card border-secondary">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC9CydZKXb4jMDqyNmxDORbwKCXSXWZRE95yJe2m1ZbNQKalBaua9SkAHJosajVUhvoViGjP3LzwAFuP-0QGSN1krUbDv9XXGpHLw-jafracLR3hOgejcA_KjVv0Sk2Se-zeDg4LyZZnPFWggACHgCOfvsgNE86anf86FomSFXZxkmadyl8pTfxMOBo0VEwYZL4HgCiHIgy87_gToxDSQRIOBej-h9nrY3cJY65C5sE"
                                            class="emp-avatar" alt="Ratna Dewi Kusuma" />
                                        <div>
                                            <h3 class="text-title-sm text-primary mb-0" style="line-height:1.3;">Ratna
                                                Dewi Kusuma, M.Pd.</h3>
                                            <p class="text-body-sm text-secondary fw-semibold mb-0">Guru Matematika
                                                Peminatan • MIPA</p>
                                        </div>
                                    </div>
                                    <span class="emp-status">
                                        <span class="dot"></span>
                                        Aktif Mengajar
                                    </span>
                                </div>
                                <div class="emp-meta">
                                    <div class="emp-meta-row">
                                        <span class="text-outline">NIP / NUPTK:</span>
                                        <span class="mono-tiny">19850311 201001 2 021</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Beban Ajar:</span>
                                        <span class="text-primary fw-semibold">24 Jam / Minggu + Wali Kelas XII</span>
                                    </div>
                                    <div class="emp-meta-row">
                                        <span class="text-outline">Sertifikasi:</span>
                                        <span class="cert-badge cert-success">
                                            <span class="material-symbols-outlined"
                                                style="font-size:12px;">check_circle</span>
                                            Terverifikasi Kemdikbud
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <button type="button" class="btn-outline-soft">
                                    <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                                    <span>Jam Mengajar</span>
                                </button>
                                <button type="button" class="btn-fill-primary">
                                    <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                                    <span>Detail Profil</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Pagination -->
                <div
                    class="card-soft p-3 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                    <p class="text-body-sm text-outline mb-0">
                        Menampilkan <span class="fw-bold text-on-surface">6</span> dari <span
                            class="fw-bold text-on-surface">82</span> Karyawan &amp; Tenaga Pendidik
                    </p>
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="page-btn bordered" disabled>
                            <span class="material-symbols-outlined" style="font-size:14px;">chevron_left</span>
                        </button>
                        <button type="button" class="page-btn active">1</button>
                        <button type="button" class="page-btn">2</button>
                        <button type="button" class="page-btn">3</button>
                        <span class="px-1 text-outline">...</span>
                        <button type="button" class="page-btn">14</button>
                        <button type="button" class="page-btn bordered">
                            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- Bottom Nav -->
    <nav class="bottom-nav">
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:22px;">dashboard</span>
            <span class="text-label-sm mt-1">Beranda</span>
        </a>
        <a href="#" class="bottom-nav-item active" aria-current="page">
            <span class="material-symbols-outlined icon-fill" style="font-size:22px;">school</span>
            <span class="text-label-sm fw-bold mt-1">Akademik</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:22px;">account_balance_wallet</span>
            <span class="text-label-sm mt-1">Keuangan</span>
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
</body>

</html>
