@extends('layouts.app')
{{-- app/Modules/MenuManagement/VIews/index.blade.php --}}
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
@include('MenuManagement::partials.modal-roles')
@include('MenuManagement::partials.modal-actions')

@push('scripts')
<script>
    window.MENU_PARENT_OPTIONS = @json($parentOptions);
    window.MENU_ROLES = @json($roles);
    window.MENU_USERS = @json($users);

    // Token __MENU_ID__ / __ACTION_ID__ diganti manual di JS sebelum request dikirim.
    window.MENU_ROUTES = {
        store           : @json(route('menu_management.store')),
        show            : @json(route('menu_management.show', ['menu' => '__MENU_ID__'])),
        update          : @json(route('menu_management.update', ['menu' => '__MENU_ID__'])),
        reorder         : @json(route('menu_management.reorder')),
        rolesSync       : @json(route('menu_management.roles.sync', ['menu' => '__MENU_ID__'])),
        actionsIndex    : @json(route('menu_management.actions.index', ['menu' => '__MENU_ID__'])),
        actionsStore    : @json(route('menu_management.actions.store', ['menu' => '__MENU_ID__'])),
        actionUpdate    : @json(route('menu_management.actions.update', ['action' => '__ACTION_ID__'])),
        actionDestroy   : @json(route('menu_management.actions.destroy', ['action' => '__ACTION_ID__'])),
        actionSyncAccess: @json(route('menu_management.actions.syncAccess', ['action' => '__ACTION_ID__'])),
    };
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const iconType = document.getElementById('menu_icon_type');
    const iconSelect = document.getElementById('menu_icon');
    const iconPreview = document.getElementById('icon_preview');
    const iconNameDisplay = document.getElementById('icon_name_display');
    
    function updateIconPreview() {
        const selectedIcon = iconSelect.value;
        const selectedText = iconSelect.options[iconSelect.selectedIndex]?.text || '';
        const type = iconType.value;
        
        if (selectedIcon) {
            if (type === 'fa') {
                iconPreview.innerHTML = `<i class="${selectedIcon}"></i>`;
                iconNameDisplay.textContent = selectedText;
            } else {
                iconPreview.innerHTML = `<span class="material-symbols-outlined">${selectedIcon}</span>`;
                iconNameDisplay.textContent = selectedText;
            }
        } else {
            iconPreview.innerHTML = '<i class="fas fa-th-large"></i>';
            iconNameDisplay.textContent = 'Pilih icon';
        }
    }
    
    iconType.addEventListener('change', function() {
        // Filter options based on type
        const options = iconSelect.querySelectorAll('optgroup option');
        let hasVisible = false;
        
        options.forEach(opt => {
            const parentLabel = opt.closest('optgroup')?.label || '';
            if (this.value === 'fa') {
                // Show only FA icons (check if value starts with 'fas' or 'far' or 'fab')
                if (opt.value.startsWith('fas ') || opt.value.startsWith('far ') || opt.value.startsWith('fab ')) {
                    opt.style.display = '';
                    hasVisible = true;
                } else {
                    opt.style.display = 'none';
                }
            } else {
                // Show only Material icons (value doesn't start with 'fa')
                if (!opt.value.startsWith('fas ') && !opt.value.startsWith('far ') && !opt.value.startsWith('fab ')) {
                    opt.style.display = '';
                    hasVisible = true;
                } else {
                    opt.style.display = 'none';
                }
            }
        });
        
        // Reset selection
        iconSelect.value = '';
        updateIconPreview();
    });
    
    iconSelect.addEventListener('change', updateIconPreview);
    
    // Initial update
    updateIconPreview();
    iconType.dispatchEvent(new Event('change'));
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="{{ asset('assets/js/menu-management.js') }}"></script>
@endpush
@endsection