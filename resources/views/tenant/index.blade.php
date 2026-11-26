@extends('layouts.tenant')

@section('content')
    <!-- ===== HERO ===== -->
    <section class="hero bg-ep">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 d-flex flex-column gap-3">
                    <span class="chip c-sec d-inline-flex align-items-center gap-2 align-self-start"><span
                            class="pulse"></span>Terakreditasi A (Unggul) • NPSN 20109988</span>
                    <h1>SMA Nusantara <span class="t-teal">Unggul</span></h1>
                    <p class="fs-5 fw-600 t-muted mb-0">Mencetak Generasi Berkarakter, Inovatif, dan Berprestasi
                        Global</p>
                    <p class="t-muted mb-0" style="font-size:1rem;max-width:36rem">Lingkungan belajar modern
                        berbasis teknologi EduPortal yang memadukan Kurikulum Merdeka, riset ilmiah, dan pembinaan
                        karakter kepemimpinan.</p>
                    <div class="d-flex flex-wrap gap-3 pt-2">
                        <a class="btn-ep py-3 px-4" target="_blank" href="/ppdb">Daftar PPDB 2025/2026 <i
                                class="ms ms-18">arrow_forward</i></a>
                        <a class="btn-card py-3 px-4" href="#profil"><i class="ms ms-18">explore</i> Jelajahi
                            Profil</a>
                    </div>
                </div>
                <div class="col-lg-6 position-relative">
                    <button class="hero-img" type="button" data-bs-toggle="modal" data-bs-target="#videoModal"
                        aria-label="Putar video tur kampus">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCTPazXnYY4sqnTZa78W-lc9ULw7hO027u46gGtXohaxG4wWAoZgEmCXzJR8c8zo-U3YBdXnqvJ6_DgSHcPdh4hXJjmhIfM4LlbVcrjOYKYizUm0mcmUGG5jydmV4imunXsPLeIZ1r2wHouQpXDruqObJM4AbY9IKgnDZMj6KBn8KtSre7yD9pXw1GWh92gn0Y_cvX5Ox6rl56EjLKPawpDlD4THwBLTb5JJAmvKbXa"
                            alt="Gedung kampus SMA Nusantara dengan taman tropis">
                        <span class="ov"><span><small class="fw-bold text-uppercase"
                                    style="color:#89f5e7;letter-spacing:.08em">Virtual Campus Tour</small><span
                                    class="d-block fw-600" style="font-size:1rem">Lihat Kehidupan Kampus SMA
                                    Nusantara</span></span><span class="play"><i
                                    class="ms ms-32 fill">play_arrow</i></span></span>
                    </button>
                    <div class="ep-card static float-badge d-none d-sm-flex">
                        <span class="ibox c-ter"><i class="ms fill">workspace_premium</i></span>
                        <div>
                            <div class="fw-600">Top 10 SMA Terbaik</div>
                            <div class="fs-xs t-muted">Peringkat LTMPT Nasional</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== BANNER INFO ===== -->
    <section class="bg-brand py-3">
        <div
            class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
            <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                <span class="chip c-ter text-uppercase">Info Penting</span>
                <span><strong>Pendaftaran PPDB Gelombang 1 Resmi Dibuka hingga 30 Mei 2025</strong> • Beasiswa
                    Prestasi Akademik &amp; Non-Akademik Tersedia</span>
            </div>
            <a class="btn-card py-1 px-3 flex-shrink-0" href="#ppdb-section">Lihat Detail <i
                    class="ms ms-18">chevron_right</i></a>
        </div>
    </section>

    <!-- ===== STATISTIK ===== -->
    <section class="sec bg-ep">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="ep-card p-4 h-100"><span class="ibox c-pri mb-3"><i class="ms">groups</i></span>
                        <div class="t-head" style="font-size:2.5rem;font-weight:700;line-height:1.2">1.180+</div>
                        <div class="fw-600" style="font-size:1rem">Siswa Aktif</div>
                        <div class="fs-xs t-muted">Dari berbagai daerah di Indonesia</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="ep-card p-4 h-100"><span class="ibox c-sec mb-3"><i
                                class="ms">cast_for_education</i></span>
                        <div class="t-teal" style="font-size:2.5rem;font-weight:700;line-height:1.2">85+</div>
                        <div class="fw-600" style="font-size:1rem">Guru &amp; Staf Tersertifikasi</div>
                        <div class="fs-xs t-muted">Lulusan S1 &amp; S2 universitas ternama</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="ep-card p-4 h-100"><span class="ibox c-ter mb-3"><i class="ms">school</i></span>
                        <div class="t-head" style="font-size:2.5rem;font-weight:700;line-height:1.2">96%</div>
                        <div class="fw-600" style="font-size:1rem">Lulusan PTN/PTS Favorit</div>
                        <div class="fs-xs t-muted">Diterima di kampus top dalam &amp; luar negeri</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="ep-card p-4 h-100"><span class="ibox c-hi mb-3"><i class="ms">emoji_events</i></span>
                        <div class="t-teal" style="font-size:2.5rem;font-weight:700;line-height:1.2">120+</div>
                        <div class="fw-600" style="font-size:1rem">Prestasi Nasional &amp; Global</div>
                        <div class="fs-xs t-muted">Medali OSN, KSN, &amp; kompetisi internasional</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PROFIL ===== -->
    <section class="sec bg-low" id="profil">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 position-relative">
                    <div class="ratio rounded-4 overflow-hidden shadow" style="--bs-aspect-ratio:125%"><img
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4CXNUIp-5OjyF6dpuqzkwqrh2Qqmc-JWrBTlXMFDgVXNVOc6jero8cmgC7jsYqoNoQ3-VOUohRnacUuG-5xw-t8S9B3QvwFErzKHNAEMhKmDLCal8RAlhA9Uu-ULw-lg8byyZTCqy2VWBhnbKuohfFFeZWuP6QeFdr1b_iMwDgfZgerZgDix4LFjbfBnpyAfpfvLsXYA3-RuSzwanVDjsDPEgssT9VW6ca9FXtoXV"
                            alt="Siswa berkolaborasi di laboratorium sains" class="object-fit-cover"></div>
                    <div class="ep-card static position-absolute top-0 end-0 m-3 px-3 py-2 d-flex align-items-center gap-2">
                        <i class="ms t-teal fill">verified</i><span class="fw-600 t-head fs-xs">Akreditasi A
                            (Unggul)</span>
                    </div>
                </div>
                <div class="col-lg-7 d-flex flex-column gap-4">
                    <div><span class="label-sm">Mengapa SMA Nusantara</span>
                        <h2 class="h-title mt-1">Pendidikan Holistik untuk Pemimpin Masa Depan</h2>
                        <p class="t-muted mt-2 mb-0" style="font-size:1rem">Sejak berdiri pada tahun 1998, SMA
                            Nusantara berkomitmen menghadirkan ekosistem belajar yang adaptif, humanis, dan
                            berorientasi masa depan dengan standar mutu tertinggi.</p>
                    </div>
                    <div class="ep-card static p-4 d-flex gap-3"><span class="ibox c-pri"><i
                                class="ms">menu_book</i></span>
                        <div>
                            <h3 class="fs-6 fw-600 mb-1">Kurikulum Merdeka Terintegrasi</h3>
                            <p class="t-muted mb-0">Pembelajaran berbasis proyek (P5) yang disesuaikan dengan minat
                                bakat siswa serta persiapan intensif UTBK-SNBT.</p>
                        </div>
                    </div>
                    <div class="ep-card static p-4 d-flex gap-3"><span class="ibox c-sec"><i
                                class="ms">devices</i></span>
                        <div>
                            <h3 class="fs-6 fw-600 mb-1">Smart Campus &amp; E-Learning</h3>
                            <p class="t-muted mb-0">Terhubung penuh dengan platform EduPortal untuk absensi
                                real-time, materi digital, rapor online, dan komunikasi orang tua.</p>
                        </div>
                    </div>
                    <div class="ep-card static p-4 d-flex gap-3"><span class="ibox c-ter"><i
                                class="ms">psychology</i></span>
                        <div>
                            <h3 class="fs-6 fw-600 mb-1">Pembinaan Karakter &amp; Kepemimpinan</h3>
                            <p class="t-muted mb-0">Program mentoring rutin, kegiatan organisasi siswa, dan
                                pengabdian masyarakat untuk membentuk integritas dan empati.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PROGRAM AKADEMIK ===== -->
    <section class="sec bg-ep" id="akademik">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-5">
                <div><span class="label-sm">Program Akademik</span>
                    <h2 class="h-title mt-1 mb-0">Jalur Pembelajaran Unggulan</h2>
                </div>
                <a class="t-teal fw-600 d-flex align-items-center gap-1" href="#">Lihat Kurikulum Lengkap <i
                        class="ms ms-18">arrow_forward</i></a>
            </div>
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="ep-card p-4 h-100 d-flex flex-column justify-content-between gap-3">
                        <div><span class="ibox c-pri mb-3"><i class="ms">auto_stories</i></span>
                            <h3 class="fs-6 fw-600">Kelas Reguler</h3>
                            <p class="t-muted mb-0">Kurikulum nasional komprehensif dengan pendampingan belajar
                                kelompok kecil dan peminatan mata pelajaran.</p>
                        </div><span class="label-sm" style="color:var(--ep-head)">Fase E &amp; F</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="ep-card p-4 h-100 d-flex flex-column justify-content-between gap-3">
                        <div><span class="ibox c-sec mb-3"><i class="ms">biotech</i></span>
                            <h3 class="fs-6 fw-600">Kelas Riset &amp; Olimpiade</h3>
                            <p class="t-muted mb-0">Fokus pada penelitian ilmiah remaja, bimbingan intensif
                                OSN/KSN, serta publikasi karya tulis.</p>
                        </div><span class="label-sm">Seleksi Khusus</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="ep-card p-4 h-100 d-flex flex-column justify-content-between gap-3">
                        <div><span class="ibox c-ter mb-3"><i class="ms">public</i></span>
                            <h3 class="fs-6 fw-600">Program Bilingual</h3>
                            <p class="t-muted mb-0">Pengantar Bahasa Inggris aktif untuk mata pelajaran sains &amp;
                                matematika dengan sertifikasi internasional.</p>
                        </div><span class="label-sm" style="color:var(--ep-text)">Cambridge Ready</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="ep-card brand p-4 h-100 d-flex flex-column justify-content-between gap-3">
                        <div><span class="ibox mb-3" style="background:rgba(255,255,255,.2)"><i
                                    class="ms">hub</i></span>
                            <h3 class="fs-6 fw-600">Ekosistem EduPortal</h3>
                            <p class="t-soft mb-0">Akses materi digital 24/7, tryout CBT berkala, dan pemantauan
                                progres akademik secara transparan.</p>
                        </div><span class="label-sm" style="color:#89f5e7">Terintegrasi</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== BERITA & AGENDA ===== -->
    <section class="sec bg-low" id="berita">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <div><span class="label-sm">Kabar Sekolah</span>
                            <h2 class="h-title mt-1 mb-0">Berita &amp; Prestasi Terbaru</h2>
                        </div>
                        <a class="t-teal fw-600 text-nowrap ms-3" href="{{ url('/berita') }}">Semua Berita →</a>
                    </div>
                    @php
                        $cmsPosts = $posts ?? \Models\CmsPost::with('category')->published()->latest('published_at')->take(3)->get();

                        $defaultNewsImages = [
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuBj_cSkvGBnDbQ7lu4mg6rYcb6v4yYOwahHDY23drliDuqLKzSeIBOJwQ88f1JF8Qybb5HGjyVVhuhCwECYCekezQZKx2iY14ATezXem_nfbXQs5-qCD97yHIcMaYXUlX75dCWhZEeEKuI04x737XW9zsBsTojjfpCxaF7hs5PumYgglZcxCAotd9VOwZD2XCgxikAZfgtfBBIPd8MUMPsmDyyYttBVU7yP0HGdf8MW',
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuDLVr6z7KpKqM0joMBJXxOdnxAltKhTLIFVwnvh_E_Q4_FbK74jIXQGSKPUNJAqCMQm5YfMoI5JpAT_2WC810RNSshy9TVcAgVrxHgAYVKHQE90p_A9BYiorwa6ou_X7dZF9Uw3QFXnzclbvipPgaMcvrRC5H8QgLE0x9q0Ddts13sKihHq8KEAGN4KinqBO7N48T3e2VRQD-REQ0vE7vjY33irthGeIcFcKjzuwmm6',
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuAyRZ5OPsKAqdko2DQOpnbdpZDO-ONP88CBl9BYbMrod2VGKcS-QKJRqh4fkcfnVeqdALCt-KwWfXVIADQOhGjq0q44ulqxI7STXf7sA9AdRdySr4I9VRrdDR0eCKQyHe4Hr_YWruZuXA0Rmo299lk-p1nt-ANaCmrQ7BPd7K84ienMIgeez-2MRqx_qUcVAt-_GApocfXRNthY4KR0F7uISV-FTirXYTQg3EKd7vmI',
                        ];
                        $chipClasses = ['c-sec', 'c-pri', 'c-ter'];
                    @endphp
                    <div class="row g-3">
                        @if($cmsPosts->isNotEmpty())
                            @foreach($cmsPosts as $post)
                                @php
                                    $postImage = null;
                                    if (!empty($post->featured_image)) {
                                        if (\Illuminate\Support\Str::startsWith($post->featured_image, ['http://', 'https://'])) {
                                            $postImage = $post->featured_image;
                                        } elseif (file_exists(public_path('storage/' . $post->featured_image)) || \Illuminate\Support\Facades\Storage::disk('public')->exists($post->featured_image)) {
                                            $postImage = asset('storage/' . $post->featured_image);
                                        } elseif (file_exists(public_path($post->featured_image))) {
                                            $postImage = asset($post->featured_image);
                                        }
                                    }
                                    $imgSrc = $postImage ?: ($defaultNewsImages[$loop->index % count($defaultNewsImages)]);
                                    $chipClass = $chipClasses[$loop->index % count($chipClasses)];
                                    $categoryName = $post->category?->name ?? 'Berita';
                                    $publishedDate = $post->published_at
                                        ? $post->published_at->translatedFormat('d M Y')
                                        : ($post->created_at ? $post->created_at->translatedFormat('d M Y') : '');
                                @endphp
                                <div class="col-12 col-md-4">
                                    <article class="ep-card h-100 overflow-hidden d-flex flex-column">
                                        <div class="ratio zoom" style="--bs-aspect-ratio:62.5%"><img
                                                src="{{ $imgSrc }}"
                                                alt="{{ $post->title }}" loading="lazy"></div>
                                        <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between gap-2">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-center mb-2"><span
                                                        class="chip {{ $chipClass }}">{{ $categoryName }}</span><span
                                                        class="fs-xs t-muted">{{ $publishedDate }}</span></div>
                                                <h3 class="fs-6 fw-600 clamp2">{{ $post->title }}</h3>
                                                <p class="fs-xs t-muted mb-0 clamp2">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                                            </div><a class="t-head fw-600" href="{{ url('/berita/' . $post->slug) }}">Baca selengkapnya →</a>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 col-md-4">
                                <article class="ep-card h-100 overflow-hidden d-flex flex-column">
                                    <div class="ratio zoom" style="--bs-aspect-ratio:62.5%"><img
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBj_cSkvGBnDbQ7lu4mg6rYcb6v4yYOwahHDY23drliDuqLKzSeIBOJwQ88f1JF8Qybb5HGjyVVhuhCwECYCekezQZKx2iY14ATezXem_nfbXQs5-qCD97yHIcMaYXUlX75dCWhZEeEKuI04x737XW9zsBsTojjfpCxaF7hs5PumYgglZcxCAotd9VOwZD2XCgxikAZfgtfBBIPd8MUMPsmDyyYttBVU7yP0HGdf8MW"
                                            alt="Siswa memegang medali emas olimpiade sains" loading="lazy"></div>
                                    <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between gap-2">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2"><span
                                                    class="chip c-sec">Prestasi</span><span class="fs-xs t-muted">12
                                                    Mei 2025</span></div>
                                            <h3 class="fs-6 fw-600 clamp2">Tim Robotik SMA Nusantara Raih Medali Emas
                                                di Ajang Nasional</h3>
                                            <p class="fs-xs t-muted mb-0 clamp2">Inovasi robot pemilah sampah otomatis
                                                berhasil mengungguli 40 tim dari seluruh Indonesia.</p>
                                        </div><a class="t-head fw-600" href="#">Baca selengkapnya →</a>
                                    </div>
                                </article>
                            </div>
                            <div class="col-12 col-md-4">
                                <article class="ep-card h-100 overflow-hidden d-flex flex-column">
                                    <div class="ratio zoom" style="--bs-aspect-ratio:62.5%"><img
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDLVr6z7KpKqM0joMBJXxOdnxAltKhTLIFVwnvh_E_Q4_FbK74jIXQGSKPUNJAqCMQm5YfMoI5JpAT_2WC810RNSshy9TVcAgVrxHgAYVKHQE90p_A9BYiorwa6ou_X7dZF9Uw3QFXnzclbvipPgaMcvrRC5H8QgLE0x9q0Ddts13sKihHq8KEAGN4KinqBO7N48T3e2VRQD-REQ0vE7vjY33irthGeIcFcKjzuwmm6"
                                            alt="Sosialisasi kurikulum untuk siswa dan orang tua" loading="lazy">
                                    </div>
                                    <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between gap-2">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2"><span
                                                    class="chip c-pri">Akademik</span><span class="fs-xs t-muted">08
                                                    Mei 2025</span></div>
                                            <h3 class="fs-6 fw-600 clamp2">Sosialisasi Kurikulum Merdeka &amp;
                                                Peminatan Tahun Ajaran Baru</h3>
                                            <p class="fs-xs t-muted mb-0 clamp2">Panduan lengkap bagi siswa kelas X
                                                dalam memilih mata pelajaran peminatan sesuai bakat.</p>
                                        </div><a class="t-head fw-600" href="#">Baca selengkapnya →</a>
                                    </div>
                                </article>
                            </div>
                            <div class="col-12 col-md-4">
                                <article class="ep-card h-100 overflow-hidden d-flex flex-column">
                                    <div class="ratio zoom" style="--bs-aspect-ratio:62.5%"><img
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAyRZ5OPsKAqdko2DQOpnbdpZDO-ONP88CBl9BYbMrod2VGKcS-QKJRqh4fkcfnVeqdALCt-KwWfXVIADQOhGjq0q44ulqxI7STXf7sA9AdRdySr4I9VRrdDR0eCKQyHe4Hr_YWruZuXA0Rmo299lk-p1nt-ANaCmrQ7BPd7K84ienMIgeez-2MRqx_qUcVAt-_GApocfXRNthY4KR0F7uISV-FTirXYTQg3EKd7vmI"
                                            alt="Siswa menanam pohon di taman sekolah" loading="lazy"></div>
                                    <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between gap-2">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2"><span
                                                    class="chip c-ter">Kegiatan</span><span class="fs-xs t-muted">02
                                                    Mei 2025</span></div>
                                            <h3 class="fs-6 fw-600 clamp2">Peringatan Hardiknas: Gerakan Sekolah Hijau
                                                &amp; Bebas Plastik</h3>
                                            <p class="fs-xs t-muted mb-0 clamp2">Seluruh warga sekolah berpartisipasi
                                                dalam penanaman 200 bibit pohon di area kampus.</p>
                                        </div><a class="t-head fw-600" href="#">Baca selengkapnya →</a>
                                    </div>
                                </article>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <span class="label-sm">Kalender</span>
                    <h2 class="h-title mt-1 mb-4">Agenda Mendatang</h2>
                    <div class="ep-card static p-4 d-flex flex-column gap-3">
                        <div class="agenda d-flex gap-3">
                            <div class="dbox" style="background:var(--ep-brand)"><small>Mei</small>24</div>
                            <div class="min-w-0 flex-grow-1" style="min-width:0">
                                <h4 class="fs-6 fw-600 text-truncate">Rapat Wali Murid Kelas XII</h4>
                                <div class="fs-xs t-muted"><i class="ms ms-14">schedule</i> 08:30 - 11:00 WIB
                                </div>
                                <div class="fs-xs t-muted"><i class="ms ms-14">location_on</i> Aula Utama
                                    Nusantara</div>
                            </div>
                        </div>
                        <div class="agenda d-flex gap-3">
                            <div class="dbox" style="background:var(--ep-sec)"><small>Jun</small>02</div>
                            <div class="flex-grow-1" style="min-width:0">
                                <h4 class="fs-6 fw-600 text-truncate">Ujian Akhir Semester Genap</h4>
                                <div class="fs-xs t-muted"><i class="ms ms-14">schedule</i> 07:30 - 12:30 WIB
                                </div>
                                <div class="fs-xs t-muted"><i class="ms ms-14">computer</i> Lab CBT &amp; Ruang
                                    Kelas</div>
                            </div>
                        </div>
                        <div class="agenda d-flex gap-3">
                            <div class="dbox c-ter"><small>Jun</small>18</div>
                            <div class="flex-grow-1" style="min-width:0">
                                <h4 class="fs-6 fw-600 text-truncate">Pentas Seni Nusantara 2025</h4>
                                <div class="fs-xs t-muted"><i class="ms ms-14">schedule</i> 09:00 - 16:00 WIB
                                </div>
                                <div class="fs-xs t-muted"><i class="ms ms-14">theater_comedy</i> Lapangan Indoor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== EKSTRAKURIKULER ===== -->
    <section class="sec bg-ep" id="kesiswaan">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width:40rem"><span class="label-sm">Kesiswaan</span>
                <h2 class="h-title mt-1">Ekstrakurikuler &amp; Pengembangan Bakat</h2>
                <p class="t-muted mb-0">Lebih dari 20 pilihan organisasi dan klub minat bakat untuk mengasah
                    potensi non-akademik siswa.</p>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-3">
                <div class="col">
                    <div class="ep-card p-4 h-100 text-center d-flex flex-column align-items-center gap-2"><span
                            class="ibox round c-pri"><i class="ms">flag</i></span>
                        <h3 class="fs-6 fw-600 mb-0">Paskibra</h3><span class="chip c-ter">Juara 1 Provinsi</span>
                    </div>
                </div>
                <div class="col">
                    <div class="ep-card p-4 h-100 text-center d-flex flex-column align-items-center gap-2"><span
                            class="ibox round c-sec"><i class="ms">smart_toy</i></span>
                        <h3 class="fs-6 fw-600 mb-0">Robotik &amp; Coding</h3><span class="chip c-sec">Emas
                            Nasional</span>
                    </div>
                </div>
                <div class="col">
                    <div class="ep-card p-4 h-100 text-center d-flex flex-column align-items-center gap-2"><span
                            class="ibox round c-ter"><i class="ms">sports_basketball</i></span>
                        <h3 class="fs-6 fw-600 mb-0">Basket</h3><span class="chip c-pri">Finalis DBL</span>
                    </div>
                </div>
                <div class="col">
                    <div class="ep-card p-4 h-100 text-center d-flex flex-column align-items-center gap-2"><span
                            class="ibox round c-hi"><i class="ms">music_note</i></span>
                        <h3 class="fs-6 fw-600 mb-0">Paduan Suara</h3><span class="chip c-sec">Gold Diploma</span>
                    </div>
                </div>
                <div class="col">
                    <div class="ep-card p-4 h-100 text-center d-flex flex-column align-items-center gap-2"><span
                            class="ibox round c-pri"><i class="ms">science</i></span>
                        <h3 class="fs-6 fw-600 mb-0">Karya Ilmiah (KIR)</h3><span class="chip c-ter">Finalis
                            LIPI</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FASILITAS & GALERI ===== -->
    <section class="sec bg-low" id="fasilitas">
        <div class="container">
            <div class="mb-5"><span class="label-sm">Sarana &amp; Prasarana</span>
                <h2 class="h-title mt-1 mb-0">Fasilitas Modern Penunjang Belajar</h2>
            </div>
            @php
                $facilityList = $facilities ?? \Models\Facility::with(['primaryImage', 'images'])->active()->orderBy('sort_order')->take(8)->get();

                $defaultFacilityImages = [
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuCMLKdAyvf5fxKP4ecPV5DqJqRTIGXN8Axd3jrKq6ogG5iJ5A4VOJY3kS7NP9IvWiC4BazqNYcR000-NS9mP6BAxGq8Alc16hh6VtFFDP55X2DW6P92EPeNVkzXuADF9vftpXd0J_iF8kL5RCvXUFshh67Ozt11HMnmpNNy-jgjWiGMg1lk3yyAOLERn74umKxYlmEcekHjys7mZRW8btv3afjS5c5mpRVrSIE8Sru9',
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuA6bGEMqLYXfDKimAq_9-xIbuFQD13Aa0QjOq523L455VLBdKMp6BwZEdx_5dx4xuIo7AN6qzcnAPselU1RSmU1DUNFgFT-JrrC01mb6rTCIzp6eLrbRajfYKF9tbwgwGwjIiGNGuMmte3HOPQjt-a2gEJGjXm8QqXrK9CVyFVO6-6U73uyGctsNTNvIyW-N_uhb_UilEinS9QtgfseAmvtBUsoTD5AkeYOhwOEBUXG',
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuDSVj50QlXWF-3wBHdC_H_XO9WL1yIWhukLJGLMhUu0Xe7UNZZziLM8zciI9U3bBOSd2gcdVX7e1G9ORkNOEfB1TME460ha6ESoDf4SzMvgpiF6Y9wRAgf35qy3dExaqSXTbRmLl5loyM1Q0KoNMTNogQfKbtXRkMsZwFo5x-G4DC51JWh5bs5mQvYQhTweRLr0kodwf_yDg0NfpnRVRQoF_WB4tYl1LOW7K1pClOvb',
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuARfmy_1MWinPcbxpnQ4_8RUWXbs14BHSbSwqjv7yZK4ORZ94aOvFQxXWEDCFLevj-DZjkJNtOp-ZaNExxo6YEGl2wdYkHw01J0e8YHCboe_WxN8pC4p-WRFXYcZkAe366LPA0ckaRY4YgcD7Bjhft3ucSUz507IBGrIE5_YsUu77wp_K7BTGdws-D5UH03cHvEQjc-aG5RGIebUy_OYBXohdGpiavIDxZ9ycYoEz8-',
                ];
            @endphp
            <div class="row g-4 mb-5">
                @if($facilityList->isNotEmpty())
                    @foreach($facilityList as $facility)
                        @php
                            $imgSrc = null;
                            $facilityImage = $facility->primaryImage ?? $facility->images->first();

                            if ($facilityImage && !empty($facilityImage->file_path)) {
                                $path = $facilityImage->file_path;
                                if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
                                    $imgSrc = $path;
                                } elseif (file_exists(public_path('storage/' . $path)) || \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                                    $imgSrc = asset('storage/' . $path);
                                } elseif (file_exists(public_path($path))) {
                                    $imgSrc = asset($path);
                                }
                            }

                            $finalImg = $imgSrc ?: ($defaultFacilityImages[$loop->index % count($defaultFacilityImages)]);
                            $altText = $facilityImage?->caption ?: $facility->name;
                        @endphp
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="ep-card h-100 overflow-hidden">
                                <div class="ratio ratio-4x3 zoom"><img
                                        src="{{ $finalImg }}"
                                        alt="{{ $altText }}" loading="lazy"></div>
                                <div class="p-3">
                                    <h3 class="fs-6 fw-600">{{ $facility->name }}</h3>
                                    <p class="fs-xs t-muted mb-0">{{ $facility->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="ep-card h-100 overflow-hidden">
                            <div class="ratio ratio-4x3 zoom"><img
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCMLKdAyvf5fxKP4ecPV5DqJqRTIGXN8Axd3jrKq6ogG5iJ5A4VOJY3kS7NP9IvWiC4BazqNYcR000-NS9mP6BAxGq8Alc16hh6VtFFDP55X2DW6P92EPeNVkzXuADF9vftpXd0J_iF8kL5RCvXUFshh67Ozt11HMnmpNNy-jgjWiGMg1lk3yyAOLERn74umKxYlmEcekHjys7mZRW8btv3afjS5c5mpRVrSIE8Sru9"
                                    alt="Laboratorium komputer" loading="lazy"></div>
                            <div class="p-3">
                                <h3 class="fs-6 fw-600">Lab Komputer &amp; CBT</h3>
                                <p class="fs-xs t-muted mb-0">120 unit PC berkecepatan tinggi dengan koneksi fiber
                                    optik.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="ep-card h-100 overflow-hidden">
                            <div class="ratio ratio-4x3 zoom"><img
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuA6bGEMqLYXfDKimAq_9-xIbuFQD13Aa0QjOq523L455VLBdKMp6BwZEdx_5dx4xuIo7AN6qzcnAPselU1RSmU1DUNFgFT-JrrC01mb6rTCIzp6eLrbRajfYKF9tbwgwGwjIiGNGuMmte3HOPQjt-a2gEJGjXm8QqXrK9CVyFVO6-6U73uyGctsNTNvIyW-N_uhb_UilEinS9QtgfseAmvtBUsoTD5AkeYOhwOEBUXG"
                                    alt="Perpustakaan digital" loading="lazy"></div>
                            <div class="p-3">
                                <h3 class="fs-6 fw-600">Perpustakaan Digital</h3>
                                <p class="fs-xs t-muted mb-0">Koleksi 15.000+ buku fisik dan akses e-book nasional.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="ep-card h-100 overflow-hidden">
                            <div class="ratio ratio-4x3 zoom"><img
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDSVj50QlXWF-3wBHdC_H_XO9WL1yIWhukLJGLMhUu0Xe7UNZZziLM8zciI9U3bBOSd2gcdVX7e1G9ORkNOEfB1TME460ha6ESoDf4SzMvgpiF6Y9wRAgf35qy3dExaqSXTbRmLl5loyM1Q0KoNMTNogQfKbtXRkMsZwFo5x-G4DC51JWh5bs5mQvYQhTweRLr0kodwf_yDg0NfpnRVRQoF_WB4tYl1LOW7K1pClOvb"
                                    alt="Kantin sekolah cashless" loading="lazy"></div>
                            <div class="p-3">
                                <h3 class="fs-6 fw-600">Smart Canteen Cashless</h3>
                                <p class="fs-xs t-muted mb-0">Kantin sehat higienis terintegrasi kartu pelajar
                                    EduPortal.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="ep-card h-100 overflow-hidden">
                            <div class="ratio ratio-4x3 zoom"><img
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuARfmy_1MWinPcbxpnQ4_8RUWXbs14BHSbSwqjv7yZK4ORZ94aOvFQxXWEDCFLevj-DZjkJNtOp-ZaNExxo6YEGl2wdYkHw01J0e8YHCboe_WxN8pC4p-WRFXYcZkAe366LPA0ckaRY4YgcD7Bjhft3ucSUz507IBGrIE5_YsUu77wp_K7BTGdws-D5UH03cHvEQjc-aG5RGIebUy_OYBXohdGpiavIDxZ9ycYoEz8-"
                                    alt="Lapangan olahraga indoor" loading="lazy"></div>
                            <div class="p-3">
                                <h3 class="fs-6 fw-600">Lapangan Olahraga Indoor</h3>
                                <p class="fs-xs t-muted mb-0">Arena standar nasional untuk basket, futsal, dan bulu
                                    tangkis.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="mb-3"><span class="label-sm">Galeri Kampus</span>
                <h3 class="fs-4 fw-600 t-head mt-1">Momen Kehidupan Siswa</h3>
            </div>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="ratio ratio-4x3 rounded-4 overflow-hidden zoom shadow-sm h-100"><img
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCLgJ57HzAwc3s5tdL0NwDqB9HH5Gg1ByECzIfC6hKJihnPlNYH4a0icayNFixFcy_WB0LYEDgEyp0Rg9ehO9RZfFmhZQOhQ3I-k6Q6NLLc1i61LFzZlVH0cEb4GOjeglc_gnNYCCt0VMselxDAcWF_TDOhrTXzhWfaTS4kct6brXsFR0PfQ1xax7-sLPeehxxbtnSOztMNKdj8gVeQz7qfxolMFkbXkQkR_e7GTf5W"
                            alt="Siswa mengerjakan proyek robotik" loading="lazy"></div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="ratio ratio-1x1 rounded-4 overflow-hidden zoom shadow-sm"><img
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCgXPng2RsPgbSYr9ICDWykYbCjh-MquFFhl--Yw8P2E2zltrJokXQppCO5ixVaZgKxUu0wO7Y6r5WwB9rHgwGk7cd3WfMe0Ob295qGQL9tsInbRNkjqz0ocJ662R7CPG0AW63vIlwk5UeoTe877JNz6u6l6foncJ0k3hHKJoAvoZCm4ZVIiA8pFag2s3ujzZpmoyeYQH8sVAoisXv2uX5WLpap2Pt7QnTBf5GW7bwa"
                                    alt="Tari tradisional di panggung" loading="lazy"></div>
                        </div>
                        <div class="col-6">
                            <div class="ratio ratio-1x1 rounded-4 overflow-hidden zoom shadow-sm"><img
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCVJE1HxtxI65Cdlm9BpG-2M81lGEY-gmLoJVJV6PZ_pQPRaMfYIBxVSeOiETjEJsAm2pO-0ryCMpInVL8O49DovrdQV-KC1SMX-0dk6Ni9TtuSx2QRZrraImKYkNxadc9FFHb4RuejP2jYklswYbXm6o6DYIJ4qWzzwRLIWrT_3e5LDhjQJvbFemnW5lPdTWvsXC03i5c70vOOqgcwa3JbN5v4A3x7CRtM-6_BIFjG"
                                    alt="Guru membimbing siswa di kelas" loading="lazy"></div>
                        </div>
                        <div class="col-6">
                            <div class="ratio ratio-1x1 rounded-4 overflow-hidden zoom shadow-sm"><img
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAJx1vStV8gWTdlZOv1jAKRB1Y7Qouh6pWJ9E5dig_mwPL8YOtvkmhbawDRRQ64zuppuVhDJus2ZbQADnndGjXCFP0DZSCcqFuxdpPGwgYUGBGEUl_LIPBlwipQzMjKoA2ofL8OawVL-hlUwiiDdluBzUZUW9TtCf17FCTzcS0B2oNtADN_JBpOaq2og8MsVTfCC8R-60A7xA4GaaOC4a-yBAt73fpCbsu4WBhtQdBq"
                                    alt="Siswa bersorak di pertandingan basket" loading="lazy"></div>
                        </div>
                        <div class="col-6">
                            <div class="ratio ratio-1x1 rounded-4 overflow-hidden zoom shadow-sm"><img
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuD4tu7TMM-rF-VjB73PB6mjNuHyNLQFUa0H3JKh8Jmab1ActQLVWIPuPmCavM75vREGBMXE1Vlq2NBQ2zLw8Mxiq7M6dppJNOFAq9yPeKymQ87SkiJ652YUDfTT5O9mBgLFGXO1R8RpPahj26MC8GGpS3rLsjGPacn2S4DgifFo18pmvRxH0SI5WSOf3xIjwUKPihjGtiH9HafcbGcjwZvOSA6hxgAweSpNHPmDfdWN"
                                    alt="Pemandangan udara kampus" loading="lazy"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TESTIMONI ===== -->
    <section class="sec bg-ep">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width:40rem"><span class="label-sm">Kata
                    Mereka</span>
                <h2 class="h-title mt-1 mb-0">Suara Alumni, Orang Tua &amp; Siswa</h2>
            </div>
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="ep-card static p-4 h-100 d-flex flex-column justify-content-between gap-3">
                        <p class="t-muted fst-italic mb-0">“Bimbingan riset dan persiapan UTBK di SMA Nusantara
                            sangat terstruktur. Berkat dukungan guru-guru, saya berhasil diterima di Fakultas Teknik
                            ITB.”</p>
                        <div class="d-flex align-items-center gap-2 pt-2"><span class="ibox round c-pri fw-600"
                                style="width:2.5rem;height:2.5rem">RA</span>
                            <div>
                                <div class="fw-600">Raka Aditya</div>
                                <div class="fs-xs t-muted">Alumni 2024 • Mahasiswa ITB</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="ep-card static p-4 h-100 d-flex flex-column justify-content-between gap-3">
                        <p class="t-muted fst-italic mb-0">“Sebagai orang tua, fitur EduPortal sangat membantu
                            memantau kehadiran dan perkembangan nilai anak secara real-time. Lingkungannya juga
                            sangat aman.”</p>
                        <div class="d-flex align-items-center gap-2 pt-2"><span class="ibox round c-sec fw-600"
                                style="width:2.5rem;height:2.5rem">SW</span>
                            <div>
                                <div class="fw-600">Ibu Sari Wulandari</div>
                                <div class="fs-xs t-muted">Wali Murid Kelas XI MIPA</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="ep-card static p-4 h-100 d-flex flex-column justify-content-between gap-3">
                        <p class="t-muted fst-italic mb-0">“Ekstrakurikuler di sini seru dan didukung penuh oleh
                            sekolah. Saya bisa belajar coding sekaligus aktif di organisasi OSIS tanpa keteteran
                            pelajaran.”</p>
                        <div class="d-flex align-items-center gap-2 pt-2"><span class="ibox round c-ter fw-600"
                                style="width:2.5rem;height:2.5rem">NP</span>
                            <div>
                                <div class="fw-600">Nadia Putri</div>
                                <div class="fs-xs t-muted">Siswa Aktif • Ketua Klub Robotik</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PPDB ===== -->
    <section class="sec bg-brand" id="ppdb-section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 d-flex flex-column gap-3 align-items-start">
                    <span class="chip c-ter text-uppercase">PPDB 2025/2026</span>
                    <h2 class="h-title text-white">Bergabunglah Menjadi Bagian dari SMA Nusantara</h2>
                    <p class="t-soft mb-0">Gelombang 1 dibuka hingga <strong>30 Mei 2025</strong>. Tersedia jalur
                        Prestasi Akademik, Non-Akademik, dan Reguler.</p>
                    <a class="btn-sec py-3 px-4 mt-2 shadow" href="/ppdb" target="_blank">Daftar Sekarang <i
                            class="ms ms-18">arrow_forward</i></a>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div class="ep-card brand-cont p-4 h-100 d-flex gap-3"><span
                                    class="ibox round fw-600 text-white flex-none"
                                    style="width:2rem;height:2rem;background:var(--ep-sec)">1</span>
                                <div>
                                    <h3 class="fs-6 fw-600">Buat Akun PPDB</h3>
                                    <p class="fs-xs t-soft mb-0">Registrasi online melalui portal PPDB dengan NISN
                                        aktif.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="ep-card brand-cont p-4 h-100 d-flex gap-3"><span
                                    class="ibox round fw-600 text-white"
                                    style="width:2rem;height:2rem;background:var(--ep-sec)">2</span>
                                <div>
                                    <h3 class="fs-6 fw-600">Unggah Berkas</h3>
                                    <p class="fs-xs t-soft mb-0">Lengkapi rapor semester 1-5, KK, dan sertifikat
                                        prestasi.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="ep-card brand-cont p-4 h-100 d-flex gap-3"><span
                                    class="ibox round fw-600 text-white"
                                    style="width:2rem;height:2rem;background:var(--ep-sec)">3</span>
                                <div>
                                    <h3 class="fs-6 fw-600">Tes Seleksi &amp; Wawancara</h3>
                                    <p class="fs-xs t-soft mb-0">Mengikuti tes potensi akademik CBT dan wawancara
                                        siswa.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="ep-card brand-cont p-4 h-100 d-flex gap-3"><span
                                    class="ibox round fw-600 text-white"
                                    style="width:2rem;height:2rem;background:var(--ep-sec)">4</span>
                                <div>
                                    <h3 class="fs-6 fw-600">Pengumuman &amp; Daftar Ulang</h3>
                                    <p class="fs-xs t-soft mb-0">Cek hasil seleksi secara real-time di dashboard
                                        EduPortal.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FAQ & KONTAK ===== -->
    <section class="sec bg-ep">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <span class="label-sm">Tanya Jawab</span>
                    <h2 class="h-title mt-1 mb-4">Pertanyaan Umum (FAQ)</h2>
                    <div class="accordion" id="faq">
                        <div class="accordion-item">
                            <h3 class="accordion-header"><button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#f1" aria-expanded="false"
                                    aria-controls="f1">Kapan jadwal pendaftaran PPDB
                                    Gelombang 1 &amp; 2?</button></h3>
                            <div id="f1" class="accordion-collapse collapse" data-bs-parent="#faq">
                                <div class="accordion-body">Gelombang 1 dibuka mulai 1 Maret hingga 30 Mei 2025.
                                    Gelombang 2 akan dibuka pada 1 Juni hingga 25 Juni 2025 apabila kuota masih
                                    tersedia.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header"><button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#f2" aria-expanded="false"
                                    aria-controls="f2">Apakah tersedia program beasiswa bagi
                                    siswa berprestasi?</button></h3>
                            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faq">
                                <div class="accordion-body">Ya, SMA Nusantara menyediakan beasiswa potongan uang
                                    pangkal dan SPP hingga 100% bagi peraih medali OSN/O2SN atau peringkat 1-3
                                    paralel di SMP asal.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header"><button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#f3" aria-expanded="false"
                                    aria-controls="f3">Bagaimana cara orang tua memantau
                                    absensi dan nilai siswa?</button></h3>
                            <div id="f3" class="accordion-collapse collapse" data-bs-parent="#faq">
                                <div class="accordion-body">Setiap orang tua akan mendapatkan akun EduPortal khusus
                                    untuk melihat rekap absensi harian, jadwal pelajaran, tugas, serta laporan nilai
                                    secara real-time.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="label-sm">Hubungi Kami</span>
                    <h2 class="h-title mt-1 mb-4">Kontak &amp; Lokasi Kampus</h2>
                    <div class="ep-card static p-4 d-flex flex-column gap-3">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 d-flex gap-2"><i class="ms t-teal">location_on</i>
                                <div>
                                    <div class="fw-600">Alamat Kampus</div>
                                    <div class="fs-xs t-muted">Jl. Pendidikan No. 45, Jakarta Selatan 12345</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 d-flex gap-2"><i class="ms t-teal">schedule</i>
                                <div>
                                    <div class="fw-600">Jam Operasional</div>
                                    <div class="fs-xs t-muted">Senin - Jumat: 07.00 - 16.00 WIB</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 d-flex gap-2"><i class="ms t-teal">call</i>
                                <div>
                                    <div class="fw-600">Telepon</div>
                                    <div class="fs-xs t-muted">(021) 7654-3210</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 d-flex gap-2"><i class="ms t-teal">mail</i>
                                <div>
                                    <div class="fw-600">Email Resmi</div>
                                    <div class="fs-xs t-muted text-break">info@smanusantara.sch.id</div>
                                </div>
                            </div>
                        </div>
                        <div class="mapbg" role="img" aria-label="Peta lokasi kampus, Jakarta Selatan"
                            style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuAgoeU6xe_WwWM3x44Fl2F16JbAr7NPTV7LjVKC3FFOSmkuijd5_9mjOEi6EH6lw6-0kwddMAAaLCrR5-ua6vtr1F_fRJlXNkWJHmHoBADVBhRVk2e71T6R1NR6hmm2g6e54vsddS8RUeB3BQJc4Lcu6qvTCo04bFNLTv2QEp2E6-GjLoLxhjya_7lrABARzW4E-8ROSSD6iCOSAzylqRrF_v85myzqbv2it4NjDxww')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
