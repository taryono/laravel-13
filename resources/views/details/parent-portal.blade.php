@extends('layouts.front')

@section('content')
    <div class="px-4 px-4 p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/#features">Fitur</a></li>
                <li class="breadcrumb-item active">Parent Portal</li>
            </ol>
        </nav>
        <header class="py-4 mb-4">
            <div class="ep-icon mb-4 ps-3 pt-2 rounded"
                style="width:64px;height:64px;font-size:1.75rem;background:#2563eb;color:#fff"><i
                    class="bi bi-house-heart"></i></div>
            <h1 class="display-5 mb-3">Parent Portal</h1>
            <p class="lead text-secondary col-lg-8 px-0">Dashboard khusus orang tua untuk memantau nilai, absensi, dan
                pembayaran secara real-time.</p>
            <a class="btn btn-primary me-2" href="kontak-sales.html">Minta demo</a><a class="btn btn-outline-secondary"
                href="/#features">Lihat semua fitur</a>
        </header>
        <h2 class="h4 mb-3">Fitur utama</h2>
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="ep-card">
                    <h3 class="h6 mb-2">Nilai</h3>
                    <p class="text-secondary mb-0">Lihat nilai dan rapor anak kapan saja.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ep-card">
                    <h3 class="h6 mb-2">Absensi</h3>
                    <p class="text-secondary mb-0">Pantau kehadiran anak setiap hari.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ep-card">
                    <h3 class="h6 mb-2">Pembayaran</h3>
                    <p class="text-secondary mb-0">Cek tagihan dan bayar langsung dari portal.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ep-card">
                    <h3 class="h6 mb-2">Real-time</h3>
                    <p class="text-secondary mb-0">Informasi diperbarui begitu data masuk dari sekolah.</p>
                </div>
            </div>
        </div>
        <h2 class="h4 mb-3">Cara kerjanya</h2>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="ep-card"><span class="badge text-bg-primary mb-3">Langkah 1</span>
                    <p class="mb-0">Orang tua masuk lewat Portal Siswa &amp; Wali.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ep-card"><span class="badge text-bg-primary mb-3">Langkah 2</span>
                    <p class="mb-0">Pilih anak yang ingin dipantau.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ep-card"><span class="badge text-bg-primary mb-3">Langkah 3</span>
                    <p class="mb-0">Lihat nilai, kehadiran, dan tagihan di satu dasbor.</p>
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
