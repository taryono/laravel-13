@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <div class="card border-0 shadow-sm">

        {{-- Header --}}
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

                <div>
                    <h5 class="mb-1 fw-semibold">
                        Daftar Asesor
                    </h5>

                    <small class="text-muted">
                        Kelola data asesor yang terdaftar
                    </small>
                </div>

                <a
                    href="{{ route('asesor.create') }}"
                    class="btn btn-primary btn-sm px-3"
                >
                    <span class="material-symbols-outlined align-middle me-1">
                        add
                    </span>
                    Tambah Asesor
                </a>

            </div>
        </div>

        {{-- Body --}}
        <div class="card-body pt-0">

            @if(session('success'))
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >
                    <span class="material-symbols-outlined align-middle me-1">
                        check_circle
                    </span>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>
                </div>
            @endif

            <div class="table-responsive">

                {!! $dataTable->table([
                    'class' => 'table table-hover align-middle w-100 mb-0',
                ]) !!}

            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush