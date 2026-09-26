{{-- app/Modules/MenuManagement/VIews/partials/modal.blade.php --}}
<div class="modal fade modal-custom" id="menuModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="menuForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuModalTitle">Tambah Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="id" id="menu_id">

                    <div class="col-md-6">
                        <label class="form-label">Judul Menu (title)</label>
                        <input type="text" class="form-control" name="title" id="menu_title" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Flyout Title</label>
                        <input type="text" class="form-control" name="flyout_title" id="menu_flyout_title"
                            placeholder="Judul saat menu ini ditampilkan sebagai flyout (opsional)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Indonesia</label>
                        <input type="text" class="form-control" name="id_text" id="id_text"
                            placeholder="Judul indonesia">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">English</label>
                        <input type="text" class="form-control" name="en_text" id="en_text"
                            placeholder="Judul english">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Slug
                            <small class="text-muted">(kosongkan untuk auto-generate dari title)</small>
                        </label>
                        <input type="text" class="form-control" name="slug" id="menu_slug"
                            placeholder="mis. master-produk">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Route name</label>
                        <input type="text" class="form-control" name="route" id="menu_route">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control" name="url" id="menu_url">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Parent Menu</label>
                        <select class="form-select" name="parent_id" id="menu_parent_id">
                            <option value="">— Tidak ada (root) —</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <!-- ===== ICON PICKER ===== -->
                        <div class="form-icon-group mb-3">
                            <label class="form-label">
                                <i class="fas fa-icons me-1"></i> Icon Menu <span class="text-danger">*</span>
                            </label>

                            <input type="hidden" name="icon_type" id="menu_icon_type" value="fa">
                            <input type="hidden" name="icon" id="menu_icon" value="fas fa-th-large">

                            <div class="icon-picker-wrapper">
                                <div class="icon-preview-box" id="iconPreviewBox">
                                    <div class="preview-icon" id="previewIconDisplay">
                                        <i class="fas fa-th-large"></i>
                                    </div>
                                    <div class="preview-info">
                                        <div class="icon-name" id="previewIconName">Pilih Icon</div>
                                        <div class="icon-class" id="previewIconClass">fas fa-th-large</div>
                                        <span class="icon-type-badge" id="previewIconType">Font Awesome</span>
                                    </div>
                                    <div style="margin-left: auto; color: #b0b8d0;">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>

                                <div class="form-preview-result">
                                    <div class="result-icon" id="resultIconDisplay">
                                        <i class="fas fa-th-large"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Akan dikirim:</small>
                                        <code id="resultIconCode">icon_type: fa | icon: fas fa-th-large</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tampilkan di</label>
                        <select class="form-select" name="type" id="menu_type">
                            <option value="sidebar">Sidebar</option>
                            <option value="topbar">Topbar</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Urutan (sort order)</label>
                        <input type="number" class="form-control" name="sort_order" id="menu_sort_order"
                            min="0">
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="menu_is_active"
                                checked>
                            <label class="form-check-label">Aktif</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="alert alert-light border small mb-0">
                            Assignment <strong>Role</strong> (per menu) dan <strong>Action</strong>
                            (permission granular) dikelola lewat tombol
                            <em>Kelola Role</em> / <em>Kelola Action</em> di baris menu setelah menu ini disimpan.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL ICON PICKER (Nested) ===== -->
<div class="modal fade icon-picker-modal" id="iconPickerModal" tabindex="-1" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-icons"></i> Pilih Icon Menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="icon-search-box mb-2">
                    <input type="text" class="search-input" id="iconSearchInput" placeholder="🔍 Cari icon berdasarkan nama/grup...">

                    <div class="type-selector">
                        <button class="btn-type active" data-type="fa" type="button">
                            <i class="fas fa-font-awesome"></i> Font Awesome
                        </button>
                        <button class="btn-type" data-type="material" type="button">
                            <span class="material-symbols-outlined" style="font-size: 1rem; vertical-align: middle;">search</span> Material
                        </button>
                    </div>
                </div>

                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text bg-white text-muted"><i class="fas fa-keyboard me-1"></i> Input Manual</span>
                    <input type="text" class="form-control" id="customIconInput" placeholder="Ketik class FA (mis. fas fa-bell) atau nama Material (mis. bell)">
                    <button class="btn btn-outline-primary" type="button" id="applyCustomIconBtn">Gunakan</button>
                </div>

                <div class="icon-counter" id="iconCounter">Menampilkan <span id="iconCount">0</span> icon</div>

                <div class="icon-grid" id="iconGrid">
                </div>

                <div class="icon-empty" id="iconEmpty" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>Tidak ada icon yang ditemukan</p>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" id="cancelIconBtn">Batal</button>
                <button class="btn btn-primary" id="confirmIconBtn">
                    <i class="fas fa-check me-2"></i> Pilih Icon
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    // ===== DATA ICON =====
    const ICON_DATA = {
        fa: [{
                value: 'fas fa-th-large',
                label: 'Dashboard',
                group: 'Dashboard'
            },
            {
                value: 'fas fa-chart-line',
                label: 'Chart Line',
                group: 'Dashboard'
            },
            {
                value: 'fas fa-chart-bar',
                label: 'Chart Bar',
                group: 'Dashboard'
            },
            {
                value: 'fas fa-chart-pie',
                label: 'Chart Pie',
                group: 'Dashboard'
            },
            {
                value: 'fas fa-shopping-cart',
                label: 'Cart',
                group: 'E-commerce'
            },
            {
                value: 'fas fa-shopping-bag',
                label: 'Bag',
                group: 'E-commerce'
            },
            {
                value: 'fas fa-store',
                label: 'Store',
                group: 'E-commerce'
            },
            {
                value: 'fas fa-tags',
                label: 'Tags',
                group: 'E-commerce'
            },
            {
                value: 'fas fa-box',
                label: 'Box',
                group: 'E-commerce'
            },
            {
                value: 'fas fa-cube',
                label: 'Cube',
                group: 'E-commerce'
            },
            {
                value: 'fas fa-users',
                label: 'Users',
                group: 'Users'
            },
            {
                value: 'fas fa-user',
                label: 'User',
                group: 'Users'
            },
            {
                value: 'fas fa-user-plus',
                label: 'Add User',
                group: 'Users'
            },
            {
                value: 'fas fa-user-cog',
                label: 'User Settings',
                group: 'Users'
            },
            {
                value: 'fas fa-user-shield',
                label: 'User Shield',
                group: 'Users'
            },
            {
                value: 'fas fa-id-card',
                label: 'ID Card',
                group: 'Users'
            },
            {
                value: 'fas fa-cog',
                label: 'Settings',
                group: 'Settings'
            },
            {
                value: 'fas fa-sliders-h',
                label: 'Sliders',
                group: 'Settings'
            },
            {
                value: 'fas fa-tools',
                label: 'Tools',
                group: 'Settings'
            },
            {
                value: 'fas fa-edit',
                label: 'Edit',
                group: 'Settings'
            },
            {
                value: 'fas fa-plus',
                label: 'Plus',
                group: 'Settings'
            },
            {
                value: 'fas fa-minus',
                label: 'Minus',
                group: 'Settings'
            },
            {
                value: 'fas fa-times',
                label: 'Close',
                group: 'Settings'
            },
            {
                value: 'fas fa-check',
                label: 'Check',
                group: 'Settings'
            },
            {
                value: 'fas fa-search',
                label: 'Search',
                group: 'Settings'
            },
            {
                value: 'fas fa-filter',
                label: 'Filter',
                group: 'Settings'
            },
            {
                value: 'fas fa-sync',
                label: 'Sync',
                group: 'Settings'
            },
            {
                value: 'fas fa-clock',
                label: 'Clock',
                group: 'Settings'
            },
            {
                value: 'fas fa-calendar',
                label: 'Calendar',
                group: 'Settings'
            },
            {
                value: 'fas fa-bell',
                label: 'Bell',
                group: 'Settings'
            },
            {
                value: 'fas fa-envelope',
                label: 'Mail',
                group: 'Settings'
            },
            {
                value: 'fas fa-file',
                label: 'File',
                group: 'Settings'
            },
            {
                value: 'fas fa-truck',
                label: 'Truck',
                group: 'Shipping'
            },
            {
                value: 'fas fa-shipping-fast',
                label: 'Fast Shipping',
                group: 'Shipping'
            },
            {
                value: 'fas fa-route',
                label: 'Route',
                group: 'Shipping'
            },
            {
                value: 'fas fa-map-marker-alt',
                label: 'Location',
                group: 'Shipping'
            },
            {
                value: 'fas fa-warehouse',
                label: 'Warehouse',
                group: 'Shipping'
            },
            {
                value: 'fas fa-home',
                label: 'Home',
                group: 'General'
            },
            {
                value: 'fas fa-info-circle',
                label: 'Info',
                group: 'General'
            },
            {
                value: 'fas fa-question-circle',
                label: 'Help',
                group: 'General'
            },
            {
                value: 'fas fa-exclamation-triangle',
                label: 'Warning',
                group: 'General'
            },
            {
                value: 'fas fa-eye',
                label: 'Eye',
                group: 'General'
            },
            {
                value: 'fas fa-download',
                label: 'Download',
                group: 'General'
            },
            {
                value: 'fas fa-upload',
                label: 'Upload',
                group: 'General'
            },
            {
                value: 'fas fa-print',
                label: 'Print',
                group: 'General'
            },
            {
                value: 'fas fa-share',
                label: 'Share',
                group: 'General'
            },
            {
                value: 'fas fa-link',
                label: 'Link',
                group: 'General'
            },
            {
                value: 'fas fa-credit-card',
                label: 'Credit Card',
                group: 'Payments'
            },
            {
                value: 'fas fa-money-bill-wave',
                label: 'Money',
                group: 'Payments'
            },
            {
                value: 'fas fa-coins',
                label: 'Coins',
                group: 'Payments'
            },
            {
                value: 'fas fa-wallet',
                label: 'Wallet',
                group: 'Payments'
            },
        ],
        material: [{
                value: 'dashboard',
                label: 'Dashboard',
                group: 'Dashboard'
            },
            {
                value: 'analytics',
                label: 'Analytics',
                group: 'Dashboard'
            },
            {
                value: 'trending_up',
                label: 'Trending Up',
                group: 'Dashboard'
            },
            {
                value: 'assessment',
                label: 'Assessment',
                group: 'Dashboard'
            },
            {
                value: 'shopping_cart',
                label: 'Cart',
                group: 'E-commerce'
            },
            {
                value: 'shopping_bag',
                label: 'Bag',
                group: 'E-commerce'
            },
            {
                value: 'store',
                label: 'Store',
                group: 'E-commerce'
            },
            {
                value: 'inventory',
                label: 'Inventory',
                group: 'E-commerce'
            },
            {
                value: 'category',
                label: 'Category',
                group: 'E-commerce'
            },
            {
                value: 'sell',
                label: 'Sell',
                group: 'E-commerce'
            },
            {
                value: 'people',
                label: 'People',
                group: 'Users'
            },
            {
                value: 'person',
                label: 'Person',
                group: 'Users'
            },
            {
                value: 'person_add',
                label: 'Add Person',
                group: 'Users'
            },
            {
                value: 'manage_accounts',
                label: 'Manage Accounts',
                group: 'Users'
            },
            {
                value: 'badge',
                label: 'Badge',
                group: 'Users'
            },
            {
                value: 'settings',
                label: 'Settings',
                group: 'Settings'
            },
            {
                value: 'build',
                label: 'Tools',
                group: 'Settings'
            },
            {
                value: 'edit',
                label: 'Edit',
                group: 'Settings'
            },
            {
                value: 'add',
                label: 'Add',
                group: 'Settings'
            },
            {
                value: 'remove',
                label: 'Remove',
                group: 'Settings'
            },
            {
                value: 'close',
                label: 'Close',
                group: 'Settings'
            },
            {
                value: 'check',
                label: 'Check',
                group: 'Settings'
            },
            {
                value: 'search',
                label: 'Search',
                group: 'Settings'
            },
            {
                value: 'filter_alt',
                label: 'Filter',
                group: 'Settings'
            },
            {
                value: 'sync',
                label: 'Sync',
                group: 'Settings'
            },
            {
                value: 'schedule',
                label: 'Schedule',
                group: 'Settings'
            },
            {
                value: 'calendar_today',
                label: 'Calendar',
                group: 'Settings'
            },
            {
                value: 'notifications',
                label: 'Notifications',
                group: 'Settings'
            },
            {
                value: 'email',
                label: 'Email',
                group: 'Settings'
            },
            {
                value: 'description',
                label: 'Description',
                group: 'Settings'
            },
            {
                value: 'local_shipping',
                label: 'Shipping',
                group: 'Shipping'
            },
            {
                value: 'delivery_dining',
                label: 'Delivery',
                group: 'Shipping'
            },
            {
                value: 'route',
                label: 'Route',
                group: 'Shipping'
            },
            {
                value: 'location_on',
                label: 'Location',
                group: 'Shipping'
            },
            {
                value: 'warehouse',
                label: 'Warehouse',
                group: 'Shipping'
            },
            {
                value: 'home',
                label: 'Home',
                group: 'General'
            },
            {
                value: 'info',
                label: 'Info',
                group: 'General'
            },
            {
                value: 'help',
                label: 'Help',
                group: 'General'
            },
            {
                value: 'warning',
                label: 'Warning',
                group: 'General'
            },
            {
                value: 'visibility',
                label: 'View',
                group: 'General'
            },
            {
                value: 'download',
                label: 'Download',
                group: 'General'
            },
            {
                value: 'upload',
                label: 'Upload',
                group: 'General'
            },
            {
                value: 'print',
                label: 'Print',
                group: 'General'
            },
            {
                value: 'share',
                label: 'Share',
                group: 'General'
            },
            {
                value: 'link',
                label: 'Link',
                group: 'General'
            },
            {
                value: 'credit_card',
                label: 'Credit Card',
                group: 'Payments'
            },
            {
                value: 'payments',
                label: 'Payments',
                group: 'Payments'
            },
            {
                value: 'account_balance',
                label: 'ChartOfAccount Balance',
                group: 'Payments'
            },
            {
                value: 'wallet',
                label: 'Wallet',
                group: 'Payments'
            },
        ]
    };

    // ===== STATE ICON PICKER =====
    let currentType = 'fa';
    let selectedIcon = {
        value: 'fas fa-th-large',
        label: 'Dashboard',
        type: 'fa'
    };
    let searchQuery = '';
    let iconPickerInstance = null;

    // ===== DOM REFS ICON PICKER =====
    const grid = document.getElementById('iconGrid');
    const searchInput = document.getElementById('iconSearchInput');
    const emptyState = document.getElementById('iconEmpty');
    const counter = document.getElementById('iconCount');
    const customIconInput = document.getElementById('customIconInput');
    const applyCustomIconBtn = document.getElementById('applyCustomIconBtn');

    // ===== RENDER ICONS =====
    function renderIcons() {
        if (!grid) return;
        const icons = ICON_DATA[currentType] || [];
        const query = searchQuery.toLowerCase().trim();

        let filtered = icons;
        if (query) {
            filtered = icons.filter(icon =>
                icon.label.toLowerCase().includes(query) ||
                icon.value.toLowerCase().includes(query) ||
                (icon.group && icon.group.toLowerCase().includes(query))
            );
        }

        if (counter) counter.textContent = filtered.length;

        if (filtered.length === 0) {
            grid.innerHTML = '';
            if (emptyState) emptyState.style.display = 'block';
            return;
        }
        if (emptyState) emptyState.style.display = 'none';

        let html = '';
        filtered.forEach(icon => {
            const isSelected = selectedIcon.value === icon.value && currentType === selectedIcon.type;
            const iconHtml = currentType === 'fa' ?
                `<i class="${icon.value}"></i>` :
                `<span class="material-symbols-outlined">${icon.value}</span>`;

            html += `
                <div class="icon-item ${isSelected ? 'selected' : ''}" 
                     data-value="${icon.value}" 
                     data-label="${icon.label}"
                     data-type="${currentType}">
                    ${iconHtml}
                    <span class="icon-label">${icon.label}</span>
                    <span class="check-mark"><i class="fas fa-check"></i></span>
                </div>
            `;
        });

        grid.innerHTML = html;

        grid.querySelectorAll('.icon-item').forEach(el => {
            el.addEventListener('click', function() {
                const value = this.dataset.value;
                const label = this.dataset.label;
                const type = this.dataset.type;

                grid.querySelectorAll('.icon-item').forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');

                selectedIcon = { value, label, type };
                if (customIconInput) customIconInput.value = value;
            });
        });
    }

    // ===== UPDATE FORM PREVIEW (EXPORT KE WINDOW) ===== 
    window.updateIconFormPreview = function(icon) {
        if (!icon) return;

        const type = icon.type || 'fa';
        const value = icon.value || (type === 'fa' ? 'fas fa-th-large' : 'dashboard');
        const label = icon.label || value;

        const previewDisplay = document.getElementById('previewIconDisplay');
        const previewName = document.getElementById('previewIconName');
        const previewClass = document.getElementById('previewIconClass');
        const previewType = document.getElementById('previewIconType');
        const resultDisplay = document.getElementById('resultIconDisplay');
        const resultCode = document.getElementById('resultIconCode');
        const iconTypeInput = document.getElementById('menu_icon_type');
        const iconInput = document.getElementById('menu_icon');

        if (iconTypeInput) iconTypeInput.value = type;
        if (iconInput) iconInput.value = value;

        if (previewDisplay) {
            if (type === 'fa') {
                previewDisplay.innerHTML = `<i class="${value}"></i>`;
            } else {
                previewDisplay.innerHTML = `<span class="material-symbols-outlined">${value}</span>`;
            }
        }

        if (previewName) previewName.textContent = label;
        if (previewClass) previewClass.textContent = value;
        if (previewType) {
            previewType.textContent = type === 'fa' ? 'Font Awesome' : 'Material Symbols';
            previewType.className = `icon-type-badge badge ${type === 'fa' ? 'bg-primary-subtle text-primary' : 'bg-info-subtle text-info'}`;
        }

        if (resultDisplay) {
            if (type === 'fa') {
                resultDisplay.innerHTML = `<i class="${value}"></i>`;
            } else {
                resultDisplay.innerHTML = `<span class="material-symbols-outlined">${value}</span>`;
            }
        }
        if (resultCode) {
            resultCode.textContent = `icon_type: ${type} | icon: ${value}`;
        }

        currentType = type;
        selectedIcon = { type, value, label };
    };

    // ===== FUNGSI UNTUK BUKA ICON PICKER =====
    function openIconPicker() {
        const iconTypeInput = document.getElementById('menu_icon_type');
        const iconInput = document.getElementById('menu_icon');

        const type = (iconTypeInput && iconTypeInput.value) ? iconTypeInput.value : 'fa';
        const val = (iconInput && iconInput.value) ? iconInput.value : (type === 'fa' ? 'fas fa-th-large' : 'dashboard');

        currentType = type;
        selectedIcon = { type, value: val, label: val };

        const icons = ICON_DATA[currentType] || [];
        const found = icons.find(i => i.value === val);
        if (found) {
            selectedIcon.label = found.label;
        }

        document.querySelectorAll('.btn-type').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.type === currentType);
        });

        if (searchInput) searchInput.value = '';
        searchQuery = '';

        if (customIconInput) customIconInput.value = val;

        renderIcons();

        if (!iconPickerInstance) {
            iconPickerInstance = new bootstrap.Modal(document.getElementById('iconPickerModal'), {
                backdrop: 'static',
                keyboard: true
            });
        }
        iconPickerInstance.show();
    }

    // ===== EVENT LISTENERS ICON PICKER =====

    // Type selector (FA / Material)
    document.querySelectorAll('.btn-type').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.btn-type').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentType = this.dataset.type;

            // Jika icon saat ini tidak bertipe sama, berikan icon default untuk type yang dipilih
            if (selectedIcon.type !== currentType) {
                const defaultIcon = (ICON_DATA[currentType] && ICON_DATA[currentType][0]) ?
                    ICON_DATA[currentType][0] :
                    { value: currentType === 'fa' ? 'fas fa-th-large' : 'dashboard', label: 'Dashboard' };
                selectedIcon = {
                    type: currentType,
                    value: defaultIcon.value,
                    label: defaultIcon.label
                };
            }

            if (customIconInput) customIconInput.value = selectedIcon.value;
            renderIcons();
        });
    });

    // Search input
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchQuery = this.value;
            renderIcons();
        });
    }

    // Manual input button
    if (applyCustomIconBtn && customIconInput) {
        applyCustomIconBtn.addEventListener('click', function() {
            const val = customIconInput.value.trim();
            if (!val) return;

            // Auto switch type jika class font-awesome terdeteksi
            if (val.startsWith('fa ') || val.startsWith('fas ') || val.startsWith('far ') || val.startsWith('fab ') || val.startsWith('fa-')) {
                currentType = 'fa';
            }

            selectedIcon = {
                type: currentType,
                value: val,
                label: val
            };

            document.querySelectorAll('.btn-type').forEach(b => b.classList.toggle('active', b.dataset.type === currentType));
            if (grid) grid.querySelectorAll('.icon-item').forEach(i => i.classList.remove('selected'));
            window.updateIconFormPreview(selectedIcon);
            if (iconPickerInstance) iconPickerInstance.hide();
        });
    }

    // Confirm button
    const confirmBtn = document.getElementById('confirmIconBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (selectedIcon && selectedIcon.value) {
                window.updateIconFormPreview(selectedIcon);
            }
            if (iconPickerInstance) {
                iconPickerInstance.hide();
            }
        });
    }

    // Cancel button
    const cancelBtn = document.getElementById('cancelIconBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            if (iconPickerInstance) {
                iconPickerInstance.hide();
            }
        });
    }

    // Cleanup backdrops on page unload
    window.addEventListener('beforeunload', function() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
    });

    // Inisialisasi awal saat load
    document.addEventListener('DOMContentLoaded', function() {
        const previewBox = document.getElementById('iconPreviewBox');
        if (previewBox) {
            previewBox.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openIconPicker();
            });
        }

        // Tampilkan preview awal bawaan form
        const initialType = document.getElementById('menu_icon_type')?.value || 'fa';
        const initialVal = document.getElementById('menu_icon')?.value || 'fas fa-th-large';
        window.updateIconFormPreview({ type: initialType, value: initialVal, label: 'Dashboard' });
    });
</script>
