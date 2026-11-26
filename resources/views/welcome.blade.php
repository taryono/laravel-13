@extends('layouts.front')

@section('content')
    <!-- HERO -->

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
                                    <a href="{{ $slider->link_url }}" target="{{ $slider->link_target }}"
                                        class="d-block h-100">
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
                        <a href="/akademik" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-user-group"></i></div>
                        <h5>Kesiswaan</h5>
                        <p>PPDB Online, profil lengkap siswa, hingga manajemen alumni dan prestasi.</p>
                        <a href="/kesiswaan" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-wallet"></i></div>
                        <h5>Keuangan</h5>
                        <p>Pembayaran SPP Online (QRIS/VA), laporan BOS, dan RKAS otomatis.</p>
                        <a href="/keuangan" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
                        <h5>Absensi</h5>
                        <p>QR Code, RFID, hingga Face Recognition dengan notifikasi WhatsApp ke orang tua.</p>
                        <a href="/absensi" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-people-roof"></i></div>
                        <h5>Parent Portal</h5>
                        <p>Dashboard khusus orang tua untuk pantau nilai, absensi, dan pembayaran real-time.</p>
                        <a href="/parent-portal" class="detail-link">Lihat Detail →</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                        <h5>Analitik AI</h5>
                        <p>Prediksi kelulusan dan analisis perkembangan akademik berbasis kecerdasan buatan.</p>
                        <a href="/analitik" class="detail-link">Lihat Detail →</a>
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
                            <a href="/dokumentasi?">Pelajari →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-newspaper"></i></div>
                        <div>
                            <h6>Blog Edukasi</h6>
                            <p>Artikel tips implementasi teknologi di sekolah dan tren pendidikan terbaru.</p>
                            <a href="/blog">Baca Artikel →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-headset"></i></div>
                        <div>
                            <h6>Kontak Sales</h6>
                            <p>Hubungi tim kami untuk trial kustom atau demo lanjutan khusus sekolah Anda.</p>
                            <a href="/kontak">Hubungi Kami →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <div>
                            <h6>Keamanan Data</h6>
                            <p>Informasi tentang bagaimana kami menjaga privasi dan keamanan data sekolah.</p>
                            <a href="/security">Selengkapnya →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-circle-question"></i></div>
                        <div>
                            <h6>FAQ</h6>
                            <p>Jawaban atas pertanyaan yang paling sering diajukan oleh pengguna baru.</p>
                            <a href="/faq">Lihat Pertanyaan →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="resource-item">
                        <div class="resource-icon"><i class="fa-solid fa-bell"></i></div>
                        <div>
                            <h6>Update Terbaru</h6>
                            <p>Catatan rilis fitur-fitur baru yang kami luncurkan setiap bulannya.</p>
                            <a href="/changelog?">Cek Changelog →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
@endpush

@push('scripts')
    {{-- Tambahan JS khusus halaman ini (jika ada) --}}
@endpush
