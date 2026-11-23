@extends('layouts.front')

@section('content')
    <div class="px-4 px-4 p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/#bantuan">Pusat Bantuan</a></li>
                <li class="breadcrumb-item active">Keamanan Data</li>
            </ol>
        </nav>
        <h1 class="mb-2">Keamanan Data</h1>
        <p class="text-secondary mb-5">Cara kami menjaga privasi dan keamanan data sekolah. Sesuaikan isinya dengan praktik
            yang
            benar-benar Anda jalankan.</p>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="ep-card">
                    <div class="ep-icon mb-3"><i class="bi bi-shield-lock"></i></div>
                    <h2 class="h5">Akses berdasarkan peran</h2>
                    <p class="text-secondary mb-0">Setiap pengguna hanya melihat data sesuai perannya, dan dicek di sisi
                        server.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ep-card">
                    <div class="ep-icon mb-3"><i class="bi bi-key"></i></div>
                    <h2 class="h5">Enkripsi</h2>
                    <p class="text-secondary mb-0">Data dikirim melalui HTTPS dan kata sandi disimpan dalam bentuk hash.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ep-card">
                    <div class="ep-icon mb-3"><i class="bi bi-cloud-arrow-up"></i></div>
                    <h2 class="h5">Cadangan data</h2>
                    <p class="text-secondary mb-0">Pencadangan berkala agar data tetap aman bila terjadi gangguan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ep-card">
                    <div class="ep-icon mb-3"><i class="bi bi-journal-text"></i></div>
                    <h2 class="h5">Catatan aktivitas</h2>
                    <p class="text-secondary mb-0">Perubahan data penting tercatat: siapa, kapan, dan apa yang diubah.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ep-card">
                    <div class="ep-icon mb-3"><i class="bi bi-person-lock"></i></div>
                    <h2 class="h5">Privasi siswa</h2>
                    <p class="text-secondary mb-0">Data siswa dan wali hanya dipakai untuk keperluan sekolah dan tidak
                        dijual.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="ep-card">
                    <div class="ep-icon mb-3"><i class="bi bi-patch-check"></i></div>
                    <h2 class="h5">Perlindungan data pribadi</h2>
                    <p class="text-secondary mb-0">Pengelolaan data mengacu pada UU Pelindungan Data Pribadi (UU No. 27
                        Tahun
                        2022).</p>
                </div>
            </div>
        </div>
    </div>
@endsection
