@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Daftar Role</h4>
            <div class="d-flex gap-2">
                {{-- <button class="btn btn-outline-secondary">Export</button> --}}
                <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-toggle="ajax-modal"
                    data-size="lg" data-method="GET" data-title="Tambah Role Baru" data-url="{{ route('role.create') }}"
                    data-submit='<button class="btn btn-primary" type="submit" form="formRole">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                    <i class="bi bi-plus-circle"></i> Tambah Baru
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <select id="filter_is_active" class="form-select" data-column="is_active">
                                <option value="">Semua</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select id="filter_is_system" class="form-select" data-column="is_system">
                                <option value="">Semua</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <select id="filter_parent_id" class="form-select" data-column="parent_id">
                                <option value="">Semua Parent</option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100" id="btnResetRoleFilter" type="button">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {!! $dataTable->table(['class' => 'table table-bordered table-striped w-100', 'id' => 'role-table']) !!}
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script src="{{ asset('assets/js/datatable-filter.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            DataTableFilter.init('role-table', {
                selector: '#filter_is_active, #filter_is_system, #filter_parent_id',
                resetButton: '#btnResetRoleFilter'
            });
        });
    </script>
@endpush
