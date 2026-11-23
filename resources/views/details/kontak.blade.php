@extends('layouts.front')

@section('content')
    <div class="px-4 px-4 p-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/#bantuan">Pusat Bantuan</a></li>
                <li class="breadcrumb-item active">Kontak Sales</li>
            </ol>
        </nav>
        <h1 class="mb-2">Kontak Sales</h1>
        <p class="text-secondary mb-5">Hubungi tim kami untuk trial kustom atau demo lanjutan khusus sekolah Anda.</p>
        <div class="row g-4">
            <div class="col-lg-7">
                <form class="ep-card" action="#" method="post">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label" for="nama">Nama lengkap</label><input
                                class="form-control" id="nama" name="nama" required></div>
                        <div class="col-md-6"><label class="form-label" for="sekolah">Nama sekolah</label><input
                                class="form-control" id="sekolah" name="sekolah" required></div>
                        <div class="col-md-6"><label class="form-label" for="email">Email</label><input type="email"
                                class="form-control" id="email" name="email" required></div>
                        <div class="col-md-6"><label class="form-label" for="wa">WhatsApp</label><input type="tel"
                                class="form-control" id="wa" name="wa" required></div>
                        <div class="col-md-6"><label class="form-label" for="siswa">Jumlah siswa</label><select
                                class="form-select" id="siswa" name="siswa">
                                <option>Kurang dari 300</option>
                                <option>300 - 1.000</option>
                                <option>Lebih dari 1.000</option>
                            </select></div>
                        <div class="col-md-6"><label class="form-label" for="butuh">Kebutuhan</label><select
                                class="form-select" id="butuh" name="butuh">
                                <option>Demo produk</option>
                                <option>Trial kustom</option>
                                <option>Penawaran harga</option>
                            </select></div>
                        <div class="col-12"><label class="form-label" for="pesan">Pesan</label>
                            <textarea class="form-control" id="pesan" name="pesan" rows="4"></textarea>
                        </div>
                        <div class="col-12"><button class="btn btn-primary" type="submit">Kirim permintaan</button></div>
                    </div>
                </form>
            </div>
            <div class="col-lg-5">
                <div class="ep-card">
                    <h2 class="h5 mb-3">Kontak langsung</h2>
                    <p class="mb-2"><i class="bi bi-envelope me-2 text-primary"></i>sales@edupulse.id</p>
                    <p class="mb-2"><i class="bi bi-whatsapp me-2 text-primary"></i>+62 800-0000-0000</p>
                    <p class="mb-0 text-secondary"><i class="bi bi-clock me-2 text-primary"></i>Senin - Jumat, 08.00 - 17.00
                        WIB
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
