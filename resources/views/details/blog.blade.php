@extends('layouts.front')

@section('content')
    <div class="px-4 px-4 p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/#bantuan">Pusat Bantuan</a></li>
                <li class="breadcrumb-item active">Blog Edukasi</li>
            </ol>
        </nav>
        <h1 class="mb-2">Blog Edukasi</h1>
        <p class="text-secondary mb-5">Tips implementasi teknologi di sekolah dan tren pendidikan terbaru.</p>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <article class="ep-card"><span class="badge text-bg-primary mb-3">Tips</span>
                    <h2 class="h5">Memulai digitalisasi sekolah dari nol</h2>
                    <p class="text-secondary small">12 Nov 2026 &middot; 5 menit baca</p>
                    <p class="text-secondary">Ringkasan artikel ditulis di sini.</p><a href="#">Baca artikel</a>
                </article>
            </div>
            <div class="col-md-6 col-lg-4">
                <article class="ep-card"><span class="badge text-bg-success mb-3">Tren</span>
                    <h2 class="h5">Kartu pelajar digital dan kantin tanpa uang tunai</h2>
                    <p class="text-secondary small">5 Nov 2026 &middot; 4 menit baca</p>
                    <p class="text-secondary">Ringkasan artikel ditulis di sini.</p><a href="#">Baca artikel</a>
                </article>
            </div>
            <div class="col-md-6 col-lg-4">
                <article class="ep-card"><span class="badge text-bg-warning mb-3">Panduan</span>
                    <h2 class="h5">Menyiapkan PPDB online yang lancar</h2>
                    <p class="text-secondary small">28 Okt 2026 &middot; 6 menit baca</p>
                    <p class="text-secondary">Ringkasan artikel ditulis di sini.</p><a href="#">Baca artikel</a>
                </article>
            </div>
        </div>
        <nav class="mt-5" aria-label="Halaman">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">Sebelumnya</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">Berikutnya</a></li>
            </ul>
        </nav>
    </div>
@endsection
