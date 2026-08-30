{{-- resources/views/components/menu/nav-items.blade.php --}}

@foreach ($items as $item)
    @php
        $item->children = collect($item->children);
    @endphp
    <div class="bnsp-nav-item {{ $item->open ? 'open active' : '' }}">

        @if ($item->children->isNotEmpty())
            <a href="#" class="bnsp-nav-link bnsp-nav-parent">
                {{-- Sesuaikan parameter ikon sesuai kolom baru --}}
                <x-menu.icon :icon="$item->icon" :icon-type="$item->icon_type" />
                <span class="nav-label flex-grow-1">{{ $item->title }}</span>
                <span class="material-symbols-outlined chevron">chevron_right</span>
            </a>
            <ul class="bnsp-submenu" data-flyout-text="{{ $item->flyoutTitle }}" data-flyout-title="{{ $item->title }}"
                data-flyout-url="{{ $item->route ?? '#' }}">
                @foreach ($item->children as $child)
                    <li><x-menu.nav-items :items="[$child]" /></li>
                @endforeach
            </ul>
        @else
            <a href="/{{ $item->url ?? '#' }}" class="bnsp-nav-link {{ request()->is($item->route) ? 'active' : '' }}"
                data-ajax-url="{{ $item->route ?? '#' }}" data-tooltip="{{ $item->title }}">
                <x-menu.icon :icon="$item->icon" :icon-type="$item->icon_type" />
                <span class="nav-label">{{ $item->title }}</span>
            </a>
        @endif
    </div>
@endforeach
