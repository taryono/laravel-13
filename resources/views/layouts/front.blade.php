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
    <link rel="stylesheet" href="{{ asset('assets/css/front.css') }}">
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
                    <li class="nav-item"><a class="nav-link" href="/berita">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ppdb">PPDB</a></li>
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
    <main>
        @yield('content')
    </main>

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
