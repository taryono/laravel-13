@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar AcademicYear</h5>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="ajax-modal" data-size="lg" data-method="GET"
                    data-title="Tambah Data AcademicYear Baru" data-url="{{ route('academic_year.create') }}"
                    data-submit='<button class="btn btn-primary" type="submit" form="formAcademicYear">Simpan</button>'
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
            // 1. Template baris periode baru
            const periodRowTemplate = `
        <div class="period-row border p-3 mb-2 bg-light rounded">
            <div class="row">
                <div class="col-md-2 mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" name="periods[name][]" class="form-control" placeholder="Semester 1">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Kode</label>
                    <input type="text" name="periods[code][]" class="form-control" placeholder="SEM1">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="periods[start_date][]" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="periods[end_date][]" class="form-control">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Aktif</label>
                    <select name="periods[is_active][]" class="form-select">
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2 pt-4">
                    <button type="button" class="btn btn-outline-danger btn-remove-period">Hapus</button>
                </div>
            </div>
        </div>`;

            // 2. Tombol Tambah Baris
            const btnAdd = document.getElementById('btnAddPeriod');
            const wrapper = document.getElementById('periodsWrapper');
            console.log(btnAdd, wrapper);
            if (btnAdd && wrapper) {
                btnAdd.addEventListener('click', function() {
                    wrapper.insertAdjacentHTML('beforeend', periodRowTemplate);
                });
            }

            // 3. Tombol Hapus (pakai event delegation)
            wrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-period')) {
                    e.target.closest('.period-row').remove();
                }
            });
        });
    </script>
@endpush
