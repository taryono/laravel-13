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
                        <label class="form-label">Nama Menu (text)</label>
                        <input type="text" class="form-control" name="text" id="menu_text" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Name (kode internal)</label>
                        <input type="text" class="form-control" name="name" id="menu_name">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control" name="url" id="menu_url">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Route name</label>
                        <input type="text" class="form-control" name="route" id="menu_route">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Icon</label>
                        <input type="text" class="form-control" name="icon" id="menu_icon" placeholder="mis. dashboard">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Icon Type</label>
                        <select class="form-select" name="icon_type" id="menu_icon_type">
                            <option value="material">Material</option>
                            <option value="fontawesome">Font Awesome</option>
                            <option value="bootstrap">Bootstrap Icons</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tampilkan di</label>
                        <select class="form-select" name="menu_type_id" id="menu_type_id">
                            <option value="2">Sidebar</option>
                            <option value="1">Topnav</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Parent Menu</label>
                        <select class="form-select" name="parent_id" id="menu_parent_id">
                            <option value="">— Tidak ada (root) —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Module (permission)</label>
                        <select class="form-select" name="module_id" id="menu_module_id">
                            <option value="">— Tidak ada —</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" id="menu_role">
                            <option value="">— Semua role (umum) —</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="is_publish" id="menu_is_publish" checked>
                            <label class="form-check-label">Publish</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="menu_is_active" checked>
                            <label class="form-check-label">Aktif</label>
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