{{-- resources/views/admin/cbt_sessions/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Sesi Ujian')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Detail Sesi Ujian</h4>
            <div>
                <a href="{{ route('admin.cbt-sessions.edit', $session->id) }}" class="btn btn-warning">
                    <i class="fa fa-pen"></i> Edit
                </a>
                <a href="{{ route('admin.cbt-sessions.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header fw-bold">Informasi Peserta</div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="180">Siswa</th>
                                <td>: {{ $session->student->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Paket Ujian</th>
                                <td>: {{ $session->examPackage->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>: {{ $session->classroom->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jadwal</th>
                                <td>: {{ $session->schedule->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header fw-bold">Waktu Pengerjaan</div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="180">Waktu Mulai</th>
                                <td>: {{ $session->start_time?->format('d-m-Y H:i:s') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Waktu Selesai</th>
                                <td>: {{ $session->end_time?->format('d-m-Y H:i:s') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Durasi</th>
                                <td>: {{ $session->duration_seconds ? gmdate('H:i:s', $session->duration_seconds) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Waktu Submit</th>
                                <td>: {{ $session->submitted_at?->format('d-m-Y H:i:s') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Soal Terakhir</th>
                                <td>: Nomor {{ $session->current_question_index + 1 }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header fw-bold">Status &amp; Hasil</div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="180">Status</th>
                                <td>: {{ ucfirst($session->status ?? '-') }}</td>
                            </tr>
                            <tr>
                                <th>Sudah Disubmit</th>
                                <td>: {{ $session->is_submitted ? 'Ya' : 'Belum' }}</td>
                            </tr>
                            <tr>
                                <th>Nilai Akhir</th>
                                <td>: <strong>{{ $session->final_score ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Sesi Aktif</th>
                                <td>: {{ $session->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header fw-bold">Keamanan / Proctoring</div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="180">IP Address</th>
                                <td>: {{ $session->ip_address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>User Agent</th>
                                <td>: <span class="small text-muted">{{ $session->user_agent ?? '-' }}</span></td>
                            </tr>
                        </table>
                        <hr>
                        <label class="fw-bold">Catatan Pengawas:</label>
                        <p class="mb-0">{{ $session->proctor_notes ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header fw-bold">Data Jawaban (JSON, Read-only)</div>
            <div class="card-body">
                <pre class="bg-light p-3 rounded" style="max-height:400px; overflow:auto;">{{ json_encode($session->answers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Metadata</div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <th width="180">Dibuat</th>
                        <td>: {{ $session->created_at?->format('d-m-Y H:i') }} oleh {{ $session->createdBy->name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Diupdate</th>
                        <td>: {{ $session->updated_at?->format('d-m-Y H:i') }} oleh {{ $session->updatedBy->name ?? '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
@endsection
