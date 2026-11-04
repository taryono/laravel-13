@forelse ($menus as $menu)
    @php
        $isSelected = session('active_menu_category_id') == $menu->id;
    @endphp
    <a href="{{ route('dashboard.select-category.get', ['id' => $menu->id, 'redirect' => $menu->url !== '#' ? $menu->url : null]) }}"
        class="menu-card d-flex flex-column align-items-center text-center p-3 rounded-4 shadow-sm position-relative {{ $isSelected ? 'active-category' : '' }}"
        data-menu-category-id="{{ $menu->id }}"
        data-target-url="{{ $menu->url }}">
        @if ($isSelected)
            <span class="position-absolute top-0 end-0 m-2 badge rounded-pill bg-primary-edu text-white p-1" title="Kategori aktif pada sidebar">
                <span class="material-symbols-outlined icon-14 d-block">check</span>
            </span>
        @endif
        <div class="menu-icon tone-{{ $menu->tone }} rounded-3 d-flex align-items-center justify-content-center mb-2 {{ $isSelected ? 'ring-active' : '' }}">
            <span class="material-symbols-outlined icon-26">{{ $menu->icon }}</span>
        </div>
        <span class="t-label-md lh-sm">{{ $menu->title }}</span>
        <span class="t-label-sm text-variant mt-1 line-clamp-1">{{ $menu->subtitle }}</span>
    </a>
@empty
    <div class="col-12 py-4 text-center text-variant w-100" style="grid-column: 1 / -1;">
        <span class="material-symbols-outlined icon-32 mb-1 d-block">apps_outage</span>
        <span class="t-body-sm">Belum ada modul yang tersedia di halaman ini.</span>
    </div>
@endforelse
