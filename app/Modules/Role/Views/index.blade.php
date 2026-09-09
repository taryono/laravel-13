@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif 
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Role</h4>
        <div class="d-flex gap-2">
            {{-- <button class="btn btn-outline-secondary">Export</button> --}}
             <button type="button" 
                   class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                   data-toggle="ajax-modal" data-size="lg" data-method="GET"
                   data-title="Tambah Role Baru" 
                   data-url="{{ route('role.create') }}"
                   data-submit='<button class="btn btn-primary" type="submit" form="formRole">Simpan</button>'
                   data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                   <i class="bi bi-plus-circle"></i> Tambah Baru
               </button>
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
