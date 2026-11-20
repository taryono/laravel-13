@extends('layouts.front')

@section('content')
    <!-- HERO -->
    <style>
        /* ============================================
            VARIABEL DASAR (akan berubah sesuai tema)
        ============================================ */
        :root {
            --brand-blue: #2f5ce0;
            --brand-blue-dark: #1d3fa8;
            --brand-blue-rgb: 47, 92, 224;

            /* Warna untuk light mode (default) */
            --bg-body: #ffffff;
            --bg-soft: #f2f5f7;
            --text-primary: #101426;
            --text-muted: #5b6478;
            --border-color: #e6e9ee;
            --card-shadow: rgba(20, 30, 60, 0.08);
            --hero-gradient-start: #16234a;
            --hero-gradient-mid: #101a38;
            --hero-gradient-end: #0c1530;
            --hero-text: #ffffff;
            --hero-muted: #c7cede;
            --navbar-bg: #ffffff;
            --footer-bg: #0d1226;
            --footer-text: #aab1c5;
            --footer-border: rgba(255, 255, 255, .08);
            --input-bg: rgba(255, 255, 255, .06);
            --input-border: rgba(255, 255, 255, .15);
            --input-placeholder: #8b93a9;
            --video-overlay: rgba(10, 14, 28, .55);
        }

        /* ============================================
                                                                           MODE GELAP
                                                                        ============================================ */
        [data-bs-theme="dark"] {
            --bg-body: #0a0e1a;
            --bg-soft: #151d2e;
            --text-primary: #e8edf5;
            --text-muted: #98a1b5;
            --border-color: #2a3348;
            --card-shadow: rgba(0, 0, 0, 0.3);
            --hero-gradient-start: #0a0e1a;
            --hero-gradient-mid: #0f1428;
            --hero-gradient-end: #080b16;
            --hero-text: #e8edf5;
            --hero-muted: #98a1b5;
            --navbar-bg: #0a0e1a;
            --footer-bg: #050810;
            --footer-text: #7a8398;
            --footer-border: rgba(255, 255, 255, .05);
            --input-bg: rgba(255, 255, 255, .03);
            --input-border: rgba(255, 255, 255, .08);
            --input-placeholder: #5a6478;
            --video-overlay: rgba(0, 0, 0, .7);
        }

        /* ============================================
                                                                           GLOBAL STYLES
                                                                        ============================================ */
        h1,
        h2,
        h3,
        h4,
        .font-display {
            font-family: 'Poppins', sans-serif;
        }


        .btn-outline-brand {
            border: 1.5px solid var(--border-color);
            color: var(--text-primary);
            font-weight: 600;
            border-radius: 8px;
            padding: .55rem 1.3rem;
            transition: all 0.3s ease;
        }

        .btn-outline-brand:hover {
            background: var(--brand-blue);
            color: #fff;
            border-color: var(--brand-blue);
        }

        .btn-outline-white {
            border: 1.5px solid rgba(255, 255, 255, .6);
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            padding: .7rem 1.5rem;
            transition: background 0.3s ease;
        }

        .btn-outline-white:hover {
            background: rgba(255, 255, 255, .12);
            color: #fff;
        }

        /* ============================================
                                                                           HERO
                                                                        ============================================ */
        .hero {
            background: linear-gradient(135deg, var(--hero-gradient-start) 0%, var(--hero-gradient-mid) 60%, var(--hero-gradient-end) 100%);
            position: relative;
            overflow: hidden;
            padding: 5rem 0 3rem;
            transition: background 0.3s ease;
        }

        .hero h1 {
            font-weight: 800;
            font-size: 2.6rem;
            line-height: 1.15;
            color: var(--hero-text);
            transition: color 0.3s ease;
        }

        .hero p.lead {
            color: var(--hero-muted);
            font-size: 1.05rem;
            max-width: 520px;
            transition: color 0.3s ease;
        }

        .hero-img-wrap {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, .35);
        }

        .hero-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-badge {
            color: var(--hero-muted);
            font-size: .85rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .hero-badge i {
            color: #7fa0ff;
            margin-right: .4rem;
        }

        /* ============================================
                                                                           SECTIONS
                                                                        ============================================ */
        .section-eyebrow-title {
            font-weight: 800;
            font-size: 2rem;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .section-sub {
            color: var(--text-muted);
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        section {
            padding: 4.5rem 0;
        }

        .bg-soft {
            background: var(--bg-soft);
            transition: background 0.3s ease;
        }

        /* ============================================
                                                                           FEATURE CARDS
                                                                        ============================================ */
        .feature-card {
            background: var(--bg-body);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.8rem;
            height: 100%;
            transition: box-shadow 0.2s ease, transform 0.2s ease, background 0.3s ease, border-color 0.3s ease;
        }

        .feature-card:hover {
            box-shadow: 0 12px 30px var(--card-shadow);
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 46px;
            height: 46px;
            background: var(--brand-blue);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .feature-card h5 {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .feature-card p {
            color: var(--text-muted);
            font-size: .92rem;
            transition: color 0.3s ease;
        }

        .feature-card .detail-link {
            color: var(--brand-blue);
            font-weight: 600;
            font-size: .9rem;
        }

        /* ============================================
                                                                           STEPS
                                                                        ============================================ */
        .step-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--brand-blue);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0 auto 1rem;
            position: relative;
            z-index: 2;
        }

        .step-line {
            position: absolute;
            top: 28px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border-color);
            z-index: 1;
            transition: background 0.3s ease;
        }

        .step-title {
            font-weight: 700;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .step-text {
            color: var(--text-muted);
            font-size: .92rem;
            max-width: 230px;
            margin: 0 auto;
            transition: color 0.3s ease;
        }

        /* ============================================
                                                                           STATS
                                                                        ============================================ */
        .stats-section {
            background: var(--brand-blue);
            color: #fff;
        }

        .stat-card {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 14px;
            padding: 2rem 1rem;
            text-align: center;
            height: 100%;
        }

        .stat-number {
            font-weight: 800;
            font-size: 2.4rem;
            font-family: 'Poppins', sans-serif;
        }

        .stat-label {
            color: #dbe3ff;
            font-size: .9rem;
            margin-top: .3rem;
        }

        /* ============================================
                                                                           PRICING
                                                                        ============================================ */
        .price-card {
            background: var(--bg-body);
            border: 1.5px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            height: 100%;
            position: relative;
            transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .price-card.featured {
            border-color: var(--brand-blue);
            box-shadow: 0 20px 40px rgba(var(--brand-blue-rgb), 0.15);
            transform: scale(1.03);
        }

        .price-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--brand-blue);
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            padding: .3rem .9rem;
            border-radius: 999px;
        }

        .price-card h6 {
            font-weight: 700;
            letter-spacing: .03em;
            text-transform: uppercase;
            font-size: .8rem;
            color: var(--text-muted);
            transition: color 0.3s ease;
        }

        .price-card .price {
            font-weight: 800;
            font-size: 1.9rem;
            font-family: 'Poppins', sans-serif;
            margin: .6rem 0 1.2rem;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .price-card .price span {
            font-size: .9rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: color 0.3s ease;
        }

        .price-card ul {
            list-style: none;
            padding: 0;
            margin: 0 0 1.5rem;
        }

        .price-card ul li {
            font-size: .92rem;
            margin-bottom: .65rem;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .price-card ul li i {
            color: #22b573;
            margin-right: .5rem;
        }

        /* ============================================
                                                                           TESTIMONIALS
                                                                        ============================================ */
        .testi-card {
            background: var(--bg-body);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.6rem;
            height: 100%;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .testi-card p {
            font-style: italic;
            color: var(--text-primary);
            font-size: .93rem;
            transition: color 0.3s ease;
        }

        .testi-card .name {
            font-weight: 700;
            font-size: .92rem;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .testi-card .role {
            color: var(--text-muted);
            font-size: .82rem;
            transition: color 0.3s ease;
        }

        /* ============================================
                                                                           VIDEO CTA
                                                                        ============================================ */
        .video-cta {
            position: relative;
            background: linear-gradient(180deg, var(--video-overlay), rgba(0, 0, 0, 0.85)),
                url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1600&q=60') center/cover no-repeat;
            color: #fff;
            text-align: center;
            padding: 6rem 1rem;
            transition: background 0.3s ease;
        }

        .play-btn {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #22b573;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.6rem;
            margin: 0 auto 1.5rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .play-btn:hover {
            transform: scale(1.05);
        }

        .video-cta h2 {
            font-weight: 800;
        }

        /* ============================================
                                                                           RESOURCES
                                                                        ============================================ */
        .resource-item {
            display: flex;
            gap: 1rem;
            padding: 1.2rem 0;
        }

        .resource-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: var(--bg-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-blue);
            flex-shrink: 0;
            transition: background 0.3s ease;
        }

        .resource-item h6 {
            font-weight: 700;
            margin-bottom: .3rem;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .resource-item p {
            color: var(--text-muted);
            font-size: .88rem;
            margin-bottom: .3rem;
            transition: color 0.3s ease;
        }

        .resource-item a {
            color: var(--brand-blue);
            font-weight: 600;
            font-size: .88rem;
        }

        /* ============================================
                                                                           FOOTER
                                                                        ============================================ */
        footer {
            background: var(--footer-bg);
            color: var(--footer-text);
            padding-top: 4rem;
            transition: background 0.3s ease, color 0.3s ease;
        }

        footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1.1rem;
        }

        footer a {
            color: var(--footer-text);
            font-size: .9rem;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #fff;
        }

        footer .brand-icon {
            width: 32px;
            height: 32px;
            background: var(--brand-blue);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            margin-right: 8px;
        }

        .footer-bottom {
            border-top: 1px solid var(--footer-border);
            margin-top: 2.5rem;
            padding: 1.3rem 0;
            font-size: .82rem;
            transition: border-color 0.3s ease;
        }

        .social-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: .5rem;
            color: var(--footer-text);
            transition: color 0.3s ease, border-color 0.3s ease;
        }

        .social-icon:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, .4);
        }

        .newsletter-input {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: #fff;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .newsletter-input::placeholder {
            color: var(--input-placeholder);
            transition: color 0.3s ease;
        }

        .newsletter-input:focus {
            background: rgba(255, 255, 255, .1);
            border-color: var(--brand-blue);
            color: #fff;
            box-shadow: none;
        }

        /* ============================================
                                                                           NAVBAR TOGGLER
                                                                        ============================================ */
        .navbar-toggler {
            border: 1.5px solid var(--border-color);
            border-radius: 8px;
            padding: 0.4rem 0.6rem;
            transition: border-color 0.3s ease;
        }

        .navbar-toggler-icon {
            width: 1.5em;
            height: 1.5em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23101426' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            transition: background-image 0.3s ease;
        }

        [data-bs-theme="dark"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23dee2e6' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* ============================================
                                                                           RESPONSIVE FIX
                                                                        ============================================ */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .price-card.featured {
                transform: scale(1);
            }

            .section-eyebrow-title {
                font-size: 1.6rem;
            }
        }
    </style>
    <!-- HERO SLIDER -->
    <section class="hero">
        @if (isset($sliders) && $sliders->isEmpty())
            {{-- fallback jika tidak ada slider --}}
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <h1>Platform Sekolah All-in-One untuk Masa Depan Pendidikan</h1>
                        <p class="lead my-4">Kelola kurikulum, absensi, hingga keuangan dalam satu platform
                            terintegrasi.
                            Dirancang khusus untuk sekolah, guru, dan orang tua di Indonesia.</p>
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <a href="{{ route('trial.form') }}" class="btn btn-brand">Coba Gratis</a>
                            <a href="{{ route('request_demo.form') }}" class="btn btn-outline-white">Jadwalkan Demo</a>
                        </div>
                        <div class="d-flex flex-wrap gap-4 hero-badge">
                            <span><i class="fa-solid fa-rotate"></i>Dapodik Auto-Sync</span>
                            <span><i class="fa-solid fa-layer-group"></i>Multi-Kurikulum Support</span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-img-wrap">
                            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=900&q=70"
                                alt="Guru dan siswa di kelas">
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div id="heroSlider" class="carousel slide hero-carousel" data-bs-ride="carousel">
                @if ($sliders->count() > 1)
                    <div class="carousel-indicators">
                        @foreach ($sliders as $i => $s)
                            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $i }}"
                                class="{{ $i === 0 ? 'active' : '' }}" aria-label="Slide {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
                @endif

                <div class="carousel-inner">
                    @foreach ($sliders as $i => $slider)
                        @php
                            $settings = $slider->settings ?? [];
                            $height = $settings['height'] ?? null; // mis. '600px'
                            $overlay = $settings['overlay'] ?? null; // mis. 'rgba(0,0,0,.4)'
                        @endphp

                        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}"
                            style="{{ $height ? 'height:' . $height . ';' : '' }}">

                            @if ($slider->type === 'image' && $slider->image_url)
                                {{-- === TIPE IMAGE === --}}
                                @if ($slider->link_url)
                                    <a href="{{ $slider->link_url }}" target="{{ $slider->link_target }}" class="d-block h-100">
                                @endif
                                    <div class="hero-slide-image"
                                        style="background-image:url('{{ $slider->image_url }}');
                                                                                                                                                            @if ($overlay) box-shadow: inset 0 0 0 9999px {{ $overlay }}; @endif">
                                    </div>
                                    @if ($slider->link_url)
                                        </a>
                                    @endif
                            @elseif($slider->type === 'html')
                                {{-- === TIPE HTML / BLADE === --}}
                                <div class="hero-slide-html h-100">
                                    @if ($slider->blade_view)
                                        {{-- Render blade view dinamis dari folder resources/views/sliders/ --}}
                                        @includeIf('sliders.' . $slider->blade_view)
                                    @elseif($slider->html_content)
                                        {{-- Raw HTML (pastikan admin terpercaya) --}}
                                        {!! $slider->html_content !!}
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if ($sliders->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif
            </div>
        @endif
    </section>

    <!-- MODULES -->
    <section id="modules">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-eyebrow-title">Ringkasan Modul Inti</h2>
                <p class="section-sub">Solusi komprehensif untuk setiap aspek operasional sekolah Anda.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-book-open"></i></div>
                        <h5>Akademik</h5>
                        <p>Manajemen Kurikulum Merdeka, e-Learning, dan Rapor Digital otomatis.</p>
                        <a href="#" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-user-group"></i></div>
                        <h5>Kesiswaan</h5>
                        <p>PPDB Online, profil lengkap siswa, hingga manajemen alumni dan prestasi.</p>
                        <a href="#" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-wallet"></i></div>
                        <h5>Keuangan</h5>
                        <p>Pembayaran SPP Online (QRIS/VA), laporan BOS, dan RKAS otomatis.</p>
                        <a href="#" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
                        <h5>Absensi</h5>
                        <p>QR Code, RFID, hingga Face Recognition dengan notifikasi WhatsApp ke orang tua.</p>
                        <a href="#" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-people-roof"></i></div>
                        <h5>Parent Portal</h5>
                        <p>Dashboard khusus orang tua untuk pantau nilai, absensi, dan pembayaran real-time.</p>
                        <a href="#" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                        <h5>Analitik AI</h5>
                        <p>Prediksi kelulusan dan analisis perkembangan akademik berbasis kecerdasan buatan.</p>
                        <a href="#" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STEPS -->
    <section class="bg-soft">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-eyebrow-title">Implementasi Mudah &amp; Cepat</h2>
                <p class="section-sub">Transformasi digital sekolah Anda hanya dalam tiga langkah sederhana.</p>
            </div>
            <div class="row position-relative">
                <div class="step-line d-none d-md-block" style="left:16%;right:16%;"></div>
                <div class="col-md-4 text-center mb-4">
                    <div class="step-circle">01</div>
                    <div class="step-title">Daftar</div>
                    <p class="step-text">Buat akun sekolah Anda dan pilih paket yang sesuai dengan kebutuhan institusi.</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="step-circle">02</div>
                    <div class="step-title">Setup Sekolah</div>
                    <p class="step-text">Input data guru, siswa, dan kurikulum. Tim kami siap membantu proses migrasi data.
                    </p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="step-circle">03</div>
                    <div class="step-title">Gunakan &amp; Pantau</div>
                    <p class="step-text">Nikmati kemudahan operasional dan pantau perkembangan lewat dashboard eksekutif.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="stats-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Dampak Nyata EduPulse</h2>
                <p class="mb-0" style="color:#dbe3ff;">Angka bicara tentang efisiensi dan peningkatan kualitas
                    pendidikan.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">75%</div>
                        <div class="stat-label">Penghematan Waktu TU</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">92%</div>
                        <div class="stat-label">Peningkatan Kehadiran</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Sekolah Pengguna</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Laporan Otomatis</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Dukungan Teknis</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Biaya Maintenance Server</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section id="pricing">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-eyebrow-title">Pilih Paket Sekolah Anda</h2>
                <p class="section-sub">Investasi cerdas untuk masa depan digital pendidikan yang lebih baik.</p>
            </div>
            <div class="row g-4 justify-content-center align-items-center">
                <div class="col-lg-4 col-md-6">
                    <div class="price-card">
                        <h6>Paket Starter</h6>
                        <div class="price">Rp 300rb <span>/bln</span></div>
                        <ul>
                            <li><i class="fa-solid fa-circle-check"></i>Data Siswa</li>
                            <li><i class="fa-solid fa-circle-check"></i>Absensi</li>
                            <li><i class="fa-solid fa-circle-check"></i>Jadwal</li>
                            <li><i class="fa-solid fa-circle-check"></i>Pengumuman</li>
                            <li><i class="fa-solid fa-circle-check"></i>Parent Portal</li>
                        </ul>
                        <a href="#" class="btn btn-outline-brand w-100">Pilih Paket</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="price-card featured">
                        <span class="price-badge">Terpopuler</span>
                        <h6>Paket Profesional</h6>
                        <div class="price">Rp 700rb <span>/bln</span></div>
                        <ul>
                            <li><i class="fa-solid fa-circle-check"></i>Semua Starter</li>
                            <li><i class="fa-solid fa-circle-check"></i>Keuangan SPP</li>
                            <li><i class="fa-solid fa-circle-check"></i>Rapor Digital</li>
                            <li><i class="fa-solid fa-circle-check"></i>Perpustakaan</li>
                            <li><i class="fa-solid fa-circle-check"></i>E-Learning &amp; Bank Soal</li>
                        </ul>
                        <a href="#" class="btn btn-brand w-100">Pilih Paket</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="price-card">
                        <h6>Paket Enterprise</h6>
                        <div class="price">Custom Price</div>
                        <ul>
                            <li><i class="fa-solid fa-circle-check"></i>Semua Pro</li>
                            <li><i class="fa-solid fa-circle-check"></i>Payroll &amp; SDM</li>
                            <li><i class="fa-solid fa-circle-check"></i>Transportasi GPS</li>
                            <li><i class="fa-solid fa-circle-check"></i>Kantin Cashless</li>
                            <li><i class="fa-solid fa-circle-check"></i>Dapodik Sync &amp; API</li>
                        </ul>
                        <a href="#" class="btn btn-outline-brand w-100">Konsultasi</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="bg-soft">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-eyebrow-title">Dipercaya Oleh Sekolah Terbaik</h2>
                <p class="section-sub">Apa kata mereka yang telah bertransformasi bersama EduPulse.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="testi-card">
                        <p>"EduPulse sangat membantu kami dalam mengelola rapor digital. Waktu pengerjaan berkurang drastis
                            dari 2 minggu menjadi hanya 2 hari."</p>
                        <div class="name">Drs. Ahmad Subarjo</div>
                        <div class="role">Kepala Sekolah, SMA Harapan</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="testi-card">
                        <p>"Fitur notifikasi WhatsApp sangat disukai orang tua. Mereka merasa lebih tenang karena tahu
                            kehadiran anak secara real-time."</p>
                        <div class="name">Siti Aminah, M.Pd</div>
                        <div class="role">Waka Kesiswaan, SMP Tunas Bangsa</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="testi-card">
                        <p>"Manajemen keuangan jadi lebih transparan. Laporan BOS yang biasanya rumit kini bisa digenerate
                            otomatis dengan satu klik."</p>
                        <div class="name">Budi Santoso</div>
                        <div class="role">Bendahara, SD Nusantara</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="testi-card">
                        <p>"Integrasi dengan Google Workspace memudahkan guru kami dalam memberikan materi e-learning selama
                            masa hybrid learning."</p>
                        <div class="name">Rina Wijaya</div>
                        <div class="role">Guru IT, SMK Inovasi</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="testi-card">
                        <p>"Sistem PPDB Online EduPulse sangat stabil bahkan saat trafik tinggi. Pendaftaran jadi lebih
                            tertib dan minim kesalahan data."</p>
                        <div class="name">Hendra Kurniawan</div>
                        <div class="role">Ketua PPDB, Global School</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="testi-card">
                        <p>"Analitik AI membantu kami mendeteksi siswa yang butuh perhatian khusus lebih awal. Sangat
                            membantu program remedial kami."</p>
                        <div class="name">Dr. Maya Sari</div>
                        <div class="role">Konselor BK, SMA Prestasi</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIDEO CTA -->
    <div class="video-cta">
        <div class="container">
            <h2 class="mb-3">Lihat EduPulse Beraksi</h2>
            <p class="mb-4" style="color:#d8dcea;">Tonton bagaimana dashboard kami memudahkan manajemen sekolah dalam
                hitungan menit.</p>
            <div class="play-btn"><i class="fa-solid fa-play"></i></div>
            <a href="#" class="btn"
                style="background:#22b573;color:#fff;font-weight:600;padding:.6rem 1.5rem;border-radius:8px;">
                <i class="fa-solid fa-play me-2"></i>Putar Video Demo
            </a>
        </div>
    </div>

    <!-- RESOURCES -->
    <section>
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="section-eyebrow-title">Pusat Bantuan &amp; Sumber Daya</h2>
                <p class="section-sub">Segala hal yang Anda butuhkan untuk memulai perjalanan digital bersama kami.</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-book"></i></div>
                        <div>
                            <h6>Dokumentasi</h6>
                            <p>Panduan lengkap demi langkah penggunaan setiap modul EduPulse.</p>
                            <a href="#">Pelajari →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-newspaper"></i></div>
                        <div>
                            <h6>Blog Edukasi</h6>
                            <p>Artikel tips implementasi teknologi di sekolah dan tren pendidikan terbaru.</p>
                            <a href="#">Baca Artikel →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-headset"></i></div>
                        <div>
                            <h6>Kontak Sales</h6>
                            <p>Hubungi tim kami untuk trial kustom atau demo lanjutan khusus sekolah Anda.</p>
                            <a href="#">Hubungi Kami →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <div>
                            <h6>Keamanan Data</h6>
                            <p>Informasi tentang bagaimana kami menjaga privasi dan keamanan data sekolah.</p>
                            <a href="#">Selengkapnya →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-circle-question"></i></div>
                        <div>
                            <h6>FAQ</h6>
                            <p>Jawaban atas pertanyaan yang paling sering diajukan oleh pengguna baru.</p>
                            <a href="#">Lihat Pertanyaan →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-bell"></i></div>
                        <div>
                            <h6>Update Terbaru</h6>
                            <p>Catatan rilis fitur-fitur baru yang kami luncurkan setiap bulannya.</p>
                            <a href="#">Cek Changelog →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link style="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
@endpush

@push('scripts')
    {{-- Tambahan JS khusus halaman ini (jika ada) --}}
@endpush