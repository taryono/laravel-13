@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar CmsPost</h5>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="ajax-modal" data-size="lg" data-method="GET"
                    data-title="Tambah Data CmsPost Baru" data-url="{{ route('cms_post.create') }}"
                    data-submit='<button class="btn btn-primary" type="submit" form="formCmsPost">Simpan</button>'
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

    {{-- TinyMCE 7 Self-Hosted via jsDelivr (Tanpa API Key) --}}
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        // Fix wajib agar popup dialog TinyMCE (Link, Image, Code) bisa diketik di dalam Bootstrap 5 Modal
        document.addEventListener('focusin', (e) => {
            if (e.target.closest('.tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !==
                null) {
                e.stopImmediatePropagation();
            }
        });
    </script>
@endpush
