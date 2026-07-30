@foreach ($items as $item)
    <div class="bnsp-nav-item {{ $item->open ? 'open' : '' }}">
        
        @if ($item->hasChildren())
            <a href="#" class="bnsp-nav-link bnsp-nav-parent">
                <x-menu.icon :icon="$item->icon" :icon-type="$item->iconType" />
                <span class="nav-label flex-grow-1">{{ $item->text }}</span>
                <span class="material-symbols-outlined chevron">chevron_right</span>
            </a>
            <ul class="bnsp-submenu" data-flyout-text="{{ $item->flyoutTitle }}" data-flyout-title="{{ $item->text }}" data-flyout-url="{{ $item->url ?? '#' }}">
                @foreach ($item->children as $child)
                    <li><x-menu.nav-items :items="[$child]" /></li>
                @endforeach
            </ul>
        @else
            {{-- Ubah href jadi # dan simpan url asli di data-ajax-url --}}
            <a href="{{ $item->url ?? '#' }}" class="bnsp-nav-link {{ $item->active ? 'active' : '' }}"
                data-ajax-url="{{ $item->url ?? '#' }}" data-tooltip="{{ $item->text }}">
                <x-menu.icon :icon="$item->icon" :icon-type="$item->iconType" />
                <span class="nav-label">{{ $item->text }}</span>
            </a>
        @endif
    </div>
@endforeach
