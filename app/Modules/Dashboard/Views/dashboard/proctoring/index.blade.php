<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EduPortal_Live Proctoring CBT</title>

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
            --surface-dim: #cfdaf1;
            --on-surface: #111c2c;
            --on-surface-variant: #454651;
            --outline: #767682;
            --outline-variant: #c6c5d3;
            --background: #f9f9ff;
            --inverse-surface: #263142;
            --inverse-primary: #bcc3ff;
            --surface-variant: #d8e3f9;
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
            flex-direction: column;
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

        .text-on-surface-variant {
            color: var(--on-surface-variant) !important;
        }

        .text-on-secondary-fixed {
            color: var(--on-secondary-fixed) !important;
        }

        .text-on-error-container {
            color: var(--on-error-container) !important;
        }

        .text-on-tertiary-fixed-variant {
            color: var(--on-tertiary-fixed-variant) !important;
        }

        /* Top app bar */
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

        .web-nav a {
            color: var(--on-surface-variant);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            text-decoration: none;
            transition: background .15s;
        }

        .web-nav a:hover {
            background: var(--surface-container);
            color: var(--on-surface-variant);
        }

        .web-nav a.active {
            color: var(--primary);
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .notif-btn {
            position: relative;
            padding: 8px;
            border-radius: 12px;
            border: 0;
            background: transparent;
            color: var(--on-surface-variant);
            transition: background .15s;
        }

        .notif-btn:hover {
            background: var(--surface-container);
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

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--secondary-fixed);
            color: var(--on-secondary-fixed);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            line-height: 18px;
        }

        /* Sub-header */
        .sub-header {
            background: var(--surface-container-lowest);
            border-bottom: 1px solid var(--surface-container-high);
        }

        .status-pill-danger {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 10px;
            border-radius: 999px;
            background: var(--error-container);
            color: var(--on-error-container);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .6;
            }
        }

        .status-pill-neutral {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 999px;
            background: var(--surface-container);
            color: var(--on-surface-variant);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
        }

        .timer-text {
            color: var(--primary);
            font-weight: 700;
        }

        .btn-soft {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 12px;
            background: var(--surface-container-low);
            color: var(--on-surface);
            border: 1px solid var(--outline-variant);
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            transition: background .15s, transform .1s;
        }

        .btn-soft:hover {
            background: var(--surface-container);
            color: var(--on-surface);
        }

        .btn-soft:active {
            transform: scale(.95);
        }

        .btn-danger-solid {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 12px;
            background: var(--error);
            color: var(--on-error);
            border: 0;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            transition: opacity .15s, transform .1s;
        }

        .btn-danger-solid:hover {
            opacity: .9;
            color: var(--on-error);
        }

        .btn-danger-solid:active {
            transform: scale(.95);
        }

        /* Cards */
        .card-soft {
            background: var(--surface-container-lowest);
            border: 1px solid var(--surface-container);
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .kpi-card {
            padding: 16px;
            border-radius: 16px;
            border: 1px solid var(--surface-container);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            background: var(--surface-container-lowest);
        }

        .kpi-card.border-primary {
            border-left: 4px solid var(--primary-container) !important;
        }

        .kpi-card.border-secondary {
            border-left: 4px solid var(--secondary) !important;
        }

        .kpi-card.border-error {
            border-left: 4px solid var(--error) !important;
        }

        .kpi-card.border-outline {
            border-left: 4px solid var(--outline) !important;
        }

        .kpi-card.alert-tint {
            background: rgba(255, 218, 214, .1);
        }

        /* AI Alert Banner */
        .ai-alert {
            background: var(--error-container);
            border: 1px solid rgba(186, 26, 26, .3);
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        @media (min-width: 768px) {
            .ai-alert {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .ai-alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--error);
            color: var(--on-error);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
        }

        .ai-alert-tag {
            padding: 2px 8px;
            border-radius: 4px;
            background: var(--error);
            color: var(--on-error);
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        /* Filter tabs */
        .filter-tabs button {
            flex-shrink: 0;
            padding: 6px 12px;
            border-radius: 12px;
            border: 0;
            background: var(--surface-container-low);
            color: var(--on-surface-variant);
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            transition: background .15s, color .15s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .filter-tabs button:hover {
            background: var(--surface-container);
        }

        .filter-tabs button.active {
            background: var(--primary);
            color: var(--on-primary);
        }

        .filter-tabs button.warning {
            color: var(--error);
        }

        .filter-tabs .count-badge {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--error);
            color: var(--on-error);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
        }

        .view-toggle {
            border: 1px solid var(--outline-variant);
            border-radius: 12px;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .view-toggle button {
            padding: 4px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--on-surface-variant);
            transition: background .15s, color .15s;
        }

        .view-toggle button:hover {
            background: var(--surface-container);
        }

        .view-toggle button.active {
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
            color: var(--on-surface-variant);
            font-size: 20px;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 8px 16px 8px 40px !important;
            background: var(--surface-container-low);
            border: 1px solid var(--outline-variant);
            border-radius: 12px;
            font-size: 14px;
            line-height: 20px;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .search-input::placeholder {
            color: rgba(69, 70, 81, .7);
        }

        .search-input:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(0, 106, 97, .25);
            background: var(--surface-container-low);
        }

        .form-select-soft {
            padding: 8px 12px;
            background: var(--surface-container-low);
            border: 1px solid var(--outline-variant);
            border-radius: 12px;
            font-size: 14px;
            line-height: 20px;
            color: var(--on-surface);
            outline: none;
        }

        .form-select-soft:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(0, 106, 97, .25);
        }

        /* Student monitor cards */
        .monitor-card {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            padding: 16px;
            background: var(--surface-container-lowest);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .monitor-card.critical {
            border: 2px solid var(--error);
        }

        .monitor-card.warning {
            border: 2px solid var(--tertiary-fixed-dim);
        }

        .monitor-card.normal {
            border: 1px solid var(--surface-container-high);
        }

        .monitor-card.offline {
            border: 1px solid var(--outline-variant);
            opacity: .95;
        }

        .corner-badge {
            position: absolute;
            top: 0;
            right: 0;
            padding: 2px 12px;
            border-bottom-left-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .corner-badge.danger {
            background: var(--error);
            color: var(--on-error);
        }

        .corner-badge.warning {
            background: var(--tertiary-container);
            color: var(--tertiary-fixed);
        }

        .corner-badge.success {
            background: var(--secondary-fixed);
            color: var(--on-secondary-fixed);
        }

        .corner-badge.offline {
            background: var(--surface-dim);
            color: var(--on-surface-variant);
        }

        .avatar-monitor {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .avatar-monitor.danger {
            background: var(--error-container);
            color: var(--error);
            border: 1px solid rgba(186, 26, 26, .2);
        }

        .avatar-monitor.warning {
            background: var(--surface-container-high);
            color: var(--tertiary-container);
            border: 1px solid rgba(247, 189, 56, .4);
        }

        .avatar-monitor.success {
            background: var(--secondary-container);
            color: var(--on-secondary-container);
        }

        .avatar-monitor.offline {
            background: var(--surface-container-high);
            color: var(--outline);
        }

        .metric-inline {
            background: var(--surface-container-low);
            border: 1px solid var(--surface-container);
            border-radius: 12px;
            padding: 10px;
            margin-bottom: 12px;
        }

        .metric-inline .progress {
            height: 8px;
            border-radius: 999px;
            background: var(--surface-container-high);
            overflow: hidden;
            margin-bottom: 8px;
        }

        .metric-inline .progress-bar {
            border-radius: 999px;
        }

        .progress-danger .progress-bar {
            background-color: var(--error);
        }

        .progress-warning .progress-bar {
            background-color: var(--tertiary-container);
        }

        .progress-success .progress-bar {
            background-color: var(--secondary);
        }

        .progress-outline .progress-bar {
            background-color: var(--outline);
        }

        .btn-action {
            padding: 8px 12px;
            border-radius: 12px;
            border: 0;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            transition: background .15s, opacity .15s, transform .1s;
        }

        .btn-action:active {
            transform: scale(.95);
        }

        .btn-action.neutral {
            background: var(--surface-container-high);
            color: var(--on-surface);
        }

        .btn-action.neutral:hover {
            background: var(--surface-container);
        }

        .btn-action.danger {
            background: var(--error);
            color: var(--on-error);
        }

        .btn-action.danger:hover {
            opacity: .9;
        }

        .btn-action.outline {
            background: var(--surface-container-low);
            border: 1px solid var(--outline-variant);
            color: var(--on-surface);
        }

        .btn-action.outline:hover {
            background: var(--surface-container);
        }

        .btn-action.primary {
            background: var(--primary-container);
            color: var(--on-primary);
        }

        .btn-action.primary:hover {
            background: var(--primary);
        }

        /* Audit log */
        .audit-log-item {
            display: flex;
            gap: 12px;
            font-size: 12px;
            line-height: 16px;
        }

        .time-badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            line-height: 14px;
            flex-shrink: 0;
            align-self: flex-start;
        }

        .time-badge.danger {
            background: var(--error-container);
            color: var(--on-error-container);
        }

        .time-badge.warning {
            background: var(--tertiary-fixed);
            color: var(--on-tertiary-fixed-variant);
        }

        .time-badge.success {
            background: var(--secondary-fixed);
            color: var(--on-secondary-fixed);
        }

        .time-badge.neutral {
            background: var(--surface-container);
            color: var(--on-surface-variant);
        }

        .live-dot-pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--secondary);
            animation: pulse 1.5s infinite;
        }

        /* Network status */
        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            border-radius: 12px;
            background: var(--surface-container-low);
            font-size: 12px;
            line-height: 16px;
        }

        /* Emergency broadcast */
        .broadcast-card {
            background: var(--primary-container);
            color: var(--on-primary);
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .broadcast-input {
            width: 100%;
            padding: 6px 12px;
            border-radius: 12px;
            border: 0;
            background: var(--surface-container-lowest);
            color: var(--on-surface);
            font-size: 12px;
            line-height: 16px;
            outline: none;
        }

        .broadcast-input::placeholder {
            color: var(--on-surface-variant);
        }

        .btn-broadcast {
            padding: 6px 12px;
            border-radius: 12px;
            border: 0;
            background: var(--secondary);
            color: var(--on-secondary);
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
            flex-shrink: 0;
        }

        .btn-broadcast:hover {
            opacity: .9;
            color: var(--on-secondary);
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

        /* Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--outline-variant);
            border-radius: 4px;
        }

        /* Misc */
        .link-secondary {
            color: var(--secondary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
        }

        .link-secondary:hover {
            color: var(--on-secondary-container);
            text-decoration: none;
        }

        .link-primary {
            color: var(--primary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            line-height: 18px;
        }

        .link-primary:hover {
            text-decoration: none;
            color: var(--primary);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}" />
</head>

<body>

    <!-- Top App Bar -->
    <header class="app-bar d-flex justify-content-between align-items-center px-3">
        <div class="d-flex align-items-center gap-3">
            <div class="brand-icon">
                <span class="material-symbols-outlined" style="font-size:22px;">school</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-headline-md text-primary mb-0" style="letter-spacing:-.02em;">EduPortal</span>
                <span
                    class="d-none d-md-inline-block px-2 py-1 rounded-pill bg-surface-container text-on-surface-variant text-label-sm"
                    style="background:var(--surface-container);">
                    CBT Proctoring Station
                </span>
            </div>
        </div>

        <!-- Web Nav -->
        <nav class="web-nav d-none d-md-flex align-items-center gap-2">
            <a href="#">Beranda</a>
            <a href="#" class="active">
                <span class="d-inline-block rounded-circle"
                    style="width:8px;height:8px;background:var(--secondary);"></span>
                Akademik
            </a>
            <a href="#">Keuangan</a>
            <a href="#">Pesan</a>
            <a href="#">Profil</a>
        </nav>

        <!-- Trailing -->
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="notif-btn" title="Notifikasi Pengawasan">
                <span class="material-symbols-outlined">notifications</span>
                <span class="notif-dot"></span>
            </button>
            <div class="d-none d-sm-flex align-items-center gap-2 ps-2 border-start"
                style="border-color:var(--outline-variant)!important;">
                <div class="user-avatar">PG</div>
                <div class="text-start" style="font-size:12px;line-height:16px;">
                    <p class="fw-bold text-on-surface mb-0" style="line-height:1.1;">Pak Gunawan, S.Pd</p>
                    <p class="text-on-surface-variant mb-0" style="line-height:1.1;">Pengawas Utama</p>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid pt-3 px-3" style="max-width:1280px;margin:0 auto;">
        @include('Dashboard::partials.ep_subnav', [
            'title' => 'Live Proctoring & Monitoring',
            'badge' => 'Ujian Berlangsung_Sesi 1',
            'subtitle' => 'Simulasi Matematika Peminatan XII MIPA • Lab Multimedia 1 & 2',
            'metaIcon' => 'timer',
            'metaLabel' => 'Sisa Waktu',
            'metaValue' => '01:14:22',
        ])
    </div>

    <!-- Sub-header -->
    <div class="sub-header">
        <div class="container-fluid" style="max-width:1280px;margin:0 auto;">
            <div class="px-3 py-4 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">

                <div>
                    <!-- Status row -->
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <button type="button"
                            class="btn btn-link p-0 link-secondary d-inline-flex align-items-center gap-1 text-decoration-none">
                            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
                            <span>Kembali ke CBT</span>
                        </button>
                        <span style="color:var(--outline-variant);">•</span>
                        <div class="status-pill-danger">
                            <span class="rounded-circle" style="width:8px;height:8px;background:var(--error);"></span>
                            <span>Ujian Berlangsung_Sesi 1</span>
                        </div>
                        <div class="status-pill-neutral d-none d-sm-inline-flex">
                            <span class="material-symbols-outlined" style="font-size:14px;">timer</span>
                            <span class="timer-text">01:14:22 Tersisa</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="d-flex flex-wrap align-items-baseline gap-2">
                        <h1 class="text-headline-md text-on-surface mb-0" style="letter-spacing:-.02em;">
                            Live Proctoring &amp; Monitoring
                        </h1>
                        <span class="text-body-sm text-on-surface-variant fw-medium">
                            Simulasi Matematika Peminatan XII MIPA (Kode: CBT-MIPA-12)
                        </span>
                    </div>

                    <p class="text-body-sm text-on-surface-variant mt-1 d-flex align-items-center gap-2 mb-0 flex-wrap">
                        <span class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined text-secondary" style="font-size:14px;">domain</span>
                            <span>Lab Multimedia 1 &amp; 2</span>
                        </span>
                        <span>•</span>
                        <span class="d-flex align-items-center gap-1 text-secondary">
                            <span class="material-symbols-outlined" style="font-size:14px;">wifi</span>
                            <span>Jaringan Lab Stabil (16ms)</span>
                        </span>
                    </p>
                </div>

                <!-- Actions -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button type="button" class="btn-soft">
                        <span class="material-symbols-outlined text-primary" style="font-size:18px;">campaign</span>
                        <span>Broadcast Pesan</span>
                    </button>
                    <button type="button" class="btn-soft">
                        <span class="material-symbols-outlined"
                            style="font-size:18px;color:var(--tertiary-container);">key</span>
                        <span>Token Darurat</span>
                    </button>
                    <button type="button" class="btn-danger-solid">
                        <span class="material-symbols-outlined" style="font-size:18px;">pause_circle</span>
                        <span>Pause Semua Sesi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main -->
    <main class="container-fluid flex-grow-1 py-4" style="max-width:1280px;margin:0 auto;">
        <div class="d-flex flex-column gap-4 px-3">

            <!-- KPI Row -->
            <div class="row g-3">
                <!-- Total Peserta -->
                <div class="col-6 col-md-3">
                    <div class="kpi-card border-primary h-100">
                        <div class="d-flex align-items-center justify-content-between text-on-surface-variant mb-1">
                            <span class="text-label-sm">TOTAL PESERTA</span>
                            <span class="material-symbols-outlined text-primary" style="font-size:18px;">groups</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="text-headline-lg text-primary" style="letter-spacing:-.02em;">72</span>
                            <span class="text-body-sm text-on-surface-variant">Siswa Terdaftar</span>
                        </div>
                        <p class="text-body-sm text-on-surface-variant mt-1 mb-0">100% Terkonfirmasi hadir</p>
                    </div>
                </div>

                <!-- Aktif -->
                <div class="col-6 col-md-3">
                    <div class="kpi-card border-secondary h-100">
                        <div class="d-flex align-items-center justify-content-between text-on-surface-variant mb-1">
                            <span class="text-label-sm">AKTIF &amp; FOKUS</span>
                            <span class="material-symbols-outlined text-secondary"
                                style="font-size:18px;">verified_user</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="text-headline-lg text-secondary" style="letter-spacing:-.02em;">68</span>
                            <span class="text-body-sm text-on-surface-variant">Siswa</span>
                        </div>
                        <p class="text-body-sm text-secondary fw-medium mt-1 mb-0">94.4% Berjalan lancar</p>
                    </div>
                </div>

                <!-- Anomali -->
                <div class="col-6 col-md-3">
                    <div class="kpi-card border-error alert-tint h-100">
                        <div class="d-flex align-items-center justify-content-between text-on-surface-variant mb-1">
                            <span class="text-label-sm text-error">PERINGATAN ANOMALI</span>
                            <span class="material-symbols-outlined text-error" style="font-size:18px;">warning</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="text-headline-lg text-error" style="letter-spacing:-.02em;">3</span>
                            <span class="text-body-sm text-error fw-medium">Perlu Cek</span>
                        </div>
                        <p class="text-body-sm text-on-surface-variant mt-1 mb-0">2 Alt+Tab, 1 Suara bising</p>
                    </div>
                </div>

                <!-- Offline -->
                <div class="col-6 col-md-3">
                    <div class="kpi-card border-outline h-100">
                        <div class="d-flex align-items-center justify-content-between text-on-surface-variant mb-1">
                            <span class="text-label-sm">OFFLINE / DISKONEK</span>
                            <span class="material-symbols-outlined text-outline"
                                style="font-size:18px;">wifi_off</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="text-headline-lg text-on-surface" style="letter-spacing:-.02em;">1</span>
                            <span class="text-body-sm text-on-surface-variant">Siswa Terputus</span>
                        </div>
                        <p class="text-body-sm text-on-surface-variant mt-1 mb-0">Meja C-08 (Lab 2)</p>
                    </div>
                </div>
            </div>

            <!-- AI Alert Banner -->
            <div class="ai-alert">
                <div class="d-flex align-items-start gap-3">
                    <div class="ai-alert-icon">
                        <span class="material-symbols-outlined" style="font-size:24px;">gavel</span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="ai-alert-tag">AI Proctor Alert</span>
                            <span class="text-body-sm text-on-error-container fw-semibold">Real-Time Surveillance
                                Detection</span>
                        </div>
                        <h2 class="text-title-sm text-on-error-container mt-1 fw-bold mb-0">
                            Peringatan Dini: 2 Siswa terdeteksi berpindah tab (Alt+Tab) / dual monitor
                        </h2>
                        <p class="text-body-sm text-on-surface-variant mt-1 mb-0">
                            Terdeteksi pada peserta
                            <span class="fw-bold text-on-surface">Rian Pratama (Meja A-12)</span> dan
                            <span class="fw-bold text-on-surface">Fahri Hamzah (Meja A-18)</span>
                            dalam rentang 3 menit terakhir.
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0 align-self-end align-self-md-center">
                    <button type="button" class="btn-soft"
                        style="background:var(--surface-container-lowest);color:var(--error);border-color:rgba(186,26,26,.3);">
                        Tegur Siswa
                    </button>
                    <button type="button" class="btn-danger-solid">
                        Kunci Layar Siswa
                    </button>
                </div>
            </div>

            <!-- Workspace Grid -->
            <div class="row g-4">

                <!-- LEFT -->
                <section class="col-12 col-lg-8 d-flex flex-column gap-3">

                    <!-- Filter Bar -->
                    <div class="card-soft p-3 d-flex flex-column gap-3">
                        <div
                            class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                            <!-- Filter Tabs -->
                            <div
                                class="filter-tabs d-flex align-items-center gap-2 overflow-auto pb-1 pb-sm-0 custom-scrollbar">
                                <button type="button" class="active">Semua Peserta (72)</button>
                                <button type="button" class="warning">
                                    <span>Perlu Perhatian</span>
                                    <span class="count-badge">3</span>
                                </button>
                                <button type="button">Selesai (14)</button>
                                <button type="button">Offline (1)</button>
                            </div>

                            <!-- View Switcher -->
                            <div class="view-toggle align-self-end align-self-sm-auto">
                                <button type="button" class="active" title="Tampilan Grid Layar">
                                    <span class="material-symbols-outlined" style="font-size:18px;">grid_view</span>
                                </button>
                                <button type="button" title="Tampilan Tabel">
                                    <span class="material-symbols-outlined"
                                        style="font-size:18px;">format_list_bulleted</span>
                                </button>
                            </div>
                        </div>

                        <!-- Search & Lab Filter -->
                        <div class="d-flex flex-column flex-sm-row align-items-center gap-3">
                            <div class="search-wrap w-100">
                                <span class="material-symbols-outlined search-icon">search</span>
                                <input type="text" class="search-input"
                                    placeholder="Cari nama siswa, NISN, atau no meja lab (mis: A-12)..." />
                            </div>
                            <select class="form-select-soft w-100" style="max-width:200px;">
                                <option>Semua Ruangan Lab</option>
                                <option>Lab Multimedia 1</option>
                                <option>Lab Multimedia 2</option>
                            </select>
                        </div>
                    </div>

                    <!-- Student Cards -->
                    <div class="row g-3">

                        <!-- Card 1: Rian Pratama (Critical) -->
                        <div class="col-12 col-md-6">
                            <div class="monitor-card critical h-100">
                                <div class="corner-badge danger">
                                    <span class="material-symbols-outlined" style="font-size:14px;">warning</span>
                                    <span>3x Pelanggaran</span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <div class="avatar-monitor danger">
                                            <span class="material-symbols-outlined"
                                                style="font-size:28px;">videocam_off</span>
                                        </div>
                                        <div>
                                            <h3 class="text-title-sm text-on-surface fw-bold mb-0">Rian Pratama</h3>
                                            <p class="text-body-sm text-on-surface-variant mb-0">Meja A-12 • XII MIPA 1
                                            </p>
                                            <div class="mt-1">
                                                <span
                                                    class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill"
                                                    style="background:var(--error-container);color:var(--on-error-container);font-size:11px;font-weight:700;line-height:14px;">
                                                    <span class="rounded-circle"
                                                        style="width:6px;height:6px;background:var(--error);"></span>
                                                    Berpindah Tab 3x (Alt+Tab)
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="metric-inline">
                                        <div class="d-flex align-items-center justify-content-between mb-1"
                                            style="font-size:12px;line-height:16px;">
                                            <span class="text-on-surface-variant">Progress Pengerjaan:</span>
                                            <span class="fw-bold text-on-surface">28/40 Soal (70%)</span>
                                        </div>
                                        <div class="progress progress-danger">
                                            <div class="progress-bar" role="progressbar" style="width:70%;"
                                                aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between text-on-surface-variant"
                                            style="font-size:12px;line-height:16px;">
                                            <span class="d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined text-secondary"
                                                    style="font-size:15px;">battery_5_bar</span>
                                                <span>Baterai: 85%</span>
                                            </span>
                                            <span class="d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined text-error"
                                                    style="font-size:15px;">visibility_off</span>
                                                <span>Fokus Layar: Rendah</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 pt-2 border-top"
                                    style="border-color:var(--surface-container)!important;">
                                    <div class="col-6">
                                        <button type="button" class="btn-action neutral w-100">
                                            <span class="material-symbols-outlined"
                                                style="font-size:16px;color:var(--tertiary-container);">announcement</span>
                                            <span>Peringatkan</span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn-action danger w-100">
                                            <span class="material-symbols-outlined"
                                                style="font-size:16px;">lock</span>
                                            <span>Kunci Sesi</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Alya Nurul (Warning) -->
                        <div class="col-12 col-md-6">
                            <div class="monitor-card warning h-100">
                                <div class="corner-badge warning">
                                    <span class="material-symbols-outlined" style="font-size:14px;">volume_up</span>
                                    <span>Audio Alert</span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <div class="avatar-monitor warning">
                                            <span class="material-symbols-outlined"
                                                style="font-size:28px;">graphic_eq</span>
                                        </div>
                                        <div>
                                            <h3 class="text-title-sm text-on-surface fw-bold mb-0">Alya Nurul</h3>
                                            <p class="text-body-sm text-on-surface-variant mb-0">Meja B-04 • XII MIPA 1
                                            </p>
                                            <div class="mt-1">
                                                <span
                                                    class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill text-on-tertiary-fixed-variant"
                                                    style="background:var(--tertiary-fixed);font-size:11px;font-weight:700;line-height:14px;">
                                                    <span class="rounded-circle"
                                                        style="width:6px;height:6px;background:var(--tertiary-container);"></span>
                                                    Terdeteksi Suara Sekitar (74dB)
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="metric-inline">
                                        <div class="d-flex align-items-center justify-content-between mb-1"
                                            style="font-size:12px;line-height:16px;">
                                            <span class="text-on-surface-variant">Progress Pengerjaan:</span>
                                            <span class="fw-bold text-on-surface">35/40 Soal (87.5%)</span>
                                        </div>
                                        <div class="progress progress-warning">
                                            <div class="progress-bar" role="progressbar" style="width:87.5%;"
                                                aria-valuenow="87.5" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between text-on-surface-variant"
                                            style="font-size:12px;line-height:16px;">
                                            <span class="d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined text-secondary"
                                                    style="font-size:15px;">videocam</span>
                                                <span>Kamera: Aktif</span>
                                            </span>
                                            <span class="d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:15px;color:var(--tertiary-container);">hearing</span>
                                                <span>Mikrofon: Merekam</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 pt-2 border-top"
                                    style="border-color:var(--surface-container)!important;">
                                    <div class="col-6">
                                        <button type="button" class="btn-action neutral w-100">
                                            <span class="material-symbols-outlined"
                                                style="font-size:16px;">headset_mic</span>
                                            <span>Dengar Audio</span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn-action outline w-100">
                                            <span class="material-symbols-outlined"
                                                style="font-size:16px;">chat</span>
                                            <span>Tegur Halus</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Dimas Aditya (Normal) -->
                        <div class="col-12 col-md-6">
                            <div class="monitor-card normal h-100">
                                <div class="corner-badge success">
                                    <span class="material-symbols-outlined"
                                        style="font-size:14px;">check_circle</span>
                                    <span>Normal</span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <div class="avatar-monitor success">
                                            <span class="material-symbols-outlined"
                                                style="font-size:28px;">desktop_windows</span>
                                        </div>
                                        <div>
                                            <h3 class="text-title-sm text-on-surface fw-bold mb-0">Dimas Aditya</h3>
                                            <p class="text-body-sm text-on-surface-variant mb-0">Meja A-01 • XII MIPA 1
                                            </p>
                                            <div class="mt-1">
                                                <span
                                                    class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill text-secondary"
                                                    style="background:rgba(137,245,231,.5);font-size:11px;font-weight:700;line-height:14px;">
                                                    <span class="rounded-circle"
                                                        style="width:6px;height:6px;background:var(--secondary);"></span>
                                                    Fokus Aktif (Webcam Terhubung)
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="metric-inline">
                                        <div class="d-flex align-items-center justify-content-between mb-1"
                                            style="font-size:12px;line-height:16px;">
                                            <span class="text-on-surface-variant">Progress Pengerjaan:</span>
                                            <span class="fw-bold text-on-surface">32/40 Soal (80%)</span>
                                        </div>
                                        <div class="progress progress-success">
                                            <div class="progress-bar" role="progressbar" style="width:80%;"
                                                aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between text-on-surface-variant"
                                            style="font-size:12px;line-height:16px;">
                                            <span class="d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined text-secondary"
                                                    style="font-size:15px;">wifi</span>
                                                <span>Koneksi: 24ms</span>
                                            </span>
                                            <span class="d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined text-secondary"
                                                    style="font-size:15px;">lock</span>
                                                <span>Kiosk Mode: Aktif</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 pt-2 border-top"
                                    style="border-color:var(--surface-container)!important;">
                                    <div class="col-6">
                                        <button type="button" class="btn-action outline w-100">
                                            <span class="material-symbols-outlined"
                                                style="font-size:16px;">visibility</span>
                                            <span>Lihat Layar</span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn-action outline w-100"
                                            style="color:var(--on-surface-variant);">
                                            <span class="material-symbols-outlined"
                                                style="font-size:16px;">more_horiz</span>
                                            <span>Opsi</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Sarah Melinda (Offline) -->
                        <div class="col-12 col-md-6">
                            <div class="monitor-card offline h-100">
                                <div class="corner-badge offline">
                                    <span class="material-symbols-outlined" style="font-size:14px;">link_off</span>
                                    <span>Terputus</span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <div class="avatar-monitor offline">
                                            <span class="material-symbols-outlined"
                                                style="font-size:28px;">cloud_off</span>
                                        </div>
                                        <div>
                                            <h3 class="text-title-sm text-on-surface fw-bold mb-0">Sarah Melinda</h3>
                                            <p class="text-body-sm text-on-surface-variant mb-0">Meja C-08 • XII MIPA 2
                                            </p>
                                            <div class="mt-1">
                                                <span
                                                    class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill text-on-surface-variant"
                                                    style="background:var(--surface-container-high);font-size:11px;font-weight:700;line-height:14px;">
                                                    <span class="rounded-circle"
                                                        style="width:6px;height:6px;background:var(--outline);"></span>
                                                    Koneksi Terputus 2m lalu
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="metric-inline">
                                        <div class="d-flex align-items-center justify-content-between mb-1"
                                            style="font-size:12px;line-height:16px;">
                                            <span class="text-on-surface-variant">Progress Terakhir:</span>
                                            <span class="fw-bold text-on-surface">19/40 Soal (47.5%)</span>
                                        </div>
                                        <div class="progress progress-outline">
                                            <div class="progress-bar" role="progressbar" style="width:47.5%;"
                                                aria-valuenow="47.5" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between text-on-surface-variant"
                                            style="font-size:12px;line-height:16px;">
                                            <span class="d-flex align-items-center gap-1 text-error">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:15px;">power_off</span>
                                                <span>Sinyal Hilang</span>
                                            </span>
                                            <span class="d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:15px;">save</span>
                                                <span>Jawaban Tersimpan</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 pt-2 border-top"
                                    style="border-color:var(--surface-container)!important;">
                                    <div class="col-6">
                                        <button type="button" class="btn-action primary w-100">
                                            <span class="material-symbols-outlined"
                                                style="font-size:16px;">restart_alt</span>
                                            <span>Reset Login</span>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn-action outline w-100">
                                            <span class="material-symbols-outlined" style="font-size:16px;">pin</span>
                                            <span>Beri Token Baru</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex align-items-center justify-content-between pt-2">
                        <p class="text-body-sm text-on-surface-variant mb-0">
                            Menampilkan 4 dari 72 total peserta ujian
                        </p>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary disabled"
                                disabled>Sebelumnya</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Selanjutnya</button>
                        </div>
                    </div>
                </section>

                <!-- RIGHT -->
                <aside class="col-12 col-lg-4 d-flex flex-column gap-3">

                    <!-- Audit Log -->
                    <div class="card-soft p-3">
                        <div class="d-flex align-items-center justify-content-between pb-2 border-bottom"
                            style="border-color:var(--surface-container-high)!important;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-primary"
                                    style="font-size:20px;">history_edu</span>
                                <h2 class="text-title-sm text-on-surface fw-bold mb-0">Log Aktivitas Proctoring</h2>
                            </div>
                            <span class="d-flex align-items-center gap-1 text-secondary text-label-sm">
                                <span class="live-dot-pulse"></span>
                                Live Feed
                            </span>
                        </div>

                        <div class="mt-3 d-flex flex-column gap-3 custom-scrollbar"
                            style="max-height:380px;overflow-y:auto;padding-right:4px;">

                            <!-- Event 1 -->
                            <div class="audit-log-item">
                                <span class="time-badge danger">09:44</span>
                                <div>
                                    <p class="fw-bold text-on-surface mb-0">Rian Pratama</p>
                                    <p class="text-on-surface-variant mb-0">Browser kehilangan fokus (Alt+Tab
                                        terdeteksi).</p>
                                    <span class="text-label-sm text-error fw-medium">Status: Menunggu tindakan</span>
                                </div>
                            </div>
                            <!-- Event 2 -->
                            <div class="audit-log-item">
                                <span class="time-badge warning">09:42</span>
                                <div>
                                    <p class="fw-bold text-on-surface mb-0">Alya Nurul</p>
                                    <p class="text-on-surface-variant mb-0">Audio ambient melebihi ambang batas desibel
                                        normal.</p>
                                </div>
                            </div>
                            <!-- Event 3 -->
                            <div class="audit-log-item">
                                <span class="time-badge success">09:41</span>
                                <div>
                                    <p class="fw-bold text-on-surface mb-0">Budi Santoso</p>
                                    <p class="text-on-surface-variant mb-0">Menyelesaikan ujian dan berhasil submit
                                        nilai.</p>
                                    <span class="text-label-sm text-secondary fw-medium">Nilai tersimpan di
                                        server</span>
                                </div>
                            </div>
                            <!-- Event 4 -->
                            <div class="audit-log-item">
                                <span class="time-badge neutral">09:38</span>
                                <div>
                                    <p class="fw-bold text-on-surface mb-0">Pengawas (Pak Gunawan)</p>
                                    <p class="text-on-surface-variant mb-0">Token perpanjangan waktu diberikan ke Lab 1
                                        (+15 menit).</p>
                                </div>
                            </div>
                            <!-- Event 5 -->
                            <div class="audit-log-item">
                                <span class="time-badge neutral">09:30</span>
                                <div>
                                    <p class="fw-bold text-on-surface mb-0">Sistem AI Proctor</p>
                                    <p class="text-on-surface-variant mb-0">Pemeriksaan integritas webcam berkala: 71
                                        webcam online aktif.</p>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn w-100 mt-3 btn-soft justify-content-center">
                            Unduh Audit Log (.CSV)
                        </button>
                    </div>

                    <!-- Network Status -->
                    <div class="card-soft p-3 d-flex flex-column gap-3">
                        <h3 class="text-title-sm text-on-surface fw-bold d-flex align-items-center gap-2 mb-0">
                            <span class="material-symbols-outlined text-secondary"
                                style="font-size:20px;">router</span>
                            Status Jaringan &amp; Server CBT
                        </h3>
                        <div class="d-flex flex-column gap-2">
                            <div class="status-row">
                                <span class="text-on-surface-variant">CBT Local Server #1:</span>
                                <span class="fw-bold text-secondary d-flex align-items-center gap-1">
                                    <span class="rounded-circle"
                                        style="width:8px;height:8px;background:var(--secondary);"></span>
                                    Online (CPU 22%)
                                </span>
                            </div>
                            <div class="status-row">
                                <span class="text-on-surface-variant">Latency Lab Multimedia 1:</span>
                                <span class="fw-bold text-on-surface">14 ms (Sangat Baik)</span>
                            </div>
                            <div class="status-row">
                                <span class="text-on-surface-variant">Latency Lab Multimedia 2:</span>
                                <span class="fw-bold text-on-surface">19 ms (Baik)</span>
                            </div>
                            <div class="status-row">
                                <span class="text-on-surface-variant">AI Integrity Checker:</span>
                                <span class="fw-bold text-primary">Berjalan (Model v4.2)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Broadcast -->
                    <div class="broadcast-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="material-symbols-outlined" style="font-size:22px;">volume_up</span>
                            <h3 class="text-title-sm fw-bold mb-0">Pesan Pengawas Instan</h3>
                        </div>
                        <p class="text-body-sm mb-3" style="opacity:.9;">
                            Kirimkan instruksi mendadak ke layar semua peserta ujian tanpa mematikan aplikasi.
                        </p>
                        <div class="d-flex gap-2">
                            <input type="text" class="broadcast-input flex-grow-1"
                                placeholder="Ketik pengumuman..." />
                            <button type="button" class="btn-broadcast">Kirim</button>
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
        <a href="#" class="bottom-nav-item active">
            <span class="material-symbols-outlined icon-fill" style="font-size:22px;">school</span>
            <span class="text-label-sm mt-1">Akademik</span>
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
