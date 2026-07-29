@foreach ($items as $item)
    <li class="menu-tree-item" data-id="{{ $item->id }}">
        <div class="menu-tree-row d-flex align-items-center gap-2 p-2 border rounded mb-1 bg-white">
            <span class="drag-handle" style="cursor:grab;">
                <span class="material-symbols-outlined">drag_indicator</span>
            </span>

            <span class="material-symbols-outlined text-secondary">
                {{ $item->icon ?? 'radio_button_unchecked' }}
            </span>

            <span class="flex-grow-1">
                {{ $item->text }}
                @if ($item->isTopnav)
                    <span class="badge bg-info-subtle text-info ms-1">Topnav</span>
                @endif
                @if (!$item->active && !($item->module_id ?? null))
                @endif
                @if (isset($item->role) && $item->role)
                    <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $item->role }}</span>
                @endif
            </span>

            <button class="btn btn-sm btn-outline-secondary" onclick="openMenuModal('edit', {{ $item->id }})">
                <span class="material-symbols-outlined">edit</span>
            </button>
            <button class="btn btn-sm btn-outline-danger" data-confirm-delete data-url="{{ route('admin.menus.destroy', $item->id) }}" data-method="DELETE">
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