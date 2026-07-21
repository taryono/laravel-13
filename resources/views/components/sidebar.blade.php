<!-- Sidebar -->
{{-- resources/views/components/sidebar.blade.php --}}


{{-- <aside class="bnsp-sidebar d-flex flex-column" id="bnspSidebar">
    <div class="bnsp-sidebar-brand mb-5 px-2">
        <h1 class="h5 fw-bold text-white mb-1">BNSP Portal</h1>
        <p class="text-white-50 small text-uppercase mb-0" style="font-size:.7rem;">National Agency</p>
    </div>

    <nav class="d-flex flex-column gap-2 flex-grow-1">

        <!-- Dashboard (leaf, active) -->
        <div class="bnsp-nav-item">
            <a href="#" class="bnsp-nav-link active" data-tooltip="Dashboard">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">dashboard</span>
                <span class="nav-label">Dashboard</span>
            </a>
        </div>

        <!-- Certifications (parent, multi-level) -->
        <div class="bnsp-nav-item">
            <a href="#" class="bnsp-nav-link bnsp-nav-parent" data-tooltip="Certifications">
                <span class="material-symbols-outlined">verified</span>
                <span class="nav-label flex-grow-1">Certifications</span>
                <span class="material-symbols-outlined chevron">chevron_right</span>
            </a>
            <ul class="bnsp-submenu" data-flyout-title="Certifications">
                <li><a href="#" class="bnsp-nav-link"><span class="nav-label">Overview</span></a></li>
                <li>
                    <div class="bnsp-nav-item">
                        <a href="#" class="bnsp-nav-link bnsp-nav-parent">
                            <span class="nav-label flex-grow-1">Schemes</span>
                            <span class="material-symbols-outlined chevron">chevron_right</span>
                        </a>
                        <ul class="bnsp-submenu">
                            <li><a href="#" class="bnsp-nav-link"><span class="nav-label">CPOF</span></a></li>
                            <li><a href="#" class="bnsp-nav-link"><span class="nav-label">CPSP</span></a></li>
                            <li><a href="#" class="bnsp-nav-link"><span class="nav-label">CCMS</span></a></li>
                            <li><a href="#" class="bnsp-nav-link"><span class="nav-label">CPST</span></a></li>
                        </ul>
                    </div>
                </li>
                <li><a href="#" class="bnsp-nav-link"><span class="nav-label">Requirements</span></a></li>
            </ul>
        </div>

        <!-- Assessments (parent, 2-level) -->
        <div class="bnsp-nav-item">
            <a href="#" class="bnsp-nav-link bnsp-nav-parent" data-tooltip="Assessments">
                <span class="material-symbols-outlined">assignment_turned_in</span>
                <span class="nav-label flex-grow-1">Assessments</span>
                <span class="material-symbols-outlined chevron">chevron_right</span>
            </a>
            <ul class="bnsp-submenu" data-flyout-title="Assessments">
                <li><a href="#" class="bnsp-nav-link"><span class="nav-label">Upcoming</span></a></li>
                <li><a href="#" class="bnsp-nav-link"><span class="nav-label">History</span></a></li>
            </ul>
        </div>

        <!-- Competency (leaf) -->
        <div class="bnsp-nav-item">
            <a href="#" class="bnsp-nav-link" data-tooltip="Competency">
                <span class="material-symbols-outlined">psychology</span>
                <span class="nav-label">Competency</span>
            </a>
        </div>

        <!-- Reports (parent, 2-level) -->
        <div class="bnsp-nav-item">
            <a href="#" class="bnsp-nav-link bnsp-nav-parent" data-tooltip="Reports">
                <span class="material-symbols-outlined">analytics</span>
                <span class="nav-label flex-grow-1">Reports</span>
                <span class="material-symbols-outlined chevron">chevron_right</span>
            </a>
            <ul class="bnsp-submenu" data-flyout-title="Reports">
                <li><a href="#" class="bnsp-nav-link"><span class="nav-label">Monthly</span></a></li>
                <li><a href="#" class="bnsp-nav-link"><span class="nav-label">Annual</span></a></li>
            </ul>
        </div>

    </nav>

    <div class="mt-auto pt-4 border-top border-white border-opacity-10">
        <button class="bnsp-sidebar-cta mb-3">
            <span class="material-symbols-outlined">add_circle</span>
            <span>New Application</span>
        </button>
        <div class="d-flex flex-column gap-1">
            <a href="#" class="bnsp-nav-link py-2" data-tooltip="Help Center">
                <span class="material-symbols-outlined">help</span>
                <span class="nav-label">Help Center</span>
            </a>
            <a href="#" class="bnsp-nav-link py-2" data-tooltip="Logout">
                <span class="material-symbols-outlined">logout</span>
                <span class="nav-label">Logout</span>
            </a>
        </div>
    </div>
</aside> --}}


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
        <button class="bnsp-sidebar-cta mb-3">
            <span class="material-symbols-outlined">add_circle</span>
            <span>New Application</span>
        </button>
        <div class="d-flex flex-column gap-1">
            <a href="#" class="bnsp-nav-link py-2" data-tooltip="Help Center">
                <span class="material-symbols-outlined">help</span>
                <span class="nav-label">Help Center</span>
            </a>
            <a href="#" class="bnsp-nav-link py-2" data-tooltip="Logout">
                <span class="material-symbols-outlined">logout</span>
                <span class="nav-label">Logout</span>
            </a>
        </div>
    </div>
</aside>
