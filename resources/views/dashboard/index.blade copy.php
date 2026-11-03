@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h2>Halo {{ auth()->user()->name }}</h2>

    <!-- Welcome header -->
    <section class="mb-4">
        <h2 class="fw-bold" style="color:var(--bnsp-primary); font-size:1.75rem;">Halo, Asesi testing</h2>
        <p class="text-muted mb-0" style="max-width:40rem;">
            Selamat datang di portal sertifikasi profesi Anda. Pantau progress dan jadwal ujian Anda di sini.
        </p>
    </section>

    <!-- Stats -->
    <section class="row row-cols-1 row-cols-md-4 g-3 mb-4">
        <div class="col">
            <div class="bnsp-card bnsp-stat-card p-4">
                <p class="text-muted small mb-1">Total Aktif</p>
                <p class="fw-bold mb-0" style="color:var(--bnsp-primary); font-size:1.5rem;">04</p>
            </div>
        </div>
        <div class="col">
            <div class="bnsp-card bnsp-stat-card accent-success p-4">
                <p class="text-muted small mb-1">Selesai</p>
                <p class="fw-bold mb-0" style="color:var(--bnsp-success); font-size:1.5rem;">12</p>
            </div>
        </div>
        <div class="col">
            <div class="bnsp-card bnsp-stat-card accent-secondary p-4">
                <p class="text-muted small mb-1">Menunggu Jadwal</p>
                <p class="fw-bold mb-0" style="color:var(--bnsp-secondary); font-size:1.5rem;">01</p>
            </div>
        </div>
        <div class="col">
            <div class="bnsp-card bnsp-stat-card accent-muted p-4">
                <p class="text-muted small mb-1">Poin Kompetensi</p>
                <p class="fw-bold mb-0" style="color:var(--bnsp-primary-container); font-size:1.5rem;">850</p>
            </div>
        </div>
    </section>

    <!-- Certification grid header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="h5 fw-bold mb-0">Program Sertifikasi Tersedia</h3>
        <button class="btn btn-link fw-bold text-decoration-none p-0" style="color:var(--bnsp-primary);">Lihat
            Semua</button>
    </div>

    <!-- Certification cards -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">

        <div class="col">
            <div class="bnsp-card d-flex flex-column">
                <div class="bnsp-cert-media">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhfPSC1atYnhC1yAYWBDvDyt0-Orl4QEqrpWy2sIwNXWdAYCeJ_8jGHkMufHjjeHKvsD2U2K8o8BgKoO5R21Ut5ksVboYZcEZFC1mWVa9uKjO0hL6T0H2xeZyQfAhlbeSibmOCJ_GT4L6nOUjDI9gR_SLMxwvJ-HLFp2DNbEVxrxqJr6vGfvmt3AkW8ga_30-W4eYk3wMZPry6Xujl0-inKQVWxPNtVpAYtAOxTfYduR2AX8JLC-buEJD-c9DEK-sIEXYBMD8GZI8"
                        alt="Diskusi dokumen pengadaan di ruang kerja modern">
                    <span class="bnsp-cert-badge">CPOF</span>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <h4 class="bnsp-cert-title mb-2">Pelaksanaan Dasar Pengadaan Barang/Jasa (CPOF)</h4>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge-status-active">Aktif</span>
                        <span class="text-muted small">• 12 SKS</span>
                    </div>
                    <div class="row g-2 mt-auto">
                        <div class="col-6"><button class="btn btn-bnsp-secondary w-100 py-2">Detail</button>
                        </div>
                        <div class="col-6">
                            <button
                                class="btn btn-bnsp-success w-100 py-2 d-flex align-items-center justify-content-center gap-1">
                                <span class="material-symbols-outlined" style="font-size:18px;">visibility</span>
                                Aktif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="bnsp-card d-flex flex-column">
                <div class="bnsp-cert-media">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAHzf7vNExA8L3mYWz1iE7cjeqon54Qz3LXiNXzfSM0nanCu_T4fEhivbd6DP1REdz32GB_rNtCBFPjzDSFiZgMccA8AYA98eUy-tajFQpMIMQsxu_3Ru3Wg3KM0cllV-cZGkgkIeqS_GomY-xtZRrvoUEbi-XlDBkZUrRH55J5x5aePbdpzZ7zMKHporNF4m9D5Dunon8tqPB-Em5KsSDBeGyl2uExAjnq3_5AYjnQphR4z8OL1TkHr9zLnQ-3Z1v8dwXM4E7WYl8"
                        alt="Tablet menampilkan grafik strategi pengadaan">
                    <span class="bnsp-cert-badge">CPSP</span>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <h4 class="bnsp-cert-title mb-2">Pengelolaan Pengadaan Barang/Jasa (CPSP)</h4>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge-status-active">Aktif</span>
                        <span class="text-muted small">• 24 SKS</span>
                    </div>
                    <div class="row g-2 mt-auto">
                        <div class="col-6"><button class="btn btn-bnsp-secondary w-100 py-2">Detail</button>
                        </div>
                        <div class="col-6">
                            <button
                                class="btn btn-bnsp-success w-100 py-2 d-flex align-items-center justify-content-center gap-1">
                                <span class="material-symbols-outlined" style="font-size:18px;">visibility</span>
                                Aktif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="bnsp-card d-flex flex-column">
                <div class="bnsp-cert-media">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcOseCsawvk_MDdaa5vHol161s46UIXthn60hyzU8wg5RCjXDcjsWvXMnxu-8qtqVp68P15mmYqAFYPksixhELJoWsbRYSuDgKrr5oPZ4Q2pJnsd4_uoLY2BTMzO7jrf9dWbip_wXl4uRbsZrBafvfPCHyyroG1KzdwKOlk_MCNvckJltxN97hqOKe9a_zOdhTLk7vYoFXf621V7XP-1oH5UfCYI5c0Py68_nzXalLbfYt_T1l3sevyLmcXlzcLURmOemJok19pJ4"
                        alt="Dua profesional berjabat tangan di meja kontrak">
                    <span class="bnsp-cert-badge">CCMS</span>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <h4 class="bnsp-cert-title mb-2">Pelaksanaan Manajemen Kontrak (CCMS)</h4>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge-status-active">Aktif</span>
                        <span class="text-muted small">• 18 SKS</span>
                    </div>
                    <div class="row g-2 mt-auto">
                        <div class="col-6"><button class="btn btn-bnsp-secondary w-100 py-2">Detail</button>
                        </div>
                        <div class="col-6">
                            <button
                                class="btn btn-bnsp-success w-100 py-2 d-flex align-items-center justify-content-center gap-1">
                                <span class="material-symbols-outlined" style="font-size:18px;">visibility</span>
                                Aktif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="bnsp-card d-flex flex-column">
                <div class="bnsp-cert-media">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAwaEgu6XZmkUyme0nQ8oUwd9RgnK5aCIxzL8GTk6FwKZe9lcA4yXeQ-0pYApqT9AQH9LVVMhzQuX9NHbePhr_v4qzznoMXR1VNUYYm1eNJEHw4UtPp-1IAkQbWuIcCimbFJnFthhgU85K-1vgB9HRKdgG7z3kfnOlTiJukFAWDGh9QNS0v-eTy6ckj9sok3yyLYPxKpWn9FJipLcc9FhAvLCq-AQoQcNLMV7ABjR5QRl533wV8pMUfS0C17SF8iAZL1bx3SV8cLqA"
                        alt="Ruang auditorium pelatihan pengadaan modern">
                    <span class="bnsp-cert-badge">CPST</span>
                </div>
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <h4 class="bnsp-cert-title mb-2">Pengelolaan Strategi Pengadaan Barang/Jasa (CPST)</h4>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge-status-active">Aktif</span>
                        <span class="text-muted small">• 30 SKS</span>
                    </div>
                    <div class="row g-2 mt-auto">
                        <div class="col-6"><button class="btn btn-bnsp-secondary w-100 py-2">Detail</button>
                        </div>
                        <div class="col-6">
                            <button
                                class="btn btn-bnsp-success w-100 py-2 d-flex align-items-center justify-content-center gap-1">
                                <span class="material-symbols-outlined" style="font-size:18px;">visibility</span>
                                Aktif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Promo banner -->
    <div class="bnsp-promo p-4 p-md-5 d-flex flex-column flex-md-row align-items-center gap-4">
        <div class="flex-grow-1">
            <h3 class="fw-bold mb-2">Upgrade Kompetensi Anda Sekarang</h3>
            <p class="mb-4" style="opacity:.85; max-width:36rem;">
                Dapatkan sertifikasi keahlian tingkat lanjut untuk menunjang karir profesional Anda di bidang
                pengadaan nasional. Proses mudah, kredibel, dan terstandarisasi BNSP.
            </p>
            <button class="bnsp-promo-btn">Daftar Program Baru</button>
        </div>
        <div class="d-none d-lg-block" style="width:16rem; height:16rem; flex-shrink:0;">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDLjj2VeboxCYMCxiyr68ne5GzAXnLsuJcftHE7ezg7x2Kqdv38hfes9f71LgjHp7PariZh4lSW71tDMVRppj2w13Za1Bmks6jRoIw-jWfWgMmEMfL6Es7ipQw3J5eHKKgrt1YQvj_mG86j4VPHHzBBEi5VmhteRKFDjkNug1psUGxuxSipQAjHMFtPRLZWe_DCebx1lp7qcx7phuZDrS2xWvaiXtlGhprvMitqjiWQT3AhQ_IvoLrPt29UCXu2ViJbzfBP1VuOtW4"
                class="w-100 h-100" style="object-fit:contain; filter:drop-shadow(0 25px 25px rgba(0,0,0,.25));"
                alt="Medali sertifikasi profesi BNSP">
        </div>
    </div>
@endsection
