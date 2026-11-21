<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPulse — Platform Sekolah All-in-One</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            /* Warna dasar yang akan berubah sesuai mode */
            --brand-blue: #2f5ce0;
            --brand-blue-dark: #1d3fa8;
            --brand-blue-rgb: 47, 92, 224;

            /* Warna yang responsif terhadap tema */
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
        }

        /* MODE GELAP */
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
        }

        /* Terapkan variabel ke elemen */
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 5.5rem;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ============================================
       BUTTONS
    ============================================ */
        .btn-brand {
            background: var(--brand-blue);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: .55rem 1.3rem;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .btn-brand:hover {
            background: var(--brand-blue-dark);
            color: #fff;
        }

        /* ============================================
       NAVBAR
    ============================================ */
        .navbar-custom {
            position: sticky;
            top: 0;
            z-index: 1030;
            border-bottom: 1px solid var(--border-color);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 2px 10px 0 var(--card-shadow);
            transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease, padding 0.3s ease;
        }

        [data-bs-theme="dark"] .navbar-custom {
            background: rgba(10, 14, 26, 0.92);
        }

        .navbar-custom.is-stuck {
            box-shadow: 0 4px 20px -2px var(--card-shadow);
            padding-top: 0.65rem !important;
            padding-bottom: 0.65rem !important;
        }

        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .navbar-custom .nav-link {
            color: var(--text-primary);
            font-weight: 500;
            font-size: .95rem;
            margin: 0 .6rem;
            transition: color 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: var(--brand-blue);
        }

        /* Navbar Toggler Icon */
        .navbar-toggler {
            border: 1.5px solid var(--border-color);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23101426' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        [data-bs-theme="dark"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23dee2e6' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Button */
        .btn-outline-brand {
            border: 1.5px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-outline-brand:hover {
            background: var(--brand-blue);
            color: #fff;
            border-color: var(--brand-blue);
        }

        /* Hero - menggunakan variabel tema */
        .hero {
            background: linear-gradient(135deg, var(--hero-gradient-start) 0%, var(--hero-gradient-mid) 60%, var(--hero-gradient-end) 100%);
            position: relative;
            overflow: hidden;
            padding: 5rem 0 3rem;
            transition: background 0.3s ease;
        }

        .hero h1 {
            color: var(--hero-text);
        }

        .hero p.lead {
            color: var(--hero-muted);
        }

        .hero-badge {
            color: var(--hero-muted);
        }

        /* Section */
        .bg-soft {
            background: var(--bg-soft);
            transition: background 0.3s ease;
        }

        .section-sub {
            color: var(--text-muted);
        }

        /* Feature Cards */
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

        .feature-card p {
            color: var(--text-muted);
        }

        .feature-card .detail-link {
            color: var(--brand-blue);
        }

        /* Steps */
        .step-line {
            background: var(--border-color);
        }

        .step-text {
            color: var(--text-muted);
        }

        /* Stats */
        .stats-section {
            background: var(--brand-blue);
            color: #fff;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .stat-label {
            color: #dbe3ff;
        }

        /* Pricing */
        .price-card {
            background: var(--bg-body);
            border: 1.5px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            height: 100%;
            position: relative;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .price-card.featured {
            border-color: var(--brand-blue);
            box-shadow: 0 20px 40px rgba(var(--brand-blue-rgb), 0.15);
            transform: scale(1.03);
        }

        .price-card .price {
            color: var(--text-primary);
        }

        .price-card .price span {
            color: var(--text-muted);
        }

        .price-card ul li {
            color: var(--text-primary);
        }

        .price-card h6 {
            color: var(--text-muted);
        }

        /* Testimonials */
        .testi-card {
            background: var(--bg-body);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.6rem;
            height: 100%;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .testi-card p {
            color: var(--text-primary);
        }

        .testi-card .role {
            color: var(--text-muted);
        }

        /* Video CTA */
        .video-cta {
            position: relative;
            background: linear-gradient(180deg, rgba(10, 14, 28, 0.55), rgba(10, 14, 28, 0.85)),
                url('{{ asset('assets/images/photo-1517245386807-bb43f82c33c4.avif') }}') center/cover no-repeat;
            color: #fff;
            text-align: center;
            padding: 6rem 1rem;
        }

        /* Resources */
        .resource-icon {
            background: var(--bg-soft);
            color: var(--brand-blue);
            transition: background 0.3s ease;
        }

        .resource-item p {
            color: var(--text-muted);
        }

        .resource-item a {
            color: var(--brand-blue);
        }

        /* Footer */
        footer {
            background: var(--footer-bg);
            color: var(--footer-text);
            padding-top: 4rem;
            transition: background 0.3s ease, color 0.3s ease;
        }

        footer h6 {
            color: #fff;
        }

        footer a {
            color: var(--footer-text);
        }

        a {
            text-decoration: none;
        }

        footer a:hover {
            color: #fff;
        }

        .footer-bottom {
            border-top: 1px solid var(--footer-border);
        }

        .social-icon {
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--footer-text);
        }

        .social-icon:hover {
            color: #fff;
        }

        .newsletter-input {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .newsletter-input::placeholder {
            color: #8b93a9;
        }

        .newsletter-input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--brand-blue);
            color: #fff;
            box-shadow: none;
        }

        /* Bootstrap Override untuk warna teks di dark mode */
        [data-bs-theme="dark"] .text-secondary {
            color: #98a1b5 !important;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #98a1b5 !important;
        }

        /* Ikon tema */
        #themeIcon {
            font-size: 1.2rem;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        /* Warna hijau tetap */
        .price-card ul li i {
            color: #22b573;
        }

        .play-btn {
            background: #22b573;
        }


        .navbar-custom .navbar-brand .brand-icon {
            width: 32px;
            height: 32px;
            background: var(--brand-blue);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            margin-right: 8px;
            font-size: .9rem;
        }


        /* ============================================
   PENCEGAHAN HORIZONTAL SCROLL
============================================ */
        /* 1. Pastikan semua konten tidak overflow */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* 2. Batasi lebar maksimum body tanpa memutus position: sticky */
        html,
        body {
            max-width: 100%;
            overflow-x: clip;
        }

        /* 3. Pastikan container tidak overflow */
        .container,
        .container-fluid,
        .container-lg,
        .container-md,
        .container-sm,
        .container-xl,
        .container-xxl {
            max-width: 100%;
        }

        /* 4. Untuk gambar dan elemen media */
        img,
        video,
        iframe {
            max-width: 100%;
            height: auto;
        }

        /* 5. Untuk elemen dengan posisi absolute */
        .hero,
        section,
        .row {
            position: relative;
            overflow-x: hidden;
        }

        /* 6. Khusus untuk step-line di dark mode */
        .step-line {
            left: 16% !important;
            right: 16% !important;
        }

        /* 7. Responsive untuk mobile */
        @media (max-width: 768px) {
            .step-line {
                display: none !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <span class="brand-icon"><i class="fa-solid fa-graduation-cap"></i></span>
                EduPulse
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#modules">Academic</a></li>
                    <li class="nav-item"><a class="nav-link" href="#modules">Finance &amp; Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="#modules">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <button id="themeToggle" class="btn nav-link p-0 border-0 bg-transparent" title="Ubah Tema">
                        <i id="themeIcon" class="fa-regular fa-moon text-secondary"></i>
                    </button>
                    <a href="{{ route('trial.form') }}" class="btn btn-brand">Get Started</a>
                    @if (!auth()->check())
                        <a href="{{ url('/login-option') }}" class="btn btn-brand login-button">Login</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="btn btn-brand">Dashboard</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- ISI HALAMAN — BAGIAN YANG BERUBAH — --}}
    @yield('content')

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <a class="d-flex align-items-center mb-3 text-white fw-bold fs-5" href="{{ url('/') }}">
                        <span class="brand-icon"><i class="fa-solid fa-graduation-cap"></i></span>EduPulse
                    </a>
                    <p style="font-size:.9rem;">The most comprehensive SaaS ecosystem for modern schools. Empowering
                        educators, engaging parents, and streamlining administration through intelligent automation.</p>
                    <div class="mt-3">
                        <a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6>Solutions</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#">Homepage</a></li>
                        <li><a href="#">Academic Module</a></li>
                        <li><a href="#">Finance &amp; Admin</a></li>
                        <li><a href="#">Parent Portal</a></li>
                        <li><a href="#">Library System</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6>Platform</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#">Web Application</a></li>
                        <li><a href="#">Mobile App (iOS/Android)</a></li>
                        <li><a href="#">Progressive Web App</a></li>
                        <li><a href="#">API Access</a></li>
                        <li><a href="#">White Label Solution</a></li>
                        <li><a href="#">Security Standards</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6>Contact Us</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-4">
                        <li><i class="fa-solid fa-location-dot me-2"></i>Jl. Pendidikan No. 123, Jakarta, Indonesia
                        </li>
                        <li><i class="fa-solid fa-phone me-2"></i>+62 21 555 0199</li>
                        <li><i class="fa-solid fa-envelope me-2"></i>support@edupulse.com</li>
                    </ul>
                    <p style="font-size:.85rem;">Subscribe for school management tips</p>
                    <div class="input-group">
                        <input type="email" class="form-control newsletter-input" placeholder="Your email address">
                        <button class="btn btn-brand" type="button"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
                <span>© 2026 EduPulse SaaS. All rights reserved.</span>
                <div class="d-flex gap-4 mt-2 mt-md-0">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.1.0/jquery.min.js"
        integrity="sha512-LAwtVcZXPhBWVFQr9JK27ozsjKIZWJ6Y811gxInOV82L55pvK12upCcTye8dhlVJ5iIllnd4LO0zq9Ue80vxbQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        // Ambil elemen
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;

        // Ambil tema yang tersimpan atau deteksi sistem
        let currentTheme = localStorage.getItem('theme');
        if (!currentTheme) {
            currentTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        // Terapkan tema saat halaman dimuat
        applyTheme(currentTheme);

        // Fungsi terapkan tema
        function applyTheme(theme) {
            if (theme === 'dark') {
                htmlElement.setAttribute('data-bs-theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                htmlElement.setAttribute('data-bs-theme', 'light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
            localStorage.setItem('theme', theme);
        }

        // Klik untuk ganti tema
        themeToggle.addEventListener('click', () => {
            applyTheme(htmlElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
        });

        // Sticky Navbar state-aware elevation
        const navbarCustom = document.querySelector('.navbar-custom');
        if (navbarCustom) {
            const handleScroll = () => {
                if (window.scrollY > 10) {
                    navbarCustom.classList.add('is-stuck');
                } else {
                    navbarCustom.classList.remove('is-stuck');
                }
            };
            window.addEventListener('scroll', handleScroll, {
                passive: true
            });
            handleScroll();
        }
    </script>
</body>

</html>
