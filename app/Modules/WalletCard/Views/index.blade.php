@extends('layouts.app')

@section('title', 'Daftar WalletCard')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="card dt-card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white"><i class="fas fa-list-alt me-2"></i> Daftar WalletCard</h5> 
            <button type="button" class="btn btn-light btn-sm" data-toggle="ajax-modal" data-bs-toggle="ajax-modal" data-size="lg" data-method="GET"
                data-title="Tambah Data WalletCard Baru" data-url="{{ route('wallet_card.create') }}"
                data-submit='<button class="btn btn-primary" type="submit" form="formWalletCard">Simpan</button>'
                data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                <i class="fas fa-plus-circle me-1 text-primary"></i> Tambah Baru
            </button>
        </div>
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-ban me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered table-striped table-hover align-middle w-100']) !!}
            </div>
        </div>
    </div>
</div>
@endsection 

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
