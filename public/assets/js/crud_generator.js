 
            // ===== FUNGSI UTILITY =====
            function fillExample(model, columns) {
                document.getElementById('model_name').value = model;
                document.getElementById('columns').value = columns;
                document.getElementById('crudForm').scrollIntoView({
                    behavior: 'smooth'
                });
            }

            function openMenuModal(model) {
                // Buka modal menu dengan data dari model
                document.getElementById('menu_title').value = model;
                document.getElementById('menu_slug').value = '';
                const modal = new bootstrap.Modal(document.getElementById('menuModal'));
                modal.show();
            }

            // ===== TOGGLE PARENT MENU =====
            document.getElementById('create_menu').addEventListener('change', function() {
                document.getElementById('parentMenuGroup').style.display = this.checked ? 'block' : 'none';
            });

            // ===== CONFIRM SUBMIT CRUD =====
            document.getElementById('crudForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const model = document.getElementById('model_name').value;
                const columns = document.getElementById('columns').value;
                const createMenu = document.getElementById('create_menu').checked;

                let command = `php artisan make:crud-yajra ${model} --columns=${columns}`;
                if (!createMenu) command += ' --no-menu';

                if (confirm(`Execute command:\n\n${command}\n\nLanjutkan?`)) {
                    this.submit();
                }
            });

            // ===== TABLE EXAMPLE CLICK =====
            $(document).on('click', '.table-example', function() {
                const table = $(this).data('label').trim();
                const columns = $(this).data('columns');
                fillExample(table, columns);
            });

            // ===== MENU EXAMPLE CLICK =====
            $(document).on('click', '.menu-example', function() {
                const table = $(this).data('label').trim();
                openMenuModal(table);
            }); 
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
                        label: 'Account Balance',
                        group: 'Payments'
                    },
                    {
                        value: 'wallet',
                        label: 'Wallet',
                        group: 'Payments'
                    },
                ]
            };

            // ===== ICON PICKER CLASS =====
            class IconPicker {
                constructor(pickerId, modalId) {
                    this.pickerId = pickerId;
                    this.modalId = modalId;
                    this.currentType = 'fa';
                    this.searchQuery = '';
                    this.selectedIcon = {
                        value: 'fas fa-th-large',
                        label: 'Dashboard',
                        type: 'fa'
                    };
                    this.modalInstance = null;

                    this.init();
                }

                init() {
                    // Bind elements
                    this.previewBox = document.getElementById(`${this.pickerId}-preview-box`);
                    this.typeInput = document.getElementById(`${this.pickerId}-type`);
                    this.valueInput = document.getElementById(`${this.pickerId}-value`);
                    this.previewDisplay = document.getElementById(`${this.pickerId}-preview-display`);
                    this.previewName = document.getElementById(`${this.pickerId}-preview-name`);
                    this.previewClass = document.getElementById(`${this.pickerId}-preview-class`);
                    this.previewType = document.getElementById(`${this.pickerId}-preview-type`);
                    this.resultDisplay = document.getElementById(`${this.pickerId}-result-display`);
                    this.resultCode = document.getElementById(`${this.pickerId}-result-code`);

                    // Modal elements
                    this.modalElement = document.getElementById(this.modalId);
                    this.grid = document.getElementById(`${this.modalId}-grid`);
                    this.searchInput = document.getElementById(`${this.modalId}-search`);
                    this.emptyState = document.getElementById(`${this.modalId}-empty`);
                    this.counter = document.getElementById(`${this.modalId}-count`);
                    this.confirmBtn = document.getElementById(`${this.modalId}-confirm`);
                    this.cancelBtn = document.getElementById(`${this.modalId}-cancel`);

                    // Restore saved value
                    if (this.valueInput && this.valueInput.value) {
                        this.selectedIcon.value = this.valueInput.value;
                        const icons = ICON_DATA[this.currentType] || [];
                        const found = icons.find(i => i.value === this.valueInput.value);
                        if (found) {
                            this.selectedIcon.label = found.label;
                        }
                    }
                    if (this.typeInput && this.typeInput.value) {
                        this.currentType = this.typeInput.value;
                        this.selectedIcon.type = this.currentType;
                    }

                    // Update preview
                    this.updatePreview();

                    // Bind events
                    if (this.previewBox) {
                        this.previewBox.addEventListener('click', () => this.openPicker());
                    }

                    // Modal events
                    document.querySelectorAll(`#${this.modalId} .btn-type`).forEach(btn => {
                        btn.addEventListener('click', () => {
                            document.querySelectorAll(`#${this.modalId} .btn-type`).forEach(b => b.classList
                                .remove('active'));
                            btn.classList.add('active');
                            this.currentType = btn.dataset.type;
                            this.renderIcons();
                        });
                    });

                    if (this.searchInput) {
                        this.searchInput.addEventListener('input', () => {
                            this.searchQuery = this.searchInput.value;
                            this.renderIcons();
                        });
                    }

                    if (this.confirmBtn) {
                        this.confirmBtn.addEventListener('click', () => {
                            this.confirmSelection();
                        });
                    }

                    if (this.cancelBtn) {
                        this.cancelBtn.addEventListener('click', () => {
                            if (this.modalInstance) this.modalInstance.hide();
                        });
                    }

                    // Auto-close listeners for modal
                    this.modalElement.addEventListener('hidden.bs.modal', () => {
                        // Cleanup
                    });

                    // Initialize modal instance
                    if (this.modalElement) {
                        this.modalInstance = new bootstrap.Modal(this.modalElement, {
                            backdrop: 'static',
                            keyboard: true
                        });
                    }

                    // Initial render
                    this.renderIcons();
                }

                openPicker() {
                    // Reset search
                    if (this.searchInput) this.searchInput.value = '';
                    this.searchQuery = '';

                    // Set current type
                    if (this.typeInput && this.typeInput.value) {
                        this.currentType = this.typeInput.value;
                    }

                    // Update type buttons
                    document.querySelectorAll(`#${this.modalId} .btn-type`).forEach(btn => {
                        btn.classList.toggle('active', btn.dataset.type === this.currentType);
                    });

                    // Restore selected icon
                    if (this.valueInput && this.valueInput.value) {
                        this.selectedIcon.value = this.valueInput.value;
                        const icons = ICON_DATA[this.currentType] || [];
                        const found = icons.find(i => i.value === this.valueInput.value);
                        if (found) {
                            this.selectedIcon.label = found.label;
                        }
                    }

                    this.renderIcons();

                    if (this.modalInstance) {
                        this.modalInstance.show();
                    }
                }

                renderIcons() {
                    const icons = ICON_DATA[this.currentType] || [];
                    const query = this.searchQuery.toLowerCase().trim();

                    let filtered = icons;
                    if (query) {
                        filtered = icons.filter(icon =>
                            icon.label.toLowerCase().includes(query) ||
                            icon.value.toLowerCase().includes(query) ||
                            (icon.group && icon.group.toLowerCase().includes(query))
                        );
                    }

                    if (this.counter) {
                        this.counter.textContent = filtered.length;
                    }

                    if (filtered.length === 0) {
                        if (this.grid) this.grid.innerHTML = '';
                        if (this.emptyState) this.emptyState.style.display = 'block';
                        return;
                    }
                    if (this.emptyState) this.emptyState.style.display = 'none';

                    let html = '';
                    filtered.forEach(icon => {
                        const isSelected = this.selectedIcon.value === icon.value &&
                            this.currentType === this.selectedIcon.type;
                        const iconHtml = this.currentType === 'fa' ?
                            `<i class="${icon.value}"></i>` :
                            `<span class="material-symbols-outlined">${icon.value}</span>`;

                        html += `
                        <div class="icon-item ${isSelected ? 'selected' : ''}" 
                            data-value="${icon.value}" 
                            data-label="${icon.label}"
                            data-type="${this.currentType}">
                            ${iconHtml}
                            <span class="icon-label">${icon.label}</span>
                            <span class="check-mark"><i class="fas fa-check"></i></span>
                        </div>
                    `;
                    });

                    if (this.grid) {
                        this.grid.innerHTML = html;

                        this.grid.querySelectorAll('.icon-item').forEach(el => {
                            el.addEventListener('click', () => {
                                this.grid.querySelectorAll('.icon-item').forEach(i => i.classList.remove(
                                    'selected'));
                                el.classList.add('selected');

                                this.selectedIcon = {
                                    value: el.dataset.value,
                                    label: el.dataset.label,
                                    type: el.dataset.type
                                };
                            });
                        });
                    }
                }

                confirmSelection() {
                    if (this.selectedIcon) {
                        this.updatePreview();
                        if (this.modalInstance) {
                            this.modalInstance.hide();
                        }
                    }
                }

                updatePreview() {
                    const {
                        value,
                        label,
                        type
                    } = this.selectedIcon;

                    // Update inputs
                    if (this.typeInput) this.typeInput.value = type;
                    if (this.valueInput) this.valueInput.value = value;

                    // Update preview display
                    if (this.previewDisplay) {
                        if (type === 'fa') {
                            this.previewDisplay.innerHTML = `<i class="${value}"></i>`;
                        } else {
                            this.previewDisplay.innerHTML = `<span class="material-symbols-outlined">${value}</span>`;
                        }
                    }

                    if (this.previewName) this.previewName.textContent = label || value;
                    if (this.previewClass) this.previewClass.textContent = value;
                    if (this.previewType) {
                        this.previewType.textContent = type === 'fa' ? 'Font Awesome' : 'Material Symbols';
                    }

                    if (this.resultDisplay) {
                        if (type === 'fa') {
                            this.resultDisplay.innerHTML = `<i class="${value}"></i>`;
                        } else {
                            this.resultDisplay.innerHTML = `<span class="material-symbols-outlined">${value}</span>`;
                        }
                    }

                    if (this.resultCode) {
                        this.resultCode.textContent = `icon_type: ${type} | icon: ${value}`;
                    }
                }
            }

            // ===== INITIALIZE ICON PICKERS =====
            document.addEventListener('DOMContentLoaded', function() {
                // CRUD Icon Picker
                new IconPicker('crud-icon-picker', 'icon-picker-modal');

                // Menu Icon Picker
                new IconPicker('menu-icon-picker', 'icon-picker-modal');

                // Cleanup modal backdrop on page unload
                window.addEventListener('beforeunload', function() {
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => backdrop.remove());
                    document.body.classList.remove('modal-open');
                });
            });
            var submitMenuForm = document.getElementById('submitMenuForm');
            submitMenuForm.addEventListener('click', function(e) {
                e.preventDefault();
                var form = document.getElementById('menuForm');
                console.log(form, AppCore.ajax);
                AppCore.ajax.request({
                    url: form.action,
                    method: 'POST',
                    data: new FormData(form),
                    success: function(response) {
                        if (response.status == 'success') {

                        }
                    }
                })
            });
            $(document).ready(function() {
                // Saat mengetik di input pencarian
                $('#search_module').on('input', function() {
                    let keyword = $(this).val().trim().toLowerCase();

                    // Loop setiap tombol tabel
                    $('#table-list button').each(function() {
                        let label = $(this).data('label') || '';
                        let tableName = $(this).data('table') || '';
                        let text = (label + ' ' + tableName).toLowerCase();

                        // Tampilkan/sembunyikan sesuai kecocokan
                        if (text.indexOf(keyword) !== -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
            }); 