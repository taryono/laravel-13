<!-- Sidebar -->
{{-- resources/views/components/sidebar.blade.php --}}

<aside class="bnsp-sidebar d-flex flex-column" id="bnspSidebar">
    <div class="bnsp-sidebar-brand mb-3 px-2">
        <h1 class="h5 fw-bold text-white mb-1">BNSP Portal</h1>
        <p class="text-white-50 small text-uppercase mb-0" style="font-size:.7rem;">National Agency</p>
    </div>

    @if(isset($activeMenuCategory) && $activeMenuCategory)
        <div class="px-2 mb-2">
            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-white bg-opacity-10 text-white border border-white border-opacity-15">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <span class="material-symbols-outlined fs-6 text-warning">{{ $activeMenuCategory->icon ?? 'apps' }}</span>
                    <span class="small fw-semibold text-truncate" title="{{ $activeMenuCategory->name }}">{{ $activeMenuCategory->name }}</span>
                </div>
                <a href="{{ route('dashboard.select-category.get', ['id' => 'all']) }}" 
                   class="btn btn-sm text-white-50 p-0 ms-1 d-flex align-items-center" 
                   title="Reset filter (tampilkan semua menu)">
                    <span class="material-symbols-outlined fs-6">close</span>
                </a>
            </div>
        </div>
    @endif

    <nav class="d-flex flex-column gap-2 flex-grow-1 mb-3 style-dotted">
        <x-menu.nav-items :items="$menu" />
    </nav>
    <div class="mt-auto pt-4 border-top border-white border-opacity-10">
        <div class="d-flex flex-column gap-1">
            <a href="#" class="bnsp-nav-link py-2" data-tooltip="System Settings">
                <span class="material-symbols-outlined">settings</span>
                <span class="nav-label">System Settings</span>
            </a>
            <a href="#" class="bnsp-nav-link py-2" data-tooltip="Help Center">
                <span class="material-symbols-outlined">help</span>
                <span class="nav-label">Help Center</span>
            </a>
        </div>
    </div>
</aside>
