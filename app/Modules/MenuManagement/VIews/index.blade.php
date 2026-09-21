@extends('layouts.app')
{{-- app/Modules/MenuManagement/VIews/index.blade.php --}}
@section('content')
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/menu_mangement.css') }}">
    {{-- always on top and disabled browser scroll --}}
    <div class="d-flex justify-content-between align-items-center mb-3 sticky-top bg-white" style="top: -10px;">
        <h4 class="mb-0">Manajemen Menu</h4>
        <button class="btn btn-primary" onclick="openMenuModal('create')">
            <span class="material-symbols-outlined align-middle">add</span> Tambah Menu
        </button>
    </div>

    <div class="card">
        <div class="card-body" style="height: 400px;max-height:400px;overflow-y:scroll;">
            <ul id="menu-tree" class="menu-tree list-unstyled mb-0">
                @include('MenuManagement::partials.tree-item', ['items' => $tree])
            </ul>
        </div>
    </div>

    @include('MenuManagement::partials.modal')
    @include('MenuManagement::partials.modal-roles')
    @include('MenuManagement::partials.modal-actions')
    @push('scripts')
        <script>
            window.MENU_PARENT_OPTIONS = @json($parentOptions);
            window.MENU_ROLES = @json($roles);
            window.MENU_USERS = @json($users);

            // Token __MENU_ID__ / __ACTION_ID__ diganti manual di JS sebelum request dikirim.
            window.MENU_ROUTES = {
                store: @json(route('menu_management.store')),
                show: @json(route('menu_management.show', ['menu' => '__MENU_ID__'])),
                update: @json(route('menu_management.update', ['menu' => '__MENU_ID__'])),
                reorder: @json(route('menu_management.reorder')),
                rolesSync: @json(route('menu_management.roles.sync', ['menu' => '__MENU_ID__'])),
                actionsIndex: @json(route('menu_management.actions.index', ['menu' => '__MENU_ID__'])),
                actionsStore: @json(route('menu_management.actions.store', ['menu' => '__MENU_ID__'])),
                actionUpdate: @json(route('menu_management.actions.update', ['action' => '__ACTION_ID__'])),
                actionDestroy: @json(route('menu_management.actions.destroy', ['action' => '__ACTION_ID__'])),
                actionSyncAccess: @json(route('menu_management.actions.syncAccess', ['action' => '__ACTION_ID__'])),
            };
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script src="{{ asset('assets/js/menu.js') }}"></script>
    @endpush
@endsection
