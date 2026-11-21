@extends('layouts.front')

@section('content')
    <style>
        /* ============================
                                                                                       CSS VARIABLES & ROLE THEMES
                                                                                       ============================ */
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --kantin: #EA580C;
            --kantin-dark: #C2410C;
            --siswa: #2563EB;
            --siswa-dark: #1D4ED8;
            --ppdb: #16A34A;
            --ppdb-dark: #15803D;
            --guru: #6366F1;
            --guru-dark: #4F46E5;
        }

        /* ============================
                                                                                       RESPONSIVE CONTAINER & BASE
                                                                                       ============================ */
        .app-container {
            width: 100%;
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (min-width: 576px) {
            .app-container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }

        @media (min-width: 1200px) {
            .app-container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        .text-balance {
            text-wrap: balance;
        }

        .text-pretty {
            text-wrap: pretty;
        }

        /* ============================
                                                                                       HERO SECTION
                                                                                       ============================ */
        .hero-section {
            max-width: 760px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-badge {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background: #EFF6FF;
            color: var(--primary-dark);
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            border: 1px solid rgba(37, 99, 235, 0.2);
        }

        [data-bs-theme="dark"] .hero-badge {
            background: rgba(37, 99, 235, 0.15);
            color: #93C5FD;
            border-color: rgba(147, 197, 253, 0.25);
        }

        .hero-title {
            font-size: clamp(1.6rem, 3.2vw, 2.5rem);
            font-weight: 800;
            line-height: 1.25;
            color: var(--bs-body-color);
        }

        .hero-subtitle {
            font-size: clamp(0.9rem, 1.3vw, 1.1rem);
            line-height: 1.6;
            color: var(--bs-secondary-color);
        }

        /* ============================
                                                                                       ROLE CARDS (DESKTOP & MOBILE)
                                                                                       ============================ */
        .role-card {
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-top: 4px solid;
            border-radius: 1.125rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, border-color 0.2s ease;
            position: relative;
        }

        [data-bs-theme="dark"] .role-card {
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.35);
        }

        .role-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
        }

        [data-bs-theme="dark"] .role-card:hover {
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.55);
        }

        .role-card.kantin {
            border-top-color: var(--kantin);
        }

        .role-card.siswa {
            border-top-color: var(--siswa);
        }

        .role-card.ppdb {
            border-top-color: var(--ppdb);
        }

        .role-card.guru {
            border-top-color: var(--guru);
        }

        .role-card h3 {
            font-size: 1.15rem;
            letter-spacing: -0.01em;
        }

        /* Icon badge di kartu */
        .icon-badge {
            width: 48px;
            height: 48px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid;
            font-size: 1.35rem;
        }

        .icon-badge.kantin {
            background: #FFF7ED;
            color: var(--kantin);
            border-color: #FFEDD5;
        }

        .icon-badge.siswa {
            background: #EFF6FF;
            color: var(--siswa);
            border-color: #DBEAFE;
        }

        .icon-badge.ppdb {
            background: #F0FDF4;
            color: var(--ppdb);
            border-color: #DCFCE7;
        }

        .icon-badge.guru {
            background: #EEF2FF;
            color: var(--guru);
            border-color: #E0E7FF;
        }

        [data-bs-theme="dark"] .icon-badge.kantin {
            background: rgba(234, 88, 12, 0.15);
            border-color: rgba(234, 88, 12, 0.3);
        }

        [data-bs-theme="dark"] .icon-badge.siswa {
            background: rgba(37, 99, 235, 0.15);
            border-color: rgba(37, 99, 235, 0.3);
        }

        [data-bs-theme="dark"] .icon-badge.ppdb {
            background: rgba(22, 163, 74, 0.15);
            border-color: rgba(22, 163, 74, 0.3);
        }

        [data-bs-theme="dark"] .icon-badge.guru {
            background: rgba(99, 102, 241, 0.15);
            border-color: rgba(99, 102, 241, 0.3);
        }

        /* Feature list dengan bullet berwarna */
        .feature-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .feature-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            font-size: 0.8125rem;
            color: var(--bs-secondary-color);
            margin-bottom: 0.625rem;
            line-height: 1.45;
        }

        .feature-list li:last-child {
            margin-bottom: 0;
        }

        .feature-list li::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            margin-top: 0.35rem;
            flex-shrink: 0;
        }

        .feature-list.kantin li::before {
            background: var(--kantin);
        }

        .feature-list.siswa li::before {
            background: var(--siswa);
        }

        .feature-list.ppdb li::before {
            background: var(--ppdb);
        }

        .feature-list.guru li::before {
            background: var(--guru);
        }

        /* ============================
                                                                                       ROLE BUTTONS & TOUCH TARGETS
                                                                                       ============================ */
        .btn.btn-role {
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            min-height: 46px;
            padding: 0.75rem 1.25rem;
            color: #fff;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .btn.btn-role:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .btn.btn-role:active {
            transform: scale(0.98);
        }

        .btn.btn-kantin {
            background: var(--kantin);
        }

        .btn.btn-kantin:hover {
            background: var(--kantin-dark);
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.35);
        }

        .btn.btn-siswa {
            background: var(--siswa);
        }

        .btn.btn-siswa:hover {
            background: var(--siswa-dark);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
        }

        .btn.btn-ppdb {
            background: var(--ppdb);
        }

        .btn.btn-ppdb:hover {
            background: var(--ppdb-dark);
            box-shadow: 0 6px 20px rgba(22, 163, 74, 0.35);
        }

        .btn.btn-guru {
            background: var(--guru);
        }

        .btn.btn-guru:hover {
            background: var(--guru-dark);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
        }

        /* ============================
                                                                                       FAQ SECTION
                                                                                       ============================ */
        .faq-section {
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 1.25rem;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04);
            max-width: 860px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        [data-bs-theme="dark"] .faq-section {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
        }

        .faq-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #EFF6FF;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        [data-bs-theme="dark"] .faq-icon {
            background: rgba(37, 99, 235, 0.18);
        }

        /* Custom accordion */
        #faqAccordion .accordion-item {
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--bs-border-color);
        }

        #faqAccordion .accordion-item:last-child {
            border-bottom: none;
        }

        #faqAccordion .accordion-button {
            background: transparent;
            padding: 1rem 0;
            font-size: 0.925rem;
            font-weight: 600;
            color: var(--bs-body-color);
            box-shadow: none;
            gap: 0.75rem;
            min-height: 48px;
        }

        #faqAccordion .accordion-button:not(.collapsed) {
            background: transparent;
            color: var(--primary);
            box-shadow: none;
        }

        #faqAccordion .accordion-button:focus {
            box-shadow: none;
            border-color: transparent;
        }

        #faqAccordion .accordion-body {
            padding: 0 0 1rem 0;
            font-size: 0.875rem;
            line-height: 1.65;
            color: var(--bs-secondary-color);
        }

        /* ============================
                                                                                       BOTTOM SHEET & DESKTOP DIALOG (Offcanvas)
                                                                                       ============================ */
        .offcanvas-portal {
            height: auto;
            max-height: 90vh;
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem;
            border-top: none;
            box-shadow: 0 -12px 40px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        [data-bs-theme="dark"] .offcanvas-portal {
            box-shadow: 0 -12px 40px rgba(0, 0, 0, 0.6);
        }

        /* Di layar Desktop (>=768px), ubah bottom-sheet menjadi centered dialog modal */
        @media (min-width: 768px) {
            .offcanvas-portal.offcanvas-bottom {
                position: fixed;
                top: 50% !important;
                bottom: auto !important;
                left: 50% !important;
                right: auto !important;
                transform: translate(-50%, -46%) scale(0.96) !important;
                opacity: 0;
                width: 100% !important;
                max-width: 500px !important;
                border-radius: 1.5rem !important;
                border: 1px solid var(--bs-border-color) !important;
                box-shadow: 0 24px 64px rgba(0, 0, 0, 0.3) !important;
                visibility: hidden;
            }

            .offcanvas-portal.offcanvas-bottom.show {
                transform: translate(-50%, -50%) scale(1) !important;
                opacity: 1;
                visibility: visible;
            }

            .sheet-handle {
                display: none !important;
            }
        }

        .sheet-handle {
            width: 48px;
            height: 5px;
            background: var(--bs-secondary-bg);
            border-radius: 999px;
            margin: 0 auto 0.75rem;
            cursor: pointer;
        }

        .sheet-close-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: var(--bs-tertiary-bg);
            color: var(--bs-secondary-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s, color 0.2s;
            flex-shrink: 0;
        }

        .sheet-close-btn:hover {
            background: var(--bs-secondary-bg);
            color: var(--bs-body-color);
        }

        /* Modal icon badge */
        .modal-icon-badge {
            width: 48px;
            height: 48px;
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.35rem;
            border: 1px solid;
        }

        .modal-icon-badge.kantin {
            background: #FFF7ED;
            color: var(--kantin);
            border-color: #FFEDD5;
        }

        .modal-icon-badge.siswa {
            background: #EFF6FF;
            color: var(--siswa);
            border-color: #DBEAFE;
        }

        .modal-icon-badge.ppdb {
            background: #F0FDF4;
            color: var(--ppdb);
            border-color: #DCFCE7;
        }

        .modal-icon-badge.guru {
            background: #EEF2FF;
            color: var(--guru);
            border-color: #E0E7FF;
        }

        [data-bs-theme="dark"] .modal-icon-badge.kantin {
            background: rgba(234, 88, 12, 0.18);
            border-color: rgba(234, 88, 12, 0.35);
        }

        [data-bs-theme="dark"] .modal-icon-badge.siswa {
            background: rgba(37, 99, 235, 0.18);
            border-color: rgba(37, 99, 235, 0.35);
        }

        [data-bs-theme="dark"] .modal-icon-badge.ppdb {
            background: rgba(22, 163, 74, 0.18);
            border-color: rgba(22, 163, 74, 0.35);
        }

        [data-bs-theme="dark"] .modal-icon-badge.guru {
            background: rgba(99, 102, 241, 0.18);
            border-color: rgba(99, 102, 241, 0.35);
        }

        .portal-feature-pill {
            background: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 0.75rem;
            padding: 0.65rem 0.85rem;
            font-size: 0.825rem;
            color: var(--bs-body-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="app-container py-1 py-lg-1 d-flex flex-column gap-4 gap-lg-5">

        <!-- Hero Section -->
        <section class="hero-section text-center px-2 mt-3" style="padding: unset;">
            <span class="hero-badge mb-3">Tahun Ajaran 2026 / 2027</span>
            <h1 class="hero-title text-balance mb-3">
                Selamat datang di <br class="d-none d-sm-inline">
                <span style="color: var(--primary);">Portal Masuk Sekolah</span>
            </h1>
            <p class="hero-subtitle text-pretty mb-0">
                Pilih pintu masuk sesuai peran Anda untuk mengakses layanan akademik, administrasi, dan sistem manajemen
                sekolah secara aman.
            </p>
            <p>
                <i>Calon siswa baru? Daftar PPDB di <a href="">sini</a></i>
            </p>
        </section>

        <!-- Role Cards Grid -->
        <section class="row g-3 g-lg-4 row-cols-1 row-cols-sm-2 row-cols-xl-4 align-items-stretch" style="padding: unset;">

            <!-- 1. Kantin -->
            <div class="col d-flex">
                <article class="role-card kantin p-4 w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="icon-badge kantin">
                                <i class="bi bi-shop"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-body mb-1 lh-sm">Kantin</h3>
                                <p class="small text-secondary mb-0">Untuk kasir &amp; pengelola kantin sekolah.</p>
                            </div>
                        </div>
                        <ul class="feature-list kantin mb-4">
                            <li>Mencatat transaksi penjualan menu</li>
                            <li>Melihat riwayat penjualan kasir</li>
                            <li>Monitoring saldo &amp; voucher siswa</li>
                        </ul>
                    </div>
                    <button
                        class="btn btn-role btn-kantin w-100 d-flex align-items-center justify-content-center gap-2 mt-auto"
                        type="button" onclick="openLoginSheet('kantin')" aria-label="Buka portal login Kantin">
                        <span>Masuk Kantin</span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </article>
            </div>

            <!-- 2. Siswa & Wali Murid -->
            <div class="col d-flex">
                <article class="role-card siswa p-4 w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="icon-badge siswa">
                                <i class="bi bi-people"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-body mb-1 lh-sm">Pendaftar, Siswa &amp; Wali</h3>
                                <p class="small text-secondary mb-0">Untuk calon siswa, siswa, &amp; orang tua/wali.</p>
                            </div>
                        </div>
                        <ul class="feature-list siswa mb-4">
                            <li>Melihat status verifikasi pendaftaran</li>
                            <li>Akses nilai &amp; kartu ujian digital</li>
                            <li>Cek kehadiran &amp; tagihan sekolah</li>
                        </ul>
                    </div>
                    <button
                        class="btn btn-role btn-siswa w-100 d-flex align-items-center justify-content-center gap-2 mt-auto"
                        type="button" onclick="openLoginSheet('siswa')" aria-label="Buka portal login Siswa & Wali Murid">
                        <span>Masuk Portal Siswa</span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </article>
            </div>

            <!-- 3. Admin PPDB -->
            <div class="col d-flex">
                <article class="role-card ppdb p-4 w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="icon-badge ppdb">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-body mb-1 lh-sm">Admin PPDB</h3>
                                <p class="small text-secondary mb-0">Untuk panitia penerimaan siswa baru.</p>
                            </div>
                        </div>
                        <ul class="feature-list ppdb mb-4">
                            <li>Mengelola formulir &amp; berkas online</li>
                            <li>Verifikasi seleksi &amp; validasi dokumen</li>
                            <li>Rekapitulasi jalur masuk &amp; kuota</li>
                        </ul>
                    </div>
                    <button
                        class="btn btn-role btn-ppdb w-100 d-flex align-items-center justify-content-center gap-2 mt-auto"
                        type="button" onclick="openLoginSheet('ppdb')" aria-label="Buka portal login Admin PPDB">
                        <span>Masuk Admin PPDB</span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </article>
            </div>

            <!-- 4. Guru & Admin Sekolah -->
            <div class="col d-flex">
                <article class="role-card guru p-4 w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="icon-badge guru">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-body mb-1 lh-sm">Guru &amp; Admin</h3>
                                <p class="small text-secondary mb-0">Untuk guru, staf dinas, &amp; admin sekolah.</p>
                            </div>
                        </div>
                        <ul class="feature-list guru mb-4">
                            <li>Jadwal mengajar &amp; kurikulum</li>
                            <li>Presensi dan pengisian nilai rapor</li>
                            <li>Manajemen staf, kelas, &amp; laporan</li>
                        </ul>
                    </div>
                    <button
                        class="btn btn-role btn-guru w-100 d-flex align-items-center justify-content-center gap-2 mt-auto"
                        type="button" onclick="openLoginSheet('guru')" aria-label="Buka portal login Guru & Admin Sekolah">
                        <span>Masuk Staf &amp; Guru</span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </article>
            </div>

        </section>

        <!-- FAQ Section -->
        <section class="faq-section p-4 p-md-5" id="faq-section">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="faq-icon">
                    <i class="bi bi-question-lg"></i>
                </div>
                <div>
                    <h2 class="fs-5 fw-bold text-body mb-0">Bingung harus pilih portal yang mana?</h2>
                    <p class="small text-secondary mb-0">Pertanyaan umum seputar akses login akun EduPulse</p>
                </div>
            </div>

            <div class="accordion mt-3" id="faqAccordion">
                <!-- Item 1 -->
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">
                            Saya orang tua yang juga bertugas sebagai guru di sekolah ini
                        </button>
                    </h3>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Gunakan akun <strong>Guru &amp; Admin</strong> untuk menjalankan tugas dinas mengajar dan
                            absensi. Untuk melihat perkembangan akademik atau keuangan putra/putri Anda, masuk melalui
                            portal <strong>Pendaftar, Siswa &amp; Wali</strong>.
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                            Saya baru pertama kali mendaftar sebagai calon peserta didik
                        </button>
                    </h3>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Silakan pilih tombol <strong>Pendaftar, Siswa &amp; Wali</strong> lalu masukkan nomor registrasi
                            PPDB atau akun yang Anda daftarkan saat pendaftaran awal untuk melacak status seleksi dan
                            verifikasi berkas.
                        </div>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                            Bagaimana jika saya salah memilih pintu masuk?
                        </button>
                    </h3>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Tidak masalah! Sistem otentikasi akan menolak jika hak akses tidak sesuai peran akun Anda. Anda
                            dapat kembali ke beranda ini kapan saja untuk memilih portal yang sesuai.
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center small text-secondary mt-4 pt-3 border-top">
                Lupa akun atau butuh bantuan teknis? Hubungi administrator di
                <a href="mailto:support@edupulse.com" class="fw-semibold text-decoration-none"
                    style="color: var(--primary);">
                    support@edupulse.com
                </a>
            </div>
        </section>

    </main>

    <!-- ==================== RESPONSIVE LOGIN OFFCANVAS / MODAL ==================== -->
    <div class="offcanvas offcanvas-bottom offcanvas-portal" tabindex="-1" id="loginSheet"
        aria-labelledby="loginSheetLabel" data-bs-backdrop="true">
        <div class="offcanvas-header pb-0 pt-3 px-4 d-flex align-items-center justify-content-between">
            <div class="sheet-handle"></div>
            <button type="button" class="sheet-close-btn ms-auto" data-bs-dismiss="offcanvas"
                aria-label="Tutup dialog">
                <i class="bi bi-x-lg fs-6"></i>
            </button>
        </div>

        <div class="offcanvas-body pt-2 pb-4 px-4">
            <div class="text-center mb-4">
                <div id="sheetIconBadge" class="modal-icon-badge guru mb-3">
                    <i id="sheetIcon" class="bi bi-person-badge"></i>
                </div>
                <h3 class="fs-5 fw-bold text-body mb-1" id="loginSheetLabel">Masuk ke Portal</h3>
                <p class="small text-secondary mb-0 text-pretty" id="sheetDescription">
                    Silakan lanjut untuk memasukkan akun dan kata sandi Anda.
                </p>
            </div>

            <!-- Features Summary in Modal -->
            <div class="mb-4 d-flex flex-column gap-2" id="sheetFeatureList">
                <div class="portal-feature-pill">
                    <i class="bi bi-shield-check text-success fs-6"></i>
                    <span id="sheetPillText">Akses aman dengan enkripsi kredensial</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2">
                <a id="sheetLoginButton" href="{{ route('login') }}"
                    class="btn btn-role btn-guru w-100 d-flex align-items-center justify-content-center gap-2">
                    <span>Lanjutkan ke Halaman Login</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
                <button type="button" class="btn btn-light py-2 text-secondary" data-bs-dismiss="offcanvas">
                    Batal / Pilih Peran Lain
                </button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
@endpush

@push('scripts')
    <script>
        const portalRoles = {
            kantin: {
                title: 'Portal Kantin & Kasir',
                desc: 'Akses khusus untuk kasir dan pengelola tenant kantin sekolah.',
                icon: 'bi-shop',
                roleClass: 'kantin',
                btnClass: 'btn-kantin',
                url: '{{ route('login') }}',
                pill: 'Pencatatan transaksi dan manajemen saldo digital siswa'
            },
            siswa: {
                title: 'Portal Siswa & Wali Murid',
                desc: 'Akses informasi akademik, status seleksi calon siswa, dan biodata.',
                icon: 'bi-people',
                roleClass: 'siswa',
                btnClass: 'btn-siswa',
                url: '{{ route('ppdb.login') }}',
                pill: 'Pantau kelulusan, jadwal belajar, dan kartu ujian siswa'
            },
            ppdb: {
                title: 'Portal Admin PPDB',
                desc: 'Pintu masuk panitia pendaftaran untuk verifikasi berkas dan kuota jalur masuk.',
                icon: 'bi-clipboard-check',
                roleClass: 'ppdb',
                btnClass: 'btn-ppdb',
                url: '{{ route('ppdb-admin.login') }}',
                pill: 'Manajemen berkas pendaftar baru dan rekapitulasi kuota'
            },
            guru: {
                title: 'Portal Guru & Admin Sekolah',
                desc: 'Akses terpadu untuk pendidik, staf tata usaha, dan admin sekolah.',
                icon: 'bi-person-badge',
                roleClass: 'guru',
                btnClass: 'btn-guru',
                url: '{{ route('login') }}',
                pill: 'Manajemen kurikulum, input nilai rapor, dan presensi'
            }
        };

        function openLoginSheet(roleKey) {
            const config = portalRoles[roleKey];
            if (!config) return;

            // Update title and description
            document.getElementById('loginSheetLabel').textContent = config.title;
            document.getElementById('sheetDescription').textContent = config.desc;
            document.getElementById('sheetPillText').textContent = config.pill;

            // Update icon and badge
            const badge = document.getElementById('sheetIconBadge');
            badge.className = 'modal-icon-badge ' + config.roleClass + ' mb-3';
            document.getElementById('sheetIcon').className = 'bi ' + config.icon;

            // Update button
            const btn = document.getElementById('sheetLoginButton');
            btn.className = 'btn btn-role ' + config.btnClass +
                ' w-100 d-flex align-items-center justify-content-center gap-2';
            btn.href = config.url;

            // Show offcanvas via Bootstrap 5 API
            const sheetElem = document.getElementById('loginSheet');
            if (sheetElem && typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
                const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(sheetElem);
                bsOffcanvas.show();
            } else {
                // Fallback direct redirection
                window.location.href = config.url;
            }
        }
    </script>
@endpush
