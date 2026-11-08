<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Modul PPDB Baru - EduPortal</title>

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
            --on-secondary-fixed: #00201d;
            --on-secondary-fixed-variant: #005049;
            --tertiary: #291c00;
            --tertiary-container: #443000;
            --tertiary-fixed: #ffdea2;
            --tertiary-fixed-dim: #f7bd38;
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

        .text-display-lg {
            font-size: 40px;
            line-height: 48px;
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

        .text-on-secondary-fixed {
            color: var(--on-secondary-fixed) !important;
        }

        .text-on-secondary-fixed-variant {
            color: var(--on-secondary-fixed-variant) !important;
        }

        .text-on-tertiary-fixed {
            color: var(--on-tertiary-fixed) !important;
        }

        .text-on-tertiary-fixed-variant {
            color: var(--on-tertiary-fixed-variant) !important;
        }

        .text-primary-fixed-dim {
            color: var(--primary-fixed-dim) !important;
        }

        /* Top App Bar */
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
            box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 0;
            background: transparent;
            color: var(--on-surface-variant);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background .15s, transform .1s;
        }

        .icon-btn:hover {
            background: var(--surface-container);
        }

        .icon-btn:active {
            transform: scale(.95);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--surface-container-high);
            border: 1px solid var(--outline-variant);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            line-height: 18px;
        }

        /* Cards */
        .card-soft {
            background: var(--surface-container-lowest);
            border: 1px solid var(--surface-container-highest);
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .kpi-card {
            background: var(--surface-container-lowest);
            border: 1px solid var(--surface-container-highest);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .kpi-card.border-primary {
            border-left: 4px solid var(--primary) !important;
        }

        .kpi-card.border-secondary {
            border-left: 4px solid var(--secondary) !important;
        }

        .kpi-card.border-tertiary {
            border-left: 4px solid var(--tertiary-fixed-dim) !important;
        }

        .kpi-card.border-tint {
            border-left: 4px solid var(--surface-tint) !important;
        }

        .kpi-icon-box {
            padding: 8px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .kpi-icon-primary {
            background: var(--surface-container);
            color: var(--primary);
        }

        .kpi-icon-secondary {
            background: var(--secondary-fixed);
            color: var(--on-secondary-fixed);
        }

        .kpi-icon-tertiary {
            background: var(--tertiary-fixed);
            color: var(--on-tertiary-fixed);
        }

        /* Header card */
        .header-card {
            background: var(--surface-container-lowest);
            border: 1px solid var(--surface-container-highest);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

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
            color: var(--on-secondary-fixed-variant);
        }

        .link-secondary .material-symbols-outlined {
            transition: transform .15s;
        }

        .link-secondary:hover .material-symbols-outlined {
            transform: translateX(-4px);
        }

        .status-pill-live {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            background: var(--secondary-fixed);
            color: var(--on-secondary-fixed);
            border: 1px solid var(--secondary-container);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
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

        .btn-soft {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            border: 0;
            background: var(--surface-container);
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            transition: background .15s, transform .1s;
        }

        .btn-soft:hover {
            background: var(--surface-container-high);
            color: var(--primary);
        }

        .btn-soft:active {
            transform: scale(.95);
        }

        .btn-primary-solid {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            border: 0;
            background: var(--primary-container);
            color: var(--on-primary);
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            box-shadow: 0 4px 6px rgba(30, 42, 120, .15);
            transition: opacity .15s, transform .1s;
        }

        .btn-primary-solid:hover {
            color: var(--on-primary);
            opacity: .95;
        }

        .btn-primary-solid:active {
            transform: scale(.95);
        }

        /* Quick action tiles */
        .action-tile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px;
            border-radius: 12px;
            background: var(--surface-container-low);
            border: 1px solid rgba(198, 197, 211, .3);
            text-align: center;
            transition: background .15s, transform .1s;
            width: 100%;
            height: 100%;
        }

        .action-tile:hover {
            background: var(--surface-container-high);
        }

        .action-tile:active {
            transform: scale(.95);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
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

        .action-icon-high {
            background: var(--surface-container-highest);
            color: var(--primary);
        }

        .action-icon-low {
            background: var(--surface-container);
            color: var(--primary);
        }

        /* Search input */
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
            padding: 6px 12px 6px 36px !important;
            background: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: 12px;
            font-size: 12px;
            line-height: 16px;
            outline: none;
        }

        .search-input:focus {
            border-color: transparent;
            box-shadow: 0 0 0 0.2rem rgba(0, 106, 97, .25);
        }

        /* Filter tabs */
        .filter-tabs {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
            border-bottom: 1px solid var(--surface-container-high);
        }

        .filter-tabs button {
            padding: 6px 14px;
            border-radius: 8px;
            border: 0;
            background: transparent;
            color: var(--on-surface-variant);
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            white-space: nowrap;
            transition: background .15s;
        }

        .filter-tabs button:hover {
            background: var(--surface-container);
        }

        .filter-tabs button.active {
            background: var(--primary-container);
            color: var(--on-primary);
        }

        /* Student list cards */
        .student-card {
            background: var(--surface-container-lowest);
            border: 1px solid var(--surface-container-highest);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 16px;
            transition: border-color .15s, box-shadow .15s;
        }

        .student-card:hover {
            border-color: var(--secondary);
            box-shadow: 0 4px 8px rgba(0, 0, 0, .08);
        }

        @media (min-width: 768px) {
            .student-card {
                flex-direction: row;
                align-items: center;
            }
        }

        .avatar-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .avatar-secondary {
            background: var(--secondary-fixed);
            color: var(--on-secondary-fixed);
        }

        .avatar-high {
            background: var(--surface-container-high);
            color: var(--primary);
        }

        .avatar-low {
            background: var(--surface-container-low);
            color: var(--primary);
        }

        /* Status badges */
        .badge-soft {
            padding: 2px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            display: inline-flex;
            align-items: center;
            border: 1px solid transparent;
        }

        .badge-success {
            background: var(--secondary-fixed);
            color: var(--on-secondary-fixed);
            border-color: var(--secondary-container);
        }

        .badge-warning {
            background: var(--tertiary-fixed);
            color: var(--on-tertiary-fixed);
            border-color: var(--tertiary-fixed-dim);
        }

        .badge-neutral {
            background: var(--surface-container-high);
            color: var(--on-surface-variant);
            border-color: var(--outline-variant);
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 12px;
            border: 0;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            transition: background .15s, opacity .15s;
        }

        .btn-action.primary {
            background: var(--surface-container);
            color: var(--primary);
        }

        .btn-action.primary:hover {
            background: var(--surface-container-high);
            color: var(--primary);
        }

        .btn-action.secondary {
            background: var(--secondary);
            color: var(--on-secondary);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
        }

        .btn-action.secondary:hover {
            color: var(--on-secondary);
            opacity: .9;
        }

        /* Pagination */
        .page-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--outline-variant);
            background: transparent;
            color: var(--on-surface-variant);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            transition: background .15s;
        }

        .page-btn:hover {
            background: var(--surface-container);
            color: var(--on-surface-variant);
        }

        .page-btn.active {
            background: var(--primary);
            color: var(--on-primary);
            border-color: transparent;
        }

        .page-btn:disabled {
            opacity: .4;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 24px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 8px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: var(--surface-container-highest);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 24px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -24px;
            top: 4px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .timeline-dot.done {
            background: var(--secondary);
            box-shadow: 0 0 0 4px var(--secondary-fixed);
        }

        .timeline-dot.active {
            background: var(--primary-container);
            box-shadow: 0 0 0 4px var(--primary-fixed);
        }

        .timeline-dot.pending {
            background: var(--outline-variant);
        }

        /* Step badges */
        .step-num {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .step-num.neutral {
            background: var(--surface-container);
            color: var(--primary);
        }

        .step-num.secondary {
            background: var(--secondary);
            color: var(--on-secondary);
        }

        .step-num.primary {
            background: var(--primary-container);
            color: var(--on-primary);
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
            height: 6px;
            border-radius: 999px;
            background: var(--surface-container);
            overflow: hidden;
        }

        .progress-thin .progress-bar {
            background-color: var(--secondary);
            border-radius: 999px;
        }

        .accent-bar-secondary {
            width: 6px;
            height: 20px;
            background: var(--secondary);
            border-radius: 999px;
        }

        .accent-bar-primary {
            width: 6px;
            height: 20px;
            background: var(--primary);
            border-radius: 999px;
        }

        .accent-bar-secondary-container {
            width: 6px;
            height: 20px;
            background: var(--secondary-container);
            border-radius: 999px;
        }

        .divide-list> :not(:first-child) {
            margin-top: 12px;
        }
    </style>
</head>

<body>

    <!-- Top App Bar -->
    <header class="app-bar d-flex justify-content-between align-items-center px-3">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-icon">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div class="d-flex flex-column">
                <span class="text-headline-md text-primary fw-bold" style="letter-spacing:-.02em;">EduPortal</span>
                <span class="text-label-sm text-on-surface-variant d-none d-sm-block" style="margin-top:-2px;">SMA
                    Unggulan Terpadu</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="icon-btn" aria-label="Notifikasi">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <div class="user-avatar">PA</div>
        </div>
    </header>

    <!-- Main -->
    <main class="container-fluid py-4 mx-auto" style="max-width:1280px;">
        <div class="d-flex flex-column gap-4 px-3 px-sm-4 px-lg-5">

            <!-- Header card -->
            <div class="header-card">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex flex-column gap-2">
                        <a href="#" class="link-secondary">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span>Kembali ke Beranda</span>
                        </a>
                        <div class="d-flex flex-wrap align-items-center gap-3 pt-1">
                            <h1 class="text-headline-lg-mobile text-primary mb-0" style="letter-spacing:-.02em;">Modul
                                PPDB Baru</h1>
                            <span class="status-pill-live">
                                <span class="live-dot"></span>
                                Gelombang 2 Aktif
                            </span>
                        </div>
                        <p class="text-body-md text-on-surface-variant mb-0">
                            Penerimaan Peserta Didik Baru TA 2025/2026 • Periode Verifikasi Berkas &amp; Wawancara
                        </p>
                    </div>
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
                                    <span class="text-display-lg-mobile text-primary"
                                        style="letter-spacing:-.02em;">390</span>
                                    <span class="text-title-sm text-secondary fw-bold">80.4%</span>
                                </div>
                                <div class="progress progress-thin mt-2">
                                    <div class="progress-bar" role="progressbar" style="width:80.4%;"
                                        aria-valuenow="80.4" aria-valuemin="0" aria-valuemax="100"></div>
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
                                    <span class="text-display-lg-mobile text-primary"
                                        style="letter-spacing:-.02em;">120</span>
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

                        <div
                            class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
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
                                            <span>No. Reg: <strong
                                                    class="text-on-surface">PPDB-2025-0891</strong></span>
                                            <span>SMP Asal: <strong class="text-on-surface">SMPN 1
                                                    Surabaya</strong></span>
                                            <span
                                                class="d-inline-flex align-items-center gap-1 text-secondary fw-bold">
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
                                        <span class="material-symbols-outlined"
                                            style="font-size:16px;">visibility</span>
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
                                            <span>No. Reg: <strong
                                                    class="text-on-surface">PPDB-2025-0912</strong></span>
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
                                            <span class="material-symbols-outlined"
                                                style="font-size:14px;">info</span>
                                            <span>Kartu Keluarga kurang jelas / resolusi rendah</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-end gap-2 pt-3 pt-md-0 border-top border-md-0"
                                    style="border-color:var(--surface-container)!important;">
                                    <button type="button" class="btn-action secondary">
                                        <span class="material-symbols-outlined"
                                            style="font-size:16px;">edit_note</span>
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
                                            <span>No. Reg: <strong
                                                    class="text-on-surface">PPDB-2025-0955</strong></span>
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
                                        <span class="material-symbols-outlined"
                                            style="font-size:16px;">rate_review</span>
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
                                            <span>No. Reg: <strong
                                                    class="text-on-surface">PPDB-2025-0968</strong></span>
                                            <span>SMP Asal: <strong class="text-on-surface">SMP
                                                    Al-Hikmah</strong></span>
                                            <span
                                                class="d-inline-flex align-items-center gap-1 text-secondary fw-bold">
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
                                        <span class="material-symbols-outlined"
                                            style="font-size:16px;">visibility</span>
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
                                    <span class="material-symbols-outlined"
                                        style="font-size:14px;">chevron_left</span>
                                </button>
                                <button type="button" class="page-btn active">1</button>
                                <button type="button" class="page-btn">2</button>
                                <button type="button" class="page-btn">3</button>
                                <button type="button" class="page-btn">
                                    <span class="material-symbols-outlined"
                                        style="font-size:14px;">chevron_right</span>
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
                            <span class="material-symbols-outlined text-secondary"
                                style="font-size:20px;">account_tree</span>
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
