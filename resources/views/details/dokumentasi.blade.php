@extends('layouts.front')

@section('content')
    <div class="px-4 px-4 mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/#bantuan">Pusat Bantuan</a></li>
                <li class="breadcrumb-item active">Dokumentasi</li>
            </ol>
        </nav>
        <h1 class="mb-2">Dokumentasi</h1>
        <p class="text-secondary mb-5">Panduan langkah demi langkah untuk setiap modul EduPulse.</p>
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="list-group sticky-top" style="top:1rem" id="toc">
                    <a class="list-group-item list-group-item-action" href="#mulai">Memulai</a>
                    <a class="list-group-item list-group-item-action" href="#akademik">Akademik</a>
                    <a class="list-group-item list-group-item-action" href="#keuangan">Keuangan &amp; Admin</a>
                    <a class="list-group-item list-group-item-action" href="#kantin">Kantin</a>
                    <a class="list-group-item list-group-item-action" href="#ppdb">PPDB</a>
                    <a class="list-group-item list-group-item-action" href="#portal">Portal Siswa &amp; Wali</a>
                </div>
            </div>
            <div class="col-lg-9" data-bs-spy="scroll" data-bs-target="#toc" data-bs-offset="20" tabindex="0">
                <section id="mulai" class="ep-card mb-4">
                    <h2 class="h4">Memulai</h2>
                    <ol class="text-secondary mb-0">
                        <li>Buka halaman login dan pilih peran Anda.</li>
                        <li>Masuk dengan akun yang diberikan admin sekolah.</li>
                        <li>Anda akan diarahkan ke dasbor sesuai peran.</li>
                    </ol>
                </section>
                <section id="akademik" class="ep-card mb-4">
                    <h2 class="h4">Akademik</h2>
                    <p class="text-secondary mb-0">Atur jadwal mengajar, kurikulum, presensi, dan pengisian nilai rapor. Isi
                        bagian ini dengan langkah-langkah modul Akademik.</p>
                </section>
                <section id="keuangan" class="ep-card mb-4">
                    <h2 class="h4">Keuangan &amp; Admin</h2>
                    <p class="text-secondary mb-0">Kelola tagihan sekolah, data staf, kelas, dan laporan. Isi dengan panduan
                        modul Keuangan &amp; Admin.</p>
                </section>
                <section id="kantin" class="ep-card mb-4">
                    <h2 class="h4">Kantin</h2>
                    <p class="text-secondary mb-0">Catat transaksi penjualan, lihat riwayat kasir, dan pantau saldo serta
                        voucher siswa.</p>
                </section>
                <section id="ppdb" class="ep-card mb-4">
                    <h2 class="h4">PPDB</h2>
                    <p class="text-secondary mb-0">Kelola formulir dan berkas online, verifikasi seleksi, serta rekapitulasi
                        jalur masuk dan kuota.</p>
                </section>
                <section id="portal" class="ep-card mb-4">
                    <h2 class="h4">Portal Siswa &amp; Wali</h2>
                    <p class="text-secondary mb-0">Lihat status pendaftaran, nilai, kartu ujian digital, kehadiran, dan
                        tagihan
                        sekolah.</p>
                </section>
            </div>
        </div>
    </div>
@endsection
