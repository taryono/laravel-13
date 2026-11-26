<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SMA Nusantara — EduPortal' }}</title>
    <script>
        /* Terapkan tema sebelum render agar tidak berkedip */
        (function() {
            var t = null;
            try {
                t = localStorage.getItem('theme');
            } catch (e) {}
            if (!t) t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/front.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tenant.css') }}">
    @stack('styles')
</head>

<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <span class="brand-icon"><i class="fa-solid fa-graduation-cap"></i></span>
                <span class="d-flex flex-column lh-1 ms-2">
                    <span class="fw-bold" style="font-size:1.05rem; letter-spacing: -0.01em;">{{ $organization->name ?? 'SMA Nusantara' }}</span>
                    <span class="text-primary fw-semibold" style="font-size:.6875rem; letter-spacing: 0.06em; text-transform: uppercase;">EduPortal</span>
                </span>
            </a>
            <div class="d-flex align-items-center gap-2 order-lg-last ms-2">
                <button id="themeToggle" class="btn nav-link p-0 border-0 bg-transparent" title="Ubah Tema" aria-label="Ganti tema terang/gelap">
                    <i id="themeIcon" class="fa-regular fa-moon text-secondary fs-5"></i>
                </button>
                <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Buka menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#profil">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#akademik">Akademik</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kesiswaan">Kesiswaan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#berita">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="#ppdb-section">PPDB</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a class="nav-link text-muted fw-semibold" href="/login">Login</a>
                    <a class="btn btn-brand" href="/ppdb" target="_blank">Daftar PPDB</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer>
        <div class="container">
            <div class="row g-4 pb-3">
                <div class="col-lg-4 col-md-6">
                    <a class="d-flex align-items-center mb-3 text-white fw-bold fs-5 text-decoration-none" href="#">
                        <span class="brand-icon"><i class="fa-solid fa-graduation-cap"></i></span>{{ $organization->name ?? 'SMA Nusantara' }}
                    </a>
                    <p style="font-size:.9rem;" class="text-secondary">Mencetak generasi unggul, berkarakter, dan siap menghadapi tantangan global melalui pendidikan terpadu berbasis teknologi.</p>
                    <div class="mt-3">
                        <a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h6>Tautan Cepat</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#profil">Profil Sekolah</a></li>
                        <li><a href="#akademik">Program Akademik</a></li>
                        <li><a href="#kesiswaan">Kesiswaan</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h6>Informasi &amp; Layanan</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#berita">Berita &amp; Pengumuman</a></li>
                        <li><a href="#ppdb-section">Pendaftaran PPDB</a></li>
                        <li><a href="/login">Portal Siswa &amp; Guru</a></li>
                        <li><a href="#fasilitas">Fasilitas Kampus</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6>Kontak Kami</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-4">
                        <li><i class="fa-solid fa-location-dot me-2 text-primary"></i>Jl. Pendidikan No. 45, Jakarta Selatan 12345</li>
                        <li><i class="fa-solid fa-phone me-2 text-primary"></i>(021) 7654-3210</li>
                        <li><i class="fa-solid fa-envelope me-2 text-primary"></i>info@smanusantara.sch.id</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
                <span>© {{ date('Y') }} EduPortal {{ $organization->name ?? 'SMA Nusantara' }}. Hak cipta dilindungi undang-undang.</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mt-2 mt-md-0">Terakreditasi A (Unggul)</span>
            </div>
        </div>
    </footer>

    <!-- ===== MODAL VIDEO ===== -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content p-3 p-md-4 border-0 shadow-lg" style="background: var(--bg-body); border-radius: 16px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 font-display" id="videoTitle">Profil Video Kampus {{ $organization->name ?? 'SMA Nusantara' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden" style="background: var(--brand-blue);">
                    <div class="d-flex flex-column align-items-center justify-content-center gap-2 text-white">
                        <i class="fa-regular fa-circle-play" style="font-size:54px"></i>
                        <span class="fw-semibold">Video Tur Virtual Kampus {{ $organization->name ?? 'SMA Nusantara' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.1.0/jquery.min.js"
        integrity="sha512-LAwtVcZXPhBWVFQr9JK27ozsjKIZWJ6Y811gxInOV82L55pvK12upCcTye8dhlVJ5iIllnd4LO0zq9Ue80vxbQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        (function() {
            // Ambil elemen theme toggle
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
                    if (themeIcon) {
                        themeIcon.classList.remove('fa-moon');
                        themeIcon.classList.add('fa-sun');
                    }
                } else {
                    htmlElement.setAttribute('data-bs-theme', 'light');
                    if (themeIcon) {
                        themeIcon.classList.remove('fa-sun');
                        themeIcon.classList.add('fa-moon');
                    }
                }
                try {
                    localStorage.setItem('theme', theme);
                } catch (e) {}
            }

            // Klik untuk ganti tema
            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    applyTheme(htmlElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
                });
            }

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

            // Tutup menu mobile saat link diklik
            document.querySelectorAll('#navMenu .nav-link').forEach(function(a) {
                a.addEventListener('click', function() {
                    var el = document.getElementById('navMenu');
                    if (el && el.classList.contains('show')) {
                        bootstrap.Collapse.getOrCreateInstance(el).hide();
                    }
                });
            });
        })();
    </script>
</body>

</html>
