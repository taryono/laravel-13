@extends('layouts.app')
@section('title', 'Alumni & Tracer Study')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/alumni.css') }}">
@endpush
@section('content')
<div class="alumni">
    @include('Dashboard::partials.ep_subnav', [
        'title' => 'Modul Alumni & Tracer Study',
        'badge' => '4.850 Lulusan',
        'subtitle' => 'Statistik Tracer Study, Sebaran Karir & Jaringan Alumni',
        'metaIcon' => 'school',
        'metaLabel' => 'Angkatan',
        'metaValue' => '2005 - 2024',
    ])
    <!-- ============ KPI BENTO ============ -->
    <section class="d-flex flex-column gap-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
                <span class="material-symbols-outlined tc-primary" style="font-size:20px;">insights</span>
                Statistik &amp; Hasil Tracer Study
            </h3>
            <span class="bpill bg-low tc-secondary">Akademik</span>
        </div>

        <div class="row g-3">

            <!-- Card 1: Total Alumni -->
            <div class="col-6">
                <div class="al-card kpi-accent-primary p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fs-11 fw-bold tc-variant">Total Alumni</span>
                            <span class="material-symbols-outlined tc-primary" style="font-size:18px;">school</span>
                        </div>
                        <div class="fs-20 fw-bold tc-primary leading-tight">4.850</div>
                        <p class="fs-12 tc-variant mt-1 mb-0">Lulusan (Angkatan 2005 - 2024)</p>
                    </div>
                    <div class="mt-3 pt-2 border-soft-t">
                        <span class="bpill bg-success tc-success">92% Kontak Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Tracer Study -->
            <div class="col-6">
                <div class="al-card kpi-accent-secondary p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fs-11 fw-bold tc-variant">Tracer Study</span>
                            <span class="material-symbols-outlined tc-secondary" style="font-size:18px;">pie_chart</span>
                        </div>
                        <div class="fs-20 fw-bold tc-secondary leading-tight">76.4%</div>
                        <p class="fs-12 tc-variant mt-1 mb-0">Perguruan Tinggi (PTN/PTS)</p>
                    </div>
                    <div class="mt-3 pt-2 border-soft-t">
                        <span class="fs-11 tc-variant">18.2% Kerja • 5.4% Usaha</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Kampus Favorit -->
            <div class="col-12">
                <div class="al-card kpi-accent-primary p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined tc-primary" style="font-size:18px;">apartment</span>
                            <span class="fs-13 fw-bold tc-on-surface">Kampus Favorit Alumni</span>
                        </div>
                        <span class="fs-11 tc-variant">Sebaran PTN Top</span>
                    </div>
                    <div class="row g-2 text-center mt-2">
                        <div class="col-3">
                            <div class="bg-low rounded-3 p-2 d-flex flex-column align-items-center">
                                <span class="fs-13 fw-bold tc-primary">142</span>
                                <span class="fs-11 tc-variant mt-1">ITB</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-low rounded-3 p-2 d-flex flex-column align-items-center">
                                <span class="fs-13 fw-bold tc-primary">138</span>
                                <span class="fs-11 tc-variant mt-1">UI</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-low rounded-3 p-2 d-flex flex-column align-items-center">
                                <span class="fs-13 fw-bold tc-primary">115</span>
                                <span class="fs-11 tc-variant mt-1">UGM</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-low rounded-3 p-2 d-flex flex-column align-items-center">
                                <span class="fs-13 fw-bold tc-primary">98</span>
                                <span class="fs-11 tc-variant mt-1">Unpad</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Donasi & Beasiswa -->
            <div class="col-12">
                <div class="al-card kpi-accent-primary-container p-4">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            <span class="fs-11 fw-bold tc-variant">Donasi / Beasiswa Alumni</span>
                            <div class="fs-20 fw-bold tc-primary-container mt-1">Rp 185.000.000</div>
                            <p class="fs-12 tc-variant mt-2 mb-0">
                                Terhimpun untuk beasiswa adik kelas berprestasi &amp; yatim
                            </p>
                        </div>
                        <span class="ibox-lg bg-container tc-primary">
                            <span class="material-symbols-outlined" style="font-size:22px;">volunteer_activism</span>
                        </span>
                    </div>
                    <div
                        class="bg-low rounded-3 p-2 mt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span class="fs-11 fw-bold tc-primary">Tersalurkan ke 34 Siswa</span>
                        <span class="fs-11 fw-bold tc-success">Dana Terserap 100%</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ AKSI CEPAT ============ -->
    <section class="d-flex flex-column gap-3 pt-2">
        <h3 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
            <span class="material-symbols-outlined tc-secondary" style="font-size:20px;">bolt</span>
            Aksi Cepat Modul
        </h3>

        <div class="row g-2">
            <!-- Action 1: Sebar Kuisioner -->
            <div class="col-6">
                <button type="button" class="quick-card">
                    <span class="ibox bg-secondary-container tc-on-secondary-fixed-variant mb-2">
                        <span class="material-symbols-outlined" style="font-size:18px;">format_list_bulleted</span>
                    </span>
                    <span class="fs-13 fw-bold tc-primary">Sebar Kuisioner</span>
                    <span class="fs-11 tc-variant mt-1">Tracer study online</span>
                </button>
            </div>

            <!-- Action 2: Info Lowongan -->
            <div class="col-6">
                <button type="button" class="quick-card">
                    <span class="ibox bg-container tc-primary mb-2">
                        <span class="material-symbols-outlined" style="font-size:18px;">work</span>
                    </span>
                    <span class="fs-13 fw-bold tc-primary">Info Lowongan</span>
                    <span class="fs-11 tc-variant mt-1">Loker &amp; magang alumni</span>
                </button>
            </div>

            <!-- Action 3: Mentoring -->
            <div class="col-6">
                <button type="button" class="quick-card">
                    <span class="ibox bg-high tc-primary mb-2">
                        <span class="material-symbols-outlined" style="font-size:18px;">diversity_3</span>
                    </span>
                    <span class="fs-13 fw-bold tc-primary">Mentoring Alumni</span>
                    <span class="fs-11 tc-variant mt-1">Bimbingan karir siswa</span>
                </button>
            </div>

            <!-- Action 4: Direktori -->
            <div class="col-6">
                <button type="button" class="quick-card">
                    <span class="ibox bg-low tc-secondary mb-2">
                        <span class="material-symbols-outlined" style="font-size:18px;">groups</span>
                    </span>
                    <span class="fs-13 fw-bold tc-primary">Direktori Angkatan</span>
                    <span class="fs-11 tc-variant mt-1">Buku kontak 2005-2024</span>
                </button>
            </div>
        </div>
    </section>

    <!-- ============ FILTER & SEARCH ============ -->
    <section class="d-flex flex-column gap-3 pt-2">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
                <span class="material-symbols-outlined tc-primary" style="font-size:20px;">search</span>
                Eksplorasi &amp; Filter Alumni
            </h3>
            <span class="fs-12 tc-variant">Direktori</span>
        </div>

        <!-- Search Input -->
        <div class="search-wrap">
            <span class="material-symbols-outlined search-icon" style="font-size:20px;">search</span>
            <input type="text" class="search-input" placeholder="Cari nama alumni, profesi, atau kampus...">
        </div>

        <!-- Filter Angkatan Horizontal Scroll -->
        <div class="scroll-x">
            <button type="button" class="filter-chip active">Semua Angkatan</button>
            <button type="button" class="filter-chip">2024</button>
            <button type="button" class="filter-chip">2023</button>
            <button type="button" class="filter-chip">2022</button>
            <button type="button" class="filter-chip">2018</button>
            <button type="button" class="filter-chip">2015</button>
            <button type="button" class="filter-chip">2012</button>
        </div>
    </section>

    <!-- ============ KONTRIBUSI & KIPRAH ============ -->
    <section class="d-flex flex-column gap-3 pt-2 pb-4">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="fs-13 fw-bold tc-on-surface d-flex align-items-center gap-2 mb-0">
                <span class="material-symbols-outlined tc-secondary" style="font-size:20px;">badge</span>
                Kontribusi &amp; Kiprah Terbaru
            </h3>
            <span class="fs-12 fw-semibold tc-primary">Lihat Semua</span>
        </div>

        <div class="d-flex flex-column gap-3">

            <!-- Alumni 1 -->
            <div class="alumni-item" style="border-left:4px solid var(--c-secondary);">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-img">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBv4n8oLCkGbu3KsLxSttPQvCE8F4937FG5gtol2y121C5Rn4kqTXfnr8Yl49y8WYFFXMc1_Tp3AigNTK4cPW0DJva47psZsJLkDhFHxjHrpG4OYonLS0sVhC5GuJStky_05gnvWhe26LxMVJZwppuSA7WObzPRkGI3yEKLQ4BOkm5oHe5e1-jOvVw6h_z7UV-wUI--JRe3Kfv7ybF9Ae8usK-HcEY-vgNBrCs9Y4ZL"
                            alt="Dr. Raditya Pratama">
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <h4 class="fs-13 fw-bold tc-on-surface mb-0 truncate-1">Dr. Raditya Pratama</h4>
                            <span class="tagpill bg-container tc-primary flex-shrink-0">Alumni 2012</span>
                        </div>
                        <p class="fs-12 tc-variant mb-0 mt-1">Dokter Spesialis RSUP</p>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                            <span class="bpill bg-success tc-success">
                                <span class="material-symbols-outlined" style="font-size:14px;">stars</span>
                                Mentor Sains &amp; Kedokteran
                            </span>
                        </div>
                    </div>
                </div>
                <div
                    class="pt-2 mt-3 border-soft-t d-flex align-items-center justify-content-between fs-12 tc-variant flex-wrap gap-2">
                    <span>Sesi Mentoring: 12 Siswa</span>
                    <a href="#" class="link-arrow">Hubungi Alumni →</a>
                </div>
            </div>

            <!-- Alumni 2 -->
            <div class="alumni-item" style="border-left:4px solid var(--c-primary);">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-img">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjAyAElgWuDg8Zsr-kv7h-w89BaEoN3Fp9eeBDOTE3Lppb-VbXQd_PiQs4lyyR2YociX7ylfZhvrj1mdFHEAVJRVELg-IPQqf5KR_6U-aWtqzrSfHjL_uFWBHF-bDlxo8lfQF_6WWq4Nid87r5zvdJtBQP-DWAoZOa17xSuqFkwh2f_RCxMCBmr4KZ7AjVjwWbZ_Y0mq2RVteqhAvXpjdzvrPmq_qfOUvWp-SRE6w9"
                            alt="Maya Anindita, S.T., M.Sc.">
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <h4 class="fs-13 fw-bold tc-on-surface mb-0 truncate-1">Maya Anindita, S.T., M.Sc.</h4>
                            <span class="tagpill bg-container tc-primary flex-shrink-0">Alumni 2015</span>
                        </div>
                        <p class="fs-12 tc-variant mb-0 mt-1">Lead Engineer GoTo</p>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                            <span class="bpill bg-info tc-info">
                                <span class="material-symbols-outlined" style="font-size:14px;">favorite</span>
                                Donatur Beasiswa Berkelanjutan
                            </span>
                        </div>
                    </div>
                </div>
                <div
                    class="pt-2 mt-3 border-soft-t d-flex align-items-center justify-content-between fs-12 tc-variant flex-wrap gap-2">
                    <span>Kontribusi: Dana Beasiswa 2024</span>
                    <a href="#" class="link-arrow tc-primary-link">Profil Lengkap →</a>
                </div>
            </div>

            <!-- Alumni 3 -->
            <div class="alumni-item" style="border-left:4px solid var(--c-secondary);">
                <div class="d-flex align-items-start gap-3">
                    <div class="avatar-img">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZJmE2vbIxR0LebSSKyw6floTPdYCpObuFJEpl9Chz0iX67UtUMRn2yUArTKjXO1AXvWKp8fDzXnlZ7c4OrxVz6NxBux2br8QuGjQrOVU73aOmF9Qbo0C4lzk78XZhWhVw9OrJXFI0CuglnjVOZrmnxTU63b2JLxKpVatlmp9-63_STTtSC2Inj79Y-2oFWOM11lOwa9GAr395nLkLP6YIgmGsDbL79CUSNa9lQNQQ"
                            alt="Fajar Siddiq">
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <h4 class="fs-13 fw-bold tc-on-surface mb-0 truncate-1">Fajar Siddiq</h4>
                            <span class="tagpill bg-container tc-primary flex-shrink-0">Alumni 2018</span>
                        </div>
                        <p class="fs-12 tc-variant mb-0 mt-1">Founder Startup Edukasi</p>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                            <span class="bpill bg-secondary-container tc-on-secondary-fixed-variant">
                                <span class="material-symbols-outlined" style="font-size:14px;">rocket_launch</span>
                                Mitra Magang &amp; Inkubasi
                            </span>
                        </div>
                    </div>
                </div>
                <div
                    class="pt-2 mt-3 border-soft-t d-flex align-items-center justify-content-between fs-12 tc-variant flex-wrap gap-2">
                    <span>Program Magang: 5 Kuota Tersedia</span>
                    <a href="#" class="link-arrow">Daftar Magang →</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('bottom_navigation')
    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">dashboard</span>
            <span class="text-label-sm mt-1">Beranda</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">school</span>
            <span class="text-label-sm mt-1">Akademik</span>
        </a>
        <a href="#" class="bottom-nav-item active">
            <span class="material-symbols-outlined icon-fill" style="font-size:24px;">account_balance_wallet</span>
            <span class="text-label-sm fw-bold mt-1">Keuangan</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">chat</span>
            <span class="text-label-sm mt-1">Pesan</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <span class="material-symbols-outlined" style="font-size:24px;">person</span>
            <span class="text-label-sm mt-1">Profil</span>
        </a>
    </nav>
</div>
@endsection
