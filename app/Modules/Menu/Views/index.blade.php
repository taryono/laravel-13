@extends('layouts.app')

@section('content')

<div class="container-fluid px-4">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif 
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Menu</h4>
        <div class="d-flex gap-2">
            {{-- <button class="btn btn-outline-secondary">Export</button> --}}
             <a href="{{ route('menu.create') }}" class="btn btn-primary btn-sm">Tambah Baru</a>
        </div>
    </div>

    <div class="table-responsive">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped w-100']) !!}
    </div>
</div>
 
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
