<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">

    {{-- SEO Meta Dinamis dari halaman anak (seperti detail berita) --}}
    <title>@yield('title', 'Portal Informasi Sekolah') - {{ config('app.name', 'EduPulse') }}</title>
    <meta name="description" content="@yield('meta_description', 'Portal informasi resmi akademik, berita kegiatan, dan penerimaan peserta didik baru sekolah.')">
    <meta name="keywords" content="@yield('meta_keywords', 'sekolah, pendidikan, berita sekolah, ppdb, edupulse')">

    {{-- Script Pencegahan Flash of Unstyled Theme (FOUC) --}}
    <script>
        (function() {
            const storedTheme = localStorage.getItem('theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = storedTheme ? storedTheme : (systemDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
            const metaTheme = document.querySelector('meta[name="color-scheme"]');
            if (metaTheme) {
                metaTheme.content = theme === 'dark' ? 'dark' : 'light';
            }
        })();
    </script>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 CSS & Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            color-scheme: light dark;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        main {
            flex: 1;
        }

        /* Navbar dengan glassmorphism & dukungan tema */
        .navbar-public {
            background-color: var(--bs-body-bg);
            border-bottom: 1px solid var(--bs-border-color-translucent);
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }

        @supports (backdrop-filter: blur(12px)) or (-webkit-backdrop-filter: blur(12px)) {
            .navbar-public {
                background-color: rgba(var(--bs-body-bg-rgb), 0.88);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
        }

        .navbar-public .nav-link {
            font-weight: 500;
            color: var(--bs-secondary-color);
            padding: 0.5rem 0.9rem;
            border-radius: 8px;
            transition: all 0.15s ease;
        }

        .navbar-public .nav-link:hover {
            color: var(--bs-primary);
            background-color: var(--bs-secondary-bg);
        }

        .navbar-public .nav-link.active {
            color: var(--bs-primary);
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            font-weight: 600;
        }

        /* Tombol Ubah Tema */
        .theme-toggle-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid var(--bs-border-color);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle-btn:hover {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-primary);
            border-color: var(--bs-primary);
            transform: scale(1.05);
        }

        /* Kartu Berita Card Hover */
        .news-card {
            transition: transform 0.25s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.25s ease, border-color 0.25s ease;
            background-color: var(--bs-body-bg);
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08) !important;
            border-color: var(--bs-primary-border-subtle) !important;
        }

        [data-bs-theme="dark"] .news-card:hover {
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.35) !important;
        }

        .news-card-img-wrap {
            overflow: hidden;
            position: relative;
        }

        .news-card-img-wrap img {
            transition: transform 0.35s ease;
        }

        .news-card:hover .news-card-img-wrap img {
            transform: scale(1.04);
        }

        .featured-card-img-wrap img {
            transition: transform 0.4s ease;
        }

        .featured-news-card:hover .featured-card-img-wrap img {
            transform: scale(1.03);
        }

        /* Styling TinyMCE Post Body */
        .cms-post-body {
            font-size: 1.125rem;
            line-height: 1.85;
            color: var(--bs-body-color);
        }

        .cms-post-body p {
            margin-bottom: 1.35rem;
        }

        .cms-post-body h2,
        .cms-post-body h3,
        .cms-post-body h4 {
            color: var(--bs-emphasis-color);
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .cms-post-body img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .cms-post-body table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 1.5rem 0;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--bs-border-color);
        }

        .cms-post-body table th {
            background-color: var(--bs-tertiary-bg);
            color: var(--bs-emphasis-color);
            font-weight: 600;
            border-bottom: 2px solid var(--bs-border-color);
            padding: 10px 14px;
        }

        .cms-post-body table td {
            border-bottom: 1px solid var(--bs-border-color);
            padding: 10px 14px;
        }

        .cms-post-body blockquote {
            border-left: 4px solid var(--bs-primary);
            background-color: var(--bs-tertiary-bg);
            padding: 1rem 1.25rem;
            border-radius: 0 10px 10px 0;
            color: var(--bs-body-color);
            font-style: italic;
            margin: 1.5rem 0;
        }

        /* Footer Publik */
        footer.public-footer {
            background-color: var(--bs-tertiary-bg);
            color: var(--bs-secondary-color);
            border-top: 1px solid var(--bs-border-color);
        }

        footer.public-footer h5,
        footer.public-footer h6 {
            color: var(--bs-emphasis-color);
        }
    </style>

    @stack('styles')
</head>

<body>
    {{-- =================================================================
         NAVBAR PUBLIK
    ================================================================== --}}
    <nav class="navbar navbar-expand-lg navbar-public sticky-top py-2.5">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <span class="brand-icon"><i class="fa-solid fa-graduation-cap"></i></span>
                EduPulse
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-house me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.news.*') ? 'active' : '' }}"
                            href="{{ route('public.news.index') }}">
                            <i class="bi bi-newspaper me-1"></i> Berita &amp; Pengumuman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('ppdb*') ? 'active' : '' }}" href="{{ url('/ppdb') }}">
                            <i class="bi bi-person-lines-fill me-1"></i> Info PPDB
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 pt-2 pt-lg-0">
                    {{-- Tombol Toggle Dark/Light Mode --}}
                    <button id="themeToggle" class="theme-toggle-btn shadow-sm" type="button"
                        title="Ubah Tema Gelap/Terang" aria-label="Ubah Tema Gelap/Terang">
                        <i id="themeIcon" class="bi bi-moon-stars-fill text-secondary"></i>
                    </button>

                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="btn btn-primary btn-sm fw-semibold px-3 rounded-pill shadow-sm">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="btn btn-outline-primary btn-sm fw-semibold px-3 rounded-pill">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login Portal
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- =================================================================
         KONTEN HALAMAN UTAMA
    ================================================================== --}}
    <main>
        @yield('content')
    </main>

    {{-- =================================================================
         FOOTER PUBLIK
    ================================================================== --}}
    <footer class="public-footer py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="bg-primary text-white rounded-3 px-2 py-1 fs-6 shadow-sm">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </span>
                        <h5 class="fw-bold mb-0 text-body-emphasis">{{ config('app.name', 'EduPulse') }}</h5>
                    </div>
                    <p class="small text-body-secondary mb-3 pe-lg-4">
                        Platform Manajemen Pendidikan Terpadu, Tata Kelola Akademik, Transparansi Dana Sekolah, serta
                        Pusat Informasi Resmi Civitas Satuan Pendidikan.
                    </p>
                    <div class="d-flex gap-2 text-body-secondary">
                        <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle">
                            <i class="bi bi-shield-check text-success me-1"></i> Terakreditasi A
                        </span>
                        <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle">
                            <i class="bi bi-lightning-charge-fill text-warning me-1"></i> EduPulse 2026
                        </span>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="fw-bold mb-3 text-body-emphasis">Tautan Informasi</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li>
                            <a href="{{ route('public.news.index') }}"
                                class="text-decoration-none text-body-secondary hover-primary">
                                <i class="bi bi-chevron-right me-1 small"></i> Berita &amp; Agenda
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/ppdb') }}"
                                class="text-decoration-none text-body-secondary hover-primary">
                                <i class="bi bi-chevron-right me-1 small"></i> Penerimaan Siswa (PPDB)
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}"
                                class="text-decoration-none text-body-secondary hover-primary">
                                <i class="bi bi-chevron-right me-1 small"></i> Profil Sekolah
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 col-lg-4">
                    <h6 class="fw-bold mb-3 text-body-emphasis">Hubungi Sekolah</h6>
                    <p class="small text-body-secondary mb-2">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i> Jl. Pendidikan No. 123, Jakarta, Indonesia
                    </p>
                    <p class="small text-body-secondary mb-2">
                        <i class="bi bi-telephone-fill text-primary me-2"></i> +62 21 555 0199
                    </p>
                    <p class="small text-body-secondary mb-0">
                        <i class="bi bi-envelope-fill text-primary me-2"></i> info@sekolah.sch.id
                    </p>
                </div>
            </div>
            <hr class="border-secondary-subtle my-4">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-body-secondary gap-2">
                <div>
                    &copy; {{ date('Y') }} {{ config('app.name', 'EduPulse') }}. Seluruh hak cipta dilindungi
                    undang-undang.
                </div>
                <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none text-body-secondary">Kebijakan Privasi</a>
                    <span>•</span>
                    <a href="#" class="text-decoration-none text-body-secondary">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Bootstrap 5.3 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script Sinkronisasi Dark Mode --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const htmlElement = document.documentElement;

            function updateIcon(theme) {
                if (!themeIcon) return;
                if (theme === 'dark') {
                    themeIcon.className = 'bi bi-sun-fill text-warning';
                    themeIcon.setAttribute('title', 'Ganti ke Tema Terang');
                } else {
                    themeIcon.className = 'bi bi-moon-stars-fill text-secondary';
                    themeIcon.setAttribute('title', 'Ganti ke Tema Gelap');
                }
            }

            function applyTheme(theme) {
                htmlElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);
                const metaColorScheme = document.querySelector('meta[name="color-scheme"]');
                if (metaColorScheme) {
                    metaColorScheme.content = theme === 'dark' ? 'dark' : 'light';
                }
                updateIcon(theme);
            }

            // Sync icon on initial load
            const currentTheme = htmlElement.getAttribute('data-bs-theme') || 'light';
            updateIcon(currentTheme);

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const activeTheme = htmlElement.getAttribute('data-bs-theme') === 'dark' ? 'light' :
                        'dark';
                    applyTheme(activeTheme);
                });
            }

            // Listen for system theme changes if not manually overridden
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (!localStorage.getItem('theme')) {
                    applyTheme(e.matches ? 'dark' : 'light');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
