@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Student</h5>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="ajax-modal" data-size="lg" data-method="GET"
                    data-title="Tambah Data Student Baru" data-url="{{ route('student.create') }}"
                    data-submit='<button class="btn btn-primary" type="submit" form="formStudent">Simpan</button>'
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
