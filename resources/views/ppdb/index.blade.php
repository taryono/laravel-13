{{-- resources/views/ppdb/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Portal Resmi Penerimaan Peserta Didik Baru (PPDB) Online. Pendaftaran cepat, transparan, dan akuntabel.">
    <title>Portal Resmi PPDB {{ $admissions->first()?->academic_year?->name ?? '2025/2026' }} —
        {{ $admissions->first()?->school?->name ?? 'SMAN 3 Bandung' }}</title>

    {{-- Favicon & External Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    {{-- Bootstrap 5.3 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --brand-primary: #00288e;
            --brand-primary-dark: #001a61;
            --brand-primary-light: #1e40af;
            --brand-primary-subtle: #eff4ff;
            --brand-accent: #0ea5e9;
            --brand-accent-light: #e0f2fe;
            --brand-navy: #09122c;
            --brand-surface: #f8faff;
            --brand-surface-card: #ffffff;
            --brand-border: #e2e8f0;
            --brand-text: #1e293b;
            --brand-muted: #64748b;
            --brand-success: #10b981;
            --brand-success-subtle: #ecfdf5;
            --brand-warning: #f59e0b;
            --brand-warning-subtle: #fffbeb;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --shadow-subtle: 0 4px 20px rgba(0, 40, 142, 0.05);
            --shadow-elevated: 0 12px 36px rgba(0, 40, 142, 0.08);
            --shadow-float: 0 20px 48px rgba(0, 40, 142, 0.12);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--brand-surface);
            color: var(--brand-text);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .brand-font {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
        }

        .material-symbols-outlined {
            vertical-align: middle;
            font-size: 22px;
        }

        /* ── TOP ANNOUNCEMENT TICKER ── */
        .top-ticker {
            background: linear-gradient(90deg, #09122c, #00288e);
            color: #dbeafe;
            font-size: 0.82rem;
            padding: 7px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .top-ticker a {
            color: #93c5fd;
            text-decoration: none;
            transition: color 0.2s;
        }

        .top-ticker a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        /* ── NAVBAR ── */
        .navbar-ppdb {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 1040;
            transition: all 0.3s ease;
        }

        .navbar-ppdb.scrolled {
            box-shadow: 0 8px 30px rgba(0, 40, 142, 0.08);
        }

        .navbar-brand-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .navbar-brand-badge {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-light));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 40, 142, 0.25);
        }

        .nav-link-custom {
            font-size: 0.92rem;
            font-weight: 600;
            color: #334155;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            color: var(--brand-primary);
            background: var(--brand-primary-subtle);
        }

        /* ── BUTTONS ── */
        .btn-brand-primary {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-light));
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.7rem 1.6rem;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 40, 142, 0.25);
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-brand-primary:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0, 40, 142, 0.35);
        }

        .btn-brand-outline {
            border: 1.8px solid var(--brand-primary);
            color: var(--brand-primary);
            background: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.65rem 1.4rem;
            border-radius: 12px;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-brand-outline:hover {
            background: var(--brand-primary-subtle);
            color: var(--brand-primary-dark);
            transform: translateY(-2px);
        }

        /* ── HERO SECTION ── */
        .hero-section {
            position: relative;
            background: radial-gradient(circle at 10% 20%, rgba(239, 244, 255, 0.85) 0%, rgba(255, 255, 255, 1) 70%),
                radial-gradient(circle at 90% 80%, rgba(224, 242, 254, 0.6) 0%, rgba(255, 255, 255, 0) 50%);
            padding: 4.5rem 0 3.5rem;
            border-bottom: 1px solid var(--brand-border);
            overflow: hidden;
        }

        .hero-decor-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.45;
            pointer-events: none;
        }

        .hero-decor-1 {
            width: 400px;
            height: 400px;
            background: #93c5fd;
            top: -100px;
            right: -80px;
        }

        .hero-decor-2 {
            width: 320px;
            height: 320px;
            background: #c7d2fe;
            bottom: -80px;
            left: -80px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 9999px;
            color: #065f46;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
        }

        .status-dot-pulse {
            width: 8px;
            height: 8px;
            background-color: var(--brand-success);
            border-radius: 50%;
            position: relative;
        }

        .status-dot-pulse::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: rgba(16, 185, 129, 0.4);
            animation: pulse-ring 1.8s infinite cubic-bezier(0.4, 0, 0.6, 1);
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }

            100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }

        .hero-title {
            font-size: clamp(2.2rem, 4vw, 3.4rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--brand-navy);
            margin-bottom: 1.25rem;
        }

        .hero-title-highlight {
            background: linear-gradient(135deg, var(--brand-primary), #0284c7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 1.3vw, 1.15rem);
            color: var(--brand-muted);
            line-height: 1.7;
            margin-bottom: 2rem;
            max-width: 620px;
        }

        /* Hero Image Card */
        .hero-visual-card {
            position: relative;
            border-radius: var(--radius-xl);
            overflow: visible;
            box-shadow: var(--shadow-float);
        }

        .hero-main-img {
            width: 100%;
            height: auto;
            max-height: 440px;
            object-fit: cover;
            border-radius: var(--radius-xl);
            display: block;
            border: 4px solid #ffffff;
        }

        .floating-badge {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 12px 18px;
            box-shadow: 0 10px 30px rgba(0, 40, 142, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            z-index: 2;
            animation: floatSlow 4s ease-in-out infinite alternate;
        }

        .floating-badge-top {
            top: -20px;
            left: -20px;
        }

        .floating-badge-bottom {
            bottom: -20px;
            right: -15px;
            animation-delay: -2s;
        }

        @keyframes floatSlow {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-8px);
            }
        }

        /* ── METRIC STATS STRIP ── */
        .stats-strip {
            background: #ffffff;
            border: 1px solid var(--brand-border);
            border-radius: var(--radius-lg);
            padding: 1.5rem 1.8rem;
            box-shadow: var(--shadow-subtle);
            margin-top: -2.5rem;
            position: relative;
            z-index: 10;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--brand-primary-subtle);
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .stat-number {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--brand-navy);
            line-height: 1.1;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--brand-muted);
            margin-bottom: 0;
            font-weight: 500;
        }

        /* ── SECTION STYLING ── */
        .section-padding {
            padding: 4.8rem 0;
        }

        .section-header {
            margin-bottom: 3rem;
        }

        .section-tag {
            display: inline-block;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--brand-primary);
            background: var(--brand-primary-subtle);
            padding: 5px 14px;
            border-radius: 9999px;
            margin-bottom: 0.65rem;
        }

        .section-title {
            font-size: clamp(1.8rem, 2.8vw, 2.4rem);
            font-weight: 800;
            color: var(--brand-navy);
            margin-bottom: 0.6rem;
        }

        .section-desc {
            font-size: 1rem;
            color: var(--brand-muted);
            max-width: 640px;
        }

        /* ── ADMISSION CARDS ── */
        .admission-card {
            background: #ffffff;
            border: 1.5px solid var(--brand-border);
            border-radius: var(--radius-xl);
            padding: 2rem;
            box-shadow: var(--shadow-subtle);
            transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .admission-card:hover {
            border-color: #93c5fd;
            box-shadow: var(--shadow-elevated);
            transform: translateY(-4px);
        }

        .admission-card.is-active-wave {
            border-color: #bfdbfe;
            background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
        }

        .admission-card.is-active-wave::before {
            content: '';
            position: absolute;
            top: 0;
            left: 28px;
            right: 28px;
            height: 4px;
            background: linear-gradient(90deg, var(--brand-primary), var(--brand-accent));
            border-radius: 0 0 4px 4px;
        }

        .badge-wave-type {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 10px;
            border-radius: 6px;
            background: #eff6ff;
            color: var(--brand-primary);
            border: 1px solid #bfdbfe;
        }

        .info-pill-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 1.4rem 0;
        }

        .info-pill-box {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 10px 12px;
        }

        .info-pill-label {
            font-size: 0.7rem;
            color: var(--brand-muted);
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .info-pill-val {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--brand-navy);
            margin-bottom: 0;
        }

        /* Jalur chips inside card */
        .path-chip-group {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 1.5rem;
        }

        .path-chip {
            font-size: 0.74rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* ── JALUR PENDAFTARAN CARDS ── */
        .jalur-card {
            background: #ffffff;
            border: 1px solid var(--brand-border);
            border-radius: var(--radius-lg);
            padding: 2rem 1.8rem;
            height: 100%;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .jalur-card:hover {
            box-shadow: var(--shadow-elevated);
            transform: translateY(-4px);
            border-color: #cbd5e1;
        }

        .jalur-icon-box {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .icon-blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .icon-emerald {
            background: #ecfdf5;
            color: #059669;
        }

        .icon-amber {
            background: #fffbeb;
            color: #d97706;
        }

        .icon-purple {
            background: #faf5ff;
            color: #7c3aed;
        }

        .jalur-quota-tag {
            font-size: 0.74rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 6px;
            float: right;
        }

        /* ── ALUR LANGKAH PENDAFTARAN ── */
        .step-container {
            position: relative;
        }

        .step-card {
            background: #ffffff;
            border: 1px solid var(--brand-border);
            border-radius: var(--radius-lg);
            padding: 1.8rem;
            height: 100%;
            position: relative;
            transition: all 0.25s ease;
        }

        .step-card:hover {
            border-color: var(--brand-primary);
            box-shadow: var(--shadow-subtle);
            transform: translateY(-3px);
        }

        .step-badge {
            width: 42px;
            height: 42px;
            background: var(--brand-primary);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
            box-shadow: 0 4px 10px rgba(0, 40, 142, 0.2);
        }

        /* ── PERSYARATAN BERKAS TABS ── */
        .docs-card {
            background: #ffffff;
            border: 1px solid var(--brand-border);
            border-radius: var(--radius-xl);
            padding: 2.2rem;
            box-shadow: var(--shadow-subtle);
        }

        .doc-check-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .doc-check-item:last-child {
            border-bottom: none;
        }

        .doc-check-icon {
            color: var(--brand-success);
            font-size: 1.25rem;
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* ── CEK STATUS SEARCH BOX ── */
        .cek-status-box {
            background: linear-gradient(135deg, #09122c, #00288e);
            color: #ffffff;
            border-radius: var(--radius-xl);
            padding: 3rem 2.5rem;
            box-shadow: var(--shadow-float);
            position: relative;
            overflow: hidden;
        }

        .cek-status-box::after {
            content: '';
            position: absolute;
            right: -60px;
            bottom: -60px;
            width: 240px;
            height: 240px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .search-input-group {
            background: #ffffff;
            border-radius: 14px;
            padding: 6px;
            display: flex;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .search-input-group input {
            border: none;
            padding: 12px 18px;
            font-size: 0.95rem;
            flex: 1;
            border-radius: 10px;
            outline: none;
            color: var(--brand-navy);
        }

        .search-input-group input::placeholder {
            color: #94a3b8;
        }

        /* ── RESULT STATUS CARD ── */
        .applicant-result-card {
            background: #ffffff;
            color: var(--brand-text);
            border-radius: var(--radius-lg);
            padding: 1.8rem;
            margin-top: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-left: 6px solid var(--brand-primary);
        }

        /* ── FAQ ACCORDION ── */
        .accordion-ppdb .accordion-item {
            border: 1px solid var(--brand-border);
            border-radius: var(--radius-md) !important;
            margin-bottom: 12px;
            overflow: hidden;
            background: #ffffff;
        }

        .accordion-ppdb .accordion-button {
            font-weight: 700;
            color: var(--brand-navy);
            font-size: 1rem;
            padding: 1.2rem 1.4rem;
            background: #ffffff;
            box-shadow: none;
        }

        .accordion-ppdb .accordion-button:not(.collapsed) {
            color: var(--brand-primary);
            background: #f8faff;
        }

        .accordion-ppdb .accordion-body {
            color: var(--brand-muted);
            font-size: 0.92rem;
            line-height: 1.7;
            padding: 1.2rem 1.4rem;
        }

        /* ── CTA BANNER ── */
        .cta-banner {
            background: linear-gradient(135deg, #00288e 0%, #1e40af 100%);
            border-radius: var(--radius-xl);
            padding: 3.5rem 2.5rem;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .cta-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        /* ── FOOTER ── */
        .footer-ppdb {
            background: #09122c;
            color: #94a3b8;
            padding: 4rem 0 2rem;
            font-size: 0.9rem;
        }

        .footer-ppdb h6 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 1.2rem;
        }

        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
            display: inline-block;
            margin-bottom: 8px;
        }

        .footer-link:hover {
            color: #ffffff;
            transform: translateX(3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 3rem;
            padding-top: 1.5rem;
            font-size: 0.82rem;
        }

        /* ── TIMELINE ── */
        .timeline-wrapper {
            position: relative;
            padding: 1rem 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 45px;
            padding-bottom: 2rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 17px;
            top: 28px;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: 5px;
            top: 4px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--brand-primary-subtle);
            border: 3px solid var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-dot.active {
            background: var(--brand-primary);
            border-color: #93c5fd;
            box-shadow: 0 0 0 4px rgba(0, 40, 142, 0.2);
        }

        /* ── RESPONSIVE TWEAKS ── */
        @media (max-width: 991px) {
            .hero-section {
                padding: 3rem 0 2.5rem;
                text-align: center;
            }

            .hero-subtitle {
                margin: 0 auto 2rem;
            }

            .hero-cta-group {
                justify-content: center;
            }

            .floating-badge {
                display: none;
            }

            .stats-strip {
                margin-top: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .info-pill-grid {
                grid-template-columns: 1fr;
            }

            .cek-status-box {
                padding: 2rem 1.25rem;
            }

            .search-input-group {
                flex-direction: column;
                gap: 8px;
                padding: 8px;
            }

            .search-input-group .btn-brand-primary {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    {{-- ── 1. TOP ANNOUNCEMENT BAR ── --}}
    <div class="top-ticker">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark px-2 py-1 fw-bold" style="font-size: 0.72rem;">INFO RESMI</span>
                <span>Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran
                    {{ $admissions->first()?->academic_year?->name ?? '2025/2026' }} Resmi Dibuka!</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="#bantuan"><i class="fa-solid fa-headset me-1"></i> Helpdesk PPDB</a>
                <span class="text-white-50">|</span>
                <a href="https://wa.me/6281234567890?text=Halo%20Panitia%20PPDB,%20saya%20butuh%20informasi"
                    target="_blank">
                    <i class="fa-brands fa-whatsapp text-success me-1"></i> WA Panitia
                </a>
            </div>
        </div>
    </div>

    {{-- ── 2. NAVBAR ── --}}
    <nav class="navbar navbar-expand-lg navbar-ppdb py-2">
        <div class="container">
            <a class="navbar-brand-wrapper" href="{{ route('ppdb.index') }}">
                <div class="navbar-brand-badge">
                    <i class="fa-solid fa-graduation-cap fs-5"></i>
                </div>
                <div>
                    <div class="brand-font fw-bold"
                        style="color: var(--brand-navy); font-size: 1.15rem; line-height: 1.1;">
                        PPDB ONLINE
                    </div>
                    <div class="text-muted" style="font-size: 0.75rem; font-weight: 500;">
                        {{ $admissions->first()?->school?->name ?? 'Portal Resmi Sekolah' }}
                    </div>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarPpdbMenu" aria-controls="navbarPpdbMenu" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarPpdbMenu">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 py-2 py-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom active" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#gelombang">Gelombang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#jalur">Jalur Seleksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#alur">Alur Pendaftaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#syarat">Syarat Berkas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#faq">FAQ</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 pt-2 pt-lg-0">
                    <a href="#cek-status" class="btn btn-brand-outline btn-sm">
                        <i class="fa-solid fa-magnifying-glass"></i> Cek Status
                    </a>
                    @if (\Illuminate\Support\Facades\Route::has('ppdb.login'))
                        <a href="{{ route('ppdb.login') }}" class="btn btn-brand-primary btn-sm">
                            <i class="fa-solid fa-user-circle"></i> Login Pendaftar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- ── 3. HERO SECTION ── --}}
    <section class="hero-section" id="beranda">
        <div class="hero-decor-circle hero-decor-1"></div>
        <div class="hero-decor-circle hero-decor-2"></div>

        <div class="container position-relative">
            <div class="row align-items-center g-5">
                {{-- Left Text --}}
                <div class="col-lg-6">
                    <div class="status-pill animate__animated animate__fadeInDown">
                        <span class="status-dot-pulse"></span>
                        PPDB T.A. {{ $admissions->first()?->academic_year?->name ?? '2025/2026' }} RESMI DIBUKA
                    </div>

                    <h1 class="hero-title">
                        Mulai Langkah Nyata Menuju <span class="hero-title-highlight">Masa Depan Gemilang</span>
                    </h1>

                    <p class="hero-subtitle">
                        Selamat datang di Portal Resmi Penerimaan Peserta Didik Baru
                        <strong>{{ $admissions->first()?->school?->name ?? 'Sekolah Unggulan' }}</strong>.
                        Sistem pendaftaran terpadu yang cepat, objektif, transparan, dan dapat diakses dari mana saja.
                    </p>

                    <div class="d-flex flex-wrap gap-3 hero-cta-group mb-4">
                        <a href="#gelombang" class="btn-brand-primary">
                            <span>Daftar Sekarang</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="#alur" class="btn-brand-outline">
                            <i class="fa-solid fa-circle-play"></i>
                            <span>Panduan Alur</span>
                        </a>
                        <a href="#cek-status"
                            class="btn btn-link text-decoration-none fw-semibold text-muted d-flex align-items-center gap-1">
                            <i class="fa-solid fa-search text-primary"></i> Cek Hasil / Kelulusan
                        </a>
                    </div>

                    {{-- Quick Trust Badges --}}
                    <div class="pt-3 border-top d-flex flex-wrap align-items-center gap-4 text-muted"
                        style="font-size: 0.85rem;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-primary fs-5"></i>
                            <span>Data Aman &amp; Terverifikasi</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-award text-warning fs-5"></i>
                            <span>Akreditasi A (Unggul)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-success fs-5"></i>
                            <span>Layanan 24 Jam Online</span>
                        </div>
                    </div>
                </div>

                {{-- Right Visual Banner --}}
                <div class="col-lg-6">
                    <div class="hero-visual-card">
                        {{-- Top Floating Badge --}}
                        <div class="floating-badge floating-badge-top">
                            <div class="rounded-circle p-2 bg-success-subtle text-success d-flex">
                                <i class="fa-solid fa-circle-check fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size: 0.85rem; color: var(--brand-navy);">Akreditasi
                                    A Unggul</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Sekolah Rujukan Nasional</div>
                            </div>
                        </div>

                        {{-- Hero Image --}}
                        <img src="{{ asset('assets/images/ppdb_hero_banner.jpg') }}" alt="Siswa Berprestasi PPDB"
                            class="hero-main-img"
                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=80';">

                        {{-- Bottom Floating Badge --}}
                        <div class="floating-badge floating-badge-bottom">
                            <div class="rounded-circle p-2 bg-primary-subtle text-primary d-flex">
                                <i class="fa-solid fa-user-graduate fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size: 0.85rem; color: var(--brand-navy);">
                                    {{ $admissions->sum('quota') ?: '400+' }} Kuota Siswa Baru
                                </div>
                                <div class="text-muted" style="font-size: 0.72rem;">4 Jalur Seleksi Terbuka</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── METRIC STATS STRIP ── --}}
            <div class="stats-strip">
                <div class="row g-4 align-items-center justify-content-between">
                    <div class="col-6 col-md-3">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <div class="stat-number">{{ $admissions->sum('quota') ?: '400' }}</div>
                                <p class="stat-label">Total Kuota Tersedia</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-item">
                            <div class="stat-icon" style="background:#ecfdf5; color:#059669;">
                                <i class="fa-solid fa-route"></i>
                            </div>
                            <div>
                                <div class="stat-number">4 Jalur</div>
                                <p class="stat-label">Penerimaan Siswa</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-item">
                            <div class="stat-icon" style="background:#fffbeb; color:#d97706;">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <div>
                                <div class="stat-number">{{ $admissions->count() }} Gelombang</div>
                                <p class="stat-label">Jadwal Periode Aktif</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-item">
                            <div class="stat-icon" style="background:#faf5ff; color:#7c3aed;">
                                <i class="fa-solid fa-laptop-code"></i>
                            </div>
                            <div>
                                <div class="stat-number">100%</div>
                                <p class="stat-label">Digital &amp; Transparan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 4. SECTION: GELOMBANG PENDAFTARAN AKTIF ── --}}
    <section class="section-padding" id="gelombang">
        <div class="container">
            <div class="text-center section-header">
                <span class="section-tag">Jadwal &amp; Pendaftaran</span>
                <h2 class="section-title">Gelombang Pendaftaran Dibuka</h2>
                <p class="section-desc mx-auto">
                    Pilih gelombang pendaftaran yang sesuai dengan jenjang dan jalur pilihan Anda. Pastikan mengisi
                    formulir sebelum batas waktu penutupan.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                @forelse ($admissions as $admission)
                    @php
                        $startDate = $admission->start_date ? \Carbon\Carbon::parse($admission->start_date) : null;
                        $endDate = $admission->end_date ? \Carbon\Carbon::parse($admission->end_date) : null;
                        $now = now();
                        $isOpen = $admission->is_active && ($endDate ? $now->lte($endDate->endOfDay()) : true);
                        $daysLeft = $endDate ? $now->diffInDays($endDate, false) : null;
                    @endphp

                    <div class="col-lg-6">
                        <div class="admission-card is-active-wave">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge-wave-type mb-2 d-inline-block">
                                        <i class="fa-solid fa-tag me-1"></i> {{ $admission->type ?? 'Jalur Reguler' }}
                                    </span>
                                    <h3 class="fw-bold mb-1" style="font-size: 1.35rem; color: var(--brand-navy);">
                                        {{ $admission->name }}
                                    </h3>
                                    <div class="text-muted" style="font-size: 0.84rem;">
                                        <i class="fa-solid fa-school me-1 text-primary"></i>
                                        {{ $admission->school?->name ?? 'SMAN 3 Bandung' }}
                                        &bull; T.A. {{ $admission->academic_year?->name ?? '2025/2026' }}
                                    </div>
                                </div>

                                @if ($isOpen)
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold"
                                        style="font-size: 0.78rem;">
                                        <span class="spinner-grow spinner-grow-sm me-1" role="status"
                                            style="width: 0.5rem; height: 0.5rem;"></span>
                                        Dibuka
                                    </span>
                                @else
                                    <span
                                        class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fw-bold"
                                        style="font-size: 0.78rem;">
                                        Ditutup
                                    </span>
                                @endif
                            </div>

                            {{-- Info Parameters --}}
                            <div class="info-pill-grid">
                                <div class="info-pill-box">
                                    <div class="info-pill-label"><i
                                            class="fa-solid fa-calendar-days text-primary me-1"></i> Periode
                                        Pendaftaran</div>
                                    <div class="info-pill-val">
                                        @if ($startDate && $endDate)
                                            {{ $startDate->translatedFormat('d M Y') }} -
                                            {{ $endDate->translatedFormat('d M Y') }}
                                        @else
                                            Sesuai Kalender Akademik
                                        @endif
                                    </div>
                                </div>

                                <div class="info-pill-box">
                                    <div class="info-pill-label"><i class="fa-solid fa-users text-primary me-1"></i>
                                        Total Daya Tampung</div>
                                    <div class="info-pill-val">
                                        {{ $admission->quota ? $admission->quota . ' Kursi' : 'Menyesuaikan Kuota' }}
                                    </div>
                                </div>

                                <div class="info-pill-box">
                                    <div class="info-pill-label"><i class="fa-solid fa-receipt text-primary me-1"></i>
                                        Biaya Pendaftaran</div>
                                    <div class="info-pill-val text-success">
                                        @if ($admission->registration_fee > 0)
                                            Rp {{ number_format($admission->registration_fee, 0, ',', '.') }}
                                        @else
                                            Gratis (Bebas Biaya)
                                        @endif
                                    </div>
                                </div>

                                <div class="info-pill-box">
                                    <div class="info-pill-label"><i class="fa-solid fa-child text-primary me-1"></i>
                                        Ketentuan Usia</div>
                                    <div class="info-pill-val">
                                        @if ($admission->min_age || $admission->max_age)
                                            {{ $admission->min_age ?? '0' }} - {{ $admission->max_age ?? '21' }} Tahun
                                        @else
                                            Sesuai Juknis Resmi
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Paths preview --}}
                            <div class="mb-3">
                                <div class="text-muted fw-semibold mb-2"
                                    style="font-size: 0.76rem; text-transform: uppercase;">
                                    Jalur Penerimaan Tersedia:
                                </div>
                                <div class="path-chip-group">
                                    @if ($admission->paths && $admission->paths->count() > 0)
                                        @foreach ($admission->paths as $path)
                                            <span class="path-chip">
                                                <i class="fa-solid fa-check text-primary"></i> {{ $path->name }}
                                                @if ($path->quota)
                                                    ({{ $path->quota }} kursi)
                                                @endif
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="path-chip"><i class="fa-solid fa-check text-primary"></i> Jalur
                                            Zonasi (50%)</span>
                                        <span class="path-chip"><i class="fa-solid fa-check text-primary"></i> Jalur
                                            Prestasi (25%)</span>
                                        <span class="path-chip"><i class="fa-solid fa-check text-primary"></i> Jalur
                                            Afirmasi (20%)</span>
                                        <span class="path-chip"><i class="fa-solid fa-check text-primary"></i>
                                            Perpindahan Ortu (5%)</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Requirements excerpt if any --}}
                            @if ($admission->requirements)
                                <div class="p-3 bg-light rounded-3 mb-4"
                                    style="font-size: 0.82rem; border-left: 3px solid var(--brand-accent);">
                                    <strong><i class="fa-solid fa-clipboard-list text-primary me-1"></i> Catatan
                                        Persyaratan:</strong>
                                    <p class="mb-0 text-muted mt-1">{{ Str::limit($admission->requirements, 120) }}
                                    </p>
                                </div>
                            @endif

                            {{-- Action buttons --}}
                            <div
                                class="mt-auto pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    @if ($endDate && $daysLeft !== null && $daysLeft >= 0)
                                        <span
                                            class="badge bg-warning-subtle text-warning-emphasis px-3 py-2 rounded-pill fw-semibold"
                                            style="font-size: 0.78rem;">
                                            <i class="fa-regular fa-clock me-1"></i> Sisa {{ $daysLeft }} Hari
                                            Lagi
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('ppdb.register', $admission->code) }}"
                                        class="btn-brand-primary">
                                        <span>Isi Formulir Pendaftaran</span>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-8">
                        <div class="text-center p-5 bg-white rounded-4 border shadow-sm">
                            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex p-4 mb-3">
                                <i class="fa-solid fa-calendar-xmark fs-2"></i>
                            </div>
                            <h4 class="fw-bold">Belum Ada Gelombang Pendaftaran Dibuka</h4>
                            <p class="text-muted mx-auto" style="max-width: 500px;">
                                Saat ini panitia PPDB belum membuka gelombang pendaftaran baru. Silakan pantau website
                                secara berkala atau hubungi panitia melalui layanan kontak helpdesk kami.
                            </p>
                            <a href="#bantuan" class="btn btn-brand-primary mt-2">
                                <i class="fa-solid fa-headset me-1"></i> Hubungi Helpdesk PPDB
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ── 5. SECTION: JALUR SELEKSI RESMI ── --}}
    <section class="section-padding bg-white" id="jalur">
        <div class="container">
            @php
                $pathsList =
                    isset($admissionPaths) && $admissionPaths->isNotEmpty()
                        ? $admissionPaths
                        : $admissions->first()?->paths ?? collect();
            @endphp

            <div class="text-center section-header">
                <span class="section-tag">Ketentuan Penerimaan</span>
                <h2 class="section-title">
                    {{ $pathsList->count() > 0 ? $pathsList->count() . ' Jalur Seleksi Masuk Resmi' : 'Jalur Seleksi Masuk Resmi' }}
                </h2>
                <p class="section-desc mx-auto">
                    Ketahui jalur penerimaan yang paling sesuai dengan kriteria, domisili, atau prestasi calon peserta
                    didik baru.
                </p>
            </div>

            <div class="row g-4">
                @forelse ($pathsList as $path)
                    @php
                        $codeUpper = strtoupper($path->code ?? '');

                        // Icon & Theme Styling mapping
                        $theme = match (true) {
                            str_contains($codeUpper, 'ZONASI') => [
                                'icon' => 'fa-solid fa-map-location-dot',
                                'icon_class' => 'icon-blue',
                                'badge_class' => 'bg-primary-subtle text-primary border border-primary-subtle',
                            ],
                            str_contains($codeUpper, 'PRESTASI') => [
                                'icon' => 'fa-solid fa-trophy',
                                'icon_class' => 'icon-emerald',
                                'badge_class' => 'bg-success-subtle text-success border border-success-subtle',
                            ],
                            str_contains($codeUpper, 'AFIRMASI') ||
                                str_contains($codeUpper, 'KETM') ||
                                str_contains($codeUpper, 'ABK')
                                => [
                                'icon' => 'fa-solid fa-hands-holding-child',
                                'icon_class' => 'icon-amber',
                                'badge_class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                            ],
                            str_contains($codeUpper, 'PINDAH') ||
                                str_contains($codeUpper, 'MUTASI') ||
                                str_contains($codeUpper, 'TUGAS')
                                => [
                                'icon' => 'fa-solid fa-briefcase',
                                'icon_class' => 'icon-purple',
                                'badge_class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                            ],
                            default => [
                                'icon' => 'fa-solid fa-id-card-clip',
                                'icon_class' => 'icon-blue',
                                'badge_class' => 'bg-primary-subtle text-primary border border-primary-subtle',
                            ],
                        };

                        // Decode requirements JSON
                        $req = is_array($path->requirements)
                            ? $path->requirements
                            : (is_string($path->requirements)
                                ? json_decode($path->requirements, true)
                                : []);
                        $docs = $req['documents'] ?? [];
                        $notes = $req['notes'] ?? null;
                        $criteria = $req['criteria'] ?? null;

                        $targetAdmission = $path->admission ?? $admissions->first();
                    @endphp

                    <div class="col-md-6 col-lg-3">
                        <div class="jalur-card h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="jalur-icon-box {{ $theme['icon_class'] }}">
                                    <i class="{{ $theme['icon'] }}"></i>
                                </div>
                                @if ($path->quota)
                                    <span class="jalur-quota-tag {{ $theme['badge_class'] }}">
                                        Kuota {{ $path->quota }} Siswa
                                    </span>
                                @endif
                            </div>

                            <h4 class="fw-bold mb-2" style="font-size: 1.15rem; color: var(--brand-navy);">
                                {{ $path->name }}
                            </h4>

                            <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.6;">
                                {{ $path->description }}
                            </p>

                            {{-- Highlight Criteria / Min Score if available --}}
                            @if (($path->min_score && $path->min_score > 0) || $criteria)
                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    @if ($path->min_score && $path->min_score > 0)
                                        <span class="badge bg-light text-dark border px-2 py-1"
                                            style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-chart-line text-success me-1"></i> Min. Nilai:
                                            {{ number_format($path->min_score, 0) }}
                                        </span>
                                    @endif
                                    @if ($criteria)
                                        <span class="badge bg-light text-dark border px-2 py-1"
                                            style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-sliders text-primary me-1"></i> Bobot:
                                            {{ $criteria['academic_weight'] ?? 60 }}% :
                                            {{ $criteria['achievement_weight'] ?? 40 }}%
                                        </span>
                                    @endif
                                </div>
                            @endif

                            {{-- Preview Requirements Documents --}}
                            @if (!empty($docs))
                                <div class="p-2 rounded-3 bg-light border mb-3" style="font-size: 0.78rem;">
                                    <div
                                        class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-between">
                                        <span><i class="fa-solid fa-file-lines text-primary me-1"></i> Berkas
                                            Syarat:</span>
                                        <span class="text-muted" style="font-size: 0.7rem;">{{ count($docs) }}
                                            Dokumen</span>
                                    </div>
                                    <ul class="list-unstyled mb-0 text-muted">
                                        @foreach (array_slice($docs, 0, 2) as $doc)
                                            <li class="text-truncate mb-1" title="{{ $doc }}">
                                                <i class="fa-solid fa-check text-success me-1"></i>
                                                {{ $doc }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Notes if available --}}
                            @if ($notes)
                                <div class="text-muted mb-3" style="font-size: 0.76rem; line-height: 1.4;">
                                    <i class="fa-solid fa-circle-info text-primary me-1"></i>
                                    {{ Str::limit($notes, 75) }}
                                </div>
                            @endif

                            {{-- Actions in card footer --}}
                            <div class="mt-auto pt-3 border-top d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light border flex-grow-1"
                                    data-bs-toggle="modal" data-bs-target="#modalJalur{{ $path->id }}">
                                    <i class="fa-solid fa-circle-info me-1"></i> Rincian Syarat
                                </button>
                                @if ($targetAdmission)
                                    <a href="{{ route('ppdb.register', $targetAdmission->code) }}?path={{ $path->code }}"
                                        class="btn btn-sm btn-brand-primary px-3" title="Daftar Jalur Ini">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">Data jalur pendaftaran belum dikonfigurasi.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- MODALS RINCIAN PERSYARATAN JALUR --}}
    @foreach ($pathsList as $path)
        @php
            $req = is_array($path->requirements)
                ? $path->requirements
                : (is_string($path->requirements)
                    ? json_decode($path->requirements, true)
                    : []);
            $docs = $req['documents'] ?? [];
            $notes = $req['notes'] ?? null;
            $criteria = $req['criteria'] ?? null;
            $targetAdmission = $path->admission ?? $admissions->first();
        @endphp
        <div class="modal fade" id="modalJalur{{ $path->id }}" tabindex="-1"
            aria-labelledby="modalJalurLabel{{ $path->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-bottom py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill fw-bold">
                                {{ $path->code }}
                            </span>
                            <h5 class="modal-title fw-bold mb-0" id="modalJalurLabel{{ $path->id }}">
                                {{ $path->name }}
                            </h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">{{ $path->description }}</p>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-4">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="text-muted small fw-semibold">Daya Tampung / Kuota</div>
                                    <div class="fs-5 fw-bold text-dark">
                                        {{ $path->quota ? $path->quota . ' Kursi' : 'Sesuai Juknis' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="text-muted small fw-semibold">Batas Nilai Minimum</div>
                                    <div class="fs-5 fw-bold text-dark">
                                        {{ $path->min_score && $path->min_score > 0 ? number_format($path->min_score, 0) : 'Tanpa Batas Nilai' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="text-muted small fw-semibold">Periode Pendaftaran</div>
                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                                        @if ($path->open_date && $path->close_date)
                                            {{ \Carbon\Carbon::parse($path->open_date)->translatedFormat('d M') }} -
                                            {{ \Carbon\Carbon::parse($path->close_date)->translatedFormat('d M Y') }}
                                        @else
                                            Sesuai Gelombang
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($criteria)
                            <div class="alert alert-info py-2.5 px-3 rounded-3 mb-4 d-flex align-items-center gap-2"
                                style="font-size: 0.85rem;">
                                <i class="fa-solid fa-sliders fs-5 text-primary"></i>
                                <div>
                                    <strong>Formula Pembobotan:</strong> Nilai Rapor Akademik
                                    ({{ $criteria['academic_weight'] ?? 60 }}%) + Sertifikat Prestasi
                                    ({{ $criteria['achievement_weight'] ?? 40 }}%).
                                </div>
                            </div>
                        @endif

                        <h6 class="fw-bold mb-3 text-dark"><i
                                class="fa-solid fa-file-circle-check text-primary me-2"></i> Dokumen &amp; Berkas yang
                            Wajib Disiapkan:</h6>
                        @if (!empty($docs))
                            <div class="list-group list-group-flush mb-4 border rounded-3 overflow-hidden">
                                @foreach ($docs as $docItem)
                                    <div class="list-group-item d-flex align-items-center gap-3 py-2.5">
                                        <i class="fa-solid fa-check-circle text-success fs-5 flex-shrink-0"></i>
                                        <span style="font-size: 0.9rem;">{{ $docItem }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small">Sesuai persyaratan dokumen standar (KK, Akta Kelahiran, Rapor,
                                Ijazah/SKL, dan Pas Foto).</p>
                        @endif

                        @if ($notes)
                            <div class="p-3 bg-light rounded-3 text-muted"
                                style="font-size: 0.85rem; border-left: 4px solid var(--brand-accent);">
                                <strong><i class="fa-solid fa-circle-exclamation text-primary me-1"></i> Catatan Khusus
                                    Panitia:</strong>
                                <div class="mt-1">{{ $notes }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-top py-3 px-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        @if ($targetAdmission)
                            <a href="{{ route('ppdb.register', $targetAdmission->code) }}?path={{ $path->code }}"
                                class="btn btn-brand-primary">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Isi Formulir Jalur Ini
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- ── 6. SECTION: ALUR PENDAFTARAN 4 LANGKAH MUDAH ── --}}
    <section class="section-padding" id="alur">
        <div class="container">
            <div class="text-center section-header">
                <span class="section-tag">Tata Cara</span>
                <h2 class="section-title">4 Langkah Mudah Mendaftar</h2>
                <p class="section-desc mx-auto">
                    Proses registrasi dilakukan secara mandiri secara online melalui ponsel atau komputer Anda.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-badge">1</div>
                        <h4 class="fw-bold mb-2" style="font-size: 1.1rem; color: var(--brand-navy);">Pilih Gelombang
                            &amp; Isi Data</h4>
                        <p class="text-muted" style="font-size: 0.86rem; line-height: 1.6;">
                            Pilih gelombang aktif dan lengkapi formulir pendaftaran: biodata siswa, NIK, NISN, alamat
                            domisili, dan data orang tua/wali.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-badge" style="background: var(--brand-accent);">2</div>
                        <h4 class="fw-bold mb-2" style="font-size: 1.1rem; color: var(--brand-navy);">Unggah Dokumen
                            Berkas</h4>
                        <p class="text-muted" style="font-size: 0.86rem; line-height: 1.6;">
                            Upload hasil scan/foto dokumen asli (KK, Akta Kelahiran, Rapor/SKL, dan sertifikat prestasi
                            jika ada) dalam format PDF/JPG.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-badge" style="background: var(--brand-success);">3</div>
                        <h4 class="fw-bold mb-2" style="font-size: 1.1rem; color: var(--brand-navy);">Verifikasi oleh
                            Panitia</h4>
                        <p class="text-muted" style="font-size: 0.86rem; line-height: 1.6;">
                            Tim verifikator PPDB memeriksa keabsahan dan kelengkapan dokumen. Calon siswa menerima bukti
                            tanda bukti pendaftaran resmi.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-badge" style="background: var(--brand-navy);">4</div>
                        <h4 class="fw-bold mb-2" style="font-size: 1.1rem; color: var(--brand-navy);">Pengumuman &amp;
                            Daftar Ulang</h4>
                        <p class="text-muted" style="font-size: 0.86rem; line-height: 1.6;">
                            Cek status kelulusan di portal PPDB online dan lakukan registrasi ulang fisik/online sesuai
                            jadwal yang ditentukan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 7. SECTION: PERSYARATAN BERKAS & TIMELINE ── --}}
    <section class="section-padding bg-white" id="syarat">
        <div class="container">
            <div class="row g-5 align-items-center">
                {{-- Left: Document Checklist --}}
                <div class="col-lg-7">
                    <span class="section-tag">Berkas Pendaftaran</span>
                    <h2 class="section-title mb-3">Persyaratan Dokumen yang Wajib Disiapkan</h2>
                    <p class="text-muted mb-4" style="font-size: 0.95rem;">
                        Pastikan seluruh berkas difoto/scan dengan jelas, tidak terpotong, dan dapat dibaca dengan mudah
                        oleh tim verifikasi.
                    </p>

                    <div class="docs-card">
                        <h5 class="fw-bold mb-3" style="color: var(--brand-navy);">
                            <i class="fa-solid fa-folder-open text-primary me-2"></i> Dokumen Wajib (Semua Jalur)
                        </h5>

                        <div class="doc-check-item">
                            <i class="fa-solid fa-circle-check doc-check-icon"></i>
                            <div>
                                <div class="fw-bold" style="font-size: 0.92rem;">Kartu Keluarga (KK) Asli</div>
                                <div class="text-muted" style="font-size: 0.8rem;">Diterbitkan paling singkat 1 (satu)
                                    tahun sebelum tanggal pendaftaran PPDB.</div>
                            </div>
                        </div>

                        <div class="doc-check-item">
                            <i class="fa-solid fa-circle-check doc-check-icon"></i>
                            <div>
                                <div class="fw-bold" style="font-size: 0.92rem;">Akta Kelahiran / Surat Kenal Lahir
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">Dokumen resmi dari Dinas
                                    Kependudukan dan Pencatatan Sipil (Dukcapil).</div>
                            </div>
                        </div>

                        <div class="doc-check-item">
                            <i class="fa-solid fa-circle-check doc-check-icon"></i>
                            <div>
                                <div class="fw-bold" style="font-size: 0.92rem;">Ijazah / Surat Keterangan Lulus (SKL)
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">Dari sekolah jenjang sebelumnya
                                    yang mencantumkan nilai rata-rata resmi.</div>
                            </div>
                        </div>

                        <div class="doc-check-item">
                            <i class="fa-solid fa-circle-check doc-check-icon"></i>
                            <div>
                                <div class="fw-bold" style="font-size: 0.92rem;">Buku Rapor Semester 1 s.d. 5</div>
                                <div class="text-muted" style="font-size: 0.8rem;">Scan halaman nilai pengetahuan
                                    &amp; keterampilan yang telah dilegalisir kepala sekolah.</div>
                            </div>
                        </div>

                        <div class="doc-check-item">
                            <i class="fa-solid fa-circle-check doc-check-icon"></i>
                            <div>
                                <div class="fw-bold" style="font-size: 0.92rem;">Pas Foto Berwarna Terbaru (3x4)</div>
                                <div class="text-muted" style="font-size: 0.8rem;">Format JPG/PNG, seragam sekolah
                                    asal, latar belakang polos (merah/biru).</div>
                            </div>
                        </div>

                        <div class="doc-check-item">
                            <i class="fa-solid fa-circle-check doc-check-icon"></i>
                            <div>
                                <div class="fw-bold" style="font-size: 0.92rem;">Surat Pertanggungjawaban Mutlak
                                    (SPTJM)</div>
                                <div class="text-muted" style="font-size: 0.8rem;">Format formulir SPTJM disediakan di
                                    sistem dan dibubuhi materai Rp 10.000.</div>
                            </div>
                        </div>

                        <div class="mt-3 p-3 bg-light rounded-3 d-flex align-items-center gap-2 text-muted"
                            style="font-size: 0.8rem;">
                            <i class="fa-solid fa-circle-info text-primary fs-5"></i>
                            <span>Format file yang didukung: <strong>PDF, JPG, PNG</strong> (Ukuran maksimal 2 MB per
                                berkas).</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Timeline Roadmap --}}
                <div class="col-lg-5">
                    <div class="p-4 p-md-5 rounded-4 bg-light border shadow-sm">
                        <span class="section-tag mb-2">Agenda PPDB</span>
                        <h3 class="fw-bold mb-3" style="color: var(--brand-navy); font-size: 1.4rem;">Garis Waktu
                            Kegiatan</h3>
                        <p class="text-muted mb-4" style="font-size: 0.86rem;">
                            Jadwal estimasi tahapan seleksi PPDB Tahun Ajaran
                            {{ $admissions->first()?->academic_year?->name ?? '2025/2026' }}.
                        </p>

                        <div class="timeline-wrapper">
                            <div class="timeline-item">
                                <div class="timeline-dot active">
                                    <i class="fa-solid fa-check text-white" style="font-size: 11px;"></i>
                                </div>
                                <div class="fw-bold" style="color: var(--brand-navy); font-size: 0.95rem;">1.
                                    Sosialisasi &amp; Pendaftaran Akun</div>
                                <div class="text-muted" style="font-size: 0.82rem;">Pengenalan jalur, pembuatan akun
                                    siswa, dan pengisian data diri awal.</div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot active">
                                    <i class="fa-solid fa-pen text-white" style="font-size: 11px;"></i>
                                </div>
                                <div class="fw-bold text-primary" style="font-size: 0.95rem;">2. Pendaftaran Online
                                    &amp; Upload Dokumen</div>
                                <div class="text-muted" style="font-size: 0.82rem;">Pemilihan jalur sekolah dan
                                    pengunggahan berkas digital (24 Jam Online).</div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="fw-bold" style="color: var(--brand-navy); font-size: 0.95rem;">3.
                                    Verifikasi &amp; Uji Berkas Fisik</div>
                                <div class="text-muted" style="font-size: 0.82rem;">Pemeriksaan keaslian sertifikat
                                    dan koordinat domisili calon siswa.</div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="fw-bold" style="color: var(--brand-navy); font-size: 0.95rem;">4.
                                    Pengumuman Kelulusan Resmi</div>
                                <div class="text-muted" style="font-size: 0.82rem;">Diumumkan secara serentak melalui
                                    portal resmi PPDB online.</div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="fw-bold" style="color: var(--brand-navy); font-size: 0.95rem;">5. Daftar
                                    Ulang Siswa Diterima</div>
                                <div class="text-muted" style="font-size: 0.82rem;">Penyerahan berkas fisik dan
                                    penyelesaian administrasi siswa baru.</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <a href="#gelombang" class="btn btn-brand-primary w-100 justify-content-center">
                                <i class="fa-solid fa-pen-nib"></i> Daftar Gelombang Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 8. SECTION: CEK STATUS PENDAFTARAN CEPAT ── --}}
    <section class="section-padding" id="cek-status">
        <div class="container">
            <div class="cek-status-box">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <span class="badge bg-white text-primary px-3 py-1 rounded-pill fw-bold mb-3"
                            style="font-size: 0.78rem;">
                            LAYANAN MANDIRI
                        </span>
                        <h2 class="fw-bold text-white mb-2" style="font-size: 2rem;">Cek Status &amp; Hasil Seleksi
                        </h2>
                        <p class="text-white-50 mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                            Sudah pernah mendaftar? Masukkan Nomor Registrasi Pendaftaran Anda (misal:
                            <code>PPDB-2025-XXXX</code>) atau Nomor Induk Kependudukan (NIK) untuk melacak verifikasi
                            berkas dan pengumuman hasil seleksi.
                        </p>
                    </div>

                    <div class="col-lg-6">
                        <form action="{{ route('ppdb.checkStatus') }}" method="POST">
                            @csrf
                            <div class="search-input-group">
                                <input type="text" name="registration_number"
                                    value="{{ old('registration_number') }}"
                                    placeholder="Masukkan No. Registrasi atau NIK..." required autocomplete="off">
                                <button type="submit" class="btn-brand-primary">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <span>Cek Sekarang</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Alert Flash Error --}}
                @if (session('status_error'))
                    <div class="alert alert-danger mt-4 mb-0 rounded-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation fs-5 flex-shrink-0"></i>
                        <div>{{ session('status_error') }}</div>
                    </div>
                @endif

                {{-- Alert Flash Result --}}
                @if (session('applicant_result'))
                    @php
                        $res = session('applicant_result');
                    @endphp
                    <div class="applicant-result-card animate__animated animate__fadeInUp">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill fw-bold mb-1"
                                    style="font-size: 0.75rem;">
                                    NO. REG: {{ $res->registration_number }}
                                </span>
                                <h4 class="fw-bold mb-1" style="color: var(--brand-navy);">{{ $res->name }}</h4>
                                <div class="text-muted" style="font-size: 0.85rem;">
                                    NIK: {{ substr($res->nik, 0, 4) . '******' . substr($res->nik, -4) }}
                                    &bull; Asal Sekolah: {{ $res->previous_school ?? '-' }}
                                </div>
                            </div>

                            <div>
                                @php
                                    $statusName = strtoupper($res->status ?? 'DRAFT');
                                    $badgeColor = match ($statusName) {
                                        'LULUS', 'DITERIMA', 'VERIFIED' => 'bg-success text-white',
                                        'SUBMITTED', 'PENDING' => 'bg-warning text-dark',
                                        'DITOLAK' => 'bg-danger text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $badgeColor }} px-3 py-2 rounded-pill fs-6 fw-bold">
                                    {{ is_object($res->status) ? $res->status->name ?? $statusName : $statusName }}
                                </span>
                            </div>
                        </div>

                        <div class="row g-3 py-3 border-top border-bottom text-muted" style="font-size: 0.85rem;">
                            <div class="col-sm-4">
                                <span class="fw-semibold text-dark d-block">Gelombang:</span>
                                {{ $res->admission?->name ?? '-' }}
                            </div>
                            <div class="col-sm-4">
                                <span class="fw-semibold text-dark d-block">Jalur Seleksi:</span>
                                {{ $res->_path?->name ?? 'Jalur Terpilih' }}
                            </div>
                            <div class="col-sm-4">
                                <span class="fw-semibold text-dark d-block">Tanggal Registrasi:</span>
                                {{ $res->registered_at ? \Carbon\Carbon::parse($res->registered_at)->translatedFormat('d M Y, H:i') : '-' }}
                                WIB
                            </div>
                        </div>

                        <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <span class="text-muted" style="font-size: 0.82rem;">
                                Ingin mengunggah berkas perbaikan atau melihat rincian?
                            </span>
                            @if (\Illuminate\Support\Facades\Route::has('ppdb.login'))
                                <a href="{{ route('ppdb.login') }}" class="btn btn-sm btn-brand-primary">
                                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk ke Dashboard Siswa
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ── 9. SECTION: FAQ ACCORDION ── --}}
    <section class="section-padding bg-white" id="faq">
        <div class="container">
            <div class="text-center section-header">
                <span class="section-tag">Bantuan &amp; Informasi</span>
                <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
                <p class="section-desc mx-auto">
                    Temukan jawaban cepat atas pertanyaan seputar tata cara pendaftaran, syarat berkas, dan sistem
                    seleksi PPDB.
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion accordion-ppdb" id="accordionPpdbFaq">
                        {{-- FAQ 1 --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true">
                                    <i class="fa-solid fa-circle-question text-primary me-2"></i>
                                    Apakah pendaftaran PPDB online ini dikenakan biaya?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                data-bs-parent="#accordionPpdbFaq">
                                <div class="accordion-body">
                                    Untuk sekolah negeri, seluruh proses pendaftaran PPDB dari awal hingga pengumuman
                                    adalah <strong>100% Bebas Biaya (Gratis)</strong> sesuai ketentuan Kemendikbudristek
                                    dan Pemda. Untuk program khusus berbayar, rincian biaya tertera secara transparan
                                    pada rincian gelombang terkait.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 2 --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo">
                                    <i class="fa-solid fa-circle-question text-primary me-2"></i>
                                    Bagaimana jika saya salah menginput data atau dokumen pendaftaran?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse"
                                data-bs-parent="#accordionPpdbFaq">
                                <div class="accordion-body">
                                    Selama status pendaftaran Anda masih dalam tahap <em>DRAFT</em> atau belum
                                    diverifikasi final oleh panitia, Anda dapat login ke portal siswa dan melakukan
                                    pembaruan berkas. Jika sudah terlanjur difinalisasi, silakan datang langsung ke
                                    loket helpdesk PPDB di sekolah untuk mengajukan revisi.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 3 --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree">
                                    <i class="fa-solid fa-circle-question text-primary me-2"></i>
                                    Bolehkah mendaftar di lebih dari 1 (satu) jalur seleksi sekaligus?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                data-bs-parent="#accordionPpdbFaq">
                                <div class="accordion-body">
                                    Calon siswa hanya diperbolehkan memilih 1 jalur seleksi utama dalam satu gelombang
                                    pendaftaran. Namun, jika Anda tidak lolos pada Tahap 1 (misal: Jalur Afirmasi atau
                                    Prestasi), Anda diperkenankan mendaftar kembali pada Tahap 2 (Jalur Zonasi) sesuai
                                    jadwal yang ditetapkan.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 4 --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour">
                                    <i class="fa-solid fa-circle-question text-primary me-2"></i>
                                    Bagaimana perhitungan jarak radius untuk Jalur Zonasi?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse"
                                data-bs-parent="#accordionPpdbFaq">
                                <div class="accordion-body">
                                    Jarak dihitung berdasarkan garis lurus (geolokasi koordinat) antara titik alamat
                                    tempat tinggal sesuai Kartu Keluarga (KK) menuju titik koordinat gerbang utama
                                    sekolah. Sistem secara otomatis menghitung jarak tersebut saat Anda memasukkan
                                    alamat.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 5 --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFive">
                                    <i class="fa-solid fa-circle-question text-primary me-2"></i>
                                    Kapan hasil seleksi diumumkan dan bagaimana cara melihat kelulusan?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse"
                                data-bs-parent="#accordionPpdbFaq">
                                <div class="accordion-body">
                                    Hasil seleksi resmi diumumkan pada tanggal yang tercantum di jadwal gelombang pukul
                                    10:00 WIB. Anda dapat langsung mengecek status kelulusan pada fitur <strong>Cek
                                        Status</strong> di website ini menggunakan Nomor Registrasi atau NIK Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 10. SECTION: CTA BANNER ── --}}
    <section class="container my-5">
        <div class="cta-banner">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-white text-primary px-3 py-1 rounded-pill fw-bold mb-3"
                        style="font-size: 0.78rem;">
                        PENDAFTARAN TERBATAS
                    </span>
                    <h2 class="fw-bold text-white mb-2" style="font-size: clamp(1.8rem, 2.5vw, 2.4rem);">
                        Siap Menjadi Bagian dari Generasi Berprestasi?
                    </h2>
                    <p class="text-white-50 mb-0" style="font-size: 1rem; max-width: 600px;">
                        Jangan lewatkan kesempatan menimba ilmu di lingkungan belajar modern yang mendukung potensi
                        akademik dan karakter Anda.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="#gelombang"
                        class="btn btn-light text-primary fw-bold py-3 px-4 rounded-3 shadow-sm fs-6">
                        <i class="fa-solid fa-paper-plane me-2"></i> Pilih Gelombang &amp; Daftar
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 11. SECTION: PUSAT BANTUAN & KONTAK HELPDESK ── --}}
    <section class="section-padding bg-light" id="bantuan">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-4">
                    <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
                        <div class="rounded-circle bg-success-subtle text-success p-3 d-inline-flex mb-3">
                            <i class="fa-brands fa-whatsapp fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: var(--brand-navy);">WhatsApp Panitia</h4>
                        <p class="text-muted" style="font-size: 0.88rem;">
                            Butuh bantuan kilat seputar kendala teknis atau pengunggahan berkas? Hubungi helpdesk
                            WhatsApp kami.
                        </p>
                        <div class="mt-auto">
                            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20PPDB,%20saya%20ingin%20bertanya%20mengenai%20pendaftaran"
                                target="_blank" class="btn btn-success fw-bold w-100 py-2 rounded-3">
                                <i class="fa-brands fa-whatsapp me-1"></i> Chat via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
                        <div class="rounded-circle bg-primary-subtle text-primary p-3 d-inline-flex mb-3">
                            <i class="fa-solid fa-clock fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: var(--brand-navy);">Jam Operasional Panitia</h4>
                        <p class="text-muted" style="font-size: 0.88rem;">
                            Layanan konsultasi tatap muka dan verifikasi berkas fisik di sekretariat panitia:
                        </p>
                        <ul class="list-unstyled text-muted" style="font-size: 0.84rem;">
                            <li class="mb-1"><i class="fa-solid fa-check text-success me-2"></i> <strong>Senin -
                                    Kamis:</strong> 08:00 - 15:00 WIB</li>
                            <li class="mb-1"><i class="fa-solid fa-check text-success me-2"></i>
                                <strong>Jumat:</strong> 08:00 - 11:30 WIB</li>
                            <li><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i> <strong>Sabtu -
                                    Minggu:</strong> Libur (Sistem Online Tetap Aktif)</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="p-4 bg-white rounded-4 border h-100 shadow-sm">
                        <div class="rounded-circle bg-danger-subtle text-danger p-3 d-inline-flex mb-3">
                            <i class="fa-solid fa-location-dot fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: var(--brand-navy);">Sekretariat PPDB</h4>
                        <p class="text-muted" style="font-size: 0.88rem;">
                            Gedung Panitia PPDB, Kampus {{ $admissions->first()?->school?->name ?? 'SMAN 3 Bandung' }}.
                        </p>
                        <p class="text-muted mb-0" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-envelope me-2 text-primary"></i> ppdb@sekolah.sch.id<br>
                            <i class="fa-solid fa-phone me-2 text-primary"></i> (022) 4235489
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 12. FOOTER ── --}}
    <footer class="footer-ppdb">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="navbar-brand-badge" style="width: 38px; height: 38px;">
                            <i class="fa-solid fa-graduation-cap fs-6"></i>
                        </div>
                        <div class="fw-bold text-white fs-5 brand-font">
                            PPDB ONLINE
                        </div>
                    </div>
                    <p style="font-size: 0.88rem; max-width: 440px;" class="text-muted">
                        Portal Resmi Penerimaan Peserta Didik Baru (PPDB) terintegrasi berbasis digital. Memfasilitasi
                        pendaftaran yang adil, transparan, akuntabel, dan bebas dari diskriminasi.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle"
                            style="width: 36px; height: 36px; padding: 6px;"><i
                                class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle"
                            style="width: 36px; height: 36px; padding: 6px;"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle"
                            style="width: 36px; height: 36px; padding: 6px;"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <h6>Jalur Seleksi</h6>
                    <ul class="list-unstyled">
                        <li><a href="#jalur" class="footer-link">Jalur Zonasi</a></li>
                        <li><a href="#jalur" class="footer-link">Jalur Prestasi</a></li>
                        <li><a href="#jalur" class="footer-link">Jalur Afirmasi KETM</a></li>
                        <li><a href="#jalur" class="footer-link">Perpindahan Ortu</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2">
                    <h6>Tautan Cepat</h6>
                    <ul class="list-unstyled">
                        <li><a href="#gelombang" class="footer-link">Gelombang Aktif</a></li>
                        <li><a href="#alur" class="footer-link">Alur Pendaftaran</a></li>
                        <li><a href="#syarat" class="footer-link">Syarat Dokumen</a></li>
                        <li><a href="#cek-status" class="footer-link">Cek Kelulusan</a></li>
                        <li><a href="#faq" class="footer-link">Tanya Jawab (FAQ)</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h6>Layanan Bantuan</h6>
                    <p style="font-size: 0.85rem;" class="mb-2">
                        Hubungi panitia jika mengalami kendala akun atau nomor pendaftaran:
                    </p>
                    <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.05); font-size: 0.82rem;">
                        <div><i class="fa-solid fa-headset me-2 text-primary"></i> <strong>Helpdesk:</strong> 08:00 -
                            15:30 WIB</div>
                        <div class="mt-1"><i class="fa-brands fa-whatsapp me-2 text-success"></i>
                            <strong>WhatsApp:</strong> 0812-3456-7890</div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div>
                    &copy; {{ date('Y') }} Panitia PPDB Online
                    {{ $admissions->first()?->school?->name ?? 'Sekolah' }}. Seluruh hak cipta dilindungi.
                </div>
                <div class="d-flex gap-3">
                    <a href="#" class="footer-link mb-0" style="font-size: 0.8rem;">Kebijakan Privasi</a>
                    <a href="#" class="footer-link mb-0" style="font-size: 0.8rem;">Syarat &amp; Ketentuan</a>
                    <a href="{{ url('/') }}" class="footer-link mb-0" style="font-size: 0.8rem;">Portal
                        Utama</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Bootstrap Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Interactive Scroll Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navbar shadow on scroll
            const navbar = document.querySelector('.navbar-ppdb');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 30) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId && targetId !== '#') {
                        const targetElem = document.querySelector(targetId);
                        if (targetElem) {
                            e.preventDefault();
                            targetElem.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
