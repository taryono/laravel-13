@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar User</h5> 
            <button
                type="button"
                class="btn btn-sm btn-primary"
                data-toggle="ajax-modal"
                data-size="lg"
                data-method="GET"
                data-title="Create Data User"
                data-url="{{route('user.create')}}"
                data-submit='<button class="btn btn-primary modal-submit" type="button">Simpan</button>'
                data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                <span class=""><i class="fas fa-users"></i> Tambah Baru</span>
            </button>
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
@endpush
