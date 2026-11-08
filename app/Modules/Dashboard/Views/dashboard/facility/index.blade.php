@extends('layouts.app')
@section('title', 'Dashboard')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/facility.css') }}">
@endsection
@section('content')
    <!-- KPI Section -->
    <section aria-labelledby="kpi-heading" class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="fs-title-sm text-on-surface fw-bold mb-0" id="kpi-heading">Ringkasan Fasilitas</h2>
            <span class="fs-label-sm px-3 py-1 rounded-pill bg-surface-container text-on-surface-variant">Semester
                Genap</span>
        </div>
        <div class="d-grid d-grid-cols-2 gap-3">
            <!-- KPI 1 -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fs-body-sm text-on-surface-variant fw-medium">Total Aset Tercatat</span>
                    <div class="icon-box bg-surface-container text-primary-container">
                        <span class="material-symbols-outlined fs-6" data-icon="inventory_2">inventory_2</span>
                    </div>
                </div>
                <div class="fs-headline-lg-mobile text-primary fw-bold">3.420</div>
                <p class="fs-label-sm text-secondary mt-1 d-flex align-items-center gap-1 mb-0">
                    <span class="material-symbols-outlined" style="font-size: 14px;" data-icon="verified">verified</span>
                    Item Terverifikasi
                </p>
                <div class="position-absolute top-0 bottom-0 start-0 w-10 border-left-primary-container"></div>
            </div>
            <!-- KPI 2 -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fs-body-sm text-on-surface-variant fw-medium">Kondisi Prima</span>
                    <div class="icon-box" style="background-color: #ECFDF5; color: #047857;">
                        <span class="material-symbols-outlined fs-6" data-icon="check_circle">check_circle</span>
                    </div>
                </div>
                <div class="fs-headline-lg-mobile text-secondary fw-bold">94.2%</div>
                <p class="fs-label-sm text-on-surface-variant mt-1 mb-0">Siap & Layak Pakai</p>
                <div class="position-absolute top-0 bottom-0 start-0 w-10 border-left-secondary"></div>
            </div>
            <!-- KPI 3 -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fs-body-sm text-on-surface-variant fw-medium">Perbaikan</span>
                    <div class="icon-box" style="background-color: #FEF3C7; color: #B45309;">
                        <span class="material-symbols-outlined fs-6" data-icon="build">build</span>
                    </div>
                </div>
                <div class="fs-headline-lg-mobile text-on-surface fw-bold">18 <span
                        class="fs-body-sm text-on-surface-variant fw-normal">Aset</span></div>
                <div class="d-inline-flex align-items-center gap-1 mt-1 px-2 py-1 rounded-pill"
                    style="background-color: #FEF2F2; color: #B91C1C; font-size: 0.6875rem; font-weight: 700;">
                    <span class="dot bg-danger animate-pulse"></span>
                    3 Mendesak
                </div>
                <div class="position-absolute top-0 bottom-0 start-0 w-10 border-left-amber-600"></div>
            </div>
            <!-- KPI 4 -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fs-body-sm text-on-surface-variant fw-medium">Ruang & Lab</span>
                    <div class="icon-box bg-surface-container-high text-primary">
                        <span class="material-symbols-outlined fs-6" data-icon="meeting_room">meeting_room</span>
                    </div>
                </div>
                <div class="fs-headline-lg-mobile text-primary fw-bold">24 <span
                        class="fs-headline-md text-on-surface-variant fw-normal">/ 26</span></div>
                <p class="fs-label-sm text-secondary mt-1 mb-0">2 Ruangan Terisi</p>
                <div class="position-absolute top-0 bottom-0 start-0 w-10 border-left-surface-tint"></div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section aria-labelledby="actions-heading" class="mb-4">
        <h2 class="fs-title-sm text-on-surface fw-bold mb-3" id="actions-heading">Aksi Cepat</h2>
        <div class="d-grid d-grid-cols-4 gap-2">
            <!-- Action 1 -->
            <button
                class="d-flex flex-column align-items-center text-center p-2 rounded-4 bg-surface-container-lowest border border-outline-variant border-opacity-25 hover-border-secondary btn-scale transition-all shadow-sm"
                type="button">
                <div class="icon-box-lg mb-2" style="background-color: #FEF2F2; color: #B91C1C;">
                    <span class="material-symbols-outlined" data-icon="report_problem">report_problem</span>
                </div>
                <span class="fs-label-sm text-on-surface lh-tight fw-semibold">Lapor Rusak</span>
            </button>
            <!-- Action
                                <!-- Action 2 -->
            <button
                class="d-flex flex-column align-items-center text-center p-2 rounded-4 bg-surface-container-lowest border border-outline-variant border-opacity-25 btn-scale transition-all shadow-sm"
                type="button">
                <div class="icon-box-lg mb-2" style="background-color: #EEF2FF; color: var(--primary);">
                    <span class="material-symbols-outlined" data-icon="calendar_add_on">calendar_add_on</span>
                </div>
                <span class="fs-label-sm text-on-surface lh-tight fw-semibold">Booking Ruang</span>
            </button>
            <!-- Action 3 -->
            <button
                class="d-flex flex-column align-items-center text-center p-2 rounded-4 bg-surface-container-lowest border border-outline-variant border-opacity-25 btn-scale transition-all shadow-sm"
                type="button">
                <div class="icon-box-lg mb-2" style="background-color: #F0FDFA; color: var(--secondary);">
                    <span class="material-symbols-outlined" data-icon="qr_code_scanner">qr_code_scanner</span>
                </div>
                <span class="fs-label-sm text-on-surface lh-tight fw-semibold">Scan Barcode</span>
            </button>
            <!-- Action 4 -->
            <button
                class="d-flex flex-column align-items-center text-center p-2 rounded-4 bg-surface-container-lowest border border-outline-variant border-opacity-25 btn-scale transition-all shadow-sm"
                type="button">
                <div class="icon-box-lg mb-2 bg-surface-container text-on-surface-variant">
                    <span class="material-symbols-outlined" data-icon="pending_actions">pending_actions</span>
                </div>
                <span class="fs-label-sm text-on-surface lh-tight fw-semibold">Jadwal Servis</span>
            </button>
        </div>
    </section>

    <!-- Daftar Ruangan & Sarana -->
    <section aria-labelledby="rooms-heading" class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="fs-title-sm text-on-surface fw-bold mb-0" id="rooms-heading">Daftar Ruangan & Sarana
                </h2>
                <p class="fs-body-sm text-on-surface-variant mb-0">Status ketersediaan gedung pembelajaran</p>
            </div>
            <button
                class="d-flex align-items-center gap-1 text-secondary fs-label-md fw-semibold border-0 bg-transparent p-0">
                Lihat Semua
                <span class="material-symbols-outlined" style="font-size: 16px;"
                    data-icon="chevron_right">chevron_right</span>
            </button>
        </div>
        <div class="d-flex flex-column gap-3">
            <!-- Item 1: Lab Komputer Multimedia 1 -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card shadow-card-hover transition-all">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box" style="background-color: rgba(0, 106, 97, 0.1); color: var(--secondary);">
                            <span class="material-symbols-outlined" style="font-size: 20px;"
                                data-icon="desktop_windows">desktop_windows</span>
                        </div>
                        <div>
                            <h3 class="fs-title-sm text-on-surface fw-bold lh-tight mb-0">Laboratorium Komputer
                                Multimedia 1</h3>
                            <span class="fs-body-sm text-on-surface-variant">Gedung B • Lantai 2</span>
                        </div>
                    </div>
                    <span
                        class="d-flex align-items-center gap-1 fs-label-sm px-3 py-1 rounded-pill fw-semibold text-nowrap"
                        style="background-color: #ECFDF5; color: #047857;">
                        <span class="dot" style="background-color: #047857;"></span>
                        Siap Pakai
                    </span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between fs-body-sm"
                    style="border-color: #F1F5F9;">
                    <div class="d-flex align-items-center gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined" style="font-size: 16px; color: var(--secondary);"
                            data-icon="devices">devices</span>
                        Kapasitas: <strong>40 PC</strong>
                    </div>
                    <div class="fs-label-sm fw-semibold px-2 py-1 rounded"
                        style="background-color: #F0FDFA; color: #047857;">
                        Kondisi 100%
                    </div>
                </div>
            </div>

            <!-- Item 2: Lab IPA & Kimia -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card shadow-card-hover transition-all">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box" style="background-color: rgba(2, 16, 100, 0.1); color: var(--primary);">
                            <span class="material-symbols-outlined" style="font-size: 20px;"
                                data-icon="science">science</span>
                        </div>
                        <div>
                            <h3 class="fs-title-sm text-on-surface fw-bold lh-tight mb-0">Laboratorium IPA & Kimia
                            </h3>
                            <span class="fs-body-sm text-on-surface-variant">Gedung Sains • Lantai 1</span>
                        </div>
                    </div>
                    <span
                        class="d-flex align-items-center gap-1 fs-label-sm px-3 py-1 rounded-pill fw-semibold text-nowrap"
                        style="background-color: #EEF2FF; color: var(--primary);">
                        <span class="dot" style="background-color: var(--primary);"></span>
                        Dipinjam
                    </span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between fs-body-sm"
                    style="border-color: #F1F5F9;">
                    <div class="d-flex align-items-center gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined" style="font-size: 16px;" data-icon="group">group</span>
                        Kelas XI MIPA 2
                    </div>
                    <div class="fs-label-sm text-on-surface-variant">
                        Sampai 11:30 WIB
                    </div>
                </div>
            </div>

            <!-- Item 3: Aula Utama -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card shadow-card-hover transition-all">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-surface-container text-primary-container">
                            <span class="material-symbols-outlined" style="font-size: 20px;"
                                data-icon="apartment">apartment</span>
                        </div>
                        <div>
                            <h3 class="fs-title-sm text-on-surface fw-bold lh-tight mb-0">Aula Pertemuan Utama</h3>
                            <span class="fs-body-sm text-on-surface-variant">Gedung Pusat • Hall Barat</span>
                        </div>
                    </div>
                    <span
                        class="d-flex align-items-center gap-1 fs-label-sm px-3 py-1 rounded-pill fw-semibold text-nowrap"
                        style="background-color: #ECFDF5; color: #047857;">
                        <span class="dot" style="background-color: #047857;"></span>
                        Tersedia
                    </span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between fs-body-sm"
                    style="border-color: #F1F5F9;">
                    <div class="d-flex align-items-center gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined" style="font-size: 16px;"
                            data-icon="event_seat">event_seat</span>
                        Kapasitas: 350 Kursi
                    </div>
                    <button class="text-secondary fs-label-md fw-semibold border-0 bg-transparent p-0">
                        Reservasi
                    </button>
                </div>
            </div>

            <!-- Item 4: Lapangan Indoor -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card shadow-card-hover transition-all">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box" style="background-color: rgba(254, 243, 199, 0.4); color: #B45309;">
                            <span class="material-symbols-outlined" style="font-size: 20px;"
                                data-icon="sports_basketball">sports_basketball</span>
                        </div>
                        <div>
                            <h3 class="fs-title-sm text-on-surface fw-bold lh-tight mb-0">Lapangan Olahraga Indoor
                            </h3>
                            <span class="fs-body-sm text-on-surface-variant">Gedung Olahraga & Seni</span>
                        </div>
                    </div>
                    <span
                        class="d-flex align-items-center gap-1 fs-label-sm px-3 py-1 rounded-pill fw-semibold text-nowrap"
                        style="background-color: #FEF3C7; color: #B45309;">
                        <span class="dot" style="background-color: #B45309;"></span>
                        Perawatan
                    </span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between fs-body-sm"
                    style="border-color: #F1F5F9;">
                    <div class="d-flex align-items-center gap-2" style="color: #B45309;">
                        <span class="material-symbols-outlined" style="font-size: 16px;"
                            data-icon="mode_fan">mode_fan</span>
                        Terjadwal Perawatan AC
                    </div>
                    <span class="fs-label-sm text-on-surface-variant">Estimasi: Hari Ini</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Laporan Kerusakan Terbaru -->
    <section aria-labelledby="maintenance-heading" class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="fs-title-sm text-on-surface fw-bold mb-0" id="maintenance-heading">Laporan Kerusakan
                    Terbaru</h2>
                <p class="fs-body-sm text-on-surface-variant mb-0">Tiket pengerjaan & penanganan teknisi</p>
            </div>
            <span class="fs-label-sm text-primary fw-bold px-3 py-1 rounded-pill bg-primary-fixed-50">2
                Aktif</span>
        </div>
        <div class="d-flex flex-column gap-3">
            <!-- Tiket 1 -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card position-relative">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span
                                class="fs-label-sm px-2 py-1 rounded bg-surface-container text-on-surface-variant">#TKT-2024-089</span>
                            <span class="fs-label-sm px-2 py-1 rounded fw-bold"
                                style="background-color: #FEF2F2; color: #B91C1C;">Mendesak</span>
                        </div>
                        <h3 class="fs-title-sm text-on-surface fw-bold mt-2 mb-1">Proyektor Lab Fisika</h3>
                        <p class="fs-body-sm text-on-surface-variant mb-0">Lampu redup mendadak & port HDMI longgar
                        </p>
                    </div>
                    <div class="icon-box flex-shrink-0"
                        style="background-color: rgba(186, 26, 26, 0.05); color: var(--error);">
                        <span class="material-symbols-outlined" style="font-size: 20px;"
                            data-icon="videocam_off">videocam_off</span>
                    </div>
                </div>
                <div class="bg-surface-container-low p-3 rounded-3">
                    <div class="d-flex align-items-center justify-content-between fs-label-md mb-2">
                        <span class="d-flex align-items-center gap-1 text-on-surface-variant">
                            <span class="material-symbols-outlined" style="font-size: 16px; color: var(--secondary);"
                                data-icon="engineering">engineering</span>
                            Teknisi: Pak Bambang S.
                        </span>
                        <span class="text-secondary fw-bold">Dalam Pengerjaan</span>
                    </div>
                    <div class="progress-bar-custom mb-2">
                        <div class="progress-fill-secondary"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between fs-label-sm text-on-surface-variant">
                        <span>Penggantian Sparepart</span>
                        <span>65% Selesai</span>
                    </div>
                </div>
            </div>

            <!-- Tiket 2 -->
            <div
                class="bg-surface-container-lowest p-3 rounded-4 border border-outline-variant border-opacity-25 shadow-card position-relative">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span
                                class="fs-label-sm px-2 py-1 rounded bg-surface-container text-on-surface-variant">#TKT-2024-092</span>
                            <span class="fs-label-sm px-2 py-1 rounded fw-bold"
                                style="background-color: #FEF3C7; color: #B45309;">Sedang</span>
                        </div>
                        <h3 class="fs-title-sm text-on-surface fw-bold mt-2 mb-1">AC Ruang Guru B</h3>
                        <p class="fs-body-sm text-on-surface-variant mb-0">Bocor air freon & hembusan tidak dingin
                        </p>
                    </div>
                    <div class="icon-box flex-shrink-0"
                        style="background-color: rgba(254, 243, 199, 0.4); color: #B45309;">
                        <span class="material-symbols-outlined" style="font-size: 20px;"
                            data-icon="ac_unit">ac_unit</span>
                    </div>
                </div>
                <div class="bg-surface-container-low p-3 rounded-3">
                    <div class="d-flex align-items-center justify-content-between fs-label-md mb-2">
                        <span class="d-flex align-items-center gap-1 text-on-surface-variant">
                            <span class="material-symbols-outlined"
                                style="font-size: 16px; color: var(--on-primary-fixed-variant);"
                                data-icon="schedule">schedule</span>
                            Teknisi: Tim Vendor Daikin
                        </span>
                        <span class="text-primary fw-bold">Dijadwalkan</span>
                    </div>
                    <div class="progress-bar-custom mb-2">
                        <div class="progress-fill-primary"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between fs-label-sm text-on-surface-variant">
                        <span>Kunjungan Teknisi</span>
                        <span>Pukul 14:00 WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tombol Buat Tiket Baru -->
    <div class="pt-2 pb-4">
        <button
            class="w-100 py-3 rounded-4 bg-primary-container text-on-primary d-flex align-items-center justify-content-center gap-2 shadow-sm border-0 fs-headline-md btn-scale-sm transition-all"
            type="button">
            <span class="material-symbols-outlined" style="font-size: 18px;" data-icon="add_circle">add_circle</span>
            Buat Tiket Perawatan Baru
        </button>
    </div>
@endsection
