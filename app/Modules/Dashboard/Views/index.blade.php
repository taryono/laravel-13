@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <style>
        :root {
            --c-primary: #021064;
            --c-primary-container: #1e2a78;
            --c-secondary: #006a61;
            --c-secondary-container: #86f2e4;
            --c-secondary-fixed: #89f5e7;
            --c-tertiary-fixed: #ffdea2;
            --c-surface: #f9f9ff;
            --c-surface-container: #e7eeff;
            --c-surface-container-high: #dee9ff;
            --c-surface-variant: #d8e3f9;
            --c-on-surface: #111c2c;
            --c-on-surface-variant: #454651;
            --c-outline: #767682;
            --c-outline-variant: #c6c5d3;
            --c-error: #ba1a1a;
            --c-error-container: #ffdad6;
            --c-card-bg: #ffffff;
            --c-card-border: rgba(198, 197, 211, 0.45);
        }

        body.dark-mode {
            --c-primary: #8ab4f8;
            --c-primary-container: #1e2a78;
            --c-secondary: #86f2e4;
            --c-secondary-container: #004d46;
            --c-surface: #121212;
            --c-surface-container: #1e1e1e;
            --c-surface-container-high: #262626;
            --c-surface-variant: #2d2d2d;
            --c-on-surface: #f1f1f1;
            --c-on-surface-variant: #a0a8ab;
            --c-outline: #8c8c98;
            --c-outline-variant: #3a3a46;
            --c-card-bg: #1e1e1e;
            --c-card-border: rgba(255, 255, 255, 0.1);
        }

        /* Base Typography using fluid clamp() */
        .t-headline-lg {
            font-size: clamp(1.25rem, 1.05rem + 0.9vw, 1.75rem);
            line-height: 1.25;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .t-headline-md {
            font-size: clamp(1.125rem, 0.98rem + 0.55vw, 1.375rem);
            line-height: 1.3;
            font-weight: 600;
        }

        .t-title-sm {
            font-size: clamp(0.9375rem, 0.88rem + 0.25vw, 1.0625rem);
            line-height: 1.4;
            font-weight: 600;
        }

        .t-label-md {
            font-size: 0.8125rem;
            line-height: 1.25rem;
            font-weight: 600;
        }

        .t-label-sm {
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: 600;
        }

        .t-body-sm {
            font-size: 0.8125rem;
            line-height: 1.25rem;
            font-weight: 400;
        }

        .t-display {
            font-size: clamp(1.75rem, 1.35rem + 1.6vw, 2.5rem);
            line-height: 1.1;
            font-weight: 800;
        }

        /* Material Icons */
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            display: inline-block;
            white-space: nowrap;
            -webkit-font-smoothing: antialiased;
        }

        .icon-14 {
            font-size: 14px;
        }

        .icon-16 {
            font-size: 16px;
        }

        .icon-20 {
            font-size: 20px;
        }

        .icon-22 {
            font-size: 22px;
        }

        .icon-26 {
            font-size: 26px;
        }

        /* Typography helpers */
        .text-primary-edu {
            color: var(--c-primary) !important;
        }

        .text-secondary-edu {
            color: var(--c-secondary) !important;
        }

        .text-variant {
            color: var(--c-on-surface-variant) !important;
        }

        .dot-secondary {
            width: 8px;
            height: 8px;
            background: var(--c-secondary);
        }

        .badge-school {
            background: var(--c-surface-container);
            color: var(--c-primary);
            border: 1px solid var(--c-outline-variant);
            font-size: 0.75rem;
        }

        body.dark-mode .badge-school {
            background: #252535;
            color: #8ab4f8;
            border-color: rgba(255, 255, 255, 0.12);
        }

        /* Search input styling */
        .search-wrap {
            position: relative;
        }

        .search-wrap .icon-left {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--c-outline);
        }

        .search-wrap .kbd-right {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--c-surface-container);
            color: var(--c-on-surface-variant);
            padding: 2px 7px;
            border-radius: 4px;
            border: 1px solid var(--c-outline-variant);
        }

        .search-wrap .form-control {
            height: 44px;
            padding-left: 2.5rem;
            padding-right: 3rem;
            border-radius: 0.75rem;
            border-color: var(--c-card-border);
            background-color: var(--c-card-bg);
            color: var(--c-on-surface);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .search-wrap .form-control:focus {
            border-color: var(--c-secondary);
            box-shadow: 0 0 0 0.2rem rgba(0, 106, 97, 0.25);
            background-color: var(--c-card-bg);
            color: var(--c-on-surface);
        }

        /* Banner presensi */
        .banner {
            position: relative;
            overflow: hidden;
            color: #fff;
            background: linear-gradient(135deg, var(--c-primary-container), var(--c-primary) 50%, #010a3d);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .banner-chip {
            background: rgba(0, 106, 97, 0.35);
            color: var(--c-secondary-fixed);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(137, 245, 231, 0.2);
        }

        .banner-stats {
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }

        .banner-stat {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: background 0.15s ease;
        }

        .banner-stat:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .banner-blur {
            position: absolute;
            right: -32px;
            bottom: -32px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(134, 242, 228, 0.25);
            filter: blur(30px);
            pointer-events: none;
        }

        .text-sf {
            color: var(--c-secondary-fixed);
        }

        .text-sv {
            color: var(--c-surface-variant);
        }

        .text-tf {
            color: var(--c-tertiary-fixed);
        }

        .text-errc {
            color: var(--c-error-container);
        }

        /* Module Card Grid */
        .pill-soft {
            background: var(--c-surface-container);
            color: var(--c-on-surface-variant);
            border: 1px solid var(--c-outline-variant);
        }

        .menu-section {
            container-type: inline-size;
            container-name: menu-section;
        }

        /* Responsive Grid: Fluid scaling across mobile, tablet, and desktop */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        @media (min-width: 400px) {
            .menu-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.75rem;
            }
        }

        @media (min-width: 576px) {
            .menu-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 0.875rem;
            }
        }

        @media (min-width: 992px) {
            .menu-grid {
                grid-template-columns: repeat(5, 1fr);
                gap: 1rem;
            }
        }

        /* Container Query for self-adapting columns */
        @container menu-section (min-width: 380px) {
            .menu-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @container menu-section (min-width: 540px) {
            .menu-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @container menu-section (min-width: 660px) {
            .menu-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        .menu-card {
            background: var(--c-card-bg);
            border: 1px solid var(--c-card-border);
            color: var(--c-on-surface);
            text-decoration: none;
            min-height: 44px;
            transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            outline: none;
        }

        .menu-card:hover {
            border-color: rgba(0, 106, 97, 0.5);
            box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.08) !important;
            transform: translateY(-2px);
            color: var(--c-on-surface);
        }

        .menu-card:active {
            transform: scale(0.97);
        }

        .menu-card:focus-visible {
            outline: 2px solid var(--c-secondary);
            outline-offset: 2px;
        }

        .menu-icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .menu-card:hover .menu-icon {
            transform: scale(1.08);
        }

        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            line-clamp: 1;
        }

        /* Tone Palettes */
        .tone-emerald {
            background: #ecfdf5;
            color: #047857;
        }

        .tone-blue {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .tone-amber {
            background: #fffbeb;
            color: #b45309;
        }

        .tone-sky {
            background: #f0f9ff;
            color: #0369a1;
        }

        .tone-purple {
            background: #faf5ff;
            color: #7e22ce;
        }

        .tone-rose {
            background: #fff1f2;
            color: #be123c;
        }

        .tone-indigo {
            background: #eef2ff;
            color: #4338ca;
        }

        .tone-cyan {
            background: #ecfeff;
            color: #0e7490;
        }

        .tone-teal {
            background: #f0fdfa;
            color: #0f766e;
        }

        .tone-orange {
            background: #fff7ed;
            color: #c2410c;
        }

        body.dark-mode .tone-emerald {
            background: #064e3b;
            color: #6ee7b7;
        }

        body.dark-mode .tone-blue {
            background: #1e3a8a;
            color: #93c5fd;
        }

        body.dark-mode .tone-amber {
            background: #78350f;
            color: #fde68a;
        }

        body.dark-mode .tone-sky {
            background: #0c4a6e;
            color: #7dd3fc;
        }

        body.dark-mode .tone-purple {
            background: #581c87;
            color: #d8b4fe;
        }

        body.dark-mode .tone-rose {
            background: #881337;
            color: #fda4af;
        }

        body.dark-mode .tone-indigo {
            background: #312e81;
            color: #a5b4fc;
        }

        body.dark-mode .tone-cyan {
            background: #164e63;
            color: #67e8f9;
        }

        body.dark-mode .tone-teal {
            background: #134e4a;
            color: #5eead4;
        }

        body.dark-mode .tone-orange {
            background: #7c2d12;
            color: #fdba74;
        }

        /* Agenda Section & Sticky Desktop Behavior */
        .sticky-sidebar {
            position: relative;
        }

        @media (min-width: 992px) {
            .sticky-sidebar {
                position: sticky;
                top: 5.5rem;
                z-index: 10;
            }
        }

        .agenda-card {
            background: var(--c-card-bg);
            border: 1px solid var(--c-card-border);
            transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .agenda-card:hover {
            box-shadow: 0 0.35rem 1rem rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }

        .agenda-icon {
            padding: 0.625rem;
            flex-shrink: 0;
        }

        .border-l-purple {
            border-left: 4px solid #9333ea !important;
        }

        .border-l-amber {
            border-left: 4px solid #f59e0b !important;
        }

        .border-l-sky {
            border-left: 4px solid #0ea5e9 !important;
        }

        .badge-purple {
            background: #f3e8ff;
            color: #581c87;
        }

        .badge-amber {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-sky {
            background: #e0f2fe;
            color: #075985;
        }

        body.dark-mode .badge-purple {
            background: #581c87;
            color: #f3e8ff;
        }

        body.dark-mode .badge-amber {
            background: #78350f;
            color: #fef3c7;
        }

        body.dark-mode .badge-sky {
            background: #0c4a6e;
            color: #e0f2fe;
        }

        /* Bottom Nav Item */
        .nav-item-edu {
            color: var(--c-on-surface-variant);
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.75rem;
            min-height: 44px;
            transition: background 0.15s, transform 0.15s;
        }

        .nav-item-edu:hover {
            background: var(--c-surface-container-high);
            color: var(--c-on-surface-variant);
        }

        .nav-item-edu:active {
            transform: scale(0.95);
        }

        .nav-item-edu.active {
            background: var(--c-primary-container);
            color: #fff;
        }
    </style>

    {{-- ========== GREETING & SEARCH (Responsive Header) ========== --}}
    <section class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
                    @if (isset($school->name))
                        <span
                            class="badge-school d-inline-flex align-items-center gap-1 px-2.5 py-0.5 rounded-pill t-label-sm">
                            <span class="material-symbols-outlined icon-14">school</span>
                            {{ $school->name }}
                        </span>
                    @endif
                    <span class="t-body-sm text-variant d-inline-flex align-items-center gap-1">
                        <span class="dot-secondary d-inline-block rounded-circle"></span>
                        {{ $today }}
                    </span>
                </div>
                <h1 class="t-headline-lg text-primary-edu mb-1">
                    {{ $greeting }}, {{ $user->name }}
                </h1>
                <p class="t-label-md text-variant mb-0">{{ $user->role }}</p>
            </div>

            <div class="search-wrap flex-grow-1 flex-md-grow-0" style="min-width: 280px; max-width: 400px; width: 100%;">
                <span class="material-symbols-outlined icon-20 icon-left">search</span>
                <input type="text" class="form-control" placeholder="Cari data siswa, modul, laporan..."
                    aria-label="Cari data siswa, modul, laporan">
                <span class="kbd-right t-label-sm">⌘K</span>
            </div>
        </div>
    </section>

    {{-- ========== MAIN CONTENT (Desktop: 2 Columns / Mobile: Stacked) ========== --}}
    <div class="row g-4">
        {{-- KOLOM UTAMA (KIRI): Presensi & Menu Utama --}}
        <div class="col-12 col-lg-8">
            {{-- Banner Presensi --}}
            <section class="banner rounded-4 p-3 p-md-4 shadow-sm mb-4">
                <div class="position-relative" style="z-index: 1">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                        <div>
                            <span
                                class="banner-chip d-inline-flex align-items-center gap-1 px-2.5 py-1 rounded-pill t-label-sm">
                                <span class="material-symbols-outlined icon-14">calendar_month</span>
                                {{ $attendance->academic_year }}
                            </span>
                            <h2 class="t-title-sm text-white pt-2 mb-0">Presensi Siswa Hari Ini</h2>
                        </div>
                        <div class="text-start text-sm-end">
                            <span
                                class="t-display text-sf d-block lh-1">{{ number_format($attendance->percentage, 1, ',', '.') }}%</span>
                            <p class="t-label-sm text-sv mb-0 mt-1" style="opacity:.9">Target: {{ $attendance->target }}%
                            </p>
                        </div>
                    </div>

                    <div class="banner-stats row g-2 g-sm-3 text-center mt-3 pt-3 mx-0">
                        <div class="col-4 ps-0">
                            <div class="banner-stat rounded-3 p-2 p-sm-2.5">
                                <span
                                    class="t-title-sm text-white d-block">{{ number_format($attendance->present, 0, ',', '.') }}</span>
                                <span class="t-label-sm text-sv d-block">Siswa Hadir</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="banner-stat rounded-3 p-2 p-sm-2.5">
                                <span class="t-title-sm text-tf d-block">{{ $attendance->permit }}</span>
                                <span class="t-label-sm text-sv d-block">Izin / Sakit</span>
                            </div>
                        </div>
                        <div class="col-4 pe-0">
                            <div class="banner-stat rounded-3 p-2 p-sm-2.5">
                                <span class="t-title-sm text-errc d-block">{{ $attendance->absent }}</span>
                                <span class="t-label-sm text-sv d-block">Alpha</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="banner-blur"></div>
            </section>

            {{-- Menu Utama --}}
            <section class="menu-section mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h2 class="t-headline-md text-primary-edu mb-0">Menu Utama</h2>
                        <p class="t-body-sm text-variant mb-0">Akses modul operasional sekolah</p>
                    </div>
                    <span class="pill-soft px-2.5 py-1 rounded-pill t-label-sm">{{ $menus->count() }} Modul</span>
                </div>

                <div class="menu-grid">
                    @foreach ($menus as $menu)
                        <a href="{{ $menu->url }}"
                            class="menu-card d-flex flex-column align-items-center text-center p-3 rounded-4 shadow-sm">
                            <div
                                class="menu-icon tone-{{ $menu->tone }} rounded-3 d-flex align-items-center justify-content-center mb-2">
                                <span class="material-symbols-outlined icon-26">{{ $menu->icon }}</span>
                            </div>
                            <span class="t-label-md lh-sm">{{ $menu->title }}</span>
                            <span class="t-label-sm text-variant mt-1 line-clamp-1">{{ $menu->subtitle }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- KOLOM SIDEBAR (KANAN): Agenda & Info Penting (Sticky di Desktop) --}}
        <div class="col-12 col-lg-4">
            <aside class="sticky-sidebar">
                <section>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="t-headline-md text-primary-edu mb-0">Agenda &amp; Info Penting</h2>
                        <a href="#"
                            class="t-label-md text-secondary-edu text-decoration-none d-flex align-items-center gap-1">
                            Lihat Semua
                            <span class="material-symbols-outlined icon-16">chevron_right</span>
                        </a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @foreach ($agendas as $agenda)
                            <div
                                class="agenda-card border-l-{{ $agenda->tone }} rounded-4 shadow-sm p-3 d-flex align-items-start gap-3">
                                <div class="agenda-icon tone-{{ $agenda->tone }} rounded-3">
                                    <span class="material-symbols-outlined icon-22">{{ $agenda->icon }}</span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span
                                            class="badge-{{ $agenda->tone }} px-2 py-0.5 rounded-pill t-label-sm">{{ $agenda->badge }}</span>
                                        <span class="t-label-sm text-variant">{{ $agenda->time }}</span>
                                    </div>
                                    <h3 class="t-title-sm mb-1">{{ $agenda->title }}</h3>
                                    <p class="t-body-sm text-variant mb-0">{{ $agenda->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection
