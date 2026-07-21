@foreach ($menus as $menu)
    <div class="bnsp-nav-item {{ $menu->open ? 'open' : '' }}">

        <a href="{{ $menu->route ? route($menu->route) : '#' }}"
            class="bnsp-nav-link {{ $menu->active ? 'active' : '' }} {{ count($menu->children) ? 'bnsp-nav-parent' : '' }}">

            @if ($menu->icon)
                <span class="material-symbols-outlined">

                    {{ $menu->icon }}

                </span>
            @endif

            <span class="nav-label flex-grow-1">

                {{ $menu->text }}

            </span>

            @if (count($menu->children))
                <span class="material-symbols-outlined chevron">

                    chevron_right

                </span>
            @endif

        </a>

        @if (count($menu->children))
            <ul class="bnsp-submenu">

                <x-sidebar-item :menus="$menu->children" />

            </ul>
        @endif

    </div>
@endforeach
