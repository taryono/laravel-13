@extends('layouts.front')

@section('content')
<section class="hero py-5">
    <div class="container">
        {{-- HERO HEADER --}}
        <div class="row justify-content-center text-center mb-4">
            <div class="col-lg-9 col-md-11">
                <span class="badge badge-hero mb-3 px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-sparkles me-1 text-warning"></i> EduPulse Interactive Demo
                </span>
                <h1 class="display-5 fw-extrabold text-light mb-3">Jadwalkan & Pantau Demo Sekolah</h1>
                <p class="lead text-light opacity-75 mx-auto" style="max-width: 680px;">
                    Eksplorasi ekosistem modul terintegrasi EduPulse langsung bersama konsultan kami. Temukan solusi tepat untuk transformasi digital institusi Anda.
                </p>

                {{-- STATISTIK CEPAT --}}
                <div class="row g-3 justify-content-center mt-3">
                    <div class="col-6 col-md-3">
                        <div class="stat-pill">
                            <span class="stat-number">{{ $stats['total'] }}</span>
                            <span class="stat-title"><i class="fa-solid fa-clipboard-list me-1 text-primary"></i> Total Permintaan</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-pill">
                            <span class="stat-number text-warning">{{ $stats['pending'] }}</span>
                            <span class="stat-title"><i class="fa-solid fa-hourglass-half me-1 text-warning"></i> Menunggu Konfirmasi</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-pill">
                            <span class="stat-number text-info">{{ $stats['scheduled'] }}</span>
                            <span class="stat-title"><i class="fa-solid fa-calendar-check me-1 text-info"></i> Terjadwal</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-pill">
                            <span class="stat-number text-success">{{ $stats['completed'] }}</span>
                            <span class="stat-title"><i class="fa-solid fa-circle-check me-1 text-success"></i> Selesai</span>
                        </div>
                    </div>
                </div>

                {{-- NAVIGASI TAB MENU --}}
                <div class="d-flex justify-content-center mt-4">
                    <ul class="nav nav-pills custom-nav-pills p-1 rounded-pill" id="demoTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ session('success') ? '' : 'active' }} rounded-pill px-4 py-2" id="form-tab" data-bs-toggle="pill" data-bs-target="#form-pane" type="button" role="tab">
                                <i class="fa-solid fa-calendar-plus me-2"></i> Form Permintaan Demo
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ session('success') ? 'active' : '' }} rounded-pill px-4 py-2" id="list-tab" data-bs-toggle="pill" data-bs-target="#list-pane" type="button" role="tab">
                                <i class="fa-solid fa-list-check me-2"></i> Daftar Permintaan Demo
                                <span class="badge bg-primary ms-1 rounded-pill">{{ $stats['total'] }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- TAB CONTENT --}}
        <div class="tab-content" id="demoTabsContent">
            {{-- TAB 1: FORMULIR JADWALKAN DEMO --}}
            <div class="tab-pane fade {{ session('success') ? '' : 'show active' }}" id="form-pane" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        {{-- CARD FORM --}}
                        <div class="demo-card p-4 p-md-5">
                            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                                <div>
                                    <h4 class="fw-bold mb-1"><i class="fa-solid fa-file-pen text-primary me-2"></i> Formulir Pengajuan Demo</h4>
                                    <p class="text-muted small mb-0">Lengkapi data institusi Anda untuk penjadwalan presentasi langsung.</p>
                                </div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill d-none d-sm-inline-block">
                                    Respon 1×24 Jam
                                </span>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success d-flex align-items-center mb-4 rounded-3 border-0 shadow-sm" role="alert">
                                    <i class="fa-solid fa-circle-check fs-3 me-3 text-success"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1 fw-bold">Permintaan Berhasil Terkirim!</h6>
                                        <p class="mb-0 small">{{ session('success') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger d-flex align-items-center mb-4 rounded-3 border-0 shadow-sm" role="alert">
                                    <i class="fa-solid fa-triangle-exclamation fs-3 me-3 text-danger"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1 fw-bold">Terjadi Kendala</h6>
                                        <p class="mb-0 small">{{ session('error') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-warning mb-4 rounded-3 border-0 shadow-sm" role="alert">
                                    <h6 class="alert-heading mb-2 fw-bold"><i class="fa-solid fa-circle-exclamation me-1"></i> Mohon lengkapi data berikut:</h6>
                                    <ul class="mb-0 ps-3 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {!! Form::open(['route' => 'submit_request_demo.form', 'method' => 'POST']) !!}
                                @csrf

                                <div class="row g-4">
                                    {{-- Nama Sekolah --}}
                                    <div class="col-md-6">
                                        {!! Form::label('school_name', 'Nama Sekolah / Institusi', ['class' => 'form-label fw-semibold']) !!}
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-school text-muted"></i></span>
                                            {!! Form::text('school_name', old('school_name'), [
                                                'class' => 'form-control form-control-lg' . ($errors->has('school_name') ? ' is-invalid' : ''),
                                                'placeholder' => 'Contoh: SMA Negeri 1 Jakarta',
                                                'required'
                                            ]) !!}
                                        </div>
                                    </div>

                                    {{-- Nama Penanggung Jawab --}}
                                    <div class="col-md-6">
                                        {!! Form::label('person_name', 'Nama Penanggung Jawab (PIC)', ['class' => 'form-label fw-semibold']) !!}
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user text-muted"></i></span>
                                            {!! Form::text('person_name', old('person_name'), [
                                                'class' => 'form-control form-control-lg' . ($errors->has('person_name') ? ' is-invalid' : ''),
                                                'placeholder' => 'Nama lengkap Anda & gelar',
                                                'required'
                                            ]) !!}
                                        </div>
                                    </div>

                                    {{-- Jabatan --}}
                                    <div class="col-md-6">
                                        {!! Form::label('position', 'Jabatan / Peran', ['class' => 'form-label fw-semibold']) !!}
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-id-badge text-muted"></i></span>
                                            {!! Form::text('position', old('position'), [
                                                'class' => 'form-control form-control-lg' . ($errors->has('position') ? ' is-invalid' : ''),
                                                'placeholder' => 'Contoh: Kepala Sekolah, Waka Kurikulum, IT Admin',
                                                'required'
                                            ]) !!}
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-md-6">
                                        {!! Form::label('email', 'Email Institusi / Pribadi', ['class' => 'form-label fw-semibold']) !!}
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-envelope text-muted"></i></span>
                                            {!! Form::email('email', old('email'), [
                                                'class' => 'form-control form-control-lg' . ($errors->has('email') ? ' is-invalid' : ''),
                                                'placeholder' => 'nama@sekolah.sch.id',
                                                'required'
                                            ]) !!}
                                        </div>
                                    </div>

                                    {{-- Nomor Telepon --}}
                                    <div class="col-md-6">
                                        {!! Form::label('phone', 'Nomor WhatsApp / Seluler', ['class' => 'form-label fw-semibold']) !!}
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-brands fa-whatsapp text-success"></i></span>
                                            {!! Form::tel('phone', old('phone'), [
                                                'class' => 'form-control form-control-lg' . ($errors->has('phone') ? ' is-invalid' : ''),
                                                'placeholder' => '081234567890',
                                                'required'
                                            ]) !!}
                                        </div>
                                    </div>

                                    {{-- Pilihan Paket --}}
                                    <div class="col-md-6">
                                        {!! Form::label('package', 'Pilihan Paket yang Diminati', ['class' => 'form-label fw-semibold']) !!}
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-box-open text-muted"></i></span>
                                            {!! Form::select('package', [
                                                '' => '-- Pilih Paket EduPulse --',
                                                'starter' => 'Paket Starter — Rp 300rb / bln (Hingga 300 Siswa)',
                                                'professional' => 'Paket Profesional — Rp 700rb / bln (Hingga 1.000 Siswa)',
                                                'enterprise' => 'Paket Enterprise — Fitur Lengkap & Kustomisasi Khusus',
                                            ], old('package'), [
                                                'class' => 'form-select form-select-lg' . ($errors->has('package') ? ' is-invalid' : ''),
                                                'required'
                                            ]) !!}
                                        </div>
                                    </div>

                                    {{-- Tanggal & Waktu Demo --}}
                                    <div class="col-md-12">
                                        {!! Form::label('demo_datetime', 'Rencana Tanggal & Waktu Demo yang Diinginkan', ['class' => 'form-label fw-semibold']) !!}
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-calendar-days text-muted"></i></span>
                                            {!! Form::datetimeLocal('demo_datetime', old('demo_datetime'), [
                                                'class' => 'form-control form-control-lg' . ($errors->has('demo_datetime') ? ' is-invalid' : ''),
                                                'required'
                                            ]) !!}
                                        </div>
                                        <small class="text-muted mt-1 d-block"><i class="fa-regular fa-clock me-1"></i> Demo dapat dilakukan daring (Google Meet/Zoom) atau kunjungan langsung.</small>
                                    </div>

                                    {{-- Pesan / Keterangan Tambahan --}}
                                    <div class="col-12">
                                        {!! Form::label('message', 'Kebutuhan Spesifik / Pertanyaan Tambahan', ['class' => 'form-label fw-semibold']) !!}
                                        {!! Form::textarea('message', old('message'), [
                                            'class' => 'form-control',
                                            'rows' => 3,
                                            'placeholder' => 'Ceritakan jumlah siswa, modul prioritas (Akademik, CBT, Keuangan, BOS/RKAS, dsb.), atau tantangan yang dihadapi sekolah...'
                                        ]) !!}
                                    </div>

                                    {{-- Tombol Submit --}}
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary btn-submit-demo w-100 py-3 shadow">
                                            <i class="fa-solid fa-paper-plane me-2"></i> Jadwalkan Demo Sekarang
                                        </button>
                                    </div>

                                    {{-- Keterangan Bawah --}}
                                    <div class="col-12 text-center mt-2">
                                        <p class="text-muted small mb-0">
                                            <i class="fa-solid fa-shield-halved text-success me-1"></i> Data Anda aman dan tidak akan disebarluaskan. Tim spesialis kami akan segera menghubungi Anda.
                                        </p>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: DAFTAR REQUEST DEMO DARI DATABASE --}}
            <div class="tab-pane fade {{ session('success') ? 'show active' : '' }}" id="list-pane" role="tabpanel">
                <div id="daftar-demo" class="row justify-content-center">
                    <div class="col-lg-11 col-md-12">
                        <div class="demo-card p-4 p-md-5">
                            {{-- HEADER DAFTAR --}}
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
                                <div>
                                    <h4 class="fw-bold mb-1">
                                        <i class="fa-solid fa-table-list text-primary me-2"></i> Daftar Permintaan Demo
                                    </h4>
                                    <p class="text-muted small mb-0">
                                        Daftar pengajuan jadwal presentasi yang tercatat dalam sistem secara real-time.
                                    </p>
                                </div>

                                {{-- PENCARIAN LIVE & FILTER --}}
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="input-group search-group" style="min-width: 260px;">
                                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                        <input type="text" id="demoSearchInput" class="form-control" placeholder="Cari nama sekolah / PIC...">
                                    </div>
                                    <select id="statusFilter" class="form-select" style="max-width: 170px;">
                                        <option value="">Semua Status</option>
                                        <option value="pending">Menunggu</option>
                                        <option value="scheduled">Terjadwal</option>
                                        <option value="completed">Selesai</option>
                                    </select>
                                </div>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success d-flex align-items-center mb-4 rounded-3 border-0 shadow-sm" role="alert">
                                    <i class="fa-solid fa-circle-check fs-3 me-3 text-success"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1 fw-bold">Pengajuan Demo Anda Berhasil Disimpan!</h6>
                                        <p class="mb-0 small">{{ session('success') }} Data Anda telah masuk dalam antrean konfirmasi.</p>
                                    </div>
                                </div>
                            @endif

                            {{-- TABEL DAFTAR REQUEST DEMO --}}
                            @if ($demoRequests->isEmpty())
                                <div class="text-center py-5">
                                    <div class="empty-icon mb-3">
                                        <i class="fa-solid fa-calendar-xmark display-4 text-muted opacity-50"></i>
                                    </div>
                                    <h5 class="fw-bold text-muted mb-2">Belum Ada Pengajuan Demo</h5>
                                    <p class="text-muted small mb-3">Jadilah yang pertama mengajukan jadwal demo solusi EduPulse untuk sekolah Anda.</p>
                                    <button class="btn btn-primary rounded-pill px-4" onclick="document.getElementById('form-tab').click();">
                                        <i class="fa-solid fa-calendar-plus me-1"></i> Buat Pengajuan Pertama
                                    </button>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle custom-demo-table" id="demoRequestsTable">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width: 50px;">#</th>
                                                <th scope="col">Sekolah &amp; Penanggung Jawab</th>
                                                <th scope="col">Paket Pilihan</th>
                                                <th scope="col">Jadwal yang Diajukan</th>
                                                <th scope="col">Kontak</th>
                                                <th scope="col" class="text-center">Status</th>
                                                <th scope="col" class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($demoRequests as $index => $item)
                                                @php
                                                    $isNew = session('new_id') == $item->id;
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
                                                        'scheduled' => ['badge bg-info-subtle text-info border border-info-subtle', 'Terjadwal', 'fa-calendar-check'],
                                                        'completed' => ['badge bg-success-subtle text-success border border-success-subtle', 'Selesai', 'fa-circle-check'],
                                                        'contacted' => ['badge bg-primary-subtle text-primary border border-primary-subtle', 'Dihubungi', 'fa-phone-volume'],
                                                        'cancelled' => ['badge bg-secondary-subtle text-secondary border border-secondary-subtle', 'Dibatalkan', 'fa-circle-xmark'],
                                                        default => ['badge bg-warning-subtle text-warning border border-warning-subtle', 'Menunggu', 'fa-hourglass-half'],
                                                    };

                                                    // Format phone for WhatsApp
                                                    $cleanPhone = preg_replace('/[^0-9]/', '', $item->phone);
                                                    if (str_starts_with($cleanPhone, '0')) {
                                                        $waPhone = '62' . substr($cleanPhone, 1);
                                                    } else {
                                                        $waPhone = $cleanPhone;
                                                    }
                                                @endphp
                                                <tr class="demo-row {{ $isNew ? 'row-highlight' : '' }}" data-status="{{ strtolower($item->status) }}">
                                                    <td class="text-muted fw-bold">
                                                        {{ $demoRequests->firstItem() + $index }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="school-avatar me-3">
                                                                {{ $initials }}
                                                            </div>
                                                            <div>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <span class="fw-bold school-name text-truncate" style="max-width: 240px;">{{ $item->school_name }}</span>
                                                                    @if ($isNew)
                                                                        <span class="badge bg-success rounded-pill badge-new">Baru!</span>
                                                                    @endif
                                                                </div>
                                                                <div class="text-muted small">
                                                                    <i class="fa-solid fa-user me-1"></i> {{ $item->person_name }}
                                                                    <span class="badge bg-light text-dark border ms-1">{{ $item->position }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="package-pill {{ $packageClass }}">
                                                            <i class="fa-solid fa-cube me-1"></i> {{ ucfirst($item->package) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="fw-semibold">
                                                            <i class="fa-regular fa-calendar me-1 text-primary"></i>
                                                            {{ \Carbon\Carbon::parse($item->demo_datetime)->translatedFormat('d M Y') }}
                                                        </div>
                                                        <div class="text-muted small">
                                                            <i class="fa-regular fa-clock me-1"></i>
                                                            {{ \Carbon\Carbon::parse($item->demo_datetime)->format('H:i') }} WIB
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="small">
                                                            <a href="mailto:{{ $item->email }}" class="text-decoration-none text-muted d-block text-truncate" style="max-width: 170px;" title="{{ $item->email }}">
                                                                <i class="fa-regular fa-envelope me-1 text-primary"></i> {{ $item->email }}
                                                            </a>
                                                            <a href="https://wa.me/{{ $waPhone }}" target="_blank" class="text-decoration-none text-success fw-semibold small" title="Hubungi via WhatsApp">
                                                                <i class="fa-brands fa-whatsapp me-1"></i> {{ $item->phone }}
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="{{ $statusBadge[0] }} px-2 py-1 rounded-pill">
                                                            <i class="fa-solid {{ $statusBadge[2] }} me-1"></i> {{ $statusBadge[1] }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                                            <i class="fa-solid fa-eye me-1"></i> Detail
                                                        </button>
                                                    </td>
                                                </tr>

                                                {{-- MODAL DETAIL PERMINTAAN DEMO --}}
                                                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content custom-modal-content">
                                                            <div class="modal-header border-bottom">
                                                                <h5 class="modal-title fw-bold" id="detailModalLabel{{ $item->id }}">
                                                                    <i class="fa-solid fa-circle-info text-primary me-2"></i> Detail Permintaan Demo
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-theme="dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="d-flex align-items-center mb-4">
                                                                    <div class="school-avatar avatar-lg me-3">
                                                                        {{ $initials }}
                                                                    </div>
                                                                    <div>
                                                                        <h5 class="fw-bold mb-1">{{ $item->school_name }}</h5>
                                                                        <span class="{{ $statusBadge[0] }} px-2 py-1 rounded-pill small">
                                                                            <i class="fa-solid {{ $statusBadge[2] }} me-1"></i> {{ $statusBadge[1] }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div class="list-group list-group-flush border rounded-3 mb-3">
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <span class="text-muted"><i class="fa-solid fa-user me-2"></i> Penanggung Jawab</span>
                                                                        <span class="fw-semibold">{{ $item->person_name }}</span>
                                                                    </div>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <span class="text-muted"><i class="fa-solid fa-id-badge me-2"></i> Jabatan</span>
                                                                        <span class="fw-semibold">{{ $item->position }}</span>
                                                                    </div>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <span class="text-muted"><i class="fa-solid fa-box-open me-2"></i> Paket Dipilih</span>
                                                                        <span class="package-pill {{ $packageClass }}">{{ ucfirst($item->package) }}</span>
                                                                    </div>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <span class="text-muted"><i class="fa-solid fa-calendar-day me-2"></i> Waktu Demo</span>
                                                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($item->demo_datetime)->translatedFormat('l, d F Y - H:i') }} WIB</span>
                                                                    </div>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <span class="text-muted"><i class="fa-solid fa-envelope me-2"></i> Email</span>
                                                                        <span><a href="mailto:{{ $item->email }}" class="text-decoration-none">{{ $item->email }}</a></span>
                                                                    </div>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <span class="text-muted"><i class="fa-brands fa-whatsapp me-2 text-success"></i> WhatsApp</span>
                                                                        <span><a href="https://wa.me/{{ $waPhone }}" target="_blank" class="text-decoration-none text-success fw-bold">{{ $item->phone }}</a></span>
                                                                    </div>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <span class="text-muted"><i class="fa-regular fa-clock me-2"></i> Waktu Diajukan</span>
                                                                        <span class="text-muted small">{{ $item->created_at?->diffForHumans() ?? '-' }}</span>
                                                                    </div>
                                                                </div>

                                                                @if ($item->message)
                                                                    <div class="p-3 bg-body-tertiary rounded-3 border">
                                                                        <div class="fw-semibold small text-muted mb-1"><i class="fa-solid fa-comment-dots me-1"></i> Pesan &amp; Kebutuhan Khusus:</div>
                                                                        <p class="mb-0 small">{{ $item->message }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer border-top">
                                                                <a href="https://wa.me/{{ $waPhone }}?text=Halo%20{{ urlencode($item->person_name) }}%20dari%20{{ urlencode($item->school_name) }},%20kami%20dari%20EduPulse%20ingin%20mengonfirmasi%20jadwal%20demo%20Anda." target="_blank" class="btn btn-success rounded-pill px-3">
                                                                    <i class="fa-brands fa-whatsapp me-1"></i> Chat WhatsApp
                                                                </a>
                                                                <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
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
                                        Menampilkan {{ $demoRequests->firstItem() ?? 0 }} - {{ $demoRequests->lastItem() ?? 0 }} dari {{ $demoRequests->total() }} pengajuan
                                    </div>
                                    <div>
                                        {{ $demoRequests->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* ============================================
       VARIABEL WARNA & TEMA
    ============================================ */
    :root {
        --card-bg: #ffffff;
        --card-border: #e6e9ee;
        --card-shadow: rgba(20, 30, 60, 0.08);
        --text-primary: #101426;
        --text-muted: #5b6478;
        --input-bg: #ffffff;
        --input-border: #d7dbe3;
        --input-focus-shadow: rgba(47, 92, 224, 0.15);
        --table-hover-bg: #f8fafc;
        --pill-bg: rgba(255, 255, 255, 0.1);
        --avatar-bg-start: #2f5ce0;
        --avatar-bg-end: #7b42f6;
    }

    [data-bs-theme="dark"] {
        --card-bg: #151d2e;
        --card-border: #2a3348;
        --card-shadow: rgba(0, 0, 0, 0.35);
        --text-primary: #e8edf5;
        --text-muted: #98a1b5;
        --input-bg: #0a0e1a;
        --input-border: #2a3348;
        --input-focus-shadow: rgba(47, 92, 224, 0.25);
        --table-hover-bg: #1c263c;
        --pill-bg: rgba(255, 255, 255, 0.06);
        --avatar-bg-start: #3d68f5;
        --avatar-bg-end: #8b5cf6;
    }

    /* ============================================
       HERO & BADGES
    ============================================ */
    .badge-hero {
        background: rgba(47, 92, 224, 0.15);
        color: #8bb2ff;
        border: 1px solid rgba(47, 92, 224, 0.3);
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .stat-pill {
        background: var(--pill-bg);
        border: 1px solid rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border-radius: 14px;
        padding: 0.9rem 1rem;
        text-align: center;
        transition: transform 0.2s ease, background 0.2s ease;
    }
    .stat-pill:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.15);
    }
    .stat-number {
        display: block;
        font-size: 1.55rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .stat-title {
        font-size: 0.78rem;
        color: var(--hero-muted, #c7cede);
        font-weight: 500;
    }

    /* ============================================
       NAV PILLS
    ============================================ */
    .custom-nav-pills {
        background: rgba(13, 18, 38, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
    }
    .custom-nav-pills .nav-link {
        color: #c7cede;
        font-weight: 600;
        font-size: 0.92rem;
        transition: all 0.25s ease;
    }
    .custom-nav-pills .nav-link.active {
        background: var(--brand-blue, #2f5ce0);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(47, 92, 224, 0.4);
    }

    /* ============================================
       DEMO CARD
    ============================================ */
    .demo-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        box-shadow: 0 20px 60px var(--card-shadow);
        transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }

    /* ============================================
       FORM ELEMENTS
    ============================================ */
    .form-label {
        color: var(--text-primary);
        font-size: .92rem;
        margin-bottom: .45rem;
    }

    .form-control,
    .form-select {
        background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-radius: 10px;
        padding: .65rem 1rem;
        font-size: .95rem;
        color: var(--text-primary);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .input-group-text {
        background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-right: none;
        color: var(--text-muted);
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    .input-group > .form-control,
    .input-group > .form-select {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--brand-blue, #2f5ce0);
        box-shadow: 0 0 0 .25rem var(--input-focus-shadow);
        background: var(--input-bg);
        color: var(--text-primary);
    }

    .btn-submit-demo {
        background: linear-gradient(135deg, #2f5ce0, #1d3fa8);
        border: none;
        font-weight: 700;
        font-size: 1.05rem;
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-submit-demo:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(47, 92, 224, 0.4) !important;
        background: linear-gradient(135deg, #3d68f5, #244ac5);
    }

    /* ============================================
       TABEL DAFTAR REQUEST DEMO
    ============================================ */
    .custom-demo-table {
        color: var(--text-primary);
    }
    .custom-demo-table thead th {
        background: transparent;
        color: var(--text-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--card-border);
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .custom-demo-table tbody tr {
        border-bottom: 1px solid var(--card-border);
        transition: background 0.15s ease;
    }
    .custom-demo-table tbody tr:hover {
        background-color: var(--table-hover-bg);
    }

    .school-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--avatar-bg-start), var(--avatar-bg-end));
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.05rem;
        box-shadow: 0 4px 10px rgba(47, 92, 224, 0.25);
        flex-shrink: 0;
    }
    .avatar-lg {
        width: 56px;
        height: 56px;
        font-size: 1.35rem;
        border-radius: 16px;
    }

    /* HIGHLIGHT ROW UNTUK PENGIRIMAN BARU */
    .row-highlight {
        background-color: rgba(47, 92, 224, 0.08) !important;
        border-left: 4px solid #2f5ce0 !important;
        animation: pulseHighlight 2.5s infinite alternate;
    }
    @keyframes pulseHighlight {
        from { background-color: rgba(47, 92, 224, 0.06); }
        to { background-color: rgba(47, 92, 224, 0.14); }
    }
    .badge-new {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        animation: bounce 1.5s infinite;
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }

    /* PILL PAKET */
    .package-pill {
        display: inline-block;
        padding: 0.3rem 0.75rem;
        border-radius: 50rem;
        font-size: 0.8rem;
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
        color: #3b82f6;
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

    /* MODAL */
    .custom-modal-content {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
        color: var(--text-primary);
    }
    .custom-modal-content .list-group-item {
        background: transparent;
        color: var(--text-primary);
        border-color: var(--card-border);
    }

    @media (max-width: 768px) {
        .demo-card {
            padding: 1.5rem !important;
        }
        .stat-number {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. LIVE SEARCH & FILTER PADA TABEL
    const searchInput = document.getElementById('demoSearchInput');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('demoRequestsTable');

    if (table && searchInput) {
        const rows = table.querySelectorAll('tbody tr.demo-row');

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

    // 2. AUTO-SWITCH KE TAB DAFTAR JIKA ADA ANCHOR #daftar-demo ATAU FLASH SUCCESS
    if (window.location.hash === '#daftar-demo' || {{ session('success') ? 'true' : 'false' }}) {
        const listTabBtn = document.getElementById('list-tab');
        if (listTabBtn) {
            const tabInstance = new bootstrap.Tab(listTabBtn);
            tabInstance.show();

            const targetElement = document.getElementById('daftar-demo');
            if (targetElement) {
                setTimeout(() => {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 300);
            }
        }
    }
});
</script>
@endpush