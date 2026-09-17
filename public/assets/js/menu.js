// assets/js/menu.js
let menuModal;
let menuRolesModal;
let menuActionsModal;

document.addEventListener('DOMContentLoaded', () => {
    menuModal = new bootstrap.Modal(document.getElementById('menuModal'));
    menuRolesModal = new bootstrap.Modal(document.getElementById('menuRolesModal'));
    menuActionsModal = new bootstrap.Modal(document.getElementById('menuActionsModal'));

    populateSelect('menu_parent_id', window.MENU_PARENT_OPTIONS, 'id', 'label', '— Tidak ada (root) —');

    document.getElementById('menuForm').addEventListener('submit', handleMenuSubmit);
    document.getElementById('menuRolesForm').addEventListener('submit', handleMenuRolesSubmit);
    document.getElementById('actionCreateForm').addEventListener('submit', handleActionCreateSubmit);

    initSortableTree();
    initMenuRolesCheckAll();
});

function populateSelect(elId, items, valueKey, labelKey, placeholder) {
    const el = document.getElementById(elId);
    el.innerHTML = `<option value="">${placeholder}</option>`;
    items.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item[valueKey];
        opt.textContent = item[labelKey];
        el.appendChild(opt);
    });
}

function routeFor(template, id, token) {
    return template.replace(token, id);
}

// ==============================================
// MENU CRUD
// ==============================================
window.openMenuModal = async function (mode, id = null) {
    const form = document.getElementById('menuForm');
    form.reset();
    document.getElementById('menu_id').value = '';
    document.getElementById('menu_icon_type').value = 'material';
    document.getElementById('menu_type').value = 'sidebar';
    document.getElementById('menuModalTitle').textContent = mode === 'create' ? 'Tambah Menu' : 'Edit Menu';

    if (mode === 'edit' && id) {
        try {
            const data = await request({
                url: routeFor(window.MENU_ROUTES.show, id, '__MENU_ID__'),
                method: 'GET',
                showLoading: true,
            });
            document.getElementById('menu_id').value = data.id;
            document.getElementById('menu_title').value = data.title ?? '';
            document.getElementById('menu_flyout_title').value = data.flyout_title ?? '';
            document.getElementById('menu_slug').value = data.slug ?? '';
            document.getElementById('menu_url').value = data.url ?? '';
            document.getElementById('menu_route').value = data.route ?? '';
            document.getElementById('menu_icon').value = data.icon ?? '';
            document.getElementById('menu_icon_type').value = data.icon_type ?? 'material';
            document.getElementById('menu_type').value = data.type ?? 'sidebar';
            document.getElementById('menu_parent_id').value = data.parent_id ?? '';
            document.getElementById('menu_sort_order').value = data.sort_order ?? '';
            document.getElementById('menu_is_active').checked = !!data.is_active;
        } catch (e) {
            return; // handleAjaxError sudah menampilkan pesan
        }
    }

    menuModal.show();
};

async function handleMenuSubmit(e) {
    e.preventDefault();

    const id = document.getElementById('menu_id').value;
    const payload = {
        title: document.getElementById('menu_title').value,
        flyout_title: document.getElementById('menu_flyout_title').value || null,
        slug: document.getElementById('menu_slug').value || null,
        url: document.getElementById('menu_url').value,
        route: document.getElementById('menu_route').value,
        icon: document.getElementById('menu_icon').value,
        icon_type: document.getElementById('menu_icon_type').value,
        type: document.getElementById('menu_type').value,
        parent_id: document.getElementById('menu_parent_id').value || null,
        sort_order: document.getElementById('menu_sort_order').value || null,
        is_active: document.getElementById('menu_is_active').checked ? 1 : 0,
        id_text: document.getElementById('id_text').value,
        en_text: document.getElementById('en_text').value,
    };

    const url = id ? routeFor(window.MENU_ROUTES.update, id, '__MENU_ID__') : window.MENU_ROUTES.store;
    const method = id ? 'PUT' : 'POST';

    try {
        await request({ url, method, data: payload, successMessage: 'Menu berhasil disimpan.' });
        menuModal.hide();
        //window.location.reload(); // simpel: reload supaya tree ke-refresh.
    } catch (e) {
        // handleAjaxError sudah menampilkan pesan validasi
    }
}

// ==============================================
// KELOLA ROLE (menu_role: is_view/is_create/is_edit/is_delete)
// ==============================================
window.openMenuRolesModal = async function (menuId, menuTitle) {
    document.getElementById('menu_roles_menu_id').value = menuId;
    document.getElementById('menuRolesModalMenuTitle').textContent = menuTitle;

    const tbody = document.getElementById('menuRolesTableBody');
    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Memuat...</td></tr>';
    menuRolesModal.show();

    try {
        const data = await request({
            url: routeFor(window.MENU_ROUTES.show, menuId, '__MENU_ID__'),
            method: 'GET',
            showLoading: false,
        });

        const currentPivotByRoleId = {};
        (data.roles ?? []).forEach(role => {
            currentPivotByRoleId[role.id] = role.pivot ?? {};
        });

        tbody.innerHTML = '';
        window.MENU_ROLES.forEach(role => {
            const pivot = currentPivotByRoleId[role.id] ?? null;
            tbody.appendChild(buildMenuRoleRow(role, pivot));
        });
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-3">Gagal memuat data role.</td></tr>';
    }
};

function buildMenuRoleRow(role, pivot) {
    const tr = document.createElement('tr');
    tr.dataset.roleId = role.id;

    const isChecked = !!pivot;

    tr.innerHTML = `
        <td>
            <input type="checkbox" class="form-check-input role-enabled"
                   ${isChecked ? 'checked' : ''}>
        </td>
        <td>${role.display_name ?? role.name}</td>
        <td class="text-center"><input type="checkbox" class="form-check-input" data-flag="is_view" ${pivot?.is_view ? 'checked' : ''}></td>
        <td class="text-center"><input type="checkbox" class="form-check-input" data-flag="is_create" ${pivot?.is_create ? 'checked' : ''}></td>
        <td class="text-center"><input type="checkbox" class="form-check-input" data-flag="is_edit" ${pivot?.is_edit ? 'checked' : ''}></td>
        <td class="text-center"><input type="checkbox" class="form-check-input" data-flag="is_delete" ${pivot?.is_delete ? 'checked' : ''}></td>
    `;

    return tr;
}

async function handleMenuRolesSubmit(e) {
    e.preventDefault();

    const menuId = document.getElementById('menu_roles_menu_id').value;
    const assignments = [];

    document.querySelectorAll('#menuRolesTableBody tr[data-role-id]').forEach(tr => {
        const enabled = tr.querySelector('.role-enabled').checked;
        if (!enabled) return; // role tidak dicentang sama sekali -> tidak dikirim -> di-detach oleh backend

        assignments.push({
            role_id: tr.dataset.roleId,
            is_view: tr.querySelector('[data-flag="is_view"]').checked ? 1 : 0,
            is_create: tr.querySelector('[data-flag="is_create"]').checked ? 1 : 0,
            is_edit: tr.querySelector('[data-flag="is_edit"]').checked ? 1 : 0,
            is_delete: tr.querySelector('[data-flag="is_delete"]').checked ? 1 : 0,
        });
    });

    try {
        await request({
            url: routeFor(window.MENU_ROUTES.rolesSync, menuId, '__MENU_ID__'),
            method: 'POST',
            data: { assignments },
            successMessage: 'Role untuk menu berhasil disimpan.',
        });
        menuRolesModal.hide();
        // window.location.reload(); // supaya badge role di tree ikut ter-update
    } catch (e) {
        // handleAjaxError sudah menampilkan pesan validasi
    }
}

// ==============================================
// KELOLA ACTION (actions, action_role, action_user)
// ==============================================
window.openMenuActionsModal = async function (menuId, menuTitle) {
    document.getElementById('menu_actions_menu_id').value = menuId;
    document.getElementById('menuActionsModalMenuTitle').textContent = menuTitle;
    document.getElementById('actionCreateForm').reset();

    await reloadMenuActionsTable(menuId);
    menuActionsModal.show();
};

async function reloadMenuActionsTable(menuId) {
    const tbody = document.getElementById('menuActionsTableBody');
    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-3">Memuat...</td></tr>';

    try {
        const actions = await request({
            url: routeFor(window.MENU_ROUTES.actionsIndex, menuId, '__MENU_ID__'),
            method: 'GET',
            showLoading: false,
        });

        tbody.innerHTML = '';
        if (actions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-3">Belum ada action untuk menu ini.</td></tr>';
            return;
        }
        actions.forEach(action => tbody.appendChild(buildActionRow(action)));
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-3">Gagal memuat data action.</td></tr>';
    }
}

function buildActionRow(action) {
    const tr = document.createElement('tr');
    tr.dataset.actionId = action.id;

    const selectedRoleIds = (action.roles ?? []).map(r => r.id);
    const selectedUserIds = (action.users ?? []).map(u => u.id);

    tr.innerHTML = `
        <td><code>${action.name}</code></td>
        <td>${action.display_name}</td>
        <td class="text-muted small">${action.description ?? ''}</td>
        <td class="text-center">
            <input type="checkbox" class="form-check-input" disabled ${action.is_active ? 'checked' : ''}>
        </td>
        <td>
            <select class="form-select form-select-sm" multiple size="3" data-role-select></select>
        </td>
        <td>
            <select class="form-select form-select-sm" multiple size="3" data-user-select></select>
        </td>
        <td class="text-nowrap">
            <button type="button" class="btn btn-sm btn-outline-primary" data-save-action-access title="Simpan assignment">
                <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" data-delete-action title="Hapus action">
                <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
            </button>
        </td>
    `;

    const roleSelect = tr.querySelector('[data-role-select]');
    window.MENU_ROLES.forEach(role => {
        const opt = new Option(role.display_name ?? role.name, role.id, false, selectedRoleIds.includes(role.id));
        roleSelect.appendChild(opt);
    });

    const userSelect = tr.querySelector('[data-user-select]');
    window.MENU_USERS.forEach(user => {
        const opt = new Option(user.name, user.id, false, selectedUserIds.includes(user.id));
        userSelect.appendChild(opt);
    });

    tr.querySelector('[data-save-action-access]').addEventListener('click', () => handleSaveActionAccess(tr, action.id));
    tr.querySelector('[data-delete-action]').addEventListener('click', () => handleDeleteAction(tr, action.id));

    return tr;
}

function getSelectedValues(selectEl) {
    return Array.from(selectEl.selectedOptions).map(opt => opt.value);
}

async function handleActionCreateSubmit(e) {
    e.preventDefault();

    const menuId = document.getElementById('menu_actions_menu_id').value;
    const form = e.target;
    const payload = {
        name: form.name.value,
        display_name: form.display_name.value,
        description: form.description.value || null,
        is_active: 1,
    };

    try {
        await request({
            url: routeFor(window.MENU_ROUTES.actionsStore, menuId, '__MENU_ID__'),
            method: 'POST',
            data: payload,
            successMessage: 'Action berhasil ditambahkan.',
        });
        form.reset();
        await reloadMenuActionsTable(menuId);
    } catch (e) {
        // handleAjaxError sudah menampilkan pesan validasi
    }
}

async function handleSaveActionAccess(tr, actionId) {
    const roleIds = getSelectedValues(tr.querySelector('[data-role-select]'));
    const userIds = getSelectedValues(tr.querySelector('[data-user-select]'));

    try {
        await request({
            url: routeFor(window.MENU_ROUTES.actionSyncAccess, actionId, '__ACTION_ID__'),
            method: 'POST',
            data: { role_ids: roleIds, user_ids: userIds },
            successMessage: 'Assignment action berhasil disimpan.',
        });
    } catch (e) {
        // handleAjaxError sudah menampilkan pesan validasi
    }
}

async function handleDeleteAction(tr, actionId) {
    if (!confirm('Hapus action ini? Assignment role/user pada action ini juga akan hilang.')) {
        return;
    }

    try {
        await request({
            url: routeFor(window.MENU_ROUTES.actionDestroy, actionId, '__ACTION_ID__'),
            method: 'DELETE',
            successMessage: 'Action berhasil dihapus.',
        });
        tr.remove();
    } catch (e) {
        // handleAjaxError sudah menampilkan pesan
    }
}

// ==============================================
// DRAG & DROP TREE (SortableJS, nested multi-level)
// ==============================================
function initSortableTree() {
    document.querySelectorAll('.menu-tree, .menu-tree-children').forEach(el => {
        new Sortable(el, {
            group: 'menu-tree',
            handle: '.drag-handle',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onEnd: saveTreeOrder,
        });
    });
}

function saveTreeOrder() {
    const rootEl = document.getElementById('menu-tree');
    const tree = serializeTree(rootEl);

    request({
        url: window.MENU_ROUTES.reorder,
        method: 'POST',
        data: { tree },
        showLoading: false,
        successMessage: 'Urutan menu diperbarui.',
    }).catch(() => {
        // Kalau gagal simpan, reload supaya tree kembali ke state DB yang valid
        window.location.reload();
    });
}

function serializeTree(ulEl) {
    const items = [];
    ulEl.querySelectorAll(':scope > .menu-tree-item').forEach(li => {
        const childrenUl = li.querySelector(':scope > .menu-tree-children');
        items.push({
            id: li.dataset.id,
            children: childrenUl ? serializeTree(childrenUl) : [],
        });
    });
    return items;
}

// ==============================================
// CHECK ALL — Kelola Role modal
// ==============================================
function initMenuRolesCheckAll() {
    const tbody = document.getElementById('menuRolesTableBody');
    const masterCheckbox = document.getElementById('menuRolesCheckAllMaster');

    // Event delegation di tbody, aman walau baris dirender ulang tiap modal dibuka
    tbody.addEventListener('change', (e) => {
        const target = e.target;

        // Klik checkbox "role-enabled" di satu baris -> centang/uncheck 4 permission baris itu
        if (target.classList.contains('role-enabled')) {
            const tr = target.closest('tr');
            tr.querySelectorAll('[data-flag]').forEach(cb => {
                cb.checked = target.checked;
            });
        }

        // Kalau salah satu permission diklik manual, sinkronkan checkbox role-enabled di baris itu
        if (target.matches('[data-flag]')) {
            const tr = target.closest('tr');
            const roleEnabled = tr.querySelector('.role-enabled');
            const flags = tr.querySelectorAll('[data-flag]');
            const anyChecked = [...flags].some(cb => cb.checked);
            roleEnabled.checked = anyChecked; // ikut tercentang kalau minimal 1 permission aktif
        }

        syncMasterCheckbox();
    });

    // Klik checkbox master di header -> centang/uncheck semua role + semua permission
    masterCheckbox.addEventListener('change', (e) => {
        const checked = e.target.checked;
        tbody.querySelectorAll('.role-enabled, [data-flag]').forEach(cb => {
            cb.checked = checked;
        });
    });

    function syncMasterCheckbox() {
        const allBoxes = tbody.querySelectorAll('.role-enabled, [data-flag]');
        if (allBoxes.length === 0) {
            masterCheckbox.checked = false;
            return;
        }
        const allChecked = [...allBoxes].every(cb => cb.checked);
        masterCheckbox.checked = allChecked;
    }
}