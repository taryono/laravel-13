<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card modal-custom">
                <div class="card-header">
                    <h3 class="card-title">🚀 Generate CRUD</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                            {{ session('success') }}
                            @if (session('output'))
                                <pre class="mt-3 p-2 bg-dark text-light" style="max-height:300px;overflow:auto;">
                                    {{ session('output') }}
                                </pre>
                            @endif
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- ===== FORM GENERATE CRUD ===== -->
                    <form action="{{ route('crud_generator.generate') }}" method="POST" id="crudForm">
                        @csrf

                        <div class="form-group">
                            <label for="model_name">Nama Model <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('model_name') is-invalid @enderror"
                                id="model_name" name="model_name" placeholder="Contoh: Product, Category, User"
                                value="{{ old('model_name') }}" required>
                            <small class="form-text text-muted">Hanya huruf, tanpa spasi. Akan otomatis diubah ke StudlyCase.</small>
                            @error('model_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="columns">Kolom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('columns') is-invalid @enderror"
                                id="columns" name="columns" placeholder="Contoh: id,name,email,price,stock"
                                value="{{ old('columns', 'id,name') }}" required>
                            <small class="form-text text-muted">
                                Pisahkan dengan koma. Kolom 'id' secara default akan ditampilkan.
                                Kolom berakhiran '_id' otomatis jadi foreign key.
                            </small>
                            @error('columns')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- ===== ICON PICKER CRUD ===== -->
                        <x-icon-picker 
                            id="crud-icon-picker"
                            name="icon"
                            typeName="icon_type"
                            value="fas fa-th-large"
                            typeValue="fa"
                            label="Icon Menu"
                            :required="true"
                        />

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="create_menu" name="create_menu"
                                    value="1" {{ old('create_menu', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="create_menu">
                                    Buat Menu untuk Super Admin
                                </label>
                            </div>
                            <small class="form-text text-muted">Otomatis mendaftarkan menu di sidebar untuk role super-admin.</small>
                        </div>

                        <div class="form-group" id="parentMenuGroup" style="display:none;">
                            <label for="parent_menu">Parent Menu</label>
                            <select class="form-control" id="parent_menu" name="parent_menu">
                                <option value="">Tanpa Parent (Root)</option>
                                @foreach ($existingMenus ?? [] as $menu)
                                    <option value="{{ $menu->slug }}">{{ $menu->title }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Jadikan sub-menu dari menu yang dipilih.</small>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-play"></i> Generate CRUD
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" formaction="{{ route('crud_generator.generateAsync') }}"
                                    class="btn btn-info btn-block">
                                    <i class="fas fa-clock"></i> Generate Background
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info-circle"></i> Tips:</h5>
                            <ul class="mb-0">
                                <li>Gunakan nama model <strong>singular</strong> (Product, bukan Products)</li>
                                <li>Kolom <strong>id</strong> akan otomatis ditambahkan</li>
                                <li>Kolom <strong>created_at, updated_at</strong> otomatis dari migration</li>
                                <li>Proses akan <strong>clear cache</strong> otomatis setelah selesai</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">📊 History Generate</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history ?? [] as $item)
                                    <tr>
                                        <td>{{ $item['model'] }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item['status'] == 'success' ? 'success' : 'danger' }}">
                                                {{ $item['status'] }}
                                            </span>
                                        </td>
                                        <td>{{ $item['created_at']->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            Belum ada history
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">⚡ Module Ready</h3>
                </div>
                <div class="card-body">
                    @foreach ($tables as $table)
                        <button type="button" class="btn btn-sm btn-outline-primary btn-block mb-2 table-example"
                            data-table="{{ $table['name'] }}" data-label="{{ $table['label'] }}"
                            data-columns="{{ implode(',', $table['columns']) }}">
                            📋 {{ $table['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">⚡ Menu Ready</h3>
                </div>
                <div class="card-body">
                    @foreach ($tables as $table)
                        <button type="button" class="btn btn-sm btn-outline-primary btn-block mb-2 menu-example"
                            data-table="{{ $table['name'] }}" data-label="{{ $table['label'] }}"
                            data-columns="{{ implode(',', $table['columns']) }}">
                            📋 {{ $table['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL MENU GENERATOR ===== -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('crud_generator.menuGenerator') }}" method="POST" id="menuForm">
                @csrf
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
                        <input type="text" class="form-control" name="id_text" id="menu_id_text" placeholder="Judul indonesia">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">English</label>
                        <input type="text" class="form-control" name="en_text" id="menu_en_text"
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
                        <!-- ===== ICON PICKER MENU ===== -->
                        <x-icon-picker 
                            id="menu-icon-picker"
                            name="icon"
                            typeName="icon_type"
                            value="fas fa-th-large"
                            typeValue="fa"
                            label="Icon Menu"
                            :required="true"
                        />
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
                        <input type="number" class="form-control" name="sort_order" id="menu_sort_order" min="0">
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="menu_is_active" checked>
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

<!-- ===== ICON PICKER MODAL ===== -->
<x-icon-picker-modal id="icon-picker-modal" />

@push('scripts')
    <script>
        // ===== FUNGSI UTILITY =====
        function fillExample(model, columns) {
            document.getElementById('model_name').value = model;
            document.getElementById('columns').value = columns;
            document.getElementById('crudForm').scrollIntoView({ behavior: 'smooth' });
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
    </script>

    <!-- ===== ICON PICKER JAVASCRIPT ===== -->
    <script>
        // ===== DATA ICON =====
        const ICON_DATA = {
            fa: [
                { value: 'fas fa-th-large', label: 'Dashboard', group: 'Dashboard' },
                { value: 'fas fa-chart-line', label: 'Chart Line', group: 'Dashboard' },
                { value: 'fas fa-chart-bar', label: 'Chart Bar', group: 'Dashboard' },
                { value: 'fas fa-chart-pie', label: 'Chart Pie', group: 'Dashboard' },
                { value: 'fas fa-shopping-cart', label: 'Cart', group: 'E-commerce' },
                { value: 'fas fa-shopping-bag', label: 'Bag', group: 'E-commerce' },
                { value: 'fas fa-store', label: 'Store', group: 'E-commerce' },
                { value: 'fas fa-tags', label: 'Tags', group: 'E-commerce' },
                { value: 'fas fa-box', label: 'Box', group: 'E-commerce' },
                { value: 'fas fa-cube', label: 'Cube', group: 'E-commerce' },
                { value: 'fas fa-users', label: 'Users', group: 'Users' },
                { value: 'fas fa-user', label: 'User', group: 'Users' },
                { value: 'fas fa-user-plus', label: 'Add User', group: 'Users' },
                { value: 'fas fa-user-cog', label: 'User Settings', group: 'Users' },
                { value: 'fas fa-user-shield', label: 'User Shield', group: 'Users' },
                { value: 'fas fa-id-card', label: 'ID Card', group: 'Users' },
                { value: 'fas fa-cog', label: 'Settings', group: 'Settings' },
                { value: 'fas fa-sliders-h', label: 'Sliders', group: 'Settings' },
                { value: 'fas fa-tools', label: 'Tools', group: 'Settings' },
                { value: 'fas fa-edit', label: 'Edit', group: 'Settings' },
                { value: 'fas fa-plus', label: 'Plus', group: 'Settings' },
                { value: 'fas fa-minus', label: 'Minus', group: 'Settings' },
                { value: 'fas fa-times', label: 'Close', group: 'Settings' },
                { value: 'fas fa-check', label: 'Check', group: 'Settings' },
                { value: 'fas fa-search', label: 'Search', group: 'Settings' },
                { value: 'fas fa-filter', label: 'Filter', group: 'Settings' },
                { value: 'fas fa-sync', label: 'Sync', group: 'Settings' },
                { value: 'fas fa-clock', label: 'Clock', group: 'Settings' },
                { value: 'fas fa-calendar', label: 'Calendar', group: 'Settings' },
                { value: 'fas fa-bell', label: 'Bell', group: 'Settings' },
                { value: 'fas fa-envelope', label: 'Mail', group: 'Settings' },
                { value: 'fas fa-file', label: 'File', group: 'Settings' },
                { value: 'fas fa-truck', label: 'Truck', group: 'Shipping' },
                { value: 'fas fa-shipping-fast', label: 'Fast Shipping', group: 'Shipping' },
                { value: 'fas fa-route', label: 'Route', group: 'Shipping' },
                { value: 'fas fa-map-marker-alt', label: 'Location', group: 'Shipping' },
                { value: 'fas fa-warehouse', label: 'Warehouse', group: 'Shipping' },
                { value: 'fas fa-home', label: 'Home', group: 'General' },
                { value: 'fas fa-info-circle', label: 'Info', group: 'General' },
                { value: 'fas fa-question-circle', label: 'Help', group: 'General' },
                { value: 'fas fa-exclamation-triangle', label: 'Warning', group: 'General' },
                { value: 'fas fa-eye', label: 'Eye', group: 'General' },
                { value: 'fas fa-download', label: 'Download', group: 'General' },
                { value: 'fas fa-upload', label: 'Upload', group: 'General' },
                { value: 'fas fa-print', label: 'Print', group: 'General' },
                { value: 'fas fa-share', label: 'Share', group: 'General' },
                { value: 'fas fa-link', label: 'Link', group: 'General' },
                { value: 'fas fa-credit-card', label: 'Credit Card', group: 'Payments' },
                { value: 'fas fa-money-bill-wave', label: 'Money', group: 'Payments' },
                { value: 'fas fa-coins', label: 'Coins', group: 'Payments' },
                { value: 'fas fa-wallet', label: 'Wallet', group: 'Payments' },
            ],
            material: [
                { value: 'dashboard', label: 'Dashboard', group: 'Dashboard' },
                { value: 'analytics', label: 'Analytics', group: 'Dashboard' },
                { value: 'trending_up', label: 'Trending Up', group: 'Dashboard' },
                { value: 'assessment', label: 'Assessment', group: 'Dashboard' },
                { value: 'shopping_cart', label: 'Cart', group: 'E-commerce' },
                { value: 'shopping_bag', label: 'Bag', group: 'E-commerce' },
                { value: 'store', label: 'Store', group: 'E-commerce' },
                { value: 'inventory', label: 'Inventory', group: 'E-commerce' },
                { value: 'category', label: 'Category', group: 'E-commerce' },
                { value: 'sell', label: 'Sell', group: 'E-commerce' },
                { value: 'people', label: 'People', group: 'Users' },
                { value: 'person', label: 'Person', group: 'Users' },
                { value: 'person_add', label: 'Add Person', group: 'Users' },
                { value: 'manage_accounts', label: 'Manage Accounts', group: 'Users' },
                { value: 'badge', label: 'Badge', group: 'Users' },
                { value: 'settings', label: 'Settings', group: 'Settings' },
                { value: 'build', label: 'Tools', group: 'Settings' },
                { value: 'edit', label: 'Edit', group: 'Settings' },
                { value: 'add', label: 'Add', group: 'Settings' },
                { value: 'remove', label: 'Remove', group: 'Settings' },
                { value: 'close', label: 'Close', group: 'Settings' },
                { value: 'check', label: 'Check', group: 'Settings' },
                { value: 'search', label: 'Search', group: 'Settings' },
                { value: 'filter_alt', label: 'Filter', group: 'Settings' },
                { value: 'sync', label: 'Sync', group: 'Settings' },
                { value: 'schedule', label: 'Schedule', group: 'Settings' },
                { value: 'calendar_today', label: 'Calendar', group: 'Settings' },
                { value: 'notifications', label: 'Notifications', group: 'Settings' },
                { value: 'email', label: 'Email', group: 'Settings' },
                { value: 'description', label: 'Description', group: 'Settings' },
                { value: 'local_shipping', label: 'Shipping', group: 'Shipping' },
                { value: 'delivery_dining', label: 'Delivery', group: 'Shipping' },
                { value: 'route', label: 'Route', group: 'Shipping' },
                { value: 'location_on', label: 'Location', group: 'Shipping' },
                { value: 'warehouse', label: 'Warehouse', group: 'Shipping' },
                { value: 'home', label: 'Home', group: 'General' },
                { value: 'info', label: 'Info', group: 'General' },
                { value: 'help', label: 'Help', group: 'General' },
                { value: 'warning', label: 'Warning', group: 'General' },
                { value: 'visibility', label: 'View', group: 'General' },
                { value: 'download', label: 'Download', group: 'General' },
                { value: 'upload', label: 'Upload', group: 'General' },
                { value: 'print', label: 'Print', group: 'General' },
                { value: 'share', label: 'Share', group: 'General' },
                { value: 'link', label: 'Link', group: 'General' },
                { value: 'credit_card', label: 'Credit Card', group: 'Payments' },
                { value: 'payments', label: 'Payments', group: 'Payments' },
                { value: 'account_balance', label: 'Account Balance', group: 'Payments' },
                { value: 'wallet', label: 'Wallet', group: 'Payments' },
            ]
        };

        // ===== ICON PICKER CLASS =====
        class IconPicker {
            constructor(pickerId, modalId) {
                this.pickerId = pickerId;
                this.modalId = modalId;
                this.currentType = 'fa';
                this.searchQuery = '';
                this.selectedIcon = { value: 'fas fa-th-large', label: 'Dashboard', type: 'fa' };
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
                        document.querySelectorAll(`#${this.modalId} .btn-type`).forEach(b => b.classList.remove('active'));
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
                            this.grid.querySelectorAll('.icon-item').forEach(i => i.classList.remove('selected'));
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
                const { value, label, type } = this.selectedIcon;

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
    </script>
@endpush