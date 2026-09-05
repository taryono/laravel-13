{{-- app/Modules/MenuManagement/VIews/partials/modal.blade.php --}}
<div class="modal fade" id="menuModal" tabindex="-1">
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

                   <div class="col-md-4">
                    <label class="form-label">Icon Type</label>
                    <select class="form-select" name="icon_type" id="menu_icon_type">
                        <option value="fa">Font Awesome</option>
                        <option value="material">Material Symbols</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Icon</label>
                    <div class="input-group">
                        <span class="input-group-text" id="icon_preview">
                            <i class="fas fa-th-large"></i>
                        </span>
                        <select class="form-select" name="icon" id="menu_icon">
                            <!-- Font Awesome Icons -->
                            <optgroup label="📊 Dashboard & Analytics">
                                <option value="fas fa-th-large">📊 Dashboard</option>
                                <option value="fas fa-chart-line">📈 Chart Line</option>
                                <option value="fas fa-chart-bar">📊 Chart Bar</option>
                                <option value="fas fa-chart-pie">📊 Chart Pie</option>
                                <option value="fas fa-analytics">📊 Analytics</option>
                            </optgroup>
                            
                            <optgroup label="🛒 E-commerce & Orders">
                                <option value="fas fa-shopping-cart">🛒 Shopping Cart</option>
                                <option value="fas fa-shopping-bag">🛍️ Shopping Bag</option>
                                <option value="fas fa-shopping-basket">🧺 Shopping Basket</option>
                                <option value="fas fa-store">🏪 Store</option>
                                <option value="fas fa-tags">🏷️ Tags</option>
                                <option value="fas fa-tag">🏷️ Tag</option>
                                <option value="fas fa-box">📦 Box</option>
                                <option value="fas fa-boxes">📦 Boxes</option>
                                <option value="fas fa-archive">📚 Archive</option>
                                <option value="fas fa-cube">🧊 Cube</option>
                                <option value="fas fa-cubes">🧊 Cubes</option>
                            </optgroup>
                            
                            <optgroup label="🚚 Shipping & Logistics">
                                <option value="fas fa-truck">🚚 Truck</option>
                                <option value="fas fa-shipping-fast">🚚 Shipping Fast</option>
                                <option value="fas fa-route">🗺️ Route</option>
                                <option value="fas fa-map-marker-alt">📍 Map Marker</option>
                                <option value="fas fa-location-dot">📍 Location Dot</option>
                                <option value="fas fa-dolly">🛒 Dolly</option>
                                <option value="fas fa-warehouse">🏭 Warehouse</option>
                            </optgroup>
                            
                            <optgroup label="👥 Users & Management">
                                <option value="fas fa-users">👥 Users</option>
                                <option value="fas fa-user">👤 User</option>
                                <option value="fas fa-user-plus">👤 User Plus</option>
                                <option value="fas fa-user-cog">⚙️ User Settings</option>
                                <option value="fas fa-user-shield">🛡️ User Shield</option>
                                <option value="fas fa-user-tie">💼 User Tie</option>
                                <option value="fas fa-id-card">🪪 ID Card</option>
                                <option value="fas fa-id-badge">🪪 ID Badge</option>
                            </optgroup>
                            
                            <optgroup label="⚙️ Settings & Tools">
                                <option value="fas fa-cog">⚙️ Settings</option>
                                <option value="fas fa-sliders-h">🎚️ Sliders</option>
                                <option value="fas fa-tools">🔧 Tools</option>
                                <option value="fas fa-wrench">🔧 Wrench</option>
                                <option value="fas fa-edit">✏️ Edit</option>
                                <option value="fas fa-pen">🖊️ Pen</option>
                                <option value="fas fa-pencil-alt">✏️ Pencil</option>
                                <option value="fas fa-plus">➕ Plus</option>
                                <option value="fas fa-minus">➖ Minus</option>
                                <option value="fas fa-times">✖️ Times</option>
                                <option value="fas fa-check">✅ Check</option>
                                <option value="fas fa-search">🔍 Search</option>
                                <option value="fas fa-filter">🔽 Filter</option>
                                <option value="fas fa-sync">🔄 Sync</option>
                                <option value="fas fa-sync-alt">🔄 Sync Alt</option>
                                <option value="fas fa-clock">🕐 Clock</option>
                                <option value="fas fa-calendar">📅 Calendar</option>
                                <option value="fas fa-calendar-alt">📅 Calendar Alt</option>
                                <option value="fas fa-bell">🔔 Bell</option>
                                <option value="fas fa-envelope">✉️ Envelope</option>
                                <option value="fas fa-inbox">📥 Inbox</option>
                                <option value="fas fa-file">📄 File</option>
                                <option value="fas fa-file-alt">📄 File Alt</option>
                                <option value="fas fa-file-pdf">📄 PDF</option>
                            </optgroup>
                            
                            <optgroup label="📱 General">
                                <option value="fas fa-home">🏠 Home</option>
                                <option value="fas fa-info-circle">ℹ️ Info</option>
                                <option value="fas fa-question-circle">❓ Help</option>
                                <option value="fas fa-exclamation-triangle">⚠️ Warning</option>
                                <option value="fas fa-check-circle">✅ Check Circle</option>
                                <option value="fas fa-times-circle">❌ Times Circle</option>
                                <option value="fas fa-eye">👁️ Eye</option>
                                <option value="fas fa-eye-slash">🚫 Eye Slash</option>
                                <option value="fas fa-download">⬇️ Download</option>
                                <option value="fas fa-upload">⬆️ Upload</option>
                                <option value="fas fa-print">🖨️ Print</option>
                                <option value="fas fa-share">↗️ Share</option>
                                <option value="fas fa-link">🔗 Link</option>
                                <option value="fas fa-external-link-alt">🔗 External Link</option>
                            </optgroup>
                            
                            <optgroup label="💳 Payments">
                                <option value="fas fa-credit-card">💳 Credit Card</option>
                                <option value="fas fa-money-bill-wave">💰 Money Bill</option>
                                <option value="fas fa-money-bill">💰 Money Bill</option>
                                <option value="fas fa-coins">🪙 Coins</option>
                                <option value="fas fa-wallet">👛 Wallet</option>
                                <option value="fas fa-hand-holding-usd">🤝 Holding USD</option>
                            </optgroup>
                            
                            <!-- Material Icons -->
                            <optgroup label="🔄 Material Symbols (Switch to Material type)">
                                <option value="dashboard">dashboard</option>
                                <option value="shopping_cart">shopping_cart</option>
                                <option value="shopping_bag">shopping_bag</option>
                                <option value="store">store</option>
                                <option value="inventory">inventory</option>
                                <option value="warehouse">warehouse</option>
                                <option value="people">people</option>
                                <option value="person">person</option>
                                <option value="settings">settings</option>
                                <option value="analytics">analytics</option>
                                <option value="trending_up">trending_up</option>
                                <option value="trending_down">trending_down</option>
                                <option value="assessment">assessment</option>
                                <option value="receipt">receipt</option>
                                <option value="local_shipping">local_shipping</option>
                                <option value="delivery_dining">delivery_dining</option>
                                <option value="category">category</option>
                                <option value="inventory_2">inventory_2</option>
                                <option value="sell">sell</option>
                                <option value="payments">payments</option>
                                <option value="account_balance">account_balance</option>
                                <option value="assignment">assignment</option>
                                <option value="notifications">notifications</option>
                                <option value="email">email</option>
                                <option value="search">search</option>
                                <option value="filter_alt">filter_alt</option>
                                <option value="more_vert">more_vert</option>
                                <option value="more_horiz">more_horiz</option>
                                <option value="add">add</option>
                                <option value="remove">remove</option>
                                <option value="delete">delete</option>
                                <option value="edit">edit</option>
                                <option value="save">save</option>
                                <option value="download">download</option>
                                <option value="upload">upload</option>
                                <option value="print">print</option>
                                <option value="share">share</option>
                                <option value="link">link</option>
                                <option value="help">help</option>
                                <option value="info">info</option>
                                <option value="warning">warning</option>
                                <option value="check_circle">check_circle</option>
                                <option value="cancel">cancel</option>
                                <option value="lock">lock</option>
                                <option value="lock_open">lock_open</option>
                            </optgroup>
                        </select>
                    </div>
                    <small class="text-muted">
                        <span id="icon_name_display">Pilih icon</span>
                    </small>
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