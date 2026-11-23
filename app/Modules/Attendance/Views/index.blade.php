@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Kehadiran</h5>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="ajax-modal" data-size="lg" data-method="GET"
                    data-title="Tambah Data Kehadiran Baru" data-url="{{ route('attendance.create') }}"
                    data-submit='<button class="btn btn-primary" type="submit" form="formAttendance">Simpan</button>'
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
    <script>
        function calculateOvertime() {
            const checkIn = document.getElementById('actual_check_in').value;
            const checkOut = document.getElementById('actual_check_out').value;

            if (checkIn && checkOut) {
                const [inHour, inMin] = checkIn.split(':').map(Number);
                const [outHour, outMin] = checkOut.split(':').map(Number);

                let totalMinutes = (outHour * 60 + outMin) _(inHour * 60 + inMin);

                // Jika pulang lebih kecil dari masuk (melewati tengah malam)
                if (totalMinutes < 0) {
                    totalMinutes += 24 * 60;
                }

                // Jika total jam kerja lebih dari 8 jam, hitung lembur (1.5x)
                const normalWorkMinutes = 8 * 60;
                let overtimeMinutes = 0;

                if (totalMinutes > normalWorkMinutes) {
                    overtimeMinutes = totalMinutes_normalWorkMinutes;
                    // Lembur dihitung per 30 menit
                    const overtimeHours = Math.round(overtimeMinutes / 30) * 0.5;
                    document.getElementById('overtime_hours').value = overtimeHours.toFixed(1);
                } else {
                    document.getElementById('overtime_hours').value = '0.0';
                }
            } else {
                alert('Silahkan isi Jam Masuk dan Jam Pulang terlebih dahulu!');
            }
        }
    </script>
@endpush
