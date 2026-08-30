<!-- Sidebar -->
{{-- resources/views/components/sidebar.blade.php --}}

<aside class="bnsp-sidebar d-flex flex-column" id="bnspSidebar">
    <div class="bnsp-sidebar-brand mb-5 px-2">
        <h1 class="h5 fw-bold text-white mb-1">BNSP Portal</h1>
        <p class="text-white-50 small text-uppercase mb-0" style="font-size:.7rem;">National Agency</p>
    </div>
    <nav class="d-flex flex-column gap-2 flex-grow-1">
        <x-menu.nav-items :items="$menu" />
    </nav>

    <div class="mt-auto pt-4 border-top border-white border-opacity-10">
        <div class="d-flex flex-column gap-1">
            <a href="#" class="bnsp-nav-link py-2" data-tooltip="Help Center">
                <span class="material-symbols-outlined">help</span>
                <span class="nav-label">Help Center</span>
            </a>
        </div>
    </div>
</aside>
