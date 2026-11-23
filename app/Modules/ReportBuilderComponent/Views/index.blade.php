<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Report Templates Studio_Rapor Builder</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="{{ asset('assets/css/raport_template.css') }}" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100" x-data="templateIndexData()">

    <!-- ======================================================== -->
    <!-- TOP NAVIGATION BAR -->
    <!-- ======================================================== -->
    <header class="app-header">
        <div class="container-xxl px-3 px-sm-4 px-lg-5">
            <div class="d-flex align-items-center justify-content-between" style="height:64px;">

                <!-- Left: Brand & Breadcrumb -->
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.report-builder.index') }}"
                        class="d-flex align-items-center gap-3 text-decoration-none">
                        <div class="rounded-4 bg-gradient-brand d-flex align-items-center justify-content-center text-white shadow-sm"
                            style="width:40px; height:40px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="d-block fw-bold text-dark" style="letter-spacing:-0.02em;">
                                Report Builder
                                <span class="badge rounded-pill bg-brand-50 text-brand-700 border"
                                    style="font-size:10px; border-color:var(--brand-200) !important;">STUDIO</span>
                            </span>
                            <p class="mb-0 text-muted d-none d-sm-block" style="font-size:12px;">Raport Generator System
                            </p>
                        </div>
                    </a>

                    <div class="d-none d-md-flex text-muted mx-2" style="color:#cbd5e1 !important;">/</div>

                    <!-- Breadcrumbs -->
                    <nav class="d-none d-md-flex align-items-center gap-2 fw-medium text-muted" style="font-size:12px;">
                        <a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">Dashboard</a>
                        <span>›</span>
                        <span class="text-dark fw-semibold">Master Template Rapor</span>
                    </nav>
                </div>

                <!-- Center/Right: Navigation Tabs & CTAs -->
                <div class="d-flex align-items-center gap-3">
                    <nav class="d-none d-sm-flex nav-pill-group align-items-center gap-1">
                        <a href="{{ route('admin.report-builder.index') }}" class="active">
                            📋 Daftar Template
                        </a>
                        <a href="{{ route('admin.report-builder.components.index') }}">
                            <span>🧩</span> Palette Komponen
                        </a>
                    </nav>

                    <div class="d-none d-sm-block bg-secondary bg-opacity-25" style="width:1px; height:24px;"></div>

                    <!-- Primary Action -->
                    <a href="{{ route('admin.report-builder.builder') }}"
                        class="btn btn-brand d-inline-flex align-items-center gap-2 fw-semibold rounded-3 px-3 px-sm-4 py-2"
                        style="font-size:13px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="d-none d-sm-inline">Buat Template Baru</span>
                        <span class="d-sm-none">Buat</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- ======================================================== -->
    <!-- MAIN CONTENT -->
    <!-- ======================================================== -->
    <main class="flex-grow-1 container-xxl px-3 px-sm-4 px-lg-5 py-4 py-lg-5" style="max-width: 1280px;">

        <!-- Flash Alert Messages -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                class="alert alert-success border-0 rounded-4 d-flex align-items-center justify-content-between shadow-sm mb-4"
                style="background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-white d-flex align-items-center justify-content-center fw-bold"
                        style="width:32px; height:32px; color:#059669;">✓</div>
                    <p class="mb-0 fw-semibold small">{{ session('success') }}</p>
                </div>
                <button type="button" @click="show = false" class="btn btn-link text-decoration-none fw-bold p-0"
                    style="color:#059669;">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms
                class="alert border-0 rounded-4 d-flex align-items-center justify-content-between shadow-sm mb-4"
                style="background:#fff1f2; color:#9f1239; border:1px solid #fecdd3 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-white d-flex align-items-center justify-content-center fw-bold"
                        style="width:32px; height:32px; color:#e11d48;">!</div>
                    <p class="mb-0 fw-semibold small">{{ session('error') }}</p>
                </div>
                <button type="button" @click="show = false" class="btn btn-link text-decoration-none fw-bold p-0"
                    style="color:#e11d48;">✕</button>
            </div>
        @endif

        <!-- ======================================================== -->
        <!-- HERO / HEADER BANNER -->
        <!-- ======================================================== -->
        <div class="position-relative overflow-hidden rounded-4xl bg-gradient-hero text-white p-4 p-sm-5 shadow-lg mb-4 mb-lg-5"
            style="border:1px solid rgba(30,41,59,0.8);">
            <!-- Background Decorative Ornaments -->
            <div class="position-absolute rounded-circle"
                style="right:-4rem; top:-4rem; width:20rem; height:20rem; background:rgba(99,102,241,0.2); filter:blur(60px); pointer-events:none;">
            </div>
            <div class="position-absolute rounded-circle"
                style="left:-3rem; bottom:-3rem; width:16rem; height:16rem; background:rgba(59,130,246,0.15); filter:blur(50px); pointer-events:none;">
            </div>

            <div
                class="position-relative d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                <div style="max-width:42rem;">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2"
                        style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.1); font-size:12px; font-weight:600; color:var(--brand-200);">
                        <span class="rounded-circle bg-success"
                            style="width:8px; height:8px; box-shadow:0 0 0 4px rgba(34,197,94,0.2);"></span>
                        Drag &amp; Drop Visual Template Engine
                    </div>
                    <h1 class="fw-bold mb-2 text-white" style="letter-spacing:-0.03em; font-size:1.75rem;">
                        Master Template Rapor Santri
                    </h1>
                    <p class="mb-0 text-white-50" style="font-size:14px; line-height:1.6;">
                        Rancang format rapor kustom dengan dukungan ukuran kertas A4, F4, A3, hingga kustom. Atur tata
                        letak seksi, tabel nilai dinamis, catatan wali, hingga tanda tangan digital.
                    </p>
                </div>

                <!-- Quick Action Buttons on Banner -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.report-builder.builder') }}"
                        class="btn bg-white text-dark fw-semibold rounded-3 d-inline-flex align-items-center gap-2 px-4 py-2 shadow-sm"
                        style="font-size:14px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor"
                            class="text-brand-600" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Buat Template</span>
                    </a>
                    <a href="{{ route('admin.report-builder.components.index') }}"
                        class="btn fw-semibold rounded-3 d-inline-flex align-items-center gap-2 px-4 py-2 text-white"
                        style="font-size:14px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);">
                        <span>🧩</span>
                        <span>Kelola Palette</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- STATISTICS CARDS -->
        <!-- ======================================================== -->
        <div class="row g-3 g-sm-4 mb-4 mb-lg-5">
            <!-- Stat 1: Total Templates -->
            <div class="col-6 col-lg-3">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-bold text-uppercase text-muted"
                                style="font-size:11px; letter-spacing:0.08em;">Total Template</span>
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px; background:#eef2ff; color:#4f46e5; font-size:18px;">📄
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <div class="fw-bold text-dark" style="font-size:1.75rem;">
                                {{ $stats['total'] ?? $templates->total() }}
                            </div>
                            <span class="text-muted fw-medium" style="font-size:12px;">template</span>
                        </div>
                        <p class="mb-0 text-muted mt-1" style="font-size:12px;">Tersimpan dalam database</p>
                    </div>
                </div>
            </div>

            <!-- Stat 2: Active Templates -->
            <div class="col-6 col-lg-3">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-bold text-uppercase"
                                style="font-size:11px; letter-spacing:0.08em; color:#059669;">Template Aktif</span>
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px; background:#ecfdf5; color:#059669;">
                                <span class="rounded-circle bg-success"
                                    style="width:12px; height:12px; box-shadow:0 0 0 4px #d1fae5;"></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <div class="fw-bold" style="font-size:1.75rem; color:#059669;">
                                {{ $stats['active'] ?? 0 }}</div>
                            <span class="fw-medium" style="font-size:12px; color:rgba(5,150,105,0.8);">aktif</span>
                        </div>
                        <p class="mb-0 text-muted mt-1" style="font-size:12px;">Siap dipakai saat cetak rapor</p>
                    </div>
                </div>
            </div>

            <!-- Stat 3: Default Template -->
            <div class="col-6 col-lg-3">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-bold text-uppercase"
                                style="font-size:11px; letter-spacing:0.08em; color:#d97706;">Template Bawaan</span>
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px; background:#fffbeb; color:#f59e0b; font-size:18px;">⭐
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <div class="fw-bold" style="font-size:1.75rem; color:#d97706;">
                                {{ $stats['default'] ?? 0 }}</div>
                            <span class="fw-medium" style="font-size:12px; color:rgba(217,119,6,0.8);">default</span>
                        </div>
                        <p class="mb-0 text-muted mt-1" style="font-size:12px;">Otomatis dipilih jika belum diatur</p>
                    </div>
                </div>
            </div>

            <!-- Stat 4: Formats & Paper Sizes -->
            <div class="col-6 col-lg-3">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-bold text-uppercase"
                                style="font-size:11px; letter-spacing:0.08em; color:#2563eb;">Ukuran Kertas</span>
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px; background:#eff6ff; color:#2563eb; font-size:18px;">📐
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <div class="fw-bold" style="font-size:1.75rem; color:#2563eb;">
                                {{ count($stats['paper_sizes'] ?? ['A4']) ?: 1 }}
                            </div>
                            <span class="fw-medium" style="font-size:12px; color:rgba(37,99,235,0.8);">ukuran</span>
                        </div>
                        <p class="mb-0 text-muted mt-1" style="font-size:12px;">
                            {{ implode(', ', array_slice($stats['paper_sizes'] ?? ['A4', 'F4'], 0, 3)) ?: 'A4, F4' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- FILTER BAR & VIEW TOGGLE -->
        <!-- ======================================================== -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 mb-lg-5">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.report-builder.index') }}">
                    <div
                        class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">

                        <!-- Search Input -->
                        <div class="position-relative flex-grow-1">
                            <div class="position-absolute top-50 translate-middle-y text-muted ps-3"
                                style="pointer-events:none;">
                                <svg width="16" height="16" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama template, jenis rapor, atau deskripsi..."
                                class="form-control rounded-3 ps-5 py-2" style="font-size:13px;">
                        </div>

                        <!-- Dropdown Filters: Paper Size & Status -->
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <!-- Paper Size -->
                            <div class="d-flex align-items-center gap-2">
                                <label class="fw-semibold text-muted mb-0" style="font-size:12px;">Kertas:</label>
                                <select name="paper_size" onchange="this.form.submit()"
                                    class="form-select form-select-sm rounded-3"
                                    style="font-size:12px; min-width:170px; background:#f8fafc;">
                                    <option value="all"
                                        {{ request('paper_size') == 'all' || !request('paper_size') ? 'selected' : '' }}>
                                        Semua Ukuran</option>
                                    <option value="A4" {{ request('paper_size') == 'A4' ? 'selected' : '' }}>A4
                                        (210 x 297 mm)</option>
                                    <option value="F4" {{ request('paper_size') == 'F4' ? 'selected' : '' }}>F4 /
                                        Folio (215 x 330 mm)</option>
                                    <option value="A3" {{ request('paper_size') == 'A3' ? 'selected' : '' }}>A3
                                        (297 x 420 mm)</option>
                                    <option value="A5" {{ request('paper_size') == 'A5' ? 'selected' : '' }}>A5
                                        (148 x 210 mm)</option>
                                    <option value="B5" {{ request('paper_size') == 'B5' ? 'selected' : '' }}>B5
                                        (176 x 250 mm)</option>
                                    <option value="Letter" {{ request('paper_size') == 'Letter' ? 'selected' : '' }}>
                                        Letter</option>
                                    <option value="Legal" {{ request('paper_size') == 'Legal' ? 'selected' : '' }}>
                                        Legal</option>
                                    <option value="Custom" {{ request('paper_size') == 'Custom' ? 'selected' : '' }}>
                                        Custom Size</option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="d-flex align-items-center gap-2">
                                <label class="fw-semibold text-muted mb-0" style="font-size:12px;">Status:</label>
                                <select name="status" onchange="this.form.submit()"
                                    class="form-select form-select-sm rounded-3"
                                    style="font-size:12px; min-width:140px; background:#f8fafc;">
                                    <option value="all"
                                        {{ request('status') == 'all' || request('status') === null ? 'selected' : '' }}>
                                        Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif
                                        / Draft</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-dark rounded-3 fw-semibold px-3 py-2"
                                style="font-size:12px;">
                                Terapkan
                            </button>

                            @if (request()->hasAny(['search', 'type', 'paper_size', 'status']))
                                <a href="{{ route('admin.report-builder.index') }}"
                                    class="btn rounded-3 fw-semibold px-3 py-2"
                                    style="font-size:12px; background:#f1f5f9; color:#475569;">
                                    Reset
                                </a>
                            @endif

                            <!-- View Switcher -->
                            <div class="d-none d-sm-block bg-secondary bg-opacity-25 mx-1"
                                style="width:1px; height:24px;"></div>

                            <div class="view-toggle d-flex align-items-center">
                                <button type="button" @click="viewMode = 'grid'"
                                    :class="viewMode === 'grid' ? 'active' : ''" class="btn">
                                    <svg width="14" height="14" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    <span class="d-none d-sm-inline">Kartu</span>
                                </button>
                                <button type="button" @click="viewMode = 'table'"
                                    :class="viewMode === 'table' ? 'active' : ''" class="btn">
                                    <svg width="14" height="14" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    <span class="d-none d-sm-inline">Tabel</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Type Filter Tabs (Quick Filter) -->
                    <div class="d-flex align-items-center gap-2 overflow-auto pt-1" style="scrollbar-width:thin;">
                        <span class="text-muted fw-semibold text-uppercase d-none d-sm-inline me-1"
                            style="font-size:10px; letter-spacing:0.06em;">Kategori:</span>

                        <a href="{{ route('admin.report-builder.index', array_merge(request()->except('type', 'page'), ['type' => 'all'])) }}"
                            class="pill pill-neutral {{ !request('type') || request('type') == 'all' ? 'active' : '' }}">
                            Semua Kategori ({{ $stats['total'] ?? 0 }})
                        </a>

                        <a href="{{ route('admin.report-builder.index', array_merge(request()->except('type', 'page'), ['type' => 'academic'])) }}"
                            class="pill pill-indigo {{ request('type') == 'academic' ? 'active' : '' }}">
                            <span>🎓</span> Akademik ({{ $stats['types']['academic'] ?? 0 }})
                        </a>

                        <a href="{{ route('admin.report-builder.index', array_merge(request()->except('type', 'page'), ['type' => 'religious'])) }}"
                            class="pill pill-emerald {{ request('type') == 'religious' ? 'active' : '' }}">
                            <span>🕌</span> Keagamaan / Diniyah ({{ $stats['types']['religious'] ?? 0 }})
                        </a>

                        <a href="{{ route('admin.report-builder.index', array_merge(request()->except('type', 'page'), ['type' => 'memorization'])) }}"
                            class="pill pill-amber {{ request('type') == 'memorization' ? 'active' : '' }}">
                            <span>📖</span> Tahfidz Al-Qur'an ({{ $stats['types']['memorization'] ?? 0 }})
                        </a>

                        <a href="{{ route('admin.report-builder.index', array_merge(request()->except('type', 'page'), ['type' => 'pesantren'])) }}"
                            class="pill pill-sky {{ request('type') == 'pesantren' ? 'active' : '' }}">
                            <span>🏛️</span> Kepesantrenan ({{ $stats['types']['pesantren'] ?? 0 }})
                        </a>

                        <a href="{{ route('admin.report-builder.index', array_merge(request()->except('type', 'page'), ['type' => 'combined'])) }}"
                            class="pill pill-purple {{ request('type') == 'combined' ? 'active' : '' }}">
                            <span>🌟</span> Gabungan / Komprehensif ({{ $stats['types']['combined'] ?? 0 }})
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TEMPLATES CONTAINER: GRID VIEW -->
        <!-- ======================================================== -->
        <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-200"
            class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">

            @forelse($templates as $t)
                @php
                    $config = $t->configuration ?? [];
                    $sections = $config['sections'] ?? [];
                    $sectionCount = count($sections);
                    $pageSettings = $config['page'] ?? [];
                    $paperSize = $t->paper_size ?? ($pageSettings['size'] ?? 'A4');
                    $orientation = $t->orientation ?? ($pageSettings['orientation'] ?? 'portrait');
                    $isPortrait = $orientation === 'portrait';
                    $dim = $t->paper_dimensions ?? ['width' => 210, 'height' => 297];
                    $globalStyle = $config['globalStyle'] ?? [];
                    $fontFamily = $globalStyle['fontFamily'] ?? 'Times New Roman';
                    $fontSize = $globalStyle['fontSize'] ?? '12';

                    $typeBadges = [
                        'academic' => ['label' => 'Akademik', 'icon' => '🎓', 'cls' => 'badge-soft-indigo'],
                        'religious' => ['label' => 'Keagamaan', 'icon' => '🕌', 'cls' => 'badge-soft-emerald'],
                        'memorization' => ['label' => 'Tahfidz', 'icon' => '📖', 'cls' => 'badge-soft-amber'],
                        'pesantren' => ['label' => 'Kepesantrenan', 'icon' => '🏛️', 'cls' => 'badge-soft-sky'],
                        'combined' => ['label' => 'Gabungan', 'icon' => '🌟', 'cls' => 'badge-soft-purple'],
                    ];
                    $typeMeta = $typeBadges[$t->type] ?? [
                        'label' => ucfirst($t->type),
                        'icon' => '📄',
                        'cls' => 'badge-soft-slate',
                    ];
                @endphp

                <div class="col">
                    <div class="card template-card h-100 border rounded-4 overflow-hidden d-flex flex-column"
                        style="border-color:rgba(226,232,240,0.9) !important;">

                        <!-- Card Header -->
                        <div class="p-4 pb-2">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                <span class="badge rounded-pill {{ $typeMeta['cls'] }} fw-semibold px-3 py-2"
                                    style="font-size:12px;">
                                    <span>{{ $typeMeta['icon'] }}</span> {{ $typeMeta['label'] }}
                                </span>

                                <div class="d-flex align-items-center gap-2">
                                    @if ($t->is_default)
                                        <span class="badge rounded-pill fw-bold px-2 py-1"
                                            style="background:#f59e0b; color:#fff; font-size:10px; box-shadow:0 0 0 2px #fde68a;">
                                            ⭐ Default
                                        </span>
                                    @endif

                                    @if ($t->is_active)
                                        <span class="badge rounded-pill badge-soft-emerald fw-semibold"
                                            style="font-size:11px;">
                                            <span class="d-inline-block rounded-circle bg-success me-1"
                                                style="width:6px; height:6px;"></span> Aktif
                                        </span>
                                    @else
                                        <span class="badge rounded-pill fw-medium"
                                            style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; font-size:11px;">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Template Title & Description -->
                            <a href="{{ route('admin.report-builder.builder', $t->id) }}"
                                class="d-block fw-bold text-dark text-decoration-none line-clamp-1 mb-1"
                                style="font-size:1.05rem;">
                                {{ $t->name }}
                            </a>
                            <p class="text-muted line-clamp-2 mb-0"
                                style="font-size:12px; line-height:1.55; min-height:32px;">
                                {{ $t->description ?: 'Template rapor santri dengan tata letak visual kustom.' }}
                            </p>
                        </div>

                        <!-- Visual Mini Paper Preview Canvas -->
                        <div class="px-4 py-2">
                            <div class="rounded-4 p-3 d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden"
                                style="background:#f1f5f9; border:1px solid rgba(226,232,240,0.7);">

                                <!-- Paper specs indicator pill -->
                                <div class="w-100 d-flex align-items-center justify-content-between fw-semibold text-muted mb-2 px-1"
                                    style="font-size:10px;">
                                    <span
                                        class="d-inline-flex align-items-center gap-1 bg-white px-2 py-1 rounded-2 shadow-sm"
                                        style="border:1px solid #e2e8f0;">
                                        <span>{{ $isPortrait ? '📄' : '📃' }}</span>
                                        <span>{{ $paperSize }} · {{ $orientation }}</span>
                                    </span>
                                    <span class="text-muted" style="color:#94a3b8 !important;">
                                        {{ round($dim['width']) }} × {{ round($dim['height']) }} mm
                                    </span>
                                </div>

                                <!-- Miniature Paper Simulation -->
                                <div class="mini-paper {{ $isPortrait ? 'paper-ratio-portrait' : 'paper-ratio-landscape' }}"
                                    style="{{ $isPortrait ? 'width:7rem;' : 'width:11rem;' }}">

                                    <!-- Mini Header / Kop lines -->
                                    <div class="d-flex align-items-center gap-2 pb-2 mb-2 border-bottom">
                                        <div class="rounded"
                                            style="width:14px; height:14px; background:var(--brand-200); flex-shrink:0;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="rounded-pill mb-1" style="height:4px; background:#94a3b8;">
                                            </div>
                                            <div class="rounded-pill"
                                                style="height:2px; width:66%; background:#cbd5e1;"></div>
                                        </div>
                                    </div>

                                    <!-- Mini Biodata Lines -->
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between gap-2 mb-1">
                                            <div class="rounded-pill"
                                                style="height:2px; width:33%; background:#cbd5e1;"></div>
                                            <div class="rounded-pill"
                                                style="height:2px; width:50%; background:#cbd5e1;"></div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-2">
                                            <div class="rounded-pill"
                                                style="height:2px; width:25%; background:#e2e8f0;"></div>
                                            <div class="rounded-pill"
                                                style="height:2px; width:66%; background:#e2e8f0;"></div>
                                        </div>
                                    </div>

                                    <!-- Mini Table Simulation -->
                                    <div class="border rounded-xs overflow-hidden mb-2">
                                        <div style="height:6px; background:#e2e8f0;"></div>
                                        <div class="p-1" style="background:#f8fafc;">
                                            <div class="rounded-pill mb-1" style="height:2px; background:#cbd5e1;">
                                            </div>
                                            <div class="rounded-pill mb-1"
                                                style="height:2px; width:80%; background:#e2e8f0;"></div>
                                            <div class="rounded-pill" style="height:2px; background:#e2e8f0;"></div>
                                        </div>
                                    </div>

                                    <!-- Mini Signatures -->
                                    <div class="position-absolute d-flex justify-content-between gap-2 pt-2 border-top"
                                        style="bottom:0.5rem; left:0.5rem; right:0.5rem;">
                                        <div class="text-center" style="width:33%;">
                                            <div class="rounded-pill mb-2" style="height:2px; background:#cbd5e1;">
                                            </div>
                                            <div style="height:6px;"></div>
                                            <div class="rounded-pill mx-auto"
                                                style="height:2px; width:75%; background:#94a3b8;"></div>
                                        </div>
                                        <div class="text-center" style="width:33%;">
                                            <div class="rounded-pill mb-2" style="height:2px; background:#cbd5e1;">
                                            </div>
                                            <div style="height:6px;"></div>
                                            <div class="rounded-pill mx-auto"
                                                style="height:2px; width:75%; background:#94a3b8;"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Margins tag footer -->
                                <div class="w-100 text-center text-muted mt-2"
                                    style="font-size:10px; font-family:ui-monospace,Menlo,monospace;">
                                    Margins: T:{{ $t->margin_top }}cm · B:{{ $t->margin_bottom }}cm ·
                                    L:{{ $t->margin_left }}cm · R:{{ $t->margin_right }}cm
                                </div>
                            </div>
                        </div>

                        <!-- Card Body Details -->
                        <div class="px-4 py-3">
                            <div class="d-flex align-items-center justify-content-between text-muted mb-2"
                                style="font-size:12px;">
                                <span class="fw-medium d-inline-flex align-items-center gap-1">
                                    <svg width="14" height="14" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Komponen Seksi:
                                </span>
                                <span class="fw-semibold text-dark px-2 py-1 rounded-2" style="background:#f1f5f9;">
                                    {{ $sectionCount }} Seksi
                                </span>
                            </div>

                            <!-- Mini Section Pills -->
                            <div class="d-flex flex-wrap gap-1 mb-2" style="max-height:48px; overflow:hidden;">
                                @forelse(array_slice($sections, 0, 4) as $sec)
                                    <span class="badge rounded-2 fw-medium"
                                        style="background:#f1f5f9; color:#334155; border:1px solid rgba(226,232,240,0.8); font-size:10px;">
                                        {{ $sec['type'] ?? 'section' }}
                                    </span>
                                @empty
                                    <span class="fst-italic text-muted" style="font-size:10px;">Belum ada seksi
                                        komponen</span>
                                @endforelse

                                @if ($sectionCount > 4)
                                    <span class="badge rounded-2 fw-semibold"
                                        style="background:var(--brand-50); color:var(--brand-600); font-size:10px;">
                                        +{{ $sectionCount_4 }} lainnya
                                    </span>
                                @endif
                            </div>

                            <!-- Typography & Signatories -->
                            <div class="pt-2 border-top d-flex align-items-center justify-content-between text-muted"
                                style="font-size:11px;">
                                <span class="d-inline-flex align-items-center gap-1 text-truncate"
                                    style="font-family:ui-monospace,Menlo,monospace; max-width:140px;"
                                    title="Font: {{ $fontFamily }}">
                                    <span>🔤</span> {{ $fontFamily }} ({{ $fontSize }}pt)
                                </span>
                                <span class="d-inline-flex align-items-center gap-1 fw-medium">
                                    <span>✍️</span> {{ $t->signatories_count ?? count($t->signatories ?? []) }}
                                    Penandatangan
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="mt-auto p-4 pt-3 border-top" style="background:rgba(248,250,252,0.7);">
                            <div class="d-flex align-items-center justify-content-between text-muted mb-3"
                                style="font-size:11px;">
                                <div class="d-flex align-items-center gap-2 text-truncate" style="max-width:150px;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                        style="width:20px; height:20px; background:var(--brand-100); color:var(--brand-700); font-size:10px;">
                                        {{ substr($t->creator?->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="text-truncate fw-medium"
                                        style="color:#475569;">{{ $t->creator?->name ?? 'Admin' }}</span>
                                </div>
                                <span style="font-family:ui-monospace,Menlo,monospace;">
                                    {{ $t->updated_at ? $t->updated_at->diffForHumans() : '-' }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex align-items-center gap-2">
                                <!-- Buka Visual Builder -->
                                <a href="{{ route('admin.report-builder.builder', $t->id) }}"
                                    class="btn btn-brand flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2 rounded-3 fw-bold py-2"
                                    style="font-size:12px;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Buka Builder</span>
                                </a>

                                <!-- Preview -->
                                <button type="button"
                                    @click="openPreview({{ $t->id }}, '{{ addslashes($t->name) }}', '{{ route('admin.report-builder.preview-render', $t->id) }}', '{{ route('admin.report-builder.builder', $t->id) }}')"
                                    class="btn btn-light border rounded-3 p-2 text-muted"
                                    title="Pratinjau Cepat Template">
                                    <svg width="16" height="16" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                <!-- Dropdown More Actions -->
                                <div class="position-relative" x-data="{ open: false }">
                                    <button type="button" @click="open = !open"
                                        class="btn btn-light border rounded-3 p-2 text-muted">
                                        <svg width="16" height="16" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.outside="open = false" x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        class="dropdown-custom position-absolute"
                                        style="right:0; bottom:100%; margin-bottom:0.5rem; z-index:30;">

                                        <form action="{{ route('admin.report-builder.duplicate', $t->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit">
                                                <span>📋</span> Duplikasi Template
                                            </button>
                                        </form>

                                        @if (!$t->is_default)
                                            <form action="{{ route('admin.report-builder.set-default', $t->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" style="color:#b45309;">
                                                    <span>⭐</span> Jadikan Default
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.report-builder.toggle', $t->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit">
                                                <span>{{ $t->is_active ? '⏸️' : '▶️' }}</span>
                                                <span>{{ $t->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                            </button>
                                        </form>

                                        <div class="border-top my-1"></div>

                                        <button type="button"
                                            @click="open = false; confirmDelete({{ $t->id }}, '{{ addslashes($t->name) }}')"
                                            style="color:#e11d48;">
                                            <span>🗑️</span> Hapus Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-12">
                    <div class="card border-0 rounded-4 shadow-sm text-center">
                        <div class="card-body p-5">
                            <div class="rounded-4 d-flex align-items-center justify-content-center mx-auto mb-4"
                                style="width:64px; height:64px; background:#eef2ff; color:var(--brand-600); font-size:1.75rem;">
                                📄
                            </div>
                            <h3 class="fw-bold text-dark mb-2" style="font-size:1.15rem;">Tidak Ada Template yang
                                Ditemukan</h3>
                            <p class="text-muted mx-auto mb-4" style="max-width:28rem; font-size:14px;">
                                Belum ada template rapor yang sesuai dengan kriteria filter atau pencarian Anda. Buat
                                template baru sekarang atau sesuaikan filter.
                            </p>
                            <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
                                <a href="{{ route('admin.report-builder.builder') }}"
                                    class="btn btn-brand rounded-3 fw-semibold px-4 py-2">
                                    + Buat Template Pertama
                                </a>
                                @if (request()->hasAny(['search', 'type', 'paper_size', 'status']))
                                    <a href="{{ route('admin.report-builder.index') }}"
                                        class="btn rounded-3 fw-semibold px-4 py-2"
                                        style="background:#f1f5f9; color:#475569;">
                                        Bersihkan Filter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- ======================================================== -->
        <!-- TEMPLATES CONTAINER: TABLE VIEW -->
        <!-- ======================================================== -->
        <div x-show="viewMode === 'table'" x-cloak x-transition:enter="transition ease-out duration-200"
            class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8fafc;">
                        <tr class="border-bottom text-uppercase text-muted fw-bold"
                            style="font-size:11px; letter-spacing:0.06em;">
                            <th class="py-3 px-4">Template Rapor</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3">Kertas &amp; Margin</th>
                            <th class="py-3 px-3">Seksi Komponen</th>
                            <th class="py-3 px-3">Penandatangan</th>
                            <th class="py-3 px-3">Status &amp; Default</th>
                            <th class="py-3 px-3">Pembuat &amp; Tanggal</th>
                            <th class="py-3 px-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $t)
                            @php
                                $config = $t->configuration ?? [];
                                $sections = $config['sections'] ?? [];
                                $sectionCount = count($sections);
                                $pageSettings = $config['page'] ?? [];
                                $paperSize = $t->paper_size ?? ($pageSettings['size'] ?? 'A4');
                                $orientation = $t->orientation ?? ($pageSettings['orientation'] ?? 'portrait');
                                $dim = $t->paper_dimensions ?? ['width' => 210, 'height' => 297];

                                $typeBadges = [
                                    'academic' => ['label' => 'Akademik', 'icon' => '🎓', 'cls' => 'badge-soft-indigo'],
                                    'religious' => [
                                        'label' => 'Keagamaan',
                                        'icon' => '🕌',
                                        'cls' => 'badge-soft-emerald',
                                    ],
                                    'memorization' => [
                                        'label' => 'Tahfidz',
                                        'icon' => '📖',
                                        'cls' => 'badge-soft-amber',
                                    ],
                                    'pesantren' => ['label' => 'Pesantren', 'icon' => '🏛️', 'cls' => 'badge-soft-sky'],
                                    'combined' => ['label' => 'Gabungan', 'icon' => '🌟', 'cls' => 'badge-soft-purple'],
                                ];
                                $typeMeta = $typeBadges[$t->type] ?? [
                                    'label' => ucfirst($t->type),
                                    'icon' => '📄',
                                    'cls' => 'badge-soft-slate',
                                ];
                            @endphp
                            <tr>
                                <!-- Template Name & ID -->
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <a href="{{ route('admin.report-builder.builder', $t->id) }}"
                                            class="fw-bold text-dark text-decoration-none">
                                            {{ $t->name }}
                                        </a>
                                        <span class="badge rounded-2"
                                            style="background:#f1f5f9; color:#64748b; font-size:10px; font-family:ui-monospace,Menlo,monospace;">
                                            ID: #{{ $t->id }}
                                        </span>
                                    </div>
                                    <p class="mb-0 text-muted line-clamp-1" style="font-size:12px; max-width:20rem;">
                                        {{ $t->description ?: 'Format rapor siswa kustom.' }}
                                    </p>
                                </td>

                                <!-- Category -->
                                <td class="py-3 px-3 text-nowrap">
                                    <span class="badge rounded-pill {{ $typeMeta['cls'] }} fw-semibold px-3 py-2"
                                        style="font-size:12px;">
                                        <span>{{ $typeMeta['icon'] }}</span> {{ $typeMeta['label'] }}
                                    </span>
                                </td>

                                <!-- Paper & Margins -->
                                <td class="py-3 px-3">
                                    <div class="d-flex align-items-center gap-2 fw-bold text-dark mb-1"
                                        style="font-size:12px;">
                                        <span>{{ $orientation === 'portrait' ? '📄' : '📃' }}</span>
                                        <span>{{ $paperSize }}</span>
                                        <span class="text-muted fw-normal">({{ ucfirst($orientation) }})</span>
                                    </div>
                                    <div class="text-muted"
                                        style="font-size:11px; font-family:ui-monospace,Menlo,monospace;">
                                        {{ round($dim['width']) }}×{{ round($dim['height']) }}mm |
                                        T:{{ $t->margin_top }} L:{{ $t->margin_left }}
                                    </div>
                                </td>

                                <!-- Sections -->
                                <td class="py-3 px-3">
                                    <div class="fw-bold text-dark mb-1" style="font-size:12px;">
                                        <span>📦</span> {{ $sectionCount }} Seksi
                                    </div>
                                    <div class="d-flex flex-wrap gap-1" style="max-width:200px;">
                                        @foreach (array_slice($sections, 0, 3) as $sec)
                                            <span class="badge rounded-2"
                                                style="background:#f1f5f9; color:#475569; font-size:10px;">
                                                {{ $sec['type'] ?? 'sec' }}
                                            </span>
                                        @endforeach
                                        @if ($sectionCount > 3)
                                            <span class="text-muted fw-medium" style="font-size:10px;">
                                                +{{ $sectionCount_3 }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Signatories -->
                                <td class="py-3 px-3 text-nowrap">
                                    <div class="fw-medium text-dark d-inline-flex align-items-center gap-1"
                                        style="font-size:12px;">
                                        <span>✍️</span> {{ $t->signatories_count ?? count($t->signatories ?? []) }}
                                        Pejabat
                                    </div>
                                </td>

                                <!-- Status & Default -->
                                <td class="py-3 px-3 text-nowrap">
                                    <div class="d-flex flex-column align-items-start gap-1">
                                        @if ($t->is_default)
                                            <span class="badge rounded-pill fw-bold px-2 py-1"
                                                style="background:#f59e0b; color:#fff; font-size:10px;">
                                                ⭐ Default
                                            </span>
                                        @endif

                                        <form action="{{ route('admin.report-builder.toggle', $t->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" title="Klik untuk mengubah status"
                                                class="badge rounded-pill border-0 fw-semibold px-3 py-2 d-inline-flex align-items-center gap-1"
                                                style="font-size:11px; {{ $t->is_active ? 'background:#ecfdf5; color:#047857; border:1px solid #a7f3d0 !important;' : 'background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0 !important;' }}">
                                                <span
                                                    class="rounded-circle {{ $t->is_active ? 'bg-success' : 'bg-secondary' }}"
                                                    style="width:6px; height:6px;"></span>
                                                <span>{{ $t->is_active ? 'Aktif' : 'Draft' }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                                <!-- Creator & Date -->
                                <td class="py-3 px-3 text-nowrap" style="font-size:12px; color:#64748b;">
                                    <div class="fw-medium text-dark">{{ $t->creator?->name ?? 'Admin' }}</div>
                                    <div style="font-size:11px;">
                                        {{ $t->updated_at ? $t->updated_at->diffForHumans() : '-' }}</div>
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-3 px-4 text-end text-nowrap">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('admin.report-builder.builder', $t->id) }}"
                                            class="btn btn-brand btn-sm rounded-3 fw-bold d-inline-flex align-items-center gap-1"
                                            style="font-size:12px;">
                                            <span>🎨</span> Builder
                                        </a>

                                        <button type="button"
                                            @click="openPreview({{ $t->id }}, '{{ addslashes($t->name) }}', '{{ route('admin.report-builder.preview-render', $t->id) }}', '{{ route('admin.report-builder.builder', $t->id) }}')"
                                            class="btn btn-link btn-sm p-2 text-muted" title="Pratinjau">
                                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        <form action="{{ route('admin.report-builder.duplicate', $t->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" title="Duplikasi Template"
                                                class="btn btn-link btn-sm p-2 text-muted">
                                                <svg width="16" height="16" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </form>

                                        <button type="button"
                                            @click="confirmDelete({{ $t->id }}, '{{ addslashes($t->name) }}')"
                                            title="Hapus Template" class="btn btn-link btn-sm p-2"
                                            style="color:#e11d48;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-5 text-center text-muted">
                                    Tidak ada data template rapor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- PAGINATION -->
        <!-- ======================================================== -->
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-2">
            <div class="text-muted fw-medium" style="font-size:12px;">
                Menampilkan <span class="fw-bold text-dark">{{ $templates->firstItem() ?? 0 }}</span> -
                <span class="fw-bold text-dark">{{ $templates->lastItem() ?? 0 }}</span> dari
                <span class="fw-bold text-dark">{{ $templates->total() }}</span> total template
            </div>
            <div>
                {{ $templates->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </main>

    <!-- ======================================================== -->
    <!-- MODAL: LIVE PREVIEW TEMPLATE -->
    <!-- ======================================================== -->
    <div x-show="previewModalOpen" x-cloak class="custom-backdrop"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-4xl shadow-lg border w-100 d-flex flex-column overflow-hidden"
            style="max-width:1024px; height:88vh; border-color:rgba(226,232,240,0.9) !important;"
            @click.outside="closePreview()">

            <!-- Modal Header -->
            <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between"
                style="background:rgba(248,250,252,0.7);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fw-bold"
                        style="width:36px; height:36px; background:var(--brand-100); color:var(--brand-700);">
                        👁️
                    </div>
                    <div>
                        <h3 class="fw-bold text-dark mb-0" style="font-size:1rem;"
                            x-text="'Pratinjau: ' + currentPreview.name"></h3>
                        <p class="mb-0 text-muted" style="font-size:12px;">
                            Tampilan render hasil kompilasi template dengan data santri sampel
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a :href="'{{ url('admin/report-builder') }}/' + currentPreview.id + '/preview-pdf'"
                        target="_blank"
                        class="btn btn-outline-success rounded-3 fw-bold d-inline-flex align-items-center gap-1 px-3 py-2"
                        style="font-size:12px;">
                        <span>📄 Cetak PDF</span>
                    </a>

                    <a :href="currentPreview.builderUrl"
                        class="btn btn-brand rounded-3 fw-bold d-inline-flex align-items-center gap-2 px-3 py-2"
                        style="font-size:12px;">
                        <span>🎨 Buka di Builder</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>

                    <button type="button" @click="closePreview()"
                        class="btn rounded-3 fw-bold d-flex align-items-center justify-content-center"
                        style="width:32px; height:32px; background:#f1f5f9; color:#64748b;">
                        ✕
                    </button>
                </div>
            </div>

            <!-- Modal Body (Iframe) -->
            <div class="flex-grow-1 position-relative overflow-hidden d-flex align-items-center justify-content-center p-3"
                style="background:rgba(226,232,240,0.6);">
                <div x-show="previewLoading"
                    class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-3"
                    style="background:rgba(241,245,249,0.9); z-index:10;">
                    <div class="spinner-border text-brand-600" role="status"
                        style="width:2.5rem; height:2.5rem; color:var(--brand-600) !important; border-width:4px;">
                    </div>
                    <p class="fw-semibold text-muted mb-0" style="font-size:12px;">Memuat pratinjau dokumen rapor...
                    </p>
                </div>

                <iframe :src="currentPreview.url" @load="previewLoading = false"
                    class="w-100 h-100 rounded-4 bg-white shadow border"
                    style="border-color:#cbd5e1 !important;"></iframe>
            </div>

            <!-- Modal Footer -->
            <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between"
                style="background:#f8fafc; font-size:12px; color:#64748b;">
                <span>💡 Dokumen di atas dirender secara otomatis berdasarkan struktur JSON dan data santri
                    sampel.</span>
                <button type="button" @click="closePreview()"
                    class="btn bg-white border rounded-3 fw-semibold px-3 py-2"
                    style="font-size:12px; color:#334155;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL: KONFIRMASI HAPUS TEMPLATE -->
    <!-- ======================================================== -->
    <div x-show="deleteModalOpen" x-cloak class="custom-backdrop"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-4xl shadow-lg border p-4 text-center"
            style="max-width:28rem; width:100%; border-color:rgba(226,232,240,0.9) !important;"
            @click.outside="deleteModalOpen = false">

            <div class="rounded-4 d-flex align-items-center justify-content-center mx-auto mb-3"
                style="width:56px; height:56px; background:#fff1f2; color:#e11d48; font-size:1.5rem;">
                🗑️
            </div>

            <h3 class="fw-bold text-dark mb-2" style="font-size:1.15rem;">Hapus Template Rapor?</h3>
            <p class="text-muted mb-4" style="font-size:12px; line-height:1.6;">
                Apakah Anda yakin ingin menghapus template
                <span class="fw-bold text-dark" x-text="'&quot;' + deleteTarget.name + '&quot;'"></span>?
                Tindakan ini tidak dapat dibatalkan.
            </p>

            <form :action="deleteActionUrl" method="POST" class="d-flex align-items-center gap-3">
                @csrf
                @method('DELETE')

                <button type="button" @click="deleteModalOpen = false"
                    class="btn flex-fill rounded-3 fw-semibold py-2 bg-white border"
                    style="font-size:12px; color:#334155;">
                    Batal
                </button>

                <button type="submit" class="btn flex-fill rounded-3 fw-semibold py-2 text-white"
                    style="font-size:12px; background:#e11d48; box-shadow:0 4px 14px rgba(225,29,72,0.2);">
                    Ya, Hapus Sekarang
                </button>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- ALPINE JS LOGIC SCRIPT -->
    <!-- ======================================================== -->
    <script>
        function templateIndexData() {
            return {
                viewMode: 'grid',
                previewModalOpen: false,
                previewLoading: true,
                currentPreview: {
                    id: null,
                    name: '',
                    url: '',
                    builderUrl: '',
                },
                deleteModalOpen: false,
                deleteTarget: {
                    id: null,
                    name: '',
                },
                get deleteActionUrl() {
                    return '{{ url('admin/report-builder') }}/' + this.deleteTarget.id;
                },
                openPreview(id, name, previewUrl, builderUrl) {
                    this.currentPreview.id = id;
                    this.currentPreview.name = name;
                    this.currentPreview.url = previewUrl;
                    this.currentPreview.builderUrl = builderUrl;
                    this.previewLoading = true;
                    this.previewModalOpen = true;
                },
                closePreview() {
                    this.previewModalOpen = false;
                    this.currentPreview.url = '';
                },
                confirmDelete(id, name) {
                    this.deleteTarget.id = id;
                    this.deleteTarget.name = name;
                    this.deleteModalOpen = true;
                }
            };
        }
    </script>

</body>

</html>
