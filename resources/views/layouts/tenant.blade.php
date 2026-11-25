<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMA Nusantara - EduPortal</title>
    <script>
        /* Terapkan tema sebelum render agar tidak berkedip */
        (function() {
            var t = null;
            try {
                t = localStorage.getItem('theme')
            } catch (e) {}
            if (!t) t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', t)
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/tenant.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-ep fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 py-0" href="#">
                <span class="logo"><i class="ms ms-18 fill">school</i></span>
                <span class="d-flex flex-column lh-1"><span class="fw-600 t-head" style="font-size:1rem">SMA
                        Nusantara</span><span class="t-muted"
                        style="font-size:.6875rem;font-weight:700">EduPortal</span></span>
            </a>
            <div class="d-flex align-items-center gap-2 order-lg-last">
                <button class="btn-theme" id="themeToggle" type="button" aria-label="Ganti tema terang/gelap"><i
                        class="ms ms-18" id="themeIcon">dark_mode</i></button>
                <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="collapse"
                    data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Buka menu"><i
                        class="ms t-head">menu</i></button>
            </div>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav mx-lg-auto gap-lg-1 py-3 py-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#profil">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#akademik">Akademik</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kesiswaan">Kesiswaan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#berita">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="#ppdb-section">PPDB</a></li>
                </ul>
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 pb-3 pb-lg-0">
                    <div class="searchbox d-flex align-items-center px-2 py-1" style="min-width:12rem"><i
                            class="ms ms-18 t-muted me-1">search</i><input type="search"
                            placeholder="Cari informasi..." aria-label="Cari"></div>
                    <a class="nav-link t-muted fw-600" href="#">Login</a>
                    <a class="btn-ep justify-content-center" href="#ppdb-section">Daftar PPDB</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-low py-5">
        <div class="container">
            <div class="row g-4 pb-3">
                <div class="col-12 col-md-6 d-flex flex-column gap-2">
                    <div class="d-flex align-items-center gap-2"><span class="logo" style="width:2rem;height:2rem"><i
                                class="ms ms-18 fill">school</i></span><span class="fw-600 t-head"
                            style="font-size:1rem">SMA Nusantara</span></div>
                    <p class="t-muted mb-1" style="max-width:28rem">Mencetak generasi unggul, berkarakter, dan siap
                        menghadapi tantangan global melalui pendidikan terpadu berbasis teknologi.</p>
                    <div class="fs-xs t-muted d-flex flex-column gap-1">
                        <span><i class="ms ms-14 t-teal">location_on</i> Jl. Pendidikan No. 45, Jakarta Selatan
                            12345</span>
                        <span><i class="ms ms-14 t-teal">call</i> (021) 7654-3210</span>
                        <span><i class="ms ms-14 t-teal">mail</i> info@smanusantara.sch.id</span>
                    </div>
                </div>
                <div class="col-6 col-md-3 d-flex flex-column gap-2"><span class="fw-600">Tautan Cepat</span><a
                        href="#">Beranda</a><a href="#profil">Profil Sekolah</a><a href="#akademik">Program
                        Akademik</a><a href="#kesiswaan">Kesiswaan</a></div>
                <div class="col-6 col-md-3 d-flex flex-column gap-2"><span class="fw-600">Informasi &amp;
                        Layanan</span><a href="#berita">Berita &amp; Pengumuman</a><a href="#ppdb-section">Pendaftaran
                        PPDB</a><a href="#">Portal Siswa &amp; Guru</a></div>
            </div>
            <div
                class="pt-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 fs-xs t-muted border-top">
                <span>© 2025 EduPortal SMA Nusantara. Hak cipta dilindungi undang-undang.</span><span>Terakreditasi A
                    (Unggul)</span>
            </div>
        </div>
    </footer>

    <!-- ===== MODAL VIDEO ===== -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fs-6 fw-600 t-head mb-0" id="videoTitle">Profil Video Kampus SMA Nusantara</h3>
                    <button type="button" class="btn-theme" data-bs-dismiss="modal" aria-label="Tutup"><i
                            class="ms ms-18">close</i></button>
                </div>
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden bg-brand">
                    <div class="d-flex flex-column align-items-center justify-content-center gap-2"><i class="ms fill"
                            style="font-size:48px">smart_display</i><span>Video Tur Virtual Kampus SMA Nusantara</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        (function() {
            var root = document.documentElement,
                icon = document.getElementById('themeIcon');

            function sync() {
                icon.textContent = root.getAttribute('data-bs-theme') === 'dark' ? 'light_mode' : 'dark_mode'
            }
            sync();
            document.getElementById('themeToggle').addEventListener('click', function() {
                var t = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-bs-theme', t);
                try {
                    localStorage.setItem('theme', t)
                } catch (e) {}
                sync();
            });
            /* Tutup menu mobile saat link diklik */
            document.querySelectorAll('#nav .nav-link').forEach(function(a) {
                a.addEventListener('click', function() {
                    var el = document.getElementById('nav');
                    if (el.classList.contains('show')) bootstrap.Collapse.getOrCreateInstance(el)
                        .hide();
                })
            });
        })();
    </script>
</body>

</html>
