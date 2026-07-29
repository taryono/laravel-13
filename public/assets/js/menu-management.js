// assets/js/menu-management.js
let menuModal;
let menuFormMode = 'create';

document.addEventListener('DOMContentLoaded', () => {
    menuModal = new bootstrap.Modal(document.getElementById('menuModal'));

    populateSelect('menu_parent_id', window.MENU_PARENT_OPTIONS, 'id', 'label', '— Tidak ada (root) —');
    populateSelect('menu_module_id', window.MENU_MODULES, 'id', 'name', '— Tidak ada —');
    populateSelect('menu_role', window.MENU_ROLES.map(r => ({ id: r, name: r })), 'id', 'name', '— Semua role (umum) —');

    document.getElementById('menuForm').addEventListener('submit', handleMenuSubmit);

    initSortableTree();
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

window.openMenuModal = async function (mode, id = null) {
    menuFormMode = mode;
    const form = document.getElementById('menuForm');
    form.reset();
    document.getElementById('menu_id').value = '';
    document.getElementById('menuModalTitle').textContent = mode === 'create' ? 'Tambah Menu' : 'Edit Menu';

    if (mode === 'edit' && id) {
        try {
            const data = await request({ url: `/admin/menus/${id}`, method: 'GET', showLoading: true });
            document.getElementById('menu_id').value = data.id;
            document.getElementById('menu_text').value = data.text ?? '';
            document.getElementById('menu_name').value = data.name ?? '';
            document.getElementById('menu_url').value = data.url ?? '';
            document.getElementById('menu_route').value = data.route ?? '';
            document.getElementById('menu_icon').value = data.icon ?? '';
            document.getElementById('menu_icon_type').value = data.icon_type ?? 'material';
            document.getElementById('menu_type_id').value = data.menu_type_id ?? 2;
            document.getElementById('menu_parent_id').value = data.parent_id ?? '';
            document.getElementById('menu_module_id').value = data.module_id ?? '';
            document.getElementById('menu_role').value = data.role ?? '';
            document.getElementById('menu_is_publish').checked = !!data.is_publish;
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
        text: document.getElementById('menu_text').value,
        name: document.getElementById('menu_name').value,
        url: document.getElementById('menu_url').value,
        route: document.getElementById('menu_route').value,
        icon: document.getElementById('menu_icon').value,
        icon_type: document.getElementById('menu_icon_type').value,
        menu_type_id: document.getElementById('menu_type_id').value,
        parent_id: document.getElementById('menu_parent_id').value || null,
        module_id: document.getElementById('menu_module_id').value || null,
        role: document.getElementById('menu_role').value || null,
        is_publish: document.getElementById('menu_is_publish').checked ? 1 : 0,
        is_active: document.getElementById('menu_is_active').checked ? 1 : 0,
    };

    const url = id ? `/admin/menus/${id}` : '/admin/menus';
    const method = id ? 'PUT' : 'POST';

    try {
        await request({ url, method, data: payload, successMessage: 'Menu berhasil disimpan.' });
        menuModal.hide();
        window.location.reload(); // simpel: reload supaya tree ke-refresh. Bisa dioptimalkan jadi re-render partial via AJAX.
    } catch (e) {
        // handleAjaxError sudah menampilkan pesan validasi
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
        url: '/admin/menus/reorder/save',
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