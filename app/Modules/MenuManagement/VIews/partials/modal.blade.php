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
                        <label class="form-label">Icon</label>
                        <input type="text" class="form-control" name="icon" id="menu_icon" placeholder="mis. dashboard">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Icon Type</label>
                        <select class="form-select" name="icon_type" id="menu_icon_type">
                            <option value="material">Material</option>
                            <option value="fa">Font Awesome</option>
                        </select>
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