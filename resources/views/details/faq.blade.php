@extends('layouts.front')

@section('content')
    <div class="px-4 px-4 mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/#bantuan">Pusat Bantuan</a></li>
                <li class="breadcrumb-item active">FAQ</li>
            </ol>
        </nav>
        <h1 class="mb-2">FAQ</h1>
        <p class="text-secondary mb-5">Jawaban atas pertanyaan yang paling sering diajukan pengguna baru.</p>

        <div class="accordion d-flex flex-column justify-content-between" id="faq">
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button" data-bs-toggle="collapse"
                        data-bs-target="#q1">Bagaimana cara masuk ke EduPulse?</button></h2>
                <div id="q1" class="accordion-collapse collapse show" data-bs-parent="#faq">
                    <div class="accordion-body text-secondary">Pilih peran Anda di halaman utama, lalu masuk dengan akun
                        dari
                        admin sekolah.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#q2">Saya lupa kata sandi. Apa yang harus dilakukan?</button></h2>
                <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faq">
                    <div class="accordion-body text-secondary">Gunakan tautan "Lupa kata sandi" di halaman login, atau minta
                        admin sekolah mengatur ulang.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#q3">Apakah calon siswa perlu akun untuk mendaftar PPDB?</button></h2>
                <div id="q3" class="accordion-collapse collapse" data-bs-parent="#faq">
                    <div class="accordion-body text-secondary">Calon siswa mendaftar lewat halaman PPDB, lalu memantau
                        statusnya
                        di Portal Siswa.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#q4">Satu akun bisa punya lebih dari satu peran?</button></h2>
                <div id="q4" class="accordion-collapse collapse" data-bs-parent="#faq">
                    <div class="accordion-body text-secondary">Bisa. Admin sekolah dapat menambahkan peran lain ke akun yang
                        sama.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#q5">Apakah data sekolah saya aman?</button></h2>
                <div id="q5" class="accordion-collapse collapse" data-bs-parent="#faq">
                    <div class="accordion-body text-secondary">Ya. Lihat detailnya di halaman <a
                            href="keamanan-data.html">Keamanan Data</a>.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#q6">Bagaimana cara meminta demo?</button></h2>
                <div id="q6" class="accordion-collapse collapse" data-bs-parent="#faq">
                    <div class="accordion-body text-secondary">Isi formulir di halaman <a href="kontak-sales.html">Kontak
                            Sales</a> dan tim kami akan menghubungi Anda.</div>
                </div>
            </div>
        </div>
        <p class="text-secondary mt-4">Belum menemukan jawaban? <a href="kontak-sales.html">Hubungi kami</a>.</p>
    </div>
@endsection
