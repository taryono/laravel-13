{{-- app/Modules/MenuManagement/VIews/partials/tree-item.blade.php --}}
@foreach ($items as $item)
    <li class="menu-tree-item" data-id="{{ $item->id }}">
        <div class="menu-tree-row d-flex align-items-center gap-2 p-2 border rounded mb-1">
            <span class="drag-handle" style="cursor:grab;">
                <span class="material-symbols-outlined">drag_indicator</span>
            </span> 
            {{-- resources/views/components/menu/icon.blade.php --}}
            @if ($item->icon) 
                @if ($item->iconType === 'fa')
                    <i class="{{ $item->icon }}"></i>
                @else
                    <span class="material-symbols-outlined">{{ $item->icon }}</span>
                @endif
            @endif 
            <span class="flex-grow-1">
                {{ $item->title }}

                @if (($item->type ?? null) === 'topbar')
                    <span class="badge bg-info-subtle text-info ms-1">Topbar</span>
                @endif

                @if (!($item->is_active ?? true))
                    <span class="badge bg-secondary-subtle text-secondary ms-1">Nonaktif</span>
                @endif

                {{-- $item->roles diasumsikan sudah di-eager-load lewat menu()->with('roles') di MenuRepository.
                     Kalau tree builder masih mengembalikan plain object/array (bukan Eloquent),
                     bagian ini perlu disesuaikan supaya relasi roles ikut terbawa. --}}
                @foreach (($item->roles ?? []) as $role)
                    <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $role->name }}</span>
                @endforeach
            </span>

            <button type="button" class="btn btn-sm btn-outline-secondary"
                    title="Kelola Role"
                    onclick="openMenuRolesModal({{ $item->id }}, {{ \Illuminate\Support\Js::from($item->title) }})">
                <span class="material-symbols-outlined">admin_panel_settings</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary"
                    title="Kelola Action"
                    onclick="openMenuActionsModal({{ $item->id }}, {{ \Illuminate\Support\Js::from($item->title) }})">
                <span class="material-symbols-outlined">bolt</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary"
                    title="Edit"
                    onclick="openMenuModal('edit', {{ $item->id }})">
                <span class="material-symbols-outlined">edit</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger"
                    title="Hapus"
                    data-confirm-delete
                    data-url="{{ route('menu_management.destroy', $item->id) }}"
                    data-method="DELETE">
                <span class="material-symbols-outlined">delete</span>
            </button>
        </div>

        <ul class="menu-tree-children list-unstyled ms-4">
            @if (!empty($item->children))
                @include('MenuManagement::partials.tree-item', ['items' => $item->children])
            @endif
        </ul>
    </li>
@endforeach