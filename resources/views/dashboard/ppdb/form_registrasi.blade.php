<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran PPDB 2025/2026 - Portal Resmi Penerimaan Peserta Didik Baru</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Material Symbols Outlined -->
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">

    <style>
        :root {
            --bs-primary: #00288e;
            --bs-primary-rgb: 0, 40, 142;
            --bs-primary-container: #1e40af;
            --bs-secondary: #006398;
            --bs-secondary-container: #5bb8fe;
            --bs-primary-fixed: #dde1ff;
            --bs-secondary-fixed: #cce5ff;
            --bs-surface: #f8f9ff;
            --bs-on-surface: #0d1c2e;
            --bs-on-surface-variant: #444653;
            --bs-surface-container-lowest: #ffffff;
            --bs-surface-container-low: #eff4ff;
            --bs-surface-container: #e6eeff;
            --bs-surface-container-high: #dce9ff;
            --bs-surface-container-highest: #d5e3fc;
            --bs-outline: #757684;
            --bs-outline-variant: #c4c5d5;
            --bs-error: #ba1a1a;
            --bs-success: #10B981;
            --bs-success-dark: #065F46;
            --bs-success-light: #ECFDF5;
            --bs-success-border: #A7F3D0;
            --bs-warning: #F59E0B;
            --bs-warning-dark: #92400E;
            --bs-warning-light: #FFFBEB;
            --bs-warning-border: #FDE68A;
            --bs-slate-300: #CBD5E1;
            --bs-slate-400: #94A3B8;
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bs-surface);
            color: var(--bs-on-surface);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            padding-bottom: 9rem;
        }

        @media (min-width: 768px) {
            body {
                padding-bottom: 6rem;
            }
        }

        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
            vertical-align: middle;
        }

        /* ===== TOP APP BAR ===== */
        .app-header {
            background: var(--bs-surface-container-lowest);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .app-header .brand-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: var(--bs-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        /* ===== CARDS ===== */
        .ppdb-card {
            background: var(--bs-surface-container-lowest);
            border: 1px solid var(--bs-outline-variant);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
        }

        .ppdb-card .card-number {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            background: var(--bs-primary-fixed);
            color: var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        /* ===== BADGES ===== */
        .badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-success-soft {
            background: var(--bs-success-light);
            color: var(--bs-success-dark);
            border: 1px solid var(--bs-success-border);
        }

        .badge-warning-soft {
            background: var(--bs-warning-light);
            color: var(--bs-warning-dark);
            border: 1px solid var(--bs-warning-border);
        }

        /* ===== FORM CONTROLS ===== */
        .form-control,
        .form-select {
            height: 3rem;
            border: 1px solid var(--bs-slate-300);
            border-radius: 0.5rem;
            font-size: 0.9rem;
            background-color: var(--bs-surface-container-lowest);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--bs-primary-container);
            box-shadow: 0 0 0 2px rgba(91, 184, 254, 0.35);
        }

        .form-control::placeholder {
            color: var(--bs-slate-400);
        }

        textarea.form-control {
            height: auto;
            min-height: 4rem;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--bs-on-surface);
            margin-bottom: 0.35rem;
        }

        /* ===== JALUR CHIP SELECTOR ===== */
        .jalur-chip {
            cursor: pointer;
            border: 1px solid var(--bs-slate-300);
            background: var(--bs-surface-container-lowest);
            border-radius: 0.75rem;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
            transition: all 0.15s;
            height: 100%;
        }

        .jalur-chip:hover {
            background: var(--bs-surface-container-low);
        }

        .jalur-chip.active {
            border: 2px solid var(--bs-primary);
            background: rgba(221, 225, 255, 0.3);
        }

        .jalur-chip.active .jalur-label {
            color: var(--bs-primary);
            font-weight: 700;
        }

        .jalur-chip input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        /* ===== CHECKBOX PILL ===== */
        .check-pill {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem;
            border-radius: 0.5rem;
            border: 1px solid var(--bs-slate-300);
            background: var(--bs-surface-container-lowest);
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.15s;
        }

        .check-pill:hover {
            background: var(--bs-surface-container-low);
        }

        .check-pill.active {
            border-color: var(--bs-primary);
            background: rgba(221, 225, 255, 0.3);
            color: var(--bs-primary);
        }

        /* ===== TIMELINE STEPPER ===== */
        .timeline {
            position: relative;
            padding-left: 1.75rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.6rem;
            top: 0.5rem;
            bottom: 0.5rem;
            width: 2px;
            background: var(--bs-slate-300);
        }

        .timeline-step {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-step:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -1.75rem;
            top: 0.15rem;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bs-surface-container-lowest);
            border: 2px solid var(--bs-outline-variant);
            z-index: 1;
        }

        .timeline-dot.active {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 4px var(--bs-primary-fixed);
            color: #fff;
        }

        .timeline-dot.done {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 4px var(--bs-primary-fixed);
            color: #fff;
        }

        .timeline-dot .dot-inner {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 999px;
            background: var(--bs-outline);
        }

        /* ===== FILE UPLOAD ===== */
        .file-box {
            border: 1px dashed var(--bs-slate-300);
            border-radius: 0.75rem;
            padding: 1rem;
            background: var(--bs-surface-container-low);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            transition: background 0.15s;
        }

        .file-box:hover {
            background: var(--bs-surface-container);
        }

        @media (min-width: 576px) {
            .file-box {
                flex-direction: row;
                text-align: left;
            }
        }

        .file-box-uploaded {
            border: 1px solid var(--bs-success-border);
            border-style: solid;
            background: var(--bs-surface-container-low);
        }

        /* ===== STICKY BOTTOM BAR ===== */
        .sticky-action-bar {
            position: fixed;
            bottom: 3.75rem;
            left: 0;
            right: 0;
            z-index: 1040;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.06);
            padding: 0.75rem 1rem;
        }

        @media (min-width: 768px) {
            .sticky-action-bar {
                bottom: 0;
            }
        }

        /* ===== BOTTOM NAV ===== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1050;
            background: var(--bs-surface-container-lowest);
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 0.5rem 0.75rem;
        }

        @media (min-width: 768px) {
            .bottom-nav {
                display: none;
            }
        }

        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.75rem;
            border-radius: 0.75rem;
            color: var(--bs-on-surface-variant);
            text-decoration: none;
            font-size: 0.65rem;
            transition: all 0.15s;
        }

        .bottom-nav a.active {
            background: var(--bs-primary-fixed);
            color: var(--bs-primary);
            font-weight: 700;
        }

        /* ===== BUTTONS ===== */
        .btn-primary-custom {
            background: var(--bs-primary);
            border: none;
            color: #fff;
            font-weight: 700;
            height: 3rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.15s;
        }

        .btn-primary-custom:hover {
            background: #1D4ED8;
            color: #fff;
        }

        .btn-primary-custom:disabled {
            opacity: 0.7;
        }

        .btn-outline-custom {
            border: 1px solid var(--bs-slate-300);
            background: var(--bs-surface-container-lowest);
            color: var(--bs-on-surface-variant);
            font-weight: 600;
            height: 3rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-outline-custom:hover {
            background: var(--bs-surface-container-low);
            color: var(--bs-on-surface);
        }

        /* ===== GEO PIN BOX ===== */
        .geo-box {
            padding: 0.85rem;
            background: var(--bs-surface-container-low);
            border-radius: 0.5rem;
            border: 1px solid var(--bs-outline-variant);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        /* ===== INFO ALERT ===== */
        .info-alert {
            padding: 0.85rem;
            background: rgba(221, 225, 255, 0.3);
            border: 1px solid rgba(0, 40, 142, 0.2);
            border-radius: 0.75rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
        }

        /* ===== HELP CARD GRADIENT ===== */
        .help-card {
            background: linear-gradient(135deg, var(--bs-primary), var(--bs-primary-container));
            color: #fff;
            border-radius: 0.75rem;
            padding: 1.25rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .help-card .help-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
            transition: background 0.15s;
        }

        .help-card .help-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        /* ===== MICRO ANIMATION ===== */
        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.98);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ===== TIMER BOX ===== */
        .timer-box {
            background: var(--bs-warning-light);
            border-left: 4px solid var(--bs-warning);
            border-radius: 0.5rem;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* ===== PROGRESS BAR ===== */
        .progress-thin {
            height: 0.5rem;
            border-radius: 999px;
            background: var(--bs-surface-container-high);
            overflow: hidden;
        }

        .progress-thin .progress-bar {
            background: var(--bs-primary);
            border-radius: 999px;
        }

        /* ===== MODAL CUSTOM ===== */
        .modal-doc-preview {
            background: var(--bs-surface-container-lowest);
            border-radius: 1rem;
            border: 1px solid var(--bs-outline-variant);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 42rem;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .doc-preview-content {
            background: var(--bs-surface-container-low);
            padding: 1.5rem;
            overflow-y: auto;
            display: flex;
            justify-content: center;
        }

        .doc-preview-paper {
            background: #fff;
            border: 1px solid var(--bs-outline-variant);
            border-radius: 0.75rem;
            padding: 1.5rem;
            max-width: 32rem;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* ===== RESPONSIVE TYPOGRAPHY ===== */
        .headline-lg-mobile {
            font-size: 1.5rem;
            line-height: 2rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        @media (min-width: 768px) {
            .headline-lg-mobile {
                font-size: 2rem;
                line-height: 2.5rem;
            }
        }

        /* ===== ICON STACK IN BUTTON ===== */
        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .btn-icon .material-symbols-outlined {
            font-size: 1rem;
        }

        /* Small button */
        .btn-sm-custom {
            padding: 0.35rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            border: 1px solid var(--bs-outline-variant);
            background: var(--bs-surface-container-lowest);
            color: var(--bs-primary);
            transition: background 0.15s;
        }

        .btn-sm-custom:hover {
            background: var(--bs-surface-container);
            color: var(--bs-primary);
        }
    </style>
</head>

<body>

    <!-- ==================== TOP APP BAR ==================== -->
    <header class="app-header">
        <div class="container-fluid" style="max-width: 1280px;">
            <div class="d-flex justify-content-between align-items-center py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="brand-icon">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:var(--bs-primary); font-size:0.95rem; line-height:1.1;">PPDB
                            2025/2026</div>
                        <div class="d-flex align-items-center gap-1"
                            style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                            <span class="d-inline-block rounded-circle"
                                style="width:0.4rem;height:0.4rem;background:var(--bs-success);"></span>
                            Provinsi • Jalur Reguler Terpadu
                        </div>
                    </div>
                </div>
                <div>
                    <a href="#bantuan" class="btn btn-sm rounded-circle p-2" style="color:var(--bs-primary);"
                        aria-label="Pusat Bantuan PPDB">
                        <span class="material-symbols-outlined">help_outline</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- ==================== MAIN ==================== -->
    <main class="container-fluid pt-3 px-3 px-md-4" style="max-width: 1280px;">

        <!-- ===== HERO / STATUS BANNER ===== -->
        <section class="mb-4">
            <div class="ppdb-card">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                            <span class="badge-soft badge-success-soft">
                                <span class="rounded-circle"
                                    style="width:0.4rem;height:0.4rem;background:var(--bs-success); animation:pulse 1.5s infinite;"></span>
                                Pendaftaran Dibuka
                            </span>
                            <span class="badge-soft"
                                style="background:var(--bs-surface-container); color:var(--bs-on-surface-variant); border:none;">
                                Tahap I: SMA / SMK Negeri
                            </span>
                        </div>
                        <h1 class="headline-lg-mobile mb-1">Formulir Pendaftaran Siswa Baru</h1>
                        <p class="mb-0" style="font-size:0.8rem; color:var(--bs-on-surface-variant);">
                            Tahun Ajaran 2025/2026. Lengkapi data berkas dengan valid sesuai dokumen resmi Dukcapil dan
                            Kemendikbudristek.
                        </p>
                    </div>
                    <div class="timer-box flex-shrink-0">
                        <span class="material-symbols-outlined" style="color:var(--bs-warning-dark);">timer</span>
                        <div>
                            <div style="font-size:0.65rem; color:var(--bs-warning-dark);">Batas Pengisian Berkas</div>
                            <div class="fw-bold" style="font-size:0.8rem; color:var(--bs-warning-dark);">24 Juni 2025,
                                23:59 WIB</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== GRID: FORM (8) + SIDEBAR (4) ===== -->
        <div class="row g-4 align-items-start">

            <!-- ========== LEFT: FORM ========== -->
            <div class="col-12 col-lg-8">

                <!-- ===== CARD 1: Data Pribadi ===== -->
                <article class="ppdb-card mb-4">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
                        style="border-color:var(--bs-outline-variant) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="card-number">1</div>
                            <div>
                                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Data Pribadi Calon Siswa
                                </h2>
                                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                    Kesesuaian NIK dan NISN terintegrasi langsung dengan Dapodik</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined" style="color:var(--bs-primary);">badge</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- NIK & NISN -->
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="nik">Nomor Induk Kependudukan (NIK) <span
                                        style="color:var(--bs-error);">*</span></label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="nik" maxlength="16"
                                        placeholder="16 digit sesuai Kartu Keluarga" value="3271045209080002">
                                    <span
                                        class="material-symbols-outlined position-absolute end-0 top-50 translate-middle-y me-3"
                                        style="color:var(--bs-success); font-size:1.1rem;">check_circle</span>
                                </div>
                                <p class="mt-1 mb-0 d-flex align-items-center gap-1"
                                    style="font-size:0.7rem; color:var(--bs-success-dark);">
                                    <span class="material-symbols-outlined"
                                        style="font-size:0.85rem;">verified_user</span>
                                    NIK Terverifikasi Dukcapil Kota
                                </p>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="nisn">Nomor Induk Siswa Nasional (NISN) <span
                                        style="color:var(--bs-error);">*</span></label>
                                <input type="text" class="form-control" id="nisn" maxlength="10"
                                    placeholder="10 digit nomor NISN aktif" value="0087412941">
                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="form-label" for="nama_lengkap">Nama Lengkap (Sesuai Akta Kelahiran) <span
                                    style="color:var(--bs-error);">*</span></label>
                            <input type="text" class="form-control text-uppercase" id="nama_lengkap"
                                placeholder="Contoh: Muhammad Farhan Al-Hafiz" value="Muhammad Farhan Al-Hafiz">
                        </div>

                        <!-- Tempat & Tanggal Lahir -->
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="tempat_lahir">Tempat Lahir <span
                                        style="color:var(--bs-error);">*</span></label>
                                <input type="text" class="form-control" id="tempat_lahir"
                                    placeholder="Kota/Kabupaten kelahiran" value="Bandung">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="tanggal_lahir">Tanggal Lahir <span
                                        style="color:var(--bs-error);">*</span></label>
                                <input type="date" class="form-control" id="tanggal_lahir" value="2009-08-14">
                            </div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <span class="form-label d-block mb-2">Jenis Kelamin <span
                                    style="color:var(--bs-error);">*</span></span>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="jalur-chip active w-100 align-items-center"
                                        style="flex-direction:row; justify-content:center;">
                                        <input type="radio" name="jenis_kelamin" value="L" checked>
                                        <span
                                            style="font-size:0.75rem; font-weight:600; color:var(--bs-primary);">Laki-Laki</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="jalur-chip w-100 align-items-center"
                                        style="flex-direction:row; justify-content:center;">
                                        <input type="radio" name="jenis_kelamin" value="P">
                                        <span style="font-size:0.75rem; font-weight:600;">Perempuan</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Asal Sekolah -->
                        <div>
                            <label class="form-label" for="asal_sekolah">Asal Sekolah (SMP / MTs) <span
                                    style="color:var(--bs-error);">*</span></label>
                            <div class="position-relative">
                                <input type="text" class="form-control" id="asal_sekolah"
                                    placeholder="Cari nama SMP / NPSN asal" value="SMP Negeri 1 Kota Bandung">
                                <span
                                    class="material-symbols-outlined position-absolute end-0 top-50 translate-middle-y me-3"
                                    style="color:var(--bs-outline); font-size:1.1rem;">search</span>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="form-label" for="alamat">Alamat Domisili Sesuai KK <span
                                    style="color:var(--bs-error);">*</span></label>
                            <textarea class="form-control" id="alamat" rows="2"
                                placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan">Jl. Diponegoro No. 42, RT 03 / RW 07, Citarum, Kec. Bandung Wetan</textarea>
                        </div>

                        <!-- Geo Pin -->
                        <div class="geo-box">
                            <div class="d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined"
                                    style="color:var(--bs-secondary);">pin_drop</span>
                                <div>
                                    <div style="font-size:0.7rem; font-weight:600;">Titik Koordinat Rumah Calon Siswa
                                    </div>
                                    <div class="font-monospace"
                                        style="font-size:0.7rem; color:var(--bs-on-surface-variant);">-6.902484,
                                        107.618721 (Akurasi 8 Meter)</div>
                                </div>
                            </div>
                            <button type="button" class="btn-sm-custom">
                                <span class="material-symbols-outlined" style="font-size:0.9rem;">my_location</span>
                                Perbarui Titik Peta
                            </button>
                        </div>
                    </div>
                </article>

                <!-- ===== CARD 2: Pilihan Jalur & Sekolah ===== -->
                <article class="ppdb-card mb-4">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
                        style="border-color:var(--bs-outline-variant) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="card-number">2</div>
                            <div>
                                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Pilihan Jalur &amp;
                                    Sekolah Tujuan</h2>
                                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Pilih
                                    jalur yang sesuai dengan kriteria syarat kelulusan pendaftar</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined"
                            style="color:var(--bs-primary);">account_balance</span>
                    </div>

                    <div class="d-flex flex-column gap-4">
                        <!-- Jalur Chips -->
                        <div>
                            <span class="form-label d-block mb-2">Pilih Jalur Pendaftaran <span
                                    style="color:var(--bs-error);">*</span></span>
                            <div class="row g-2">
                                <div class="col-6 col-sm-3">
                                    <label class="jalur-chip active w-100">
                                        <input type="radio" name="jalur_daftar" value="zonasi" checked>
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <span class="material-symbols-outlined"
                                                style="color:var(--bs-primary); font-size:1.1rem;">share_location</span>
                                            <span class="rounded-circle"
                                                style="width:0.5rem;height:0.5rem;background:var(--bs-primary);"></span>
                                        </div>
                                        <span class="jalur-label" style="font-size:0.75rem;">Zonasi Radius</span>
                                        <span style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Kuota
                                            50%</span>
                                    </label>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <label class="jalur-chip w-100">
                                        <input type="radio" name="jalur_daftar" value="prestasi">
                                        <span class="material-symbols-outlined"
                                            style="color:var(--bs-on-surface-variant); font-size:1.1rem;">emoji_events</span>
                                        <span class="jalur-label" style="font-size:0.75rem;">Prestasi Rapor</span>
                                        <span style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Kuota
                                            25%</span>
                                    </label>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <label class="jalur-chip w-100">
                                        <input type="radio" name="jalur_daftar" value="afirmasi">
                                        <span class="material-symbols-outlined"
                                            style="color:var(--bs-on-surface-variant); font-size:1.1rem;">volunteer_activism</span>
                                        <span class="jalur-label" style="font-size:0.75rem;">Afirmasi KIP</span>
                                        <span style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Kuota
                                            20%</span>
                                    </label>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <label class="jalur-chip w-100">
                                        <input type="radio" name="jalur_daftar" value="mutasi">
                                        <span class="material-symbols-outlined"
                                            style="color:var(--bs-on-surface-variant); font-size:1.1rem;">swap_horiz</span>
                                        <span class="jalur-label" style="font-size:0.75rem;">Pindah Tugas</span>
                                        <span style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Kuota
                                            5%</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Pilihan 1 -->
                        <div class="p-3 rounded-3"
                            style="background:var(--bs-surface-container-low); border:1px solid var(--bs-outline-variant);">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                <span class="d-flex align-items-center gap-2 fw-bold"
                                    style="font-size:0.8rem; color:var(--bs-primary);">
                                    <span
                                        class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                        style="width:1.25rem;height:1.25rem;background:var(--bs-primary); font-size:0.7rem;">1</span>
                                    Sekolah Pilihan Pertama (Prioritas Utama)
                                </span>
                                <span class="badge-soft badge-success-soft" style="font-size:0.65rem;">Jarak: 840
                                    Meter</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label">Pilih Sekolah Negeri</label>
                                    <select class="form-select">
                                        <option selected>SMA Negeri 3 Bandung</option>
                                        <option>SMA Negeri 5 Bandung</option>
                                        <option>SMA Negeri 2 Bandung</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label">Peminatan / Konsentrasi</label>
                                    <select class="form-select">
                                        <option selected>MIPA (Matematika &amp; Ilmu Alam)</option>
                                        <option>IPS (Ilmu Pengetahuan Sosial)</option>
                                        <option>Bahasa &amp; Budaya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pilihan 2 -->
                        <div class="p-3 rounded-3"
                            style="background:var(--bs-surface-container-low); border:1px solid var(--bs-outline-variant);">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                <span class="d-flex align-items-center gap-2 fw-bold" style="font-size:0.8rem;">
                                    <span class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:1.25rem;height:1.25rem;background:var(--bs-outline-variant); font-size:0.7rem;">2</span>
                                    Sekolah Pilihan Kedua (Cadangan)
                                </span>
                                <span class="badge-soft"
                                    style="background:var(--bs-surface-container); color:var(--bs-on-surface-variant); border:none; font-size:0.65rem;">Jarak:
                                    1.450 Meter</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label">Pilih Sekolah Negeri</label>
                                    <select class="form-select">
                                        <option>SMA Negeri 5 Bandung</option>
                                        <option selected>SMA Negeri 2 Bandung</option>
                                        <option>SMA Negeri 8 Bandung</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label">Peminatan / Konsentrasi</label>
                                    <select class="form-select">
                                        <option selected>MIPA (Matematika &amp; Ilmu Alam)</option>
                                        <option>IPS (Ilmu Pengetahuan Sosial)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- ===== CARD 3: Minat Bakat ===== -->
                <article class="ppdb-card mb-4">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
                        style="border-color:var(--bs-outline-variant) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="card-number">3</div>
                            <div>
                                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Minat Bakat &amp; Bahasa
                                    Asing Pilihan</h2>
                                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                    Penyesuaian program pembinaan potensi siswa di sekolah penerima</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined" style="color:var(--bs-primary);">psychology</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- Ekstrakurikuler -->
                        <div>
                            <span class="form-label d-block mb-2">Minat Kegiatan Ekstrakurikuler (Boleh pilih hingga
                                2)</span>
                            <div class="row g-2">
                                <div class="col-6 col-sm-3">
                                    <label class="check-pill active w-100">
                                        <input type="checkbox" checked class="form-check-input mt-0">
                                        <span>PMR / UKS</span>
                                    </label>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <label class="check-pill active w-100">
                                        <input type="checkbox" checked class="form-check-input mt-0">
                                        <span>Robotik &amp; Koding</span>
                                    </label>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <label class="check-pill w-100">
                                        <input type="checkbox" class="form-check-input mt-0">
                                        <span>Paskibraka</span>
                                    </label>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <label class="check-pill w-100">
                                        <input type="checkbox" class="form-check-input mt-0">
                                        <span>KIR (Karya Ilmiah)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Bahasa & Prestasi -->
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="bahasa_asing">Pilihan Bahasa Asing Pilihan
                                    Wajib</label>
                                <select class="form-select" id="bahasa_asing">
                                    <option selected>Bahasa Jepang</option>
                                    <option>Bahasa Jerman</option>
                                    <option>Bahasa Perancis</option>
                                    <option>Bahasa Mandarin</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="kemampuan_khusus">Prestasi Olahraga / Seni
                                    (Opsional)</label>
                                <input type="text" class="form-control" id="kemampuan_khusus"
                                    placeholder="Contoh: Juara 1 Taekwondo Tingkat Kota"
                                    value="Medali Perak Olimpiade Sains Kota">
                            </div>
                        </div>

                        <!-- Kesediaan Tes -->
                        <div class="geo-box align-items-start">
                            <input type="checkbox" checked class="form-check-input mt-1" id="kesediaan_tes">
                            <label for="kesediaan_tes" class="mb-0" style="font-size:0.8rem; cursor:pointer;">
                                <strong>Verifikasi Kesediaan:</strong> Saya menyatakan bersedia mengikuti Tes Diagnostik
                                Pemetaan Akademik dan Minat Bakat yang dijadwalkan oleh Satuan Pendidikan secara
                                daring/luring.
                            </label>
                        </div>
                    </div>
                </article>

                <!-- ===== CARD 4: Data Orang Tua ===== -->
                <article class="ppdb-card mb-4">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
                        style="border-color:var(--bs-outline-variant) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="card-number">4</div>
                            <div>
                                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Informasi Orang Tua
                                    &amp; Wali Siswa</h2>
                                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Kontak
                                    darurat dan penerima notifikasi kelulusan resmi</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined"
                            style="color:var(--bs-primary);">family_restroom</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="nama_ortu">Nama Lengkap Ayah / Ibu <span
                                        style="color:var(--bs-error);">*</span></label>
                                <input type="text" class="form-control" id="nama_ortu"
                                    value="Bambang Hendrawan, S.T.">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="nik_ortu">NIK Orang Tua <span
                                        style="color:var(--bs-error);">*</span></label>
                                <input type="text" class="form-control" id="nik_ortu" maxlength="16"
                                    value="3271041908750001">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-sm-4">
                                <label class="form-label" for="pendidikan_ortu">Pendidikan Terakhir</label>
                                <select class="form-select" id="pendidikan_ortu">
                                    <option>SMA / Sederajat</option>
                                    <option>D3 / Diploma</option>
                                    <option selected>S1 / Sarjana</option>
                                    <option>S2 / Magister</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label class="form-label" for="pekerjaan_ortu">Pekerjaan Orang Tua</label>
                                <select class="form-select" id="pekerjaan_ortu">
                                    <option>PNS / TNI / POLRI</option>
                                    <option selected>Karyawan Swasta</option>
                                    <option>Wiraswasta / Usaha</option>
                                    <option>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label class="form-label" for="penghasilan_ortu">Rentang Penghasilan Bulanan</label>
                                <select class="form-select" id="penghasilan_ortu">
                                    <option>&lt; Rp 2.500.000</option>
                                    <option>Rp 2.500.000 - Rp 5.000.000</option>
                                    <option selected>Rp 5.000.000 - Rp 10.000.000</option>
                                    <option>&gt; Rp 10.000.000</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="form-label" for="wa_ortu">Nomor WhatsApp Aktif (Notifikasi Status Seleksi)
                                <span style="color:var(--bs-error);">*</span></label>
                            <div class="position-relative">
                                <span class="position-absolute start-0 top-50 translate-middle-y ms-3 fw-bold"
                                    style="font-size:0.8rem; color:var(--bs-on-surface-variant);">+62</span>
                                <input type="tel" class="form-control ps-5 font-monospace" id="wa_ortu"
                                    value="81223344556" style="padding-left:3rem;">
                            </div>
                            <p class="mt-1 mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                Nomor ini akan menerima token verifikasi PIN pengumuman dan tautan unduh bukti tanda
                                terima.
                            </p>
                        </div>

                        <div class="pt-2">
                            <label class="d-inline-flex align-items-center gap-2" style="cursor:pointer;">
                                <input type="checkbox" class="form-check-input mt-0">
                                <span style="font-size:0.8rem;">Data pendaftar diwakilkan oleh Wali resmi (bukan orang
                                    tua kandung)</span>
                            </label>
                        </div>
                    </div>
                </article>

                <!-- ===== CARD 5: Unggah Dokumen ===== -->
                <article class="ppdb-card mb-4">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
                        style="border-color:var(--bs-outline-variant) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="card-number">5</div>
                            <div>
                                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Unggah Dokumen Berkas
                                    Persyaratan</h2>
                                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Format
                                    berkas PDF/JPG/PNG maksimal 2MB per file</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined" style="color:var(--bs-primary);">upload_file</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- Akta Kelahiran -->
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label mb-0">Akta Kelahiran Calon Siswa <span
                                        style="color:var(--bs-error);">*</span></label>
                                <span class="d-flex align-items-center gap-1"
                                    style="font-size:0.7rem; color:var(--bs-success-dark); font-weight:500;">
                                    <span class="material-symbols-outlined"
                                        style="font-size:0.9rem; color:var(--bs-success);">check_circle</span>
                                    Terunggah
                                </span>
                            </div>
                            <div class="file-box file-box-uploaded">
                                <div class="d-flex align-items-center gap-3 min-w-0">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2"
                                        style="width:2.5rem;height:2.5rem;background:var(--bs-success-light); color:var(--bs-success-dark);">
                                        <span class="material-symbols-outlined"
                                            style="font-size:1.3rem;">description</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-truncate" style="font-size:0.8rem;">
                                            akta_kelahiran_farhan.pdf</div>
                                        <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">1.2 MB •
                                            Diunggah 18 Juni 2025</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn-sm-custom" data-bs-toggle="modal"
                                        data-bs-target="#modalPreviewAkta">
                                        <span class="material-symbols-outlined"
                                            style="font-size:0.9rem;">visibility</span> Lihat
                                    </button>
                                    <button type="button" class="btn btn-sm p-2" title="Ganti File">
                                        <span class="material-symbols-outlined"
                                            style="color:var(--bs-outline); font-size:1rem;">cached</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Keluarga -->
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label mb-0">Kartu Keluarga (KK) Asli / Legalisir <span
                                        style="color:var(--bs-error);">*</span></label>
                                <span class="d-flex align-items-center gap-1"
                                    style="font-size:0.7rem; color:var(--bs-success-dark); font-weight:500;">
                                    <span class="material-symbols-outlined"
                                        style="font-size:0.9rem; color:var(--bs-success);">check_circle</span>
                                    Terunggah
                                </span>
                            </div>
                            <div class="file-box file-box-uploaded">
                                <div class="d-flex align-items-center gap-3 min-w-0">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2"
                                        style="width:2.5rem;height:2.5rem;background:var(--bs-primary-fixed); color:var(--bs-primary);">
                                        <span class="material-symbols-outlined"
                                            style="font-size:1.3rem;">family_restroom</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-truncate" style="font-size:0.8rem;">
                                            kartu_keluarga_farhan_2025.pdf</div>
                                        <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">2.4 MB •
                                            Diunggah 18 Juni 2025</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn-sm-custom" data-bs-toggle="modal"
                                        data-bs-target="#modalPreviewKK">
                                        <span class="material-symbols-outlined"
                                            style="font-size:0.9rem;">visibility</span> Lihat
                                    </button>
                                    <button type="button" class="btn btn-sm p-2" title="Ganti File">
                                        <span class="material-symbols-outlined"
                                            style="color:var(--bs-outline); font-size:1rem;">cached</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Rapor -->
                        <div>
                            <label class="form-label">Scan Rapor Semester 1 - 5 (SMP / MTs) <span
                                    style="color:var(--bs-error);">*</span></label>
                            <div class="file-box">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2"
                                        style="width:2.5rem;height:2.5rem;background:var(--bs-surface-container-highest); color:var(--bs-primary);">
                                        <span class="material-symbols-outlined"
                                            style="font-size:1.3rem;">menu_book</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium" style="font-size:0.8rem;">File PDF gabungan nilai
                                            pengetahuan semester 1 s.d. 5</div>
                                        <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Disertai
                                            halaman tanda tangan dan cap legalisir kepala sekolah</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-sm-custom"
                                    style="border-color:var(--bs-primary); color:var(--bs-primary); font-weight:700;">
                                    <span class="material-symbols-outlined" style="font-size:0.9rem;">upload</span>
                                    Pilih File
                                </button>
                            </div>
                        </div>

                        <!-- Sertifikat Prestasi -->
                        <div>
                            <label class="form-label">Sertifikat Prestasi / Piagam Penghargaan <span
                                    style="color:var(--bs-on-surface-variant); font-weight:400;">(Opsional)</span></label>
                            <div class="file-box">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2"
                                        style="width:2.5rem;height:2.5rem;background:var(--bs-surface-container-highest); color:var(--bs-on-surface-variant);">
                                        <span class="material-symbols-outlined"
                                            style="font-size:1.3rem;">military_tech</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium" style="font-size:0.8rem;">Piagam kejuaraan minimal
                                            tingkat kota/kabupaten</div>
                                        <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Dibutuhkan
                                            jika mendaftar jalur Prestasi Non-Akademik</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-sm-custom">
                                    <span class="material-symbols-outlined"
                                        style="font-size:0.9rem;">visibility</span> Lihat
                                </button>
                            </div>
                        </div>

                        <!-- Info Alert -->
                        <div class="info-alert">
                            <span class="material-symbols-outlined mt-1"
                                style="color:var(--bs-primary); font-size:1.1rem;">info</span>
                            <p class="mb-0" style="font-size:0.8rem;">
                                Pastikan dokumen terlihat jelas, tidak buram, dan teks dapat terbaca untuk mempercepat
                                proses verifikasi panitia.
                            </p>
                        </div>
                    </div>
                </article>

            </div>

            <!-- ========== RIGHT: SIDEBAR ========== -->
            <aside class="col-12 col-lg-4">

                <!-- Jadwal Timeline -->
                <div class="ppdb-card mb-4" id="jadwal-section">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom"
                        style="border-color:var(--bs-outline-variant) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined"
                                style="color:var(--bs-primary);">calendar_month</span>
                            <h3 class="mb-0" style="font-size:1rem; font-weight:600;">Jadwal PPDB 2025</h3>
                        </div>
                        <span class="badge-soft badge-success-soft" style="font-size:0.65rem;">Tahap 1 Aktif</span>
                    </div>

                    <div class="timeline">
                        <!-- Step 1 -->
                        <div class="timeline-step">
                            <div class="timeline-dot active">
                                <span class="material-symbols-outlined" style="font-size:0.8rem;">check</span>
                            </div>
                            <div class="p-3 rounded-3"
                                style="background:rgba(221,225,255,0.3); border:1px solid rgba(0,40,142,0.2);">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                                    <span class="fw-bold" style="font-size:0.8rem; color:var(--bs-primary);">1.
                                        Pendaftaran Online</span>
                                    <span class="badge"
                                        style="background:var(--bs-primary); color:#fff; font-size:0.55rem; text-transform:uppercase; letter-spacing:0.05em;">Sedang
                                        Berjalan</span>
                                </div>
                                <p class="mb-0 mt-1" style="font-size:0.8rem;">19 Juni - 24 Juni 2025</p>
                                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                    Layanan 24 Jam (Ditutup pkl 23:59 WIB)</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="timeline-step">
                            <div class="timeline-dot">
                                <span class="dot-inner"
                                    style="background:var(--bs-primary); animation:pulse 1.5s infinite;"></span>
                            </div>
                            <div class="p-2">
                                <span class="fw-bold d-block" style="font-size:0.8rem;">2. Verifikasi Berkas &amp;
                                    Dokumen</span>
                                <p class="mb-0" style="font-size:0.8rem; color:var(--bs-on-surface-variant);">25
                                    Juni - 28 Juni 2025</p>
                                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Jam
                                    Kerja Tim Panitia: 08:00 - 15:30 WIB</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="timeline-step">
                            <div class="timeline-dot">
                                <span class="dot-inner"></span>
                            </div>
                            <div class="p-2">
                                <span class="fw-bold d-block" style="font-size:0.8rem;">3. Pengumuman Kelulusan</span>
                                <p class="mb-0" style="font-size:0.8rem; color:var(--bs-on-surface-variant);">01
                                    Juli 2025</p>
                                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                    Diumumkan serentak pukul 10:00 WIB</p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="timeline-step">
                            <div class="timeline-dot">
                                <span class="dot-inner"></span>
                            </div>
                            <div class="p-2">
                                <span class="fw-bold d-block" style="font-size:0.8rem;">4. Daftar Ulang Fisik di
                                    Sekolah</span>
                                <p class="mb-0" style="font-size:0.8rem; color:var(--bs-on-surface-variant);">02
                                    Juli - 05 Juli 2025</p>
                                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                    Membawa berkas fisik asli ke sekolah tujuan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Kuota -->
                <div class="ppdb-card mb-4">
                    <div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom"
                        style="border-color:var(--bs-outline-variant) !important;">
                        <span class="material-symbols-outlined" style="color:var(--bs-secondary);">pie_chart</span>
                        <h3 class="mb-0" style="font-size:0.95rem; font-weight:700;">Estimasi Kuota Sekolah</h3>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="p-3 rounded-2 d-flex align-items-center justify-content-between"
                            style="background:var(--bs-surface-container-low);">
                            <div>
                                <div class="fw-bold" style="font-size:0.8rem;">SMA Negeri 3 Bandung</div>
                                <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Jalur Zonasi Terisi
                                    184 / 220 Siswa</div>
                            </div>
                            <span class="badge-soft"
                                style="background:var(--bs-primary-fixed); color:var(--bs-primary); border:none; font-size:0.7rem; font-weight:700;">Sisa
                                36</span>
                        </div>
                        <div class="p-3 rounded-2 d-flex align-items-center justify-content-between"
                            style="background:var(--bs-surface-container-low);">
                            <div>
                                <div class="fw-bold" style="font-size:0.8rem;">SMA Negeri 2 Bandung</div>
                                <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Jalur Zonasi Terisi
                                    198 / 240 Siswa</div>
                            </div>
                            <span class="badge-soft"
                                style="background:var(--bs-primary-fixed); color:var(--bs-primary); border:none; font-size:0.7rem; font-weight:700;">Sisa
                                42</span>
                        </div>
                    </div>
                </div>

                <!-- Pusat Layanan -->
                <div class="help-card" id="bantuan">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="material-symbols-outlined"
                            style="color:var(--bs-secondary-container);">contact_support</span>
                        <h3 class="mb-0" style="font-size:0.95rem; font-weight:700;">Pusat Layanan &amp; Pengaduan
                        </h3>
                    </div>
                    <p class="mb-3" style="font-size:0.8rem; opacity:0.9;">
                        Mengalami kendala sinkronisasi NIK atau titik peta koordinat rumah? Hubungi Posko Layanan
                        Terpadu PPDB.
                    </p>
                    <div class="d-flex flex-column gap-2">
                        <a href="tel:0224203344" class="help-link">
                            <span class="d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined" style="font-size:1rem;">call</span>
                                Hotline (022) 420-3344
                            </span>
                            <span class="material-symbols-outlined" style="font-size:0.85rem;">open_in_new</span>
                        </a>
                        <a href="https://wa.me/628112345678" target="_blank" rel="noopener" class="help-link">
                            <span class="d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined" style="font-size:1rem;">chat</span>
                                WhatsApp Helpdesk 24/7
                            </span>
                            <span class="material-symbols-outlined" style="font-size:0.85rem;">open_in_new</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- ==================== STICKY ACTION BAR ==================== -->
    <section class="sticky-action-bar">
        <div class="container-fluid" style="max-width:1280px;">
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                <div
                    class="w-100 w-sm-auto d-flex align-items-center justify-content-between justify-content-sm-start gap-3">
                    <div>
                        <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Langkah 5 dari 5 Formulir
                            Selesai</div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold" style="font-size:0.8rem; color:var(--bs-primary);">85% Kelengkapan
                                Data</span>
                            <span class="rounded-circle"
                                style="width:0.5rem;height:0.5rem;background:var(--bs-success);"></span>
                        </div>
                    </div>
                    <div class="progress-thin" style="width:8rem;">
                        <div class="progress-bar" style="width:85%;"></div>
                    </div>
                </div>
                <div class="w-100 w-sm-auto d-flex align-items-center gap-2">
                    <button type="button" class="btn-outline-custom flex-grow-1 flex-sm-grow-0">
                        <span class="material-symbols-outlined" style="font-size:1.1rem;">bookmark_border</span>
                        Simpan Draf
                    </button>
                    <button type="button" class="btn-primary-custom flex-grow-1 flex-sm-grow-0 px-4"
                        data-bs-toggle="modal" data-bs-target="#modalConfirm">
                        Kirim &amp; Lanjut Review
                        <span class="material-symbols-outlined" style="font-size:1.1rem;">arrow_forward</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== BOTTOM NAV (MOBILE) ==================== -->
    <nav class="bottom-nav" aria-label="Navigasi Bawah">
        <a href="#jadwal-section">
            <span class="material-symbols-outlined" style="font-size:1.2rem;">calendar_today</span>
            <span>Jadwal</span>
        </a>
        <a href="#" class="active" aria-current="page">
            <span class="material-symbols-outlined" style="font-size:1.2rem;">assignment</span>
            <span>Formulir</span>
        </a>
        <a href="#">
            <span class="material-symbols-outlined" style="font-size:1.2rem;">edit_document</span>
            <span>Draf</span>
        </a>
        <a href="#bantuan">
            <span class="material-symbols-outlined" style="font-size:1.2rem;">contact_support</span>
            <span>Bantuan</span>
        </a>
    </nav>

    <!-- ==================== MODAL: PREVIEW AKTA ==================== -->
    <div class="modal fade" id="modalPreviewAkta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-doc-preview">
                <!-- Header -->
                <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between gap-3"
                    style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                    <div class="d-flex align-items-center gap-2 min-w-0">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2"
                            style="width:2.25rem;height:2.25rem;background:rgba(16,185,129,0.1); color:var(--bs-success);">
                            <span class="material-symbols-outlined" style="font-size:1.2rem;">description</span>
                        </div>
                        <div class="min-w-0">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h4 class="mb-0 text-truncate" style="font-size:1rem; font-weight:700;">Preview
                                    Dokumen: Akta Kelahiran</h4>
                                <span class="badge-soft badge-success-soft" style="font-size:0.6rem;">
                                    <span class="material-symbols-outlined" style="font-size:0.7rem;">verified</span>
                                    Siap Diverifikasi
                                </span>
                            </div>
                            <p class="mb-0 text-truncate"
                                style="font-size:0.7rem; color:var(--bs-on-surface-variant);">akta_kelahiran_farhan.pdf
                                • 1.2 MB • Diunggah 18 Juni 2025</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm rounded-circle p-2" data-bs-dismiss="modal"
                        aria-label="Tutup Pratinjau">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <!-- Body -->
                <div class="doc-preview-content">
                    <div class="doc-preview-paper">
                        <div class="text-center border-bottom pb-3 mb-4"
                            style="border-bottom:2px solid var(--bs-on-surface) !important;">
                            <div class="d-flex align-items-center justify-content-center mx-auto mb-1 rounded-circle"
                                style="width:2.5rem;height:2.5rem;background:rgba(0,40,142,0.1); color:var(--bs-primary);">
                                <span class="material-symbols-outlined"
                                    style="font-size:1.6rem;">account_balance</span>
                            </div>
                            <h5 class="mb-0 text-uppercase"
                                style="font-size:0.75rem; font-weight:700; letter-spacing:0.1em;">Republik Indonesia
                            </h5>
                            <p class="mb-0 text-uppercase"
                                style="font-size:0.65rem; font-weight:600; color:var(--bs-on-surface-variant); letter-spacing:0.05em;">
                                Dinas Kependudukan dan Pencatatan Sipil Kota Bandung</p>
                            <div class="mt-2 pt-1 border-top border-dashed"
                                style="border-color:var(--bs-outline-variant) !important;">
                                <span class="text-uppercase"
                                    style="font-size:0.8rem; font-weight:800; letter-spacing:0.1em; color:var(--bs-primary);">Pencatatan
                                    Sipil • Warga Negara Indonesia</span>
                                <h6 class="mt-1 mb-0"
                                    style="font-size:0.85rem; font-weight:900; letter-spacing:0.05em;">KUTIPAN AKTA
                                    KELAHIRAN</h6>
                                <span class="font-monospace"
                                    style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Nomor:
                                    3271-LT-14082009-0048</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-2" style="font-size:0.8rem;">
                            <p class="mb-0">Bahwa di <strong>Bandung</strong> pada tanggal <strong>Empat Belas
                                    Agustus Dua Ribu Sembilan</strong> telah lahir:</p>
                            <div class="p-3 rounded-3 text-center my-2"
                                style="background:var(--bs-surface-container-low); border:1px solid var(--bs-outline-variant);">
                                <span class="d-block text-uppercase"
                                    style="font-size:0.9rem; font-weight:800; letter-spacing:0.05em; color:var(--bs-primary);">MUHAMMAD
                                    FARHAN AL-HAFIZ</span>
                                <span class="d-block mt-1"
                                    style="font-size:0.7rem; color:var(--bs-on-surface-variant); font-weight:500;">Anak
                                    ke-1 (Satu), Laki-laki</span>
                            </div>
                            <p class="mb-0">dari pasangan suami istri: <strong class="text-uppercase">Bambang
                                    Hendrawan</strong> dan <strong class="text-uppercase">Siti Nurhaliza</strong>.</p>
                        </div>
                        <div class="mt-4 pt-3 border-top border-dashed d-flex align-items-end justify-content-between"
                            style="border-color:var(--bs-outline-variant) !important;">
                            <div class="d-flex flex-column align-items-center p-2 rounded-2"
                                style="background:var(--bs-surface-container-low); border:1px solid var(--bs-outline-variant);">
                                <span class="material-symbols-outlined" style="font-size:2.25rem;">qr_code_2</span>
                                <span class="font-monospace"
                                    style="font-size:0.55rem; color:var(--bs-on-surface-variant);">VERIFIED BSrE</span>
                            </div>
                            <div class="text-end" style="font-size:0.7rem;">
                                <span class="d-block" style="color:var(--bs-on-surface-variant);">Kutipan dikeluarkan
                                    di Bandung</span>
                                <span class="d-block fw-semibold">Pada tanggal 20 Agustus 2009</span>
                                <div class="mt-2 fw-bold d-inline-flex align-items-center gap-1"
                                    style="color:var(--bs-primary);">
                                    <span class="material-symbols-outlined"
                                        style="font-size:0.9rem; color:var(--bs-success);">verified_user</span>
                                    Tandatangan Elektronik Sah
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2"
                    style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                    <div class="d-flex align-items-center gap-1"
                        style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                        <span class="material-symbols-outlined"
                            style="font-size:1rem; color:var(--bs-success);">task_alt</span>
                        Format PDF Terbaca Jelas
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-outline-custom" onclick="window.print()"
                            style="height:2.5rem; font-size:0.75rem;">
                            <span class="material-symbols-outlined" style="font-size:1rem;">download</span>
                            Unduh Dokumen
                        </button>
                        <button type="button" class="btn-primary-custom px-4" data-bs-dismiss="modal"
                            style="height:2.5rem; font-size:0.75rem;">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL: PREVIEW KK ==================== -->
    <div class="modal fade" id="modalPreviewKK" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-doc-preview">
                <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between gap-3"
                    style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                    <div class="d-flex align-items-center gap-2 min-w-0">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2"
                            style="width:2.25rem;height:2.25rem;background:rgba(0,40,142,0.1); color:var(--bs-primary);">
                            <span class="material-symbols-outlined" style="font-size:1.2rem;">family_restroom</span>
                        </div>
                        <div class="min-w-0">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h4 class="mb-0 text-truncate" style="font-size:1rem; font-weight:700;">Pratinjau
                                    Dokumen: Kartu Keluarga (KK)</h4>
                                <span class="badge-soft badge-success-soft" style="font-size:0.6rem;">
                                    <span class="material-symbols-outlined" style="font-size:0.7rem;">verified</span>
                                    Terverifikasi Dukcapil
                                </span>
                            </div>
                            <p class="mb-0 text-truncate"
                                style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                kartu_keluarga_farhan_2025.pdf • 2.4 MB • Diunggah 18 Juni 2025</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm rounded-circle p-2" data-bs-dismiss="modal">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="doc-preview-content">
                    <div class="doc-preview-paper">
                        <div class="text-center border-bottom pb-3 mb-4"
                            style="border-bottom:2px solid var(--bs-on-surface) !important;">
                            <div class="d-flex align-items-center justify-content-center mx-auto mb-1 rounded-circle"
                                style="width:2.5rem;height:2.5rem;background:rgba(0,40,142,0.1); color:var(--bs-primary);">
                                <span class="material-symbols-outlined" style="font-size:1.6rem;">shield</span>
                            </div>
                            <h5 class="mb-0" style="font-size:0.85rem; font-weight:900; letter-spacing:0.15em;">
                                KARTU KELUARGA</h5>
                            <p class="mb-0 font-monospace fw-bold"
                                style="font-size:0.75rem; color:var(--bs-primary); letter-spacing:0.05em;">No.
                                3271041908750001</p>
                            <div class="mt-2 pt-1 border-top border-dashed row g-2 text-start"
                                style="border-color:var(--bs-outline-variant) !important; font-size:0.65rem; color:var(--bs-on-surface-variant);">
                                <div class="col-6">
                                    <span class="d-block fw-medium">Nama Kepala Keluarga:</span>
                                    <span class="d-block fw-bold text-uppercase"
                                        style="color:var(--bs-on-surface); font-size:0.7rem;">Bambang Hendrawan,
                                        S.T.</span>
                                    <span class="d-block fw-medium mt-1">Alamat:</span>
                                    <span class="d-block fw-semibold" style="color:var(--bs-on-surface);">Jl.
                                        Diponegoro No. 42</span>
                                </div>
                                <div class="col-6">
                                    <span class="d-block fw-medium">RT / RW:</span>
                                    <span class="d-block fw-semibold" style="color:var(--bs-on-surface);">003 /
                                        007</span>
                                    <span class="d-block fw-medium mt-1">Desa / Kelurahan &amp; Kec:</span>
                                    <span class="d-block fw-semibold" style="color:var(--bs-on-surface);">Citarum,
                                        Bandung Wetan</span>
                                </div>
                            </div>
                        </div>
                        <div class="border rounded-3 overflow-hidden">
                            <div class="px-3 py-2 border-bottom"
                                style="background:var(--bs-surface-container-low); border-color:var(--bs-outline-variant) !important;">
                                <span class="fw-bold text-uppercase"
                                    style="font-size:0.7rem; letter-spacing:0.05em;">Daftar Anggota Keluarga</span>
                            </div>
                            <div style="font-size:0.7rem;">
                                <div class="p-2 d-flex align-items-center justify-content-between border-bottom"
                                    style="border-color:var(--bs-outline-variant) !important;">
                                    <div class="min-w-0">
                                        <span class="d-block fw-bold text-uppercase">1. Bambang Hendrawan, S.T.</span>
                                        <span class="font-monospace"
                                            style="font-size:0.65rem; color:var(--bs-on-surface-variant);">NIK:
                                            3271041908750001</span>
                                    </div>
                                    <span class="badge"
                                        style="background:var(--bs-surface-container); color:var(--bs-on-surface-variant); font-size:0.6rem;">Kepala
                                        Keluarga</span>
                                </div>
                                <div class="p-2 d-flex align-items-center justify-content-between border-bottom"
                                    style="border-color:var(--bs-outline-variant) !important;">
                                    <div class="min-w-0">
                                        <span class="d-block fw-bold text-uppercase">2. Siti Nurhaliza</span>
                                        <span class="font-monospace"
                                            style="font-size:0.65rem; color:var(--bs-on-surface-variant);">NIK:
                                            3271045509780003</span>
                                    </div>
                                    <span class="badge"
                                        style="background:var(--bs-surface-container); color:var(--bs-on-surface-variant); font-size:0.6rem;">Istri</span>
                                </div>
                                <div class="p-2 d-flex align-items-center justify-content-between"
                                    style="background:rgba(221,225,255,0.2); border-left:4px solid var(--bs-primary);">
                                    <div class="min-w-0">
                                        <div class="d-flex align-items-center gap-1 flex-wrap">
                                            <span class="d-block fw-bold text-uppercase"
                                                style="color:var(--bs-primary);">3. Muhammad Farhan Al-Hafiz</span>
                                            <span class="badge"
                                                style="background:var(--bs-primary); color:#fff; font-size:0.55rem;">Calon
                                                Siswa</span>
                                        </div>
                                        <span class="font-monospace" style="font-size:0.65rem;">NIK:
                                            3271045209080002</span>
                                    </div>
                                    <span class="fw-bold"
                                        style="font-size:0.7rem; color:var(--bs-primary);">Anak</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-top border-dashed d-flex align-items-end justify-content-between"
                            style="border-color:var(--bs-outline-variant) !important;">
                            <div class="d-flex flex-column align-items-center p-2 rounded-2"
                                style="background:var(--bs-surface-container-low); border:1px solid var(--bs-outline-variant);">
                                <span class="material-symbols-outlined" style="font-size:2.25rem;">qr_code_2</span>
                                <span class="font-monospace"
                                    style="font-size:0.55rem; color:var(--bs-on-surface-variant);">DISDUKCAPIL
                                    BSrE</span>
                            </div>
                            <div class="text-end" style="font-size:0.7rem;">
                                <span class="d-block" style="color:var(--bs-on-surface-variant);">Dikeluarkan di Kota
                                    Bandung</span>
                                <span class="d-block fw-semibold">Tanggal Terbit: 15 Januari 2021</span>
                                <div class="mt-2 fw-bold d-inline-flex align-items-center gap-1"
                                    style="color:var(--bs-primary);">
                                    <span class="material-symbols-outlined"
                                        style="font-size:0.9rem; color:var(--bs-success);">verified_user</span>
                                    TTE Kepala Dinas Disdukcapil Sah
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2"
                    style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                    <div class="d-flex align-items-center gap-1"
                        style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                        <span class="material-symbols-outlined"
                            style="font-size:1rem; color:var(--bs-success);">task_alt</span>
                        Dokumen KK Asli Terbaca Jelas
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-outline-custom" onclick="window.print()"
                            style="height:2.5rem; font-size:0.75rem;">
                            <span class="material-symbols-outlined" style="font-size:1rem;">download</span>
                            Unduh Dokumen
                        </button>
                        <button type="button" class="btn-primary-custom px-4" data-bs-dismiss="modal"
                            style="height:2.5rem; font-size:0.75rem;">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL: KONFIRMASI SUBMIT ==================== -->
    <div class="modal fade" id="modalConfirm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-doc-preview">
                <!-- Header -->
                <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between gap-3"
                    style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-3"
                            style="width:2.5rem;height:2.5rem;background:var(--bs-primary-fixed); color:var(--bs-primary); box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                            <span class="material-symbols-outlined"
                                style="font-size:1.4rem;">assignment_turned_in</span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="mb-0 text-truncate" style="font-size:1rem; font-weight:700;">Konfirmasi
                                Pengajuan Pendaftaran PPDB 2025/2026</h4>
                            <p class="mb-0 text-truncate"
                                style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Mohon periksa kembali
                                kelengkapan dan kebenaran data Anda sebelum dikirim permanen.</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm rounded-circle p-2" data-bs-dismiss="modal"
                        aria-label="Tutup Modal">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-4 overflow-y-auto" style="background:rgba(239,244,255,0.4);">
                    <div class="ppdb-card mb-3">
                        <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom"
                            style="border-color:var(--bs-outline-variant) !important;">
                            <span class="fw-bold d-flex align-items-center gap-2"
                                style="font-size:0.8rem; color:var(--bs-primary);">
                                <span class="material-symbols-outlined" style="font-size:1.1rem;">person</span>
                                Ringkasan Data Calon Peserta Didik
                            </span>
                            <span class="badge-soft badge-success-soft" style="font-size:0.6rem;">
                                <span class="material-symbols-outlined" style="font-size:0.7rem;">verified</span>
                                Dapodik Terverifikasi
                            </span>
                        </div>
                        <div class="row g-2" style="font-size:0.75rem;">
                            <div class="col-12 col-sm-6">
                                <div class="p-2 rounded-2 h-100" style="background:var(--bs-surface-container-low);">
                                    <span class="d-block"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Nama Lengkap
                                        Siswa</span>
                                    <strong style="font-size:0.8rem;">MUHAMMAD FARHAN AL-HAFIZ</strong>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="p-2 rounded-2 h-100" style="background:var(--bs-surface-container-low);">
                                    <span class="d-block"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">NISN /
                                        NIK</span>
                                    <span class="font-monospace fw-bold" style="font-size:0.8rem;">0087412941 /
                                        3271045209080002</span>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="p-2 rounded-2 h-100" style="background:var(--bs-surface-container-low);">
                                    <span class="d-block"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Jalur
                                        Pendaftaran</span>
                                    <strong style="font-size:0.8rem; color:var(--bs-primary);">Zonasi Radius (Radius:
                                        840m)</strong>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="p-2 rounded-2 h-100" style="background:var(--bs-surface-container-low);">
                                    <span class="d-block"
                                        style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Kelengkapan
                                        Berkas</span>
                                    <span class="fw-semibold d-flex align-items-center gap-1"
                                        style="font-size:0.8rem; color:var(--bs-success-dark);">
                                        <span class="material-symbols-outlined"
                                            style="font-size:0.95rem; color:var(--bs-success);">task_alt</span>
                                        4 Berkas Lengkap
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 mt-3 border-top" style="border-color:var(--bs-outline-variant) !important;">
                            <span class="d-block fw-semibold mb-2" style="font-size:0.7rem;">Sekolah Pilihan &amp;
                                Konsentrasi:</span>
                            <div class="d-flex flex-column gap-2">
                                <div class="p-2 rounded-2 d-flex align-items-center justify-content-between"
                                    style="background:rgba(221,225,255,0.2); border:1px solid rgba(0,40,142,0.3); font-size:0.8rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                            style="width:1.25rem;height:1.25rem;background:var(--bs-primary); font-size:0.65rem; font-weight:700;">1</span>
                                        <div>
                                            <strong class="d-block" style="font-size:0.8rem;">SMA Negeri 3
                                                Bandung</strong>
                                            <span
                                                style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Peminatan
                                                MIPA (Matematika &amp; Ilmu Alam)</span>
                                        </div>
                                    </div>
                                    <span class="badge"
                                        style="background:var(--bs-surface-container-lowest); color:var(--bs-primary); border:1px solid rgba(0,40,142,0.3); font-size:0.65rem;">Prioritas
                                        1</span>
                                </div>
                                <div class="p-2 rounded-2 d-flex align-items-center justify-content-between"
                                    style="background:var(--bs-surface-container-low); border:1px solid var(--bs-outline-variant); font-size:0.8rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:1.25rem;height:1.25rem;background:var(--bs-outline-variant); font-size:0.65rem; font-weight:700;">2</span>
                                        <div>
                                            <strong class="d-block" style="font-size:0.8rem;">SMA Negeri 2
                                                Bandung</strong>
                                            <span
                                                style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Peminatan
                                                MIPA (Matematika &amp; Ilmu Alam)</span>
                                        </div>
                                    </div>
                                    <span class="badge"
                                        style="background:var(--bs-surface-container); color:var(--bs-on-surface-variant); font-size:0.65rem;">Cadangan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pakta Integritas -->
                    <div class="p-3 rounded-3 mb-3"
                        style="background:var(--bs-warning-light); border:1px solid var(--bs-warning-border);">
                        <label class="d-flex align-items-start gap-2" style="cursor:pointer;">
                            <input type="checkbox" id="agree-integritas" class="form-check-input mt-1">
                            <span style="font-size:0.75rem; line-height:1.6;">
                                <strong>Pakta Integritas Pendaftar:</strong> Saya menyatakan dengan sesungguhnya bahwa
                                seluruh data dan dokumen yang saya unggah adalah benar dan sah. Apabila ditemukan
                                pemalsuan data di kemudian hari, saya bersedia menerima sanksi pembatalan kelulusan dan
                                proses hukum sesuai regulasi PPDB Provinsi.
                            </span>
                        </label>
                    </div>

                    <!-- Success Alert -->
                    <div id="submit-alert-success" class="d-none p-3 rounded-3"
                        style="background:var(--bs-success-light); border:1px solid var(--bs-success-border);">
                        <div class="d-flex align-items-start gap-2">
                            <span class="material-symbols-outlined mt-1"
                                style="color:var(--bs-success); font-size:1.1rem;">check_circle</span>
                            <div>
                                <strong class="d-block"
                                    style="font-size:0.8rem; color:var(--bs-success-dark);">Pendaftaran Berhasil
                                    Dikirim!</strong>
                                <p class="mb-0 mt-1" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                    Tanda bukti pendaftaran dan kode verifikasi telah dikirimkan ke nomor WhatsApp <span
                                        class="font-monospace fw-bold">+62 81223344556</span>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-top d-flex flex-column-reverse flex-sm-row align-items-center justify-content-end gap-2"
                    style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                    <button type="button" class="btn-outline-custom w-100 w-sm-auto px-4" data-bs-dismiss="modal"
                        style="height:2.75rem; font-size:0.8rem;">
                        Periksa Kembali
                    </button>
                    <button type="button" id="btn-final-submit" onclick="submitRegistration()"
                        class="btn-primary-custom w-100 w-sm-auto px-5" style="height:2.75rem; font-size:0.8rem;">
                        <span class="material-symbols-outlined" style="font-size:1.1rem;">send</span>
                        Ya, Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ===== Jalur Chip Interaction =====
            const jalurRadios = document.querySelectorAll('input[name="jalur_daftar"]');
            jalurRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    jalurRadios.forEach(r => {
                        const parent = r.closest('label');
                        if (parent) {
                            if (r.checked) {
                                parent.classList.add('active');
                            } else {
                                parent.classList.remove('active');
                            }
                        }
                    });
                });
            });

            // ===== Checkbox Pill Interaction =====
            const checkPills = document.querySelectorAll('.check-pill input[type="checkbox"]');
            checkPills.forEach(cb => {
                cb.addEventListener('change', () => {
                    const parent = cb.closest('.check-pill');
                    if (parent) {
                        parent.classList.toggle('active', cb.checked);
                    }
                });
            });

            // ===== Jenis Kelamin Chip (Radio inside jalur-chip class) =====
            const genderRadios = document.querySelectorAll('input[name="jenis_kelamin"]');
            genderRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    genderRadios.forEach(r => {
                        const parent = r.closest('label');
                        if (parent) {
                            if (r.checked) {
                                parent.classList.add('active');
                                parent.querySelector('span').style.color =
                                    'var(--bs-primary)';
                            } else {
                                parent.classList.remove('active');
                                parent.querySelector('span').style.color = '';
                            }
                        }
                    });
                });
            });
        });

        // ===== Submit Registration =====
        function submitRegistration() {
            const checkbox = document.getElementById('agree-integritas');
            if (checkbox && !checkbox.checked) {
                alert('Silakan setujui Pakta Integritas terlebih dahulu.');
                checkbox.focus();
                return;
            }

            const btn = document.getElementById('btn-final-submit');
            const alertBox = document.getElementById('submit-alert-success');

            if (btn) {
                btn.disabled = true;
                btn.innerHTML =
                    '<span class="material-symbols-outlined" style="font-size:1.1rem;">progress_activity</span> Memproses...';
            }

            setTimeout(() => {
                if (alertBox) alertBox.classList.remove('d-none');
                if (btn) {
                    btn.innerHTML =
                        '<span class="material-symbols-outlined" style="font-size:1.1rem;">check_circle</span> Terkirim';
                    btn.style.background = 'var(--bs-success)';
                }
            }, 700);
        }
    </script>
</body>

</html>
