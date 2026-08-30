{{-- resources/views/components/menu/item.blade.php --}}
@if ($item->url)
    <a href="/{{ $item->url }}"
        class="bnsp-nav-link {{ $item->active ? 'active' : '' }} {{ $item->open ? 'open' : '' }}"
        data-ajax-url="{{ $item->route ?? '#' }}" data-tooltip="{{ $item->title }}">
        <x-menu.icon :icon="$item->icon" :icon-type="$item->icon_type" />
        <span class="nav-label">{{ $item->title }}</span>
    </a>
@else
    <span class="bnsp-nav-link {{ $item->open ? 'open' : '' }}">
        <x-menu.icon :icon="$item->icon" :icon-type="$item->icon_type" />
        <span class="nav-label">{{ $item->title }}</span>
    </span>
@endif

@if ($item->children->isNotEmpty())
    <ul class="bnsp-nav-sub {{ $item->open ? 'show' : '' }}">
        @foreach ($item->children as $child)
            @include('components.menu.item', ['item' => $child])
        @endforeach
    </ul>
@endif
