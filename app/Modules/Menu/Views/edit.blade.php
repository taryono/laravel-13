{{ Form::model($menu, ['method' => 'PUT', 'route' => ['menu.update', $menu->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
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
    <input type="text" class="form-control" name="id_text" id="id_text" placeholder="Judul indonesia">
</div>
<div class="col-md-6">
    <label class="form-label">English</label>
    <input type="text" class="form-control" name="en_text" id="en_text" placeholder="Judul english">
</div>

<div class="col-md-6">
    <label class="form-label">
        Slug
        <small class="text-muted">(kosongkan untuk auto-generate dari title)</small>
    </label>
    <input type="text" class="form-control" name="slug" id="menu_slug" placeholder="mis. master-produk">
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
    <select class="form-select" name="parent_id" id="parent_id">
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
{{ Form::close() }}
<script>
    window.MENU_PARENT_OPTIONS = @json($parentOptions);
    populateSelect('menu_parent_id', window.MENU_PARENT_OPTIONS, 'id', 'label', '— Tidak ada (root) —');
</script>
