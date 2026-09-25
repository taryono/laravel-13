{{-- app/Modules/MenuManagement/VIews/partials/tree-item.blade.php --}}
@foreach ($items as $item)
    <li class="menu-tree-item" data-id="{{ $item->id }}">
        <div class="menu-tree-row d-flex align-items-center gap-2 p-2 border rounded mb-1">
            <span class="drag-handle" style="cursor:grab;">
                <span class="material-symbols-outlined text-muted">drag_indicator</span>
            </span> 
            @if ($item->icon) 
                @if ($item->iconType === 'fa')
                    <i class="{{ $item->icon }} text-primary" style="font-size: 1.15rem; width: 24px; text-align: center;"></i>
                @else
                    <span class="material-symbols-outlined text-primary align-middle" style="font-size: 1.25rem;">{{ $item->icon }}</span>
                @endif
            @endif 
            <span class="flex-grow-1">
                <strong>{{ $item->title }}</strong>
                @if ($item->id_text || $item->en_text)
                    <small class="text-muted ms-1">
                        ({{ implode(' / ', array_filter([$item->id_text, $item->en_text])) }})
                    </small>
                @endif

                @if (($item->type ?? null) === 'topbar')
                    <span class="badge bg-info-subtle text-info ms-1">Topbar</span>
                @endif

                @if (!($item->is_active ?? true))
                    <span class="badge bg-danger-subtle text-danger ms-1">Nonaktif</span>
                @endif

                @foreach (($item->roles ?? []) as $role)
                    @php
                        $roleName = is_array($role) ? ($role['display_name'] ?? $role['name'] ?? '') : ($role->display_name ?? $role->name ?? '');
                    @endphp
                    @if ($roleName)
                        <span class="badge bg-primary-subtle text-primary ms-1">{{ $roleName }}</span>
                    @endif
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
                    onclick="handleDeleteMenu({{ $item->id }}, {{ \Illuminate\Support\Js::from($item->title) }})">
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