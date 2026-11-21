@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Karyawan</h5>
                <a href="{{ route('employee.create') }}" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined align-middle">add</span>
                    Tambah Karyawan
                </a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
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
        // Auto-reload after modal close
        $(document).on('hidden.bs.modal', '.modal', function() {
            if ($(this).find('.modal-submit').data('is_execute_drawer')) {
                var tableId = $(this).find('.modal-submit').data('datatable-id');
                if (tableId) {
                    $('#' + tableId).DataTable().ajax.reload(null, false);
                }
            }
        });
    </script>
@endpush
