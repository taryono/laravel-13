@extends('layouts.app')

@section('content') 
<div class="container">
    <div class="card dt-card shadow-sm rounded-3">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-building"></i> Daftar Account
            </h5>
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('account.getDataTree') }}" class="btn btn-primary btn-sm me-3">Tree COA</a>
               <button type="button" 
                   class="btn btn-light btn-sm d-flex align-items-center gap-1"
                   data-toggle="ajax-modal" data-size="lg" data-method="GET"
                   data-title="Tambah Data Account Baru" 
                   data-url="{{ route('account.create') }}"
                   data-submit='<button class="btn btn-primary" type="submit" form="formAccount">Simpan</button>'
                   data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                   <i class="bi bi-plus-circle"></i> Tambah Baru
               </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                {!! $dataTable->table([
                    'class' => 'table table-striped table-hover align-middle w-100'
                ]) !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush