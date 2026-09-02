@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- 📋 DAFTAR USULAN LEMBUR OTOMATIS --}}
        @if ($proposals->isNotEmpty())
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🕒 Usulan Lembur Otomatis (Dari Kehadiran)</h5>
                    <small>Ada {{ $proposals->count() }} usulan menunggu verifikasi</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Jam Lembur</th>
                                    <th>Periode</th>
                                    <th width="180">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proposals as $ad)
                                    <tr>
                                        <td><strong>{{ $ad->employee->full_name }}</strong></td>
                                        <td>{{ $ad->attendance_date->format('d/m/Y') }}</td>
                                        <td>{{ $ad->actual_check_in?->format('H:i') ?? '-' }}</td>
                                        <td>{{ $ad->actual_check_out?->format('H:i') ?? '-' }}</td>
                                        <td class="text-success"><strong>{{ $ad->overtime_hours }} jam</strong></td>
                                        <td>{{ $ad->payrollPeriod->code ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('overtime.storeFromProposal') }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <input type="hidden" name="attendance_daily_id"
                                                    value="{{ $ad->id }}">
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Sahkan {{ $ad->overtime_hours }} jam lembur ini?')">
                                                    ✅ Sahkan & Simpan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info mb-4">
                ✅ Tidak ada usulan lembur baru. Semua sudah disahkan.
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Overtime</h5>

                <button type="button" class="btn btn-primary btn-sm" data-toggle="ajax-modal" data-size="lg"
                    data-method="GET" data-title="Tambah Data Overtime Baru" data-url="{{ route('overtime.create') }}"
                    data-submit='<button class="btn btn-primary" type="submit" form="formOvertime">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                    <i class="bi bi-plus-circle"></i> Tambah Baru
                </button>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped w-100']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
