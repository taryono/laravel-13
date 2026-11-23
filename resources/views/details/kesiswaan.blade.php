@extends('layouts.front')

@section('content')
    <div class="px-4 px-4 p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/#features">Fitur</a></li>
                <li class="breadcrumb-item active">Kesiswaan</li>
            </ol>
        </nav>
        <header class="py-4 mb-4">
            <div class="ep-icon mb-4 ps-3 pt-2 rounded"
                style="width:64px;height:64px;font-size:1.75rem;background:#2563eb;color:#fff"><i class="bi bi-people"></i>
            </div>
            <h1 class="display-5 mb-3">Kesiswaan</h1>
            <p class="lead text-secondary col-lg-8 px-0">PPDB Online, profil lengkap siswa, hingga manajemen alumni dan
                prestasi.</p>
            <a class="btn btn-primary me-2" href="kontak-sales.html">Minta demo</a><a class="btn btn-outline-secondary"
                href="/#features">Lihat semua fitur</a>
        </header>
        <h2 class="h4 mb-3">Fitur utama</h2>
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="ep-card">
                    <h3 class="h6 mb-2">PPDB Online</h3>
                    <p class="text-secondary mb-0">Formulir, berkas, verifikasi, dan kuota jalur masuk dalam satu alur.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ep-card">
                    <h3 class="h6 mb-2">Profil siswa</h3>
                    <p class="text-secondary mb-0">Data pribadi, orang tua, dan riwayat sekolah tersimpan rapi.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ep-card">
                    <h3 class="h6 mb-2">Alumni</h3>
                    <p class="text-secondary mb-0">Pantau lulusan dan jaga hubungan dengan sekolah.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ep-card">
                    <h3 class="h6 mb-2">Prestasi</h3>
                    <p class="text-secondary mb-0">Catat prestasi akademik dan non-akademik siswa.</p>
                </div>
            </div>
        </div>
        <h2 class="h4 mb-3">Cara kerjanya</h2>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="ep-card"><span class="badge text-bg-primary mb-3">Langkah 1</span>
                    <p class="mb-0">Calon siswa mengisi formulir dan mengunggah berkas.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ep-card"><span class="badge text-bg-primary mb-3">Langkah 2</span>
                    <p class="mb-0">Panitia memverifikasi dan menyeleksi pendaftar.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ep-card"><span class="badge text-bg-primary mb-3">Langkah 3</span>
                    <p class="mb-0">Siswa yang diterima otomatis masuk ke profil siswa.</p>
                </div>
            </div>
        </div>
        <div class="ep-card text-center mb-5">
            <h2 class="h4">Ingin melihat langsung di sekolah Anda?</h2>
            <p class="text-secondary">Tim kami siap menyiapkan demo sesuai kebutuhan sekolah.</p>
            <div><a class="btn btn-primary" href="kontak-sales.html">Hubungi tim sales</a></div>
        </div>
        <h2 class="h6 text-secondary mb-3">Modul lainnya</h2>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="/akademik">Akademik</a><a
                class="btn btn-outline-secondary btn-sm" href="/kesiswaan">Kesiswaan</a><a
                class="btn btn-outline-secondary btn-sm" href="/keuangan">Keuangan</a><a
                class="btn btn-outline-secondary btn-sm" href="/absensi">Absensi</a><a
                class="btn btn-outline-secondary btn-sm" href="/parent-portal">Parent Portal</a><a
                class="btn btn-outline-secondary btn-sm" href="/analitik">Analitik AI</a>
        </div>
    </div>
@endsection
