@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Floor</h5>
            <a href="{{ route('floor.create') }}" class="btn btn-primary btn-sm">Tambah Baru</a>
        </div>
        <div class="card-body">
            @if(session('success'))
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
        // Handle Delete via AJAX
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                let url = $(this).attr('href');
                let token = $('meta[name="csrf-token"]').attr('content');
                
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { _token: token },
                    success: function(response) {
                        if (response.success) {
                            window.LaravelDataTables["floor-table-table"].ajax.reload();
                        }
                    }
                });
            }
        });
    </script>
@endpush
