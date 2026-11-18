@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Bonus</h5>
                <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-toggle="ajax-modal"
                    data-size="lg" data-method="GET" data-title="Tambah Data Bonus Baru" data-url="{{ route('bonus.create') }}"
                    data-submit='<button class="btn btn-primary modal-submit" type="submit" form="formBonus" data-is_execute_drawer="true" data-datatable-id="bonus-table">Simpan</button>'
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
