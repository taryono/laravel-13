@extends('layouts.app')

@section('content')
    <div class="container-fluid px-0">
        {{-- STATISTIK CEPAT (SAMA PERSIS DENGAN DESAIN FRONT) --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 stat-card-admin">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="stat-icon-wrapper bg-primary-subtle text-primary me-3">
                            <i class="fa-solid fa-clipboard-list fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Total Permintaan</div>
                            <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 stat-card-admin">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="stat-icon-wrapper bg-warning-subtle text-warning me-3">
                            <i class="fa-solid fa-hourglass-half fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Menunggu Konfirmasi</div>
                            <h3 class="mb-0 fw-bold text-warning">{{ $stats['pending'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 stat-card-admin">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="stat-icon-wrapper bg-info-subtle text-info me-3">
                            <i class="fa-solid fa-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Demo Terjadwal</div>
                            <h3 class="mb-0 fw-bold text-info">{{ $stats['scheduled'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 stat-card-admin">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="stat-icon-wrapper bg-success-subtle text-success me-3">
                            <i class="fa-solid fa-circle-check fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Demo Selesai</div>
                            <h3 class="mb-0 fw-bold text-success">{{ $stats['completed'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD UTAMA --}}
        <div class="card border-0 shadow-sm rounded-4">
            {{-- HEADER CARD DENGAN TOMBOL AKSI & TABS --}}
            <div class="card-header bg-white border-bottom p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h4 class="fw-bold mb-1 d-flex align-items-center">
                            <span class="badge bg-primary me-2 rounded-3 p-2">
                                <i class="fa-solid fa-laptop-code"></i>
                            </span>
                            Manajemen Permintaan Demo Sekolah
                        </h4>
                        <p class="text-muted small mb-0">Kelola antrean prospek sekolah, jadwal presentasi produk, dan
                            status tindak lanjut.</p>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        {{-- Nav Tabs View Mode --}}
                        <ul class="nav nav-pills custom-admin-pills p-1 bg-light rounded-pill border" id="viewModeTabs"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill px-3 py-1 small fw-semibold" id="visual-tab"
                                    data-bs-toggle="pill" data-bs-target="#visual-pane" type="button" role="tab">
                                    <i class="fa-solid fa-table-list me-1"></i> Desain Visual
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-3 py-1 small fw-semibold" id="datatable-tab"
                                    data-bs-toggle="pill" data-bs-target="#datatable-pane" type="button" role="tab">
                                    <i class="fa-solid fa-table me-1"></i> DataTables
                                </button>
                            </li>
                        </ul>

                        {{-- Tombol Tambah Demo --}}
                        <button type="button"
                            class="btn btn-primary rounded-pill px-3 shadow-sm d-flex align-items-center gap-2"
                            data-toggle="ajax-modal" data-size="lg" data-method="GET"
                            data-title="Tambah Permintaan Demo Baru" data-url="{{ route('demo_request.create') }}"
                            data-submit='<button class="btn btn-primary" type="submit" form="formDemoRequest"><i class="fa-solid fa-save me-1"></i> Simpan</button>'
                            data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                            <i class="fa-solid fa-circle-plus"></i>
                            <span class="d-none d-sm-inline">Tambah Demo</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-3 p-md-4">
                @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center mb-4 rounded-3 border-0 shadow-sm"
                        role="alert">
                        <i class="fa-solid fa-circle-check fs-4 me-3 text-success"></i>
                        <div>
                            <h6 class="alert-heading mb-1 fw-bold">Berhasil!</h6>
                            <p class="mb-0 small">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <div class="tab-content" id="viewModeTabsContent">
                    {{-- TAB 1: DESAIN VISUAL SAMA PERSIS DENGAN FRONT --}}
                    <div class="tab-pane fade show active" id="visual-pane" role="tabpanel">
                        {{-- FILTER & LIVE SEARCH BAR --}}
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 p-3 bg-light rounded-4 border">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-filter text-primary"></i>
                                <span class="fw-semibold small">Filter Data:</span>
                                <select id="adminStatusFilter" class="form-select form-select-sm rounded-pill"
                                    style="max-width: 180px;">
                                    <option value="">Semua Status</option>
                                    <option value="pending">🟡 Menunggu</option>
                                    <option value="contacted">🔵 Dihubungi</option>
                                    <option value="scheduled">🟢 Terjadwal</option>
                                    <option value="completed">✅ Selesai</option>
                                    <option value="cancelled">⚪ Dibatalkan</option>
                                </select>
                            </div>

                            <div class="input-group input-group-sm" style="max-width: 320px;">
                                <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i
                                        class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" id="adminSearchInput"
                                    class="form-control border-start-0 rounded-end-pill"
                                    placeholder="Cari nama sekolah / PIC...">
                            </div>
                        </div>

                        {{-- TABEL DESAIN VISUAL --}}
                        @if ($demoRequests->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fa-solid fa-calendar-xmark display-4 text-muted opacity-50"></i>
                                </div>
                                <h5 class="fw-bold text-muted mb-2">Belum Ada Permintaan Demo</h5>
                                <p class="text-muted small mb-3">Klik tombol "Tambah Demo" di atas untuk menambahkan data
                                    secara manual.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle custom-admin-table" id="adminDemoTable">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 50px;">#</th>
                                            <th scope="col">Sekolah &amp; Penanggung Jawab</th>
                                            <th scope="col">Paket Pilihan</th>
                                            <th scope="col">Jadwal Demo</th>
                                            <th scope="col">Kontak</th>
                                            <th scope="col" class="text-center">Status</th>
                                            <th scope="col" class="text-end">Aksi Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($demoRequests as $index => $item)
                                            @php
                                                $initials = strtoupper(substr($item->school_name, 0, 2));

                                                // Paket styling
                                                $packageClass = match (strtolower($item->package)) {
                                                    'starter' => 'badge-starter',
                                                    'professional' => 'badge-professional',
                                                    'enterprise' => 'badge-enterprise',
                                                    default => 'badge-default',
                                                };

                                                // Status styling
                                                $statusBadge = match (strtolower($item->status)) {
                                                    'scheduled' => [
                                                        'badge bg-info-subtle text-info border border-info-subtle',
                                                        'Terjadwal',
                                                        'fa-calendar-check',
                                                    ],
                                                    'completed' => [
                                                        'badge bg-success-subtle text-success border border-success-subtle',
                                                        'Selesai',
                                                        'fa-circle-check',
                                                    ],
                                                    'contacted' => [
                                                        'badge bg-primary-subtle text-primary border border-primary-subtle',
                                                        'Dihubungi',
                                                        'fa-phone-volume',
                                                    ],
                                                    'cancelled' => [
                                                        'badge bg-secondary-subtle text-secondary border border-secondary-subtle',
                                                        'Dibatalkan',
                                                        'fa-circle-xmark',
                                                    ],
                                                    default => [
                                                        'badge bg-warning-subtle text-warning border border-warning-subtle',
                                                        'Menunggu',
                                                        'fa-hourglass-half',
                                                    ],
                                                };

                                                // Format phone for WhatsApp
                                                $cleanPhone = preg_replace('/[^0-9]/', '', $item->phone);
                                                $waPhone = str_starts_with($cleanPhone, '0')
                                                    ? '62' . substr($cleanPhone, 1)
                                                    : $cleanPhone;
                                            @endphp
                                            <tr class="admin-demo-row" data-status="{{ strtolower($item->status) }}">
                                                <td class="text-muted fw-bold">
                                                    {{ $demoRequests->firstItem() + $index }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="school-avatar me-3">
                                                            {{ $initials }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold school-name text-dark text-truncate"
                                                                style="max-width: 240px;">
                                                                {{ $item->school_name }}
                                                            </div>
                                                            <div class="text-muted small">
                                                                <i class="fa-solid fa-user me-1"></i>
                                                                {{ $item->person_name }}
                                                                <span
                                                                    class="badge bg-light text-secondary border ms-1">{{ $item->position }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="package-pill {{ $packageClass }}">
                                                        <i class="fa-solid fa-cube me-1"></i>
                                                        {{ ucfirst($item->package) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark">
                                                        <i class="fa-regular fa-calendar me-1 text-primary"></i>
                                                        {{ \Carbon\Carbon::parse($item->demo_datetime)->translatedFormat('d M Y') }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="fa-regular fa-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($item->demo_datetime)->format('H:i') }}
                                                        WIB
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        <a href="mailto:{{ $item->email }}"
                                                            class="text-decoration-none text-muted d-block text-truncate"
                                                            style="max-width: 170px;" title="{{ $item->email }}">
                                                            <i class="fa-regular fa-envelope me-1 text-primary"></i>
                                                            {{ $item->email }}
                                                        </a>
                                                        <a href="https://wa.me/{{ $waPhone }}?text=Halo%20{{ urlencode($item->person_name) }}%20dari%20{{ urlencode($item->school_name) }},%20kami%20dari%20EduPulse%20ingin%20mengonfirmasi%20jadwal%20demo%20Anda."
                                                            target="_blank"
                                                            class="text-decoration-none text-success fw-semibold small"
                                                            title="Chat WhatsApp">
                                                            <i class="fa-brands fa-whatsapp me-1"></i> {{ $item->phone }}
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="{{ $statusBadge[0] }} px-2 py-1 rounded-pill">
                                                        <i class="fa-solid {{ $statusBadge[2] }} me-1"></i>
                                                        {{ $statusBadge[1] }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-inline-flex gap-1">
                                                        {{-- Tombol Detail Modal --}}
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-info rounded-circle p-2 d-flex align-items-center justify-content-center"
                                                            style="width:32px;height:32px;" data-bs-toggle="modal"
                                                            data-bs-target="#adminDetailModal{{ $item->id }}"
                                                            title="Lihat Detail">
                                                            <i class="fa-solid fa-eye fs-6"></i>
                                                        </button>

                                                        {{-- Tombol Edit via AJAX Modal --}}
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                                                            style="width:32px;height:32px;" data-toggle="ajax-modal"
                                                            data-size="lg" data-method="GET"
                                                            data-title="Edit Permintaan Demo — {{ $item->school_name }}"
                                                            data-url="{{ route('demo_request.edit', $item->id) }}"
                                                            data-submit='<button class="btn btn-primary" type="submit" form="formDemoRequest"><i class="fa-solid fa-save me-1"></i> Simpan Perubahan</button>'
                                                            data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'
                                                            title="Edit Data">
                                                            <i class="fa-solid fa-pen-to-square fs-6"></i>
                                                        </button>

                                                        {{-- Tombol Hapus --}}
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center delete-button"
                                                            style="width:32px;height:32px;" data-confirm-delete="true"
                                                            data-is_execute_drawer="true"
                                                            data-url="{{ route('demo_request.destroy', $item->id) }}"
                                                            title="Hapus Data">
                                                            <i class="fa-solid fa-trash-can fs-6"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            {{-- MODAL DETAIL --}}
                                            <div class="modal fade" id="adminDetailModal{{ $item->id }}"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 border-0 shadow">
                                                        <div class="modal-header border-bottom">
                                                            <h5 class="modal-title fw-bold">
                                                                <i class="fa-solid fa-circle-info text-primary me-2"></i>
                                                                Detail Pengajuan Demo
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="d-flex align-items-center mb-4">
                                                                <div class="school-avatar avatar-lg me-3">
                                                                    {{ $initials }}
                                                                </div>
                                                                <div>
                                                                    <h5 class="fw-bold mb-1">{{ $item->school_name }}</h5>
                                                                    <span
                                                                        class="{{ $statusBadge[0] }} px-2 py-1 rounded-pill small">
                                                                        <i
                                                                            class="fa-solid {{ $statusBadge[2] }} me-1"></i>
                                                                        {{ $statusBadge[1] }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="list-group list-group-flush border rounded-3 mb-3">
                                                                <div
                                                                    class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                                    <span class="text-muted small"><i
                                                                            class="fa-solid fa-user me-2 text-primary"></i>
                                                                        Penanggung Jawab</span>
                                                                    <span
                                                                        class="fw-semibold small">{{ $item->person_name }}</span>
                                                                </div>
                                                                <div
                                                                    class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                                    <span class="text-muted small"><i
                                                                            class="fa-solid fa-id-badge me-2 text-primary"></i>
                                                                        Jabatan</span>
                                                                    <span
                                                                        class="fw-semibold small">{{ $item->position }}</span>
                                                                </div>
                                                                <div
                                                                    class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                                    <span class="text-muted small"><i
                                                                            class="fa-solid fa-box-open me-2 text-primary"></i>
                                                                        Paket Pilihan</span>
                                                                    <span
                                                                        class="package-pill {{ $packageClass }}">{{ ucfirst($item->package) }}</span>
                                                                </div>
                                                                <div
                                                                    class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                                    <span class="text-muted small"><i
                                                                            class="fa-regular fa-calendar-days me-2 text-primary"></i>
                                                                        Waktu Demo</span>
                                                                    <span
                                                                        class="fw-semibold small text-primary">{{ \Carbon\Carbon::parse($item->demo_datetime)->translatedFormat('l, d F Y_H:i') }}
                                                                        WIB</span>
                                                                </div>
                                                                <div
                                                                    class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                                    <span class="text-muted small"><i
                                                                            class="fa-regular fa-envelope me-2 text-primary"></i>
                                                                        Email</span>
                                                                    <span class="small"><a
                                                                            href="mailto:{{ $item->email }}">{{ $item->email }}</a></span>
                                                                </div>
                                                                <div
                                                                    class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                                    <span class="text-muted small"><i
                                                                            class="fa-brands fa-whatsapp me-2 text-success"></i>
                                                                        WhatsApp</span>
                                                                    <span class="small"><a
                                                                            href="https://wa.me/{{ $waPhone }}"
                                                                            target="_blank"
                                                                            class="text-success fw-bold">{{ $item->phone }}</a></span>
                                                                </div>
                                                                <div
                                                                    class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                                    <span class="text-muted small"><i
                                                                            class="fa-regular fa-clock me-2 text-primary"></i>
                                                                        Diajukan Pada</span>
                                                                    <span
                                                                        class="text-muted small">{{ $item->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</span>
                                                                </div>
                                                            </div>

                                                            @if ($item->message)
                                                                <div class="p-3 bg-light rounded-3 border mb-3">
                                                                    <div class="fw-semibold small text-muted mb-1"><i
                                                                            class="fa-solid fa-comment-dots text-primary me-1"></i>
                                                                        Pesan Sekolah:</div>
                                                                    <p class="mb-0 small">{{ $item->message }}</p>
                                                                </div>
                                                            @endif

                                                            @if ($item->notes)
                                                                <div
                                                                    class="p-3 bg-warning-subtle rounded-3 border border-warning-subtle">
                                                                    <div class="fw-semibold small text-warning mb-1"><i
                                                                            class="fa-solid fa-notes-medical me-1"></i>
                                                                        Catatan Internal Admin:</div>
                                                                    <p class="mb-0 small text-dark">{{ $item->notes }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer border-top">
                                                            <a href="https://wa.me/{{ $waPhone }}?text=Halo%20{{ urlencode($item->person_name) }}%20dari%20{{ urlencode($item->school_name) }},%20kami%20dari%20EduPulse%20ingin%20mengonfirmasi%20jadwal%20demo%20Anda."
                                                                target="_blank" class="btn btn-success rounded-pill px-3">
                                                                <i class="fa-brands fa-whatsapp me-1"></i> Hubungi WhatsApp
                                                            </a>
                                                            <button type="button"
                                                                class="btn btn-secondary rounded-pill px-3"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- PAGINASI --}}
                            <div class="d-flex justify-content-between align-items-center flex-wrap mt-4 pt-3 border-top">
                                <div class="text-muted small">
                                    Menampilkan {{ $demoRequests->firstItem() ?? 0 }}_{{ $demoRequests->lastItem() ?? 0 }}
                                    dari {{ $demoRequests->total() }} pengajuan
                                </div>
                                <div>
                                    {{ $demoRequests->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- TAB 2: YAJRA DATATABLES --}}
                    <div class="tab-pane fade" id="datatable-pane" role="tabpanel">
                        <div class="table-responsive py-2">
                            {!! $dataTable->table(['class' => 'table table-bordered table-hover w-100', 'id' => 'demo-request-table']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* STATS CARD */
        .stat-card-admin {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card-admin:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* TABS */
        .custom-admin-pills .nav-link {
            color: #5b6478;
            transition: all 0.2s ease;
        }

        .custom-admin-pills .nav-link.active {
            background-color: #2f5ce0;
            color: #ffffff;
        }

        /* AVATAR */
        .school-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2f5ce0, #7b42f6);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(47, 92, 224, 0.25);
            flex-shrink: 0;
        }

        .avatar-lg {
            width: 54px;
            height: 54px;
            font-size: 1.3rem;
            border-radius: 14px;
        }

        /* PACKAGE PILLS */
        .package-pill {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            border-radius: 50rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .badge-starter {
            background: rgba(13, 202, 240, 0.12);
            color: #0dcaf0;
            border: 1px solid rgba(13, 202, 240, 0.3);
        }

        .badge-professional {
            background: rgba(47, 92, 224, 0.12);
            color: #2f5ce0;
            border: 1px solid rgba(47, 92, 224, 0.3);
        }

        .badge-enterprise {
            background: rgba(168, 85, 247, 0.12);
            color: #a855f7;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }

        .badge-default {
            background: rgba(108, 117, 125, 0.12);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        /* TABLE */
        .custom-admin-table thead th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }

        .custom-admin-table tbody tr {
            border-bottom: 1px solid #edf2f7;
        }
    </style>
@endpush

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('adminSearchInput');
            const statusFilter = document.getElementById('adminStatusFilter');
            const table = document.getElementById('adminDemoTable');

            if (table && searchInput) {
                const rows = table.querySelectorAll('tbody tr.admin-demo-row');

                function filterRows() {
                    const query = searchInput.value.toLowerCase().trim();
                    const selectedStatus = statusFilter ? statusFilter.value.toLowerCase() : '';

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        const rowStatus = row.getAttribute('data-status') || '';

                        const matchesQuery = !query || text.includes(query);
                        const matchesStatus = !selectedStatus || rowStatus === selectedStatus;

                        if (matchesQuery && matchesStatus) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                searchInput.addEventListener('input', filterRows);
                if (statusFilter) {
                    statusFilter.addEventListener('change', filterRows);
                }
            }
        });
    </script>
@endpush
