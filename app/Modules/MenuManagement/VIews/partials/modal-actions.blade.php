{{-- app/Modules/MenuManagement/VIews/partials/modal-actions.blade.php --}}
<div class="modal fade" id="menuActionsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Kelola Action — <span id="menuActionsModalMenuTitle" class="text-muted"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="menu_actions_menu_id">

                {{-- Form tambah action baru --}}
                <form id="actionCreateForm" class="row g-2 align-items-end mb-3 border-bottom pb-3">
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Name (unik)</label>
                        <input type="text" class="form-control form-control-sm" name="name"
                               placeholder="mis. master-produk-create" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Display Name</label>
                        <input type="text" class="form-control form-control-sm" name="display_name"
                               placeholder="mis. Tambah Produk" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Description</label>
                        <input type="text" class="form-control form-control-sm" name="description">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <span class="material-symbols-outlined align-middle" style="font-size: 16px;">add</span>
                            Tambah
                        </button>
                    </div>
                </form>

                {{-- Daftar action milik menu ini, diisi via JS (GET actionsIndex) --}}
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Description</th>
                                <th class="text-center">Aktif</th>
                                <th>Role</th>
                                <th>User</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="menuActionsTableBody">
                            {{-- Contoh struktur satu baris (di-generate JS):
                            <tr data-action-id="1">
                                <td>master-produk-create</td>
                                <td>Tambah Produk</td>
                                <td>...</td>
                                <td class="text-center"><input type="checkbox" checked disabled></td>
                                <td>
                                    <select class="form-select form-select-sm" multiple data-role-select></select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" multiple data-user-select></select>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-save-action-access>Simpan</button>
                                    <button class="btn btn-sm btn-outline-danger" data-delete-action>Hapus</button>
                                </td>
                            </tr>
                            --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>