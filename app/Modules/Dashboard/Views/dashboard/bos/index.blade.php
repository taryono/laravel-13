@extends('layouts.app') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@section('title', 'Modul Dana BOS - EduPortal')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bos.css') }}">
@endpush
@section('content')
    <div class="bos-page-wrapper py-4 px-3 px-lg-4">
        @include('Dashboard::partials.ep_subnav', [
            'title' => 'Modul Dana BOS (Bantuan Operasional Sekolah)',
            'badge' => '1.180 Siswa Aktif',
            'subtitle' => 'Pengelolaan RPenyaluran & Pelaporan Pertanggungjawaban SPJKAS',
            'metaIcon' => 'school',
            'metaLabel' => 'Periode',
            'metaValue' => 'T.A. 2024/2025 Genap',
        ])

        @if (!$dashboard)
            <div class="bos-card p-5 text-center">
                <div class="mb-3 text-primary fs-1"><i class="bi bi-folder2-open"></i></div>
                <h5 class="fw-bold text-dark">Belum Ada Periode Dana BOS Aktif</h5>
                <p class="text-muted small mb-3">Silakan buat periode penyaluran Dana BOS atau jalankan seeder data untuk
                    mulai mengelola RKAS &amp; SPJ.</p>
            </div>
        @else
            @php
                $header = $dashboard['header'];
                $kpi = $dashboard['kpi_cards'];
                $siplah = $dashboard['siplah_banner'];
                $tabs = $dashboard['transaction_tabs'];
                $txPaginated = $dashboard['transactions'];
                $snp = $dashboard['snp_progress'];
                $compliance = $dashboard['compliance_box'];
                $deadline = $dashboard['deadline_notice'];
                $activeCat = strtoupper(request('category', 'ALL'));
            @endphp

            {{-- =================================================================
             1. HEADER HALAMAN & TOMBOL AKSI UTAMA
        ================================================================== --}}
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-start gap-3 mb-4">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span
                            class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2 py-1">
                            ● {{ $header['period_name'] }} ({{ $header['status'] }})
                        </span>
                        <span class="badge rounded-pill bg-light text-secondary border px-2 py-1">
                            BOS Reguler {{ $header['authority'] }}
                        </span>
                        <span class="badge rounded-pill bg-light text-secondary border px-2 py-1">
                            NPSN: {{ $header['school_npsn'] ?? '-' }}
                        </span>

                        {{-- Dropdown Ganti Periode Jika Lebih dari 1 --}}
                        @if (isset($periods) && $periods->count() > 1)
                            <form method="GET" action="{{ url()->current() }}" class="d-inline-block ms-1">
                                <select name="period_id" onchange="this.form.submit()"
                                    class="form-select form-select-sm py-0 px-2" style="font-size: 0.78rem;">
                                    @foreach ($periods as $p)
                                        <option value="{{ $p->id }}" @selected($p->id == $header['period_id'])>
                                            {{ $p->name }} ({{ $p->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @endif
                    </div>

                    <h3 class="fw-bold text-dark mb-1">Modul Dana BOS (Bantuan Operasional Sekolah)</h3>
                    <p class="text-muted small mb-0">
                        Pengelolaan RKAS, Penyaluran {{ $header['authority'] }} &amp; Pelaporan Pertanggungjawaban SPJ
                        {{ $header['period_name'] }}
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('bos.reports.export-word', ['bosReport' => $header['period_id']]) }}"
                        class="btn btn-outline-secondary btn-sm bg-white fw-semibold px-3 py-2">
                        <i class="bi bi-printer me-1"></i> Cetak BKU K7a
                    </a>
                    <a href="{{ route('bos.reports.export-word', ['bosReport' => $header['period_id']]) }}"
                        class="btn btn-outline-success btn-sm bg-white fw-semibold px-3 py-2">
                        <i class="bi bi-download me-1"></i> Unduh SPJ / Format K7
                    </a>
                    <button type="button" id="btnSyncArkas" data-period-id="{{ $header['period_id'] }}"
                        class="btn btn-primary btn-sm fw-semibold px-3 py-2">
                        <i class="bi bi-arrow-repeat me-1"></i> Sinkronisasi ARKAS
                    </button>
                </div>
            </div>

            {{-- =================================================================
             2. EMPAT KARTU KPI UTAMA
        ================================================================== --}}
            <div class="row g-3 mb-4">
                {{-- Kartu 1: Realisasi Penyerapan --}}
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="bos-card kpi-card kpi-card-teal p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Realisasi
                                Penyerapan</span>
                            <span class="badge bg-teal-subtle text-success rounded-circle p-1"><i
                                    class="bi bi-graph-up-arrow"></i></span>
                        </div>
                        <div class="fs-5 fw-bold text-dark mb-2">
                            Rp {{ number_format($kpi['realization']['amount'], 0, ',', '.') }}
                        </div>
                        <div class="d-flex justify-content-between small mb-1" style="font-size: 0.75rem;">
                            <span class="text-success fw-semibold">Realisasi
                                {{ $kpi['realization']['absorption_percent'] }}%</span>
                            <span class="text-muted">Target {{ $kpi['realization']['target_percent'] }}%</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ min(100, $kpi['realization']['absorption_percent']) }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted pt-1 border-top" style="font-size: 0.74rem;">
                            <span>Sisa Alokasi</span>
                            <span class="fw-bold text-dark">Rp
                                {{ number_format($kpi['realization']['remaining_allocation'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Kartu 2: Total Pagu BOS --}}
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="bos-card kpi-card kpi-card-blue p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Total Pagu BOS</span>
                            <span class="badge bg-primary-subtle text-primary rounded-circle p-1"><i
                                    class="bi bi-wallet2"></i></span>
                        </div>
                        <div class="fs-5 fw-bold text-dark mb-2">
                            Rp {{ number_format($kpi['allocation']['total_amount'], 0, ',', '.') }}
                        </div>
                        <div class="text-muted mb-2" style="font-size: 0.75rem;">
                            <i class="bi bi-people me-1"></i>
                            <strong>{{ number_format($kpi['allocation']['student_count'], 0, ',', '.') }} Siswa</strong>
                            Terdaftar (Cut-Off Dapodik)
                        </div>
                        <div class="d-flex justify-content-between text-muted pt-1 border-top" style="font-size: 0.74rem;">
                            <span>Satuan Biaya Standar</span>
                            <span class="fw-bold text-dark">Rp
                                {{ number_format($kpi['allocation']['unit_cost_per_student'], 0, ',', '.') }} /
                                siswa</span>
                        </div>
                    </div>
                </div>

                {{-- Kartu 3: Kas Giro BOS di Bank --}}
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="bos-card kpi-card kpi-card-purple p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Kas Giro BOS di
                                Bank</span>
                            <span class="badge bg-secondary-subtle text-dark rounded-circle p-1"><i
                                    class="bi bi-bank"></i></span>
                        </div>
                        <div class="fs-5 fw-bold text-dark mb-2">
                            Rp {{ number_format($kpi['bank_cash']['balance'], 0, ',', '.') }}
                        </div>
                        <div class="text-muted mb-2" style="font-size: 0.75rem;">
                            <i class="bi bi-building me-1"></i> {{ $kpi['bank_cash']['bank_name'] }}
                            ({{ $kpi['bank_cash']['branch_name'] }})
                        </div>
                        <div class="d-flex justify-content-between text-muted pt-1 border-top" style="font-size: 0.74rem;">
                            <span>No. Rekening Satdik</span>
                            <span class="fw-bold text-dark">{{ $kpi['bank_cash']['account_number'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Kartu 4: SiLPA TA Sebelumnya --}}
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="bos-card kpi-card kpi-card-green p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">SiLPA TA
                                Sebelumnya</span>
                            <span class="badge bg-success-subtle text-success rounded-circle p-1"><i
                                    class="bi bi-check-circle"></i></span>
                        </div>
                        <div class="fs-5 fw-bold text-success mb-2">
                            Rp {{ number_format($kpi['silpa']['amount'], 0, ',', '.') }}
                        </div>
                        <div class="text-muted mb-2" style="font-size: 0.75rem;">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            {{ $kpi['silpa']['amount'] == 0 ? '100% Terserap Tanpa Sisa Anggaran' : 'Tercatat sebagai Saldo Awal' }}
                        </div>
                        <div class="d-flex justify-content-between text-muted pt-1 border-top" style="font-size: 0.74rem;">
                            <span>Status Audit BPKP</span>
                            <span class="fw-bold text-success">{{ $kpi['silpa']['audit_status'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =================================================================
             3. BANNER KEPATUHAN SIPLAH
        ================================================================== --}}
            <div
                class="siplah-banner p-3 px-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-10 rounded-3 p-2 fs-4">
                        <i class="bi bi-patch-check-fill text-info"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <span class="fw-bold">{{ $siplah['compliance_percent'] }}% Belanja Terakreditasi SIPLah
                                {{ $header['authority'] }}</span>
                            <span class="badge bg-info text-dark fw-bold">{{ $siplah['rating_grade'] }}</span>
                        </div>
                        <div class="small text-white-50">
                            {{ $siplah['regulation_note'] }} Transaksi terhubung faktur pajak elektronik otomatis.
                        </div>
                    </div>
                </div>
                <a href="https://siplah.kemdikbud.go.id" target="_blank" rel="noopener"
                    class="btn btn-light btn-sm fw-bold px-3 py-2 text-primary flex-shrink-0">
                    Buka Katalog SIPLah <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>
            </div>

            {{-- =================================================================
             4. EMPAT TOMBOL AKSI CEPAT (QUICK ACTIONS)
        ================================================================== --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <button type="button" class="quick-action-btn w-100" data-bs-toggle="modal"
                        data-bs-target="#modalInputRealization">
                        <span class="quick-icon-box bg-primary-subtle text-primary"><i class="bi bi-plus-lg"></i></span>
                        <div>
                            <div class="fw-bold small">Input Belanja &amp; Realisasi</div>
                            <div class="text-muted" style="font-size: 0.73rem;">Nota, faktur pajak &amp; kuitansi</div>
                        </div>
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <a href="#" class="quick-action-btn">
                        <span class="quick-icon-box bg-success-subtle text-success"><i
                                class="bi bi-journal-text"></i></span>
                        <div>
                            <div class="fw-bold small">RKAS BOS {{ $header['academic_year'] ?? date('Y') }}</div>
                            <div class="text-muted" style="font-size: 0.73rem;">Rencana anggaran &amp; revisi</div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <a href="#" class="quick-action-btn">
                        <span class="quick-icon-box bg-info-subtle text-info"><i class="bi bi-cart-check"></i></span>
                        <div>
                            <div class="fw-bold small">Sinkronisasi SIPLah</div>
                            <div class="text-muted" style="font-size: 0.73rem;">Marketplace resmi Kemendikbud</div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <a href="{{ route('bos.reports.export-word', ['bosReport' => $header['period_id']]) }}"
                        class="quick-action-btn">
                        <span class="quick-icon-box bg-warning-subtle text-warning"><i
                                class="bi bi-file-earmark-check"></i></span>
                        <div>
                            <div class="fw-bold small">SPJ Otomatis K7</div>
                            <div class="text-muted" style="font-size: 0.73rem;">Format BKU &amp; Pembantu Pajak</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- =================================================================
             5. KONTEN UTAMA 2 KOLOM (KIRI: TRANSAKSI | KANAN: SNP & KEPATUHAN)
        ================================================================== --}}
            <div class="row g-4">
                {{-- KOLOM KIRI (7/12): DAFTAR TRANSAKSI BELANJA TERAKHIR --}}
                <div class="col-12 col-xl-7">
                    <div class="bos-card p-3 p-md-4 h-100 d-flex flex-column">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Daftar Transaksi Belanja Terakhir</h6>
                                <small class="text-muted">Buku Kas Umum (BKU), Integrasi SIPLah &amp; Pengeluaran Kas
                                    Riil</small>
                            </div>

                            {{-- Form Pencarian Transaksi --}}
                            <form method="GET" action="{{ url()->current() }}" class="d-flex gap-1">
                                <input type="hidden" name="period_id" value="{{ $header['period_id'] }}">
                                <input type="hidden" name="category" value="{{ $activeCat }}">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control form-control-sm" placeholder="Cari SPK / Kuitansi / Vendor...">
                                <button type="submit" class="btn btn-sm btn-outline-secondary"><i
                                        class="bi bi-search"></i></button>
                            </form>
                        </div>

                        {{-- Filter Pills --}}
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <a href="{{ request()->fullUrlWithQuery(['category' => 'ALL', 'page' => 1]) }}"
                                class="bos-filter-pill {{ $activeCat === 'ALL' ? 'active' : '' }}">
                                Semua Transaksi ({{ $tabs['all'] }})
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['category' => 'SIPLAH', 'page' => 1]) }}"
                                class="bos-filter-pill {{ $activeCat === 'SIPLAH' ? 'active' : '' }}">
                                SIPLah ({{ $tabs['siplah'] }})
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['category' => 'HONOR', 'page' => 1]) }}"
                                class="bos-filter-pill {{ $activeCat === 'HONOR' ? 'active' : '' }}">
                                Honor Non-ASN ({{ $tabs['honor'] }})
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['category' => 'SARPRAS', 'page' => 1]) }}"
                                class="bos-filter-pill {{ $activeCat === 'SARPRAS' ? 'active' : '' }}">
                                Sarpras &amp; ATK ({{ $tabs['sarpras'] }})
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['category' => 'UTILITIES', 'page' => 1]) }}"
                                class="bos-filter-pill {{ $activeCat === 'UTILITIES' ? 'active' : '' }}">
                                Utilitas &amp; Jasa ({{ $tabs['utilities'] }})
                            </a>
                        </div>

                        {{-- Tabel Transaksi --}}
                        <div class="table-responsive flex-grow-1">
                            <table class="table bos-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal / No. Bukti</th>
                                        <th>Uraian &amp; Penyedia</th>
                                        <th>Komponen SNP</th>
                                        <th class="text-end">Nominal &amp; Pajak</th>
                                        <th class="text-center">Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($txPaginated['data'] ?? [] as $tx)
                                        <tr>
                                            <td class="text-nowrap">
                                                <div class="fw-bold text-dark">{{ $tx['date_formatted'] }}</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">
                                                    {{ $tx['receipt_number'] ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $tx['description'] }}</div>
                                                <div class="text-muted" style="font-size: 0.74rem;">
                                                    @if ($tx['is_siplah'])
                                                        <span class="text-primary fw-semibold">SIPLah</span> -
                                                    @endif
                                                    {{ $tx['vendor_name'] }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge-snp">{{ $tx['component_badge'] }}</span>
                                            </td>
                                            <td class="text-end text-nowrap">
                                                <div class="fw-bold text-dark">Rp
                                                    {{ number_format($tx['total_amount'], 0, ',', '.') }}</div>
                                                @if ($tx['tax_amount'] > 0 || $tx['tax_type'])
                                                    <div class="text-success" style="font-size: 0.71rem;">
                                                        {{ $tx['tax_type'] ?? 'Pajak' }}
                                                        {{ $tx['tax_status'] ? "• {$tx['tax_status']}" : '' }}
                                                    </div>
                                                @else
                                                    <div class="text-muted" style="font-size: 0.71rem;">Bebas Pajak</div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($tx['has_attachment'])
                                                    <a href="{{ $tx['attachment_url'] }}" target="_blank"
                                                        class="btn btn-sm btn-light border text-success"
                                                        title="Lihat Lampiran">
                                                        <i class="bi bi-file-earmark-check-fill"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                Tidak ditemukan transaksi belanja untuk filter yang dipilih.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Footer Pagination --}}
                        @if (isset($txPaginated['meta']))
                            <div
                                class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 pt-3 mt-3 border-top small text-muted">
                                <div>
                                    Menampilkan
                                    {{ $txPaginated['meta']['from'] ?? 0 }}–{{ $txPaginated['meta']['to'] ?? 0 }}
                                    dari {{ $txPaginated['meta']['total'] ?? 0 }} transaksi
                                </div>
                                <div class="btn-group btn-group-sm">
                                    @if (!empty($txPaginated['links']['prev']))
                                        <a href="{{ $txPaginated['links']['prev'] }}"
                                            class="btn btn-outline-secondary">Sebelumnya</a>
                                    @endif
                                    @if (!empty($txPaginated['links']['next']))
                                        <a href="{{ $txPaginated['links']['next'] }}"
                                            class="btn btn-outline-secondary">Selanjutnya</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KOLOM KANAN (5/12): ALOKASI SNP + KEPATUHAN INSPEKTORAT --}}
                <div class="col-12 col-xl-5 d-flex flex-column gap-4">
                    {{-- Card 1: Alokasi Standar Pendidikan (SNP) --}}
                    <div class="bos-card p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Alokasi Standar Pendidikan (SNP)</h6>
                                <small class="text-muted">Realisasi per Pos Belanja RKAS
                                    {{ $header['academic_year'] ?? '' }}</small>
                            </div>
                            <span class="badge bg-primary-subtle text-primary">RKAS v2.4</span>
                        </div>

                        <div class="bg-light rounded-3 p-3 mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-uppercase text-muted fw-bold" style="font-size: 0.68rem;">Total Terencana
                                </div>
                                <div class="fs-5 fw-bold text-dark">Rp
                                    {{ number_format($snp['total_planned'], 0, ',', '.') }}</div>
                            </div>
                            <span class="badge bg-white text-dark border px-2 py-1">{{ count($snp['items']) }} Pos Belanja
                                Utama</span>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            @foreach ($snp['items'] as $item)
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1"
                                        style="font-size: 0.81rem;">
                                        <span class="fw-semibold text-dark">
                                            {{ $loop->iteration }}. {{ $item['name'] }}
                                            @if ($item['max_percentage'])
                                                <span
                                                    class="badge {{ $item['is_over_limit'] ? 'bg-danger' : 'bg-warning text-dark' }} ms-1"
                                                    style="font-size: 0.68rem;">
                                                    Max {{ $item['max_percentage'] }}%
                                                </span>
                                            @endif
                                        </span>
                                        <span class="text-muted text-nowrap ms-2">
                                            <strong class="text-dark">Rp
                                                {{ number_format($item['used_amount'] / 1000000, 1, ',', '.') }}
                                                Jt</strong>
                                            / {{ number_format($item['planned_amount'] / 1000000, 1, ',', '.') }} Jt
                                        </span>
                                    </div>
                                    <div class="progress mb-1" style="height: 6px;">
                                        <div class="progress-bar {{ $item['is_over_limit'] ? 'bg-danger' : 'bg-primary' }}"
                                            style="width: {{ min(100, $item['realization_percent']) }}%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.72rem;">
                                        <span>Realisasi {{ $item['realization_percent'] }}%</span>
                                        <span>Sisa: Rp {{ number_format($item['remaining_amount'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Card 2: Kepatuhan & Audit Inspektorat + Penanggung Jawab --}}
                    <div class="bos-card p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Kepatuhan &amp; Audit Inspektorat</h6>
                                <small class="text-muted">Verifikasi {{ $header['authority'] }} &amp; Pemda</small>
                            </div>
                            <i class="bi bi-shield-fill-check text-success fs-4"></i>
                        </div>

                        <div class="d-flex flex-column gap-2 mb-3">
                            <div
                                class="d-flex justify-content-between align-items-center p-2 px-3 rounded-3 bg-success-subtle border border-success-subtle">
                                <div>
                                    <div class="fw-bold text-dark small">BOS Salur Kemendikbud</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">Laporan SPJ Terintegrasi 100%
                                    </div>
                                </div>
                                <span class="badge bg-success">{{ $compliance['arkas_sync_status'] }}</span>
                            </div>

                            <div
                                class="d-flex justify-content-between align-items-center p-2 px-3 rounded-3 bg-primary-subtle border border-primary-subtle">
                                <div>
                                    <div class="fw-bold text-dark small">Validasi Dinas Pendidikan</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">Pemeriksaan SPJ Triwulan</div>
                                </div>
                                <span class="badge bg-primary">{{ $compliance['dinas_verification_status'] }}</span>
                            </div>
                        </div>

                        <div class="text-uppercase fw-bold text-muted mb-2" style="font-size: 0.7rem;">Penanggung Jawab
                            Keuangan Satdik</div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="signature-box h-100">
                                    <div class="text-muted" style="font-size: 0.7rem;">Kepala Sekolah</div>
                                    <div class="fw-bold text-dark small text-truncate">
                                        {{ $compliance['signatories']['principal']['name'] }}</div>
                                    <div class="text-muted" style="font-size: 0.68rem;">NIP.
                                        {{ $compliance['signatories']['principal']['nip'] }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="signature-box h-100">
                                    <div class="text-muted" style="font-size: 0.7rem;">Bendahara BOS</div>
                                    <div class="fw-bold text-dark small text-truncate">
                                        {{ $compliance['signatories']['treasurer']['name'] }}</div>
                                    <div class="text-muted" style="font-size: 0.68rem;">NIP.
                                        {{ $compliance['signatories']['treasurer']['nip'] }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-2 px-3 rounded-3 bg-light border d-flex align-items-center gap-2"
                            style="font-size: 0.74rem;">
                            <i class="bi bi-qr-code-scan text-primary fs-5"></i>
                            <span class="text-muted">
                                Dokumen ditandatangani digital <strong>BSrE/BSSN</strong>
                                @if ($compliance['tte_document_number'])
                                    ({{ $compliance['tte_document_number'] }})
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =================================================================
             6. BANNER PERINGATAN BATAS PENYERAPAN TAHAP
        ================================================================== --}}
            <div class="bos-card p-3 px-4 mt-4 d-flex align-items-center gap-3 border-start border-4 border-primary">
                <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                <div class="small text-muted">
                    <strong class="text-dark">Batas Penyerapan Tahap (Syarat Salur Tahap Berikutnya):</strong>
                    Sesuai regulasi BOS Salur, sekolah wajib merealisasikan minimal
                    <strong>{{ $deadline['minimum_absorption_percent'] }}%</strong>
                    dari dana tahap berjalan sebelum batas akhir laporan
                    <strong>{{ \Carbon\Carbon::parse($deadline['report_deadline_date'])->translatedFormat('d F Y') }}</strong>
                    agar penyaluran tahap berikutnya tidak terkena penalti pemotongan.
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnSync = document.getElementById('btnSyncArkas');
            if (btnSync) {
                btnSync.addEventListener('click', async function() {
                    const periodId = this.dataset.periodId;
                    const originalHtml = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span> Menyinkronkan...';

                    try {
                        const res = await fetch(`/api/bos/periods/${periodId}/sync-arkas`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]')?.content || ''
                            }
                        });
                        const json = await res.json();
                        alert(json.message || 'Sinkronisasi ARKAS dijadwalkan.');
                    } catch (err) {
                        alert('Gagal memicu sinkronisasi ARKAS.');
                    } finally {
                        this.disabled = false;
                        this.innerHTML = originalHtml;
                    }
                });
            }
        });
    </script>
@endpush
