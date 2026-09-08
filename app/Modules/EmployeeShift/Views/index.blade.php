@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Employee Shift</h5>
                <button type="button" 
                   class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                   data-toggle="ajax-modal" data-size="lg" data-method="GET"
                   data-title="Tambah Data Shit Employee Baru" 
                   data-url="{{ route('employee_shift.create') }}"
                   data-submit='<button class="btn btn-primary" type="submit" form="formShitEmployee">Simpan</button>'
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
    document.addEventListener('DOMContentLoaded', function() {
        const rotasiYes = document.getElementById('is_rotational_yes');
        const rotasiNo = document.getElementById('is_rotational_no');
        const rotasiFields = document.getElementById('rotasi_fields');
        const groupSelect = document.getElementById('rotation_group_select');
        const groupText = document.getElementById('rotation_group_text');

        // Tampilkan/sembunyikan field rotasi
        function toggleRotasi() {
            rotasiFields.style.display = rotasiYes.checked ? 'block' : 'none';
        }

        // Jika PILIH dari daftar → salin ke input teks
        groupSelect.addEventListener('change', function() {
            if (this.value) {
                groupText.value = this.value;
                groupSelect.value = ''; // kosongkan agar bisa ketik baru
            }
        });

        // Jalankan saat halaman dibuka
        toggleRotasi();
        rotasiYes.addEventListener('change', toggleRotasi);
        rotasiNo.addEventListener('change', toggleRotasi);
    });
    </script>
@endpush