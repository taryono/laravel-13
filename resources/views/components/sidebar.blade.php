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

    {{-- Search Input Menu Sidebar --}}
    <div class="bnsp-sidebar-search px-2 mb-2">
        <div class="position-relative d-flex align-items-center">
            <span class="material-symbols-outlined position-absolute text-white-50 pointer-events-none" 
                  style="font-size: 1.15rem; left: 10px; pointer-events: none;">search</span>
            <input type="text"
                   id="bnspSidebarSearch"
                   class="form-control form-control-sm bnsp-search-input text-white"
                   style="padding-left: 2.1rem; padding-right: 2rem;"
                   placeholder="Cari menu... (Ctrl+K)"
                   autocomplete="off"
                   aria-label="Cari menu sidebar">
            <button type="button" 
                    id="bnspSidebarSearchClear" 
                    class="btn btn-sm text-white-50 p-0 position-absolute end-0 me-2 d-none align-items-center justify-content-center" 
                    style="width: 22px; height: 22px;" 
                    title="Hapus pencarian">
                <span class="material-symbols-outlined" style="font-size: 1rem;">close</span>
            </button>
        </div>
    </div>

    {{-- Empty Search State --}}
    <div id="bnspSidebarSearchEmpty" class="px-2 py-3 text-center text-white-50 small d-none">
        <span class="material-symbols-outlined d-block mb-1 fs-5 text-white-50">search_off</span>
        <span>Menu tidak ditemukan</span>
    </div>

    <nav class="d-flex flex-column gap-2 flex-grow-1 mb-3 style-dotted" id="bnspSidebarNav">
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

<style>
    .bnsp-sidebar-search {
        transition: opacity .2s ease;
    }
    .bnsp-sidebar.collapsed .bnsp-sidebar-search {
        display: none !important;
    }
    .bnsp-search-input {
        background-color: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 0.5rem;
        color: #fff !important;
        font-size: 0.8125rem;
        height: 34px;
        transition: all 0.2s ease;
    }
    .bnsp-search-input::placeholder {
        color: rgba(255, 255, 255, 0.45);
        font-size: 0.8125rem;
    }
    .bnsp-search-input:focus {
        background-color: rgba(255, 255, 255, 0.14);
        border-color: rgba(255, 255, 255, 0.35);
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
        outline: none;
        color: #fff !important;
    }
    .bnsp-search-highlight {
        background-color: rgba(255, 222, 162, 0.35);
        color: #fff;
        border-radius: 2px;
        padding: 0 2px;
        font-weight: 700;
    }
    .bnsp-nav-item.search-match-parent > .bnsp-submenu {
        max-height: none !important;
        display: block !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('bnspSidebarSearch');
        const clearBtn = document.getElementById('bnspSidebarSearchClear');
        const emptyState = document.getElementById('bnspSidebarSearchEmpty');
        const sidebarNav = document.getElementById('bnspSidebarNav');

        if (!searchInput || !sidebarNav) return;

        // Ambil semua item menu teratas
        const topLevelItems = Array.from(sidebarNav.querySelectorAll(':scope > .bnsp-nav-item'));

        // Simpan state awal menu (buka/tutup dan teks asli label)
        function ensureOriginalState() {
            sidebarNav.querySelectorAll('.bnsp-nav-item').forEach(item => {
                if (!item.hasAttribute('data-initial-open')) {
                    item.setAttribute('data-initial-open', item.classList.contains('open') ? 'true' : 'false');
                }
                const link = item.querySelector(':scope > .bnsp-nav-link');
                const label = link ? link.querySelector('.nav-label') : null;
                if (label && !label.hasAttribute('data-original-title')) {
                    label.setAttribute('data-original-title', label.textContent.trim());
                }
            });
        }

        ensureOriginalState();

        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        // Fungsi rekursif untuk memfilter item menu berdasarkan teks label
        function filterNode(item, query) {
            const link = item.querySelector(':scope > .bnsp-nav-link');
            const label = link ? link.querySelector('.nav-label') : null;
            const originalTitle = label ? (label.getAttribute('data-original-title') || label.textContent.trim()) : '';

            let selfMatch = false;
            if (query && originalTitle) {
                selfMatch = originalTitle.toLowerCase().includes(query);
            }

            // Periksa anak menu (submenu)
            const submenu = item.querySelector(':scope > .bnsp-submenu');
            let childMatchCount = 0;

            if (submenu) {
                const childItems = Array.from(submenu.querySelectorAll(':scope > li > .bnsp-nav-item'));
                childItems.forEach(child => {
                    const matched = filterNode(child, query);
                    if (matched) childMatchCount++;
                });
            }

            const isMatched = selfMatch || (childMatchCount > 0);

            if (query === '') {
                // Reset ke kondisi semula jika pencarian kosong
                item.classList.remove('d-none', 'search-match-parent');
                const wasOpen = item.getAttribute('data-initial-open') === 'true';
                item.classList.toggle('open', wasOpen);
                if (label) {
                    label.textContent = originalTitle;
                }
                return true;
            }

            if (isMatched) {
                item.classList.remove('d-none');
                if (label) {
                    if (selfMatch) {
                        const regex = new RegExp(`(${escapeRegExp(query)})`, 'gi');
                        label.innerHTML = originalTitle.replace(regex, '<span class="bnsp-search-highlight">$1</span>');
                    } else {
                        label.textContent = originalTitle;
                    }
                }

                if (submenu) {
                    item.classList.add('open', 'search-match-parent');
                    // Jika parent cocok dan anak tidak ada spesifik yang cocok, tampilkan seluruh anak
                    if (selfMatch && childMatchCount === 0) {
                        submenu.querySelectorAll('.bnsp-nav-item').forEach(c => {
                            c.classList.remove('d-none');
                            const cLabel = c.querySelector('.nav-label');
                            if (cLabel) cLabel.textContent = cLabel.getAttribute('data-original-title') || cLabel.textContent.trim();
                        });
                    }
                }
                return true;
            } else {
                item.classList.add('d-none');
                item.classList.remove('open', 'search-match-parent');
                if (label) {
                    label.textContent = originalTitle;
                }
                return false;
            }
        }

        function performSearch() {
            const query = searchInput.value.trim().toLowerCase();

            if (clearBtn) {
                clearBtn.classList.toggle('d-none', query === '');
                clearBtn.classList.toggle('d-flex', query !== '');
            }

            ensureOriginalState();

            let anyMatch = false;
            topLevelItems.forEach(item => {
                const matched = filterNode(item, query);
                if (matched && query !== '') {
                    anyMatch = true;
                }
            });

            if (emptyState) {
                emptyState.classList.toggle('d-none', query === '' || anyMatch);
            }
        }

        searchInput.addEventListener('input', performSearch);

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                performSearch();
                searchInput.focus();
            });
        }

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                performSearch();
                searchInput.blur();
            }
        });

        // Pintasan keyboard Ctrl+K atau Cmd+K
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                const sidebar = document.getElementById('bnspSidebar');
                if (sidebar && !sidebar.classList.contains('collapsed')) {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });
    });
</script>
