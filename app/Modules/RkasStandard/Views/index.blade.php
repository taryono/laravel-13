@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar RkasStandard</h5> 
            <button type="button" class="btn btn-primary btn-sm" data-toggle="ajax-modal" data-size="lg" data-method="GET"
                data-title="Tambah Data RkasStandard Baru" data-url="{{ route('rkas_standard.create') }}"
                data-submit='<button class="btn btn-primary" type="submit" form="formRkasStandard">Simpan</button>'
                data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                <i class="bi bi-plus-circle"></i> Tambah Baru
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
