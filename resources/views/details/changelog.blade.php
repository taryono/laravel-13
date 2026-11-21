@extends('layouts.front')

@section('content')
    <div class="px-4 px-4 mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/#bantuan">Pusat Bantuan</a></li>
                <li class="breadcrumb-item active">Update Terbaru</li>
            </ol>
        </nav>
        <h1 class="mb-2">Update Terbaru</h1>
        <p class="text-secondary mb-5">Catatan rilis fitur-fitur baru yang kami luncurkan setiap bulannya.</p>
        <div class="timeline">
            <div class="ep-card">
                <h2 class="h5">v1.3.0 <small class="text-secondary fw-normal ms-2">Nov 2026</small></h2><span
                    class="badge text-bg-primary">Baru</span> <span class="badge text-bg-secondary">Perbaikan</span>
                <ul class="text-secondary mt-3 mb-0">
                    <li>Pilihan peran aktif untuk akun dengan banyak peran.</li>
                    <li>Rekap kuota PPDB per jalur masuk.</li>
                    <li>Perbaikan tampilan riwayat kasir di perangkat seluler.</li>
                </ul>
            </div>
            <div class="ep-card">
                <h2 class="h5">v1.2.0 <small class="text-secondary fw-normal ms-2">Okt 2026</small></h2><span
                    class="badge text-bg-primary">Baru</span>
                <ul class="text-secondary mt-3 mb-0">
                    <li>Kartu ujian digital di Portal Siswa.</li>
                    <li>Notifikasi saldo rendah untuk voucher kantin.</li>
                </ul>
            </div>
            <div class="ep-card">
                <h2 class="h5">v1.1.0 <small class="text-secondary fw-normal ms-2">Sep 2026</small></h2><span
                    class="badge text-bg-secondary">Perbaikan</span>
                <ul class="text-secondary mt-3 mb-0">
                    <li>Kecepatan pemuatan laporan presensi meningkat.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
