 @extends('layouts.front')

@section('content')
  <style>
    /* ============================
       CSS VARIABLES & THEME
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
       BASE
       ============================ */
    body {
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      -webkit-tap-highlight-color: transparent;
      background-color: var(--bs-body-bg);
      min-height: 100vh;
    }

    .safe-top    { padding-top: env(safe-area-inset-top, 0.75rem); }
    .safe-bottom { padding-bottom: env(safe-area-inset-bottom, 1rem); }

    .app-container {
      max-width: 448px;
      margin-left: auto;
      margin-right: auto;
    }

    /* ============================
       HEADER
       ============================ */
    .app-header {
      background: rgba(255, 255, 255, 0.88);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--bs-border-color-translucent);
    }
    [data-bs-theme="dark"] .app-header {
      background: rgba(33, 37, 41, 0.88);
    }

    .brand-icon {
      width: 40px;
      height: 40px;
      border-radius: 0.75rem;
      background: var(--primary);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    }

    .online-badge {
      font-size: 0.6875rem;
      font-weight: 600;
      background: #ECFDF5;
      color: #047857;
      border: 1px solid rgba(16, 185, 129, 0.3);
      padding: 0.25rem 0.625rem;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      white-space: nowrap;
    }
    [data-bs-theme="dark"] .online-badge {
      background: rgba(16, 185, 129, 0.12);
      color: #34D399;
      border-color: rgba(16, 185, 129, 0.25);
    }
    .online-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #10B981;
      animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50%      { opacity: 0.45; }
    }

    .theme-toggle {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: none;
      background: transparent;
      color: var(--bs-secondary-color);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s, color 0.2s;
      flex-shrink: 0;
    }
    .theme-toggle:hover {
      background: var(--bs-tertiary-bg);
      color: var(--primary);
    }

    /* ============================
       HERO
       ============================ */
    .hero-badge {
      display: inline-block;
      font-size: 0.6875rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      background: #EFF6FF;
      color: var(--primary-dark);
      padding: 0.25rem 0.75rem;
      border-radius: 999px;
    }
    [data-bs-theme="dark"] .hero-badge {
      background: rgba(37, 99, 235, 0.15);
      color: #93C5FD;
    }

    /* ============================
       ROLE CARDS
       ============================ */
    .role-card {
      background: var(--bs-body-bg);
      border: 1px solid var(--bs-border-color);
      border-top: 4px solid;
      border-radius: 1rem;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    [data-bs-theme="dark"] .role-card {
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
    }
    .role-card:hover { transform: translateY(-2px); }

    .role-card.kantin { border-top-color: var(--kantin); }
    .role-card.siswa  { border-top-color: var(--siswa); }
    .role-card.ppdb   { border-top-color: var(--ppdb); }
    .role-card.guru   { border-top-color: var(--guru); }

    .role-card h3 { font-size: 1.125rem; }

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
    }
    .icon-badge.kantin { background: #FFF7ED; color: var(--kantin); border-color: #FFEDD5; }
    .icon-badge.siswa  { background: #EFF6FF; color: var(--siswa);  border-color: #DBEAFE; }
    .icon-badge.ppdb   { background: #F0FDF4; color: var(--ppdb);   border-color: #DCFCE7; }
    .icon-badge.guru   { background: #EEF2FF; color: var(--guru);   border-color: #E0E7FF; }

    [data-bs-theme="dark"] .icon-badge.kantin { background: rgba(234, 88, 12, 0.12);  border-color: rgba(234, 88, 12, 0.25); }
    [data-bs-theme="dark"] .icon-badge.siswa  { background: rgba(37, 99, 235, 0.12);  border-color: rgba(37, 99, 235, 0.25); }
    [data-bs-theme="dark"] .icon-badge.ppdb   { background: rgba(22, 163, 74, 0.12);  border-color: rgba(22, 163, 74, 0.25); }
    [data-bs-theme="dark"] .icon-badge.guru   { background: rgba(99, 102, 241, 0.12); border-color: rgba(99, 102, 241, 0.25); }

    /* Feature list dengan bullet berwarna */
    .feature-list {
      list-style: none;
      padding-left: 0;
      margin-bottom: 0;
    }
    .feature-list li {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.75rem;
      color: var(--bs-secondary-color);
      margin-bottom: 0.5rem;
    }
    .feature-list li:last-child { margin-bottom: 0; }
    .feature-list li::before {
      content: '';
      width: 6px;
      height: 6px;
      border-radius: 50%;
      flex-shrink: 0;
    }
    .feature-list.kantin li::before { background: var(--kantin); }
    .feature-list.siswa  li::before { background: var(--siswa); }
    .feature-list.ppdb   li::before { background: var(--ppdb); }
    .feature-list.guru   li::before { background: var(--guru); }

    /* ============================
       ROLE BUTTONS
       ============================ */
    .btn.btn-role {
      border-radius: 0.75rem;
      font-weight: 600;
      font-size: 0.875rem;
      padding: 0.75rem 1rem;
      color: #fff;
      border: none;
      transition: all 0.15s ease;
    }
    .btn.btn-role:hover  { color: #fff; }
    .btn.btn-role:active { transform: scale(0.98); }

    .btn.btn-kantin { background: var(--kantin); }
    .btn.btn-kantin:hover { background: var(--kantin-dark); box-shadow: 0 6px 18px rgba(234, 88, 12, 0.3); }

    .btn.btn-siswa { background: var(--siswa); }
    .btn.btn-siswa:hover { background: var(--siswa-dark); box-shadow: 0 6px 18px rgba(37, 99, 235, 0.3); }

    .btn.btn-ppdb { background: var(--ppdb); }
    .btn.btn-ppdb:hover { background: var(--ppdb-dark); box-shadow: 0 6px 18px rgba(22, 163, 74, 0.3); }

    .btn.btn-guru { background: var(--guru); }
    .btn.btn-guru:hover { background: var(--guru-dark); box-shadow: 0 6px 18px rgba(99, 102, 241, 0.3); }

    /* ============================
       FAQ SECTION
       ============================ */
    .faq-section {
      background: var(--bs-body-bg);
      border: 1px solid var(--bs-border-color);
      border-radius: 1rem;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    }
    [data-bs-theme="dark"] .faq-section {
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
    }

    .faq-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #EFF6FF;
      color: var(--primary);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    [data-bs-theme="dark"] .faq-icon {
      background: rgba(37, 99, 235, 0.15);
    }

    /* Custom accordion */
    #faqAccordion .accordion-item {
      background: transparent;
      border: none;
      border-bottom: 1px solid var(--bs-border-color);
    }
    #faqAccordion .accordion-item:last-child { border-bottom: none; }

    #faqAccordion .accordion-button {
      background: transparent;
      padding: 0.75rem 0;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--bs-body-color);
      box-shadow: none;
      gap: 0.5rem;
    }
    #faqAccordion .accordion-button:not(.collapsed) {
      background: transparent;
      color: var(--primary);
      box-shadow: none;
    }
    #faqAccordion .accordion-button::after {
      transform: scale(0.8);
    }
    #faqAccordion .accordion-button:focus {
      box-shadow: none;
      border-color: transparent;
    }
    #faqAccordion .accordion-body {
      padding: 0 0 0.75rem 0;
      font-size: 0.7rem;
      line-height: 1.6;
      color: var(--bs-secondary-color);
    }

    /* ============================
       FOOTER
       ============================ */
    .app-footer {
      font-size: 0.6875rem;
      color: var(--bs-tertiary-color);
    }
    .app-footer a {
      color: var(--bs-secondary-color);
      text-decoration: none;
    }
    .app-footer a:hover { text-decoration: underline; }

    /* ============================
       BOTTOM SHEET (Offcanvas)
       ============================ */
    .offcanvas-bottom {
      max-width: 448px;
      left: 0;
      right: 0;
      margin: 0 auto;
      border-top-left-radius: 1.5rem;
      border-top-right-radius: 1.5rem;
      border-top: none;
      box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.15);
    }
    [data-bs-theme="dark"] .offcanvas-bottom {
      box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.5);
    }

    .sheet-handle {
      width: 48px;
      height: 6px;
      background: var(--bs-secondary-bg);
      border-radius: 999px;
      margin: 0 auto 1rem;
      cursor: pointer;
    }

    .sheet-close-btn {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: none;
      background: var(--bs-tertiary-bg);
      color: var(--bs-secondary-color);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
      flex-shrink: 0;
    }
    .sheet-close-btn:hover { background: var(--bs-secondary-bg); }

    /* Modal icon badge */
    .modal-icon-badge {
      width: 40px;
      height: 40px;
      border-radius: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .modal-icon-badge.kantin { background: #FFF7ED; color: var(--kantin); }
    .modal-icon-badge.siswa  { background: #EFF6FF; color: var(--siswa); }
    .modal-icon-badge.ppdb   { background: #F0FDF4; color: var(--ppdb); }
    .modal-icon-badge.guru   { background: #EEF2FF; color: var(--guru); }

    [data-bs-theme="dark"] .modal-icon-badge.kantin { background: rgba(234, 88, 12, 0.15); }
    [data-bs-theme="dark"] .modal-icon-badge.siswa  { background: rgba(37, 99, 235, 0.15); }
    [data-bs-theme="dark"] .modal-icon-badge.ppdb   { background: rgba(22, 163, 74, 0.15); }
    [data-bs-theme="dark"] .modal-icon-badge.guru   { background: rgba(99, 102, 241, 0.15); }

    /* Form tweaks */
    #portal-login-form .form-control {
      font-size: 0.875rem;
      border-radius: 0.75rem;
      padding: 0.625rem 0.875rem;
      background-color: var(--bs-tertiary-bg);
      border-color: var(--bs-border-color);
    }
    #portal-login-form .form-control:focus {
      background-color: var(--bs-body-bg);
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    /* ============================
       RESPONSIVE
       ============================ */
    @media (max-width: 575.98px) {
      .app-container { padding-left: 0.875rem; padding-right: 0.875rem; }
    }
  </style> 
  <!-- ==================== MAIN ==================== -->
  <main class="app-container px-3 pt-4 pb-5 d-flex flex-column gap-4">

    <!-- Hero -->
    <section class="text-center px-2">
      <span class="hero-badge mb-3">Tahun Ajaran 2026 / 2027</span>
      <h2 class="fs-4 fw-bolder text-body lh-sm mb-2">
        Selamat datang di <br>
        <span style="color: var(--primary);">Portal Masuk Sekolah</span>
      </h2>
      <p class="small text-secondary mb-0">
        Pilih pintu masuk sesuai peran Anda untuk mengakses layanan akademik dan manajemen sekolah.
      </p>
    </section>

    <!-- Role Cards -->
    <section class="d-flex flex-column gap-3">

      <!-- Kantin -->
      <article class="role-card kantin p-4">
        <div class="d-flex align-items-start gap-3 mb-3">
          <div class="icon-badge kantin">
            <i class="bi bi-shop fs-5"></i>
          </div>
          <div>
            <h3 class="fw-bold text-body mb-1 lh-sm">Kantin</h3>
            <p class="small text-secondary mb-0">Untuk kasir dan petugas kantin sekolah.</p>
          </div>
        </div>
        <ul class="feature-list kantin mb-4">
          <li>Mencatat transaksi penjualan</li>
          <li>Melihat riwayat penjualan</li>
          <li>Monitoring saldo &amp; voucher siswa</li>
        </ul>
        <button class="btn btn-role btn-kantin w-100 d-flex align-items-center justify-content-center gap-2"
                type="button" onclick="openLoginSheet('kantin')">
          <span>Masuk Kantin</span>
          <i class="bi bi-chevron-right"></i>
        </button>
      </article>

      <!-- Siswa -->
      <article class="role-card siswa p-4">
        <div class="d-flex align-items-start gap-3 mb-3">
          <div class="icon-badge siswa">
            <i class="bi bi-people fs-5"></i>
          </div>
          <div>
            <h3 class="fw-bold text-body mb-1 lh-sm">Pendaftar, Siswa &amp; Wali Murid</h3>
            <p class="small text-secondary mb-0">Untuk calon siswa, siswa, dan orang tua/wali.</p>
          </div>
        </div>
        <ul class="feature-list siswa mb-4">
          <li>Melihat biodata siswa</li>
          <li>Memantau data pendaftaran</li>
          <li>Akses nilai &amp; kartu ujian digital</li>
        </ul>
        <button class="btn btn-role btn-siswa w-100 d-flex align-items-center justify-content-center gap-2"
                type="button" onclick="openLoginSheet('siswa')">
          <span>Masuk Portal</span>
          <i class="bi bi-chevron-right"></i>
        </button>
      </article>

      <!-- PPDB -->
      <article class="role-card ppdb p-4">
        <div class="d-flex align-items-start gap-3 mb-3">
          <div class="icon-badge ppdb">
            <i class="bi bi-clipboard-check fs-5"></i>
          </div>
          <div>
            <h3 class="fw-bold text-body mb-1 lh-sm">Admin PPDB</h3>
            <p class="small text-secondary mb-0">Untuk panitia penerimaan peserta didik baru.</p>
          </div>
        </div>
        <ul class="feature-list ppdb mb-4">
          <li>Mengelola data pendaftar online</li>
          <li>Verifikasi berkas dan seleksi berkas</li>
          <li>Rekapitulasi jalur masuk &amp; kuota</li>
        </ul>
        <button class="btn btn-role btn-ppdb w-100 d-flex align-items-center justify-content-center gap-2"
                type="button" onclick="openLoginSheet('ppdb')">
          <span>Masuk Admin PPDB</span>
          <i class="bi bi-chevron-right"></i>
        </button>
      </article>

      <!-- Guru -->
      <article class="role-card guru p-4">
        <div class="d-flex align-items-start gap-3 mb-3">
          <div class="icon-badge guru">
            <i class="bi bi-person-badge fs-5"></i>
          </div>
          <div>
            <h3 class="fw-bold text-body mb-1 lh-sm">Guru &amp; Admin Sekolah</h3>
            <p class="small text-secondary mb-0">Untuk guru, tenaga kependidikan, dan admin sekolah.</p>
          </div>
        </div>
        <ul class="feature-list guru mb-4">
          <li>Mengelola data sekolah &amp; jadwal</li>
          <li>Mengakses dashboard staf &amp; pengumuman</li>
          <li>Presensi dan pengisian nilai rapor</li>
        </ul>
        <button class="btn btn-role btn-guru w-100 d-flex align-items-center justify-content-center gap-2"
                type="button" onclick="openLoginSheet('guru')">
          <span>Masuk Staf</span>
          <i class="bi bi-chevron-right"></i>
        </button>
      </article>

    </section>
    

    <!-- FAQ -->
    <section class="faq-section p-4" id="faq-section">
      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="faq-icon">
          <i class="bi bi-question-lg"></i>
        </div>
        <h3 class="fs-6 fw-bold text-body mb-0">Bingung harus pilih yang mana?</h3>
      </div>

      <div class="accordion" id="faqAccordion">
        <!-- Item 1 -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq1"
                    aria-expanded="false" aria-controls="faq1">
              Saya orang tua yang juga guru di sekolah ini
            </button>
          </h2>
          <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Gunakan akun <strong>Guru &amp; Admin Sekolah</strong> untuk tugas dinas. Untuk melihat
              perkembangan akademik putra/putri Anda, Anda dapat beralih profil atau masuk menggunakan
              portal <strong>Pendaftar, Siswa &amp; Wali Murid</strong>.
            </div>
          </div>
        </div>

        <!-- Item 2 -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq2"
                    aria-expanded="false" aria-controls="faq2">
              Saya baru mendaftar sebagai calon siswa
            </button>
          </h2>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Silakan pilih pintu <strong>Pendaftar, Siswa &amp; Wali Murid</strong> lalu masukkan nomor
              registrasi PPDB yang Anda dapatkan saat pendaftaran awal untuk melacak status seleksi.
            </div>
          </div>
        </div>

        <!-- Item 3 -->
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq3"
                    aria-expanded="false" aria-controls="faq3">
              Saya salah pilih portal, apa yang terjadi?
            </button>
          </h2>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Tidak masalah! Sistem keamanan hanya akan menolak login bila kredensial tidak sesuai role.
              Anda bisa kembali ke beranda ini kapan saja dan memilih peran yang tepat.
            </div>
          </div>
        </div>
      </div>

      <div class="text-center small text-secondary mt-3 pt-3 border-top">
        Lupa akun atau masih ragu? Hubungi admin di
        <a href="mailto:hello@example.com" class="fw-medium text-decoration-none" style="color: var(--primary);">
          hello@example.com
        </a>
      </div>
    </section>

  </main> 
@endsection

@push('styles')
    <link style="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
@endpush

@push('scripts')
    {{-- Tambahan JS khusus halaman ini (jika ada) --}}
@endpush