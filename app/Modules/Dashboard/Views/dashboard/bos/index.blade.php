@extends('layouts.app')
@section('title', 'Manajemen Siswa')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ep-subnav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/student.css') }}">
@endpush
@section('content')
    <div class="students">
        @include('Dashboard::partials.ep_subnav', [
            'title' => 'Modul Manajemen Siswa',
            'badge' => '1.180 Siswa Aktif',
            'subtitle' => 'Buku Induk, Konseling BK & Poin Kedisiplinan Siswa',
            'metaIcon' => 'school',
            'metaLabel' => 'Periode',
            'metaValue' => 'T.A. 2024/2025 Genap',
        ])
        @if ($dashboard)
            {{-- Header Status --}}
            <span class="badge">{{ $dashboard['header']['period_name'] }} ({{ $dashboard['header']['status'] }})</span>
            <h1>Modul Dana BOS (Bantuan Operasional Sekolah)</h1>

            {{-- 4 Kartu KPI Utama --}}
            <div class="row">
                <div class="col-md-3">
                    <small>REALISASI PENYERAPAN</small>
                    <h3>Rp {{ number_format($dashboard['kpi_cards']['realization']['amount'], 0, ',', '.') }}</h3>
                    <span>Realisasi {{ $dashboard['kpi_cards']['realization']['absorption_percent'] }}%</span>
                </div>

                <div class="col-md-3">
                    <small>TOTAL PAGU BOS</small>
                    <h3>Rp {{ number_format($dashboard['kpi_cards']['allocation']['total_amount'], 0, ',', '.') }}</h3>
                    <span>{{ number_format($dashboard['kpi_cards']['allocation']['student_count']) }} Siswa Terdaftar</span>
                </div>

                <div class="col-md-3">
                    <small>KAS GIRO BOS DI BANK</small>
                    <h3>Rp {{ number_format($dashboard['kpi_cards']['bank_cash']['balance'], 0, ',', '.') }}</h3>
                    <span>{{ $dashboard['kpi_cards']['bank_cash']['bank_name'] }}
                        ({{ $dashboard['kpi_cards']['bank_cash']['account_number'] }})</span>
                </div>

                <div class="col-md-3">
                    <small>SiLPA TA LALU</small>
                    <h3>Rp {{ number_format($dashboard['kpi_cards']['silpa']['amount'], 0, ',', '.') }}</h3>
                    <span>Status Audit: {{ $dashboard['kpi_cards']['silpa']['audit_status'] }}</span>
                </div>
            </div>

            {{-- Tab Filter & Tabel Transaksi (Hasil BosTransactionResource) --}}
            <div class="mb-3">
                <button>Semua Transaksi ({{ $dashboard['transaction_tabs']['all'] }})</button>
                <button>SIPLah ({{ $dashboard['transaction_tabs']['siplah'] }})</button>
                <button>Honor Non-ASN ({{ $dashboard['transaction_tabs']['honor'] }})</button>
                <button>Sarpras & ATK ({{ $dashboard['transaction_tabs']['sarpras'] }})</button>
            </div>

            <table class="table">
                <tbody>
                    @forelse($dashboard['transactions']['data'] as $tx)
                        <tr>
                            <td>
                                <strong>{{ $tx['date_formatted'] }}</strong><br>
                                <small class="text-muted">{{ $tx['receipt_number'] }}</small>
                            </td>
                            <td>
                                <strong>{{ $tx['description'] }}</strong><br>
                                <small>{{ $tx['vendor_name'] }}</small>
                            </td>
                            <td><span class="badge">{{ $tx['component_badge'] }}</span></td>
                            <td class="text-end">
                                <strong>Rp {{ number_format($tx['total_amount'], 0, ',', '.') }}</strong><br>
                                @if ($tx['tax_amount'] > 0)
                                    <small class="text-muted">{{ $tx['tax_type'] }} • {{ $tx['tax_status'] }}</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada transaksi realisasi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Widget Progres Alokasi SNP / Komponen --}}
            @foreach ($dashboard['snp_progress']['items'] as $snp)
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>
                            {{ $loop->iteration }}. {{ $snp['name'] }}
                            @if ($snp['max_percentage'])
                                <span class="badge bg-warning">Max {{ $snp['max_percentage'] }}%</span>
                            @endif
                        </span>
                        <span>Rp {{ number_format($snp['used_amount'], 0, ',', '.') }} / Rp
                            {{ number_format($snp['planned_amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ min(100, $snp['realization_percent']) }}%"></div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                Belum ada periode Dana BOS yang aktif untuk sekolah ini.
            </div>
        @endif
    @endsection

    @section('bottom_navigation')
        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="#" class="bottom-nav-item">
                <span class="material-symbols-outlined" style="font-size:24px;">dashboard</span>
                <span class="text-label-sm mt-1">Beranda</span>
            </a>
            <a href="#" class="bottom-nav-item">
                <span class="material-symbols-outlined" style="font-size:24px;">school</span>
                <span class="text-label-sm mt-1">Akademik</span>
            </a>
            <a href="#" class="bottom-nav-item active">
                <span class="material-symbols-outlined icon-fill" style="font-size:24px;">account_balance_wallet</span>
                <span class="text-label-sm fw-bold mt-1">Keuangan</span>
            </a>
            <a href="#" class="bottom-nav-item">
                <span class="material-symbols-outlined" style="font-size:24px;">chat</span>
                <span class="text-label-sm mt-1">Pesan</span>
            </a>
            <a href="#" class="bottom-nav-item">
                <span class="material-symbols-outlined" style="font-size:24px;">person</span>
                <span class="text-label-sm mt-1">Profil</span>
            </a>
        </nav>
    </div>
@endsection
