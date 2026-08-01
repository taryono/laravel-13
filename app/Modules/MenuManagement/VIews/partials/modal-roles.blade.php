{{-- app/Modules/MenuManagement/VIews/partials/modal-roles.blade.php --}}
<div class="modal fade" id="menuRolesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="menuRolesForm">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Kelola Role — <span id="menuRolesModalMenuTitle" class="text-muted"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="menu_id" id="menu_roles_menu_id">

                    <p class="text-muted small">
                        Centang role yang boleh mengakses menu ini, beserta hak aksesnya.
                        Role yang tidak dicentang sama sekali akan dilepas dari menu ini saat disimpan.
                    </p>

                    <table class="table table-sm align-middle" id="menuRolesTable">
                        <thead>
                            <tr>
                                <th style="width: 5%"></th>
                                <th>Role</th>
                                <th class="text-center">View</th>
                                <th class="text-center">Create</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </thead>
                        {{-- Baris diisi via JS dari window.MENU_ROLES + data pivot menu ini
                             (hasil fetch GET menu_management.show / endpoint show()) --}}
                        <tbody id="menuRolesTableBody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Role</button>
                </div>
            </form>
        </div>
    </div>
</div>