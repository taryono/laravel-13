@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Manajemen Menu</h4>
    <button class="btn btn-primary" onclick="openMenuModal('create')">
        <span class="material-symbols-outlined align-middle">add</span> Tambah Menu
    </button>
</div>

<div class="card">
    <div class="card-body">
        <ul id="menu-tree" class="menu-tree list-unstyled mb-0">
            @include('MenuManagement::partials.tree-item', ['items' => $tree])
        </ul>
    </div>
</div>

@include('MenuManagement::partials.modal')

@push('scripts')
<script>
    window.MENU_PARENT_OPTIONS = @json($parentOptions);
    window.MENU_MODULES = @json($modules);
    window.MENU_ROLES = @json($roles);
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="{{ asset('assets/js/menu-management.js') }}"></script>
@endpush
@endsection