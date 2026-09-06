// ===== Sidebar toggle (hamburger) =====
const sidebar = document.getElementById('bnspSidebar');
const mainWrapper = document.getElementById('bnspMainWrapper');
const hamburger = document.getElementById('bnspHamburger');
const backdrop = document.getElementById('bnspBackdrop');
const isDesktop = () => window.matchMedia('(min-width: 992px)').matches;
var isCollapsed = false; 
function initFlyoutTitles() {
    document.querySelectorAll('.bnsp-submenu[data-flyout-title]').forEach(submenu => {
        const li = document.createElement('li');
        li.className = 'bnsp-flyout-title-item';
        li.textContent = submenu.dataset.flyoutTitle;
        submenu.insertBefore(li, submenu.firstChild);
    });
}

initFlyoutTitles(); 
function bindNavParentClicks(container, respectSidebarCollapse) {
    container.querySelectorAll('.bnsp-nav-parent').forEach(link => {
        link.addEventListener('click', (e) => {
            if (respectSidebarCollapse && sidebar.classList.contains('collapsed') && isDesktop()) return;
            e.preventDefault();
            const item = link.closest('.bnsp-nav-item');
            item.classList.toggle('open');
        });

        // ✅ Saat hover di desktop collapsed → cek posisi
        const item = link.closest('.bnsp-nav-item');
        item.addEventListener('mouseenter', () => {
            if (isDesktop() && sidebar.classList.contains('collapsed')) {
                requestAnimationFrame(() => {
                    adjustSubmenuPosition(item);
                });
            }
        });
    });
}

const LAYOUT_STORAGE_KEY = 'bnsp-layout-mode' + '-' + window.loginAs; // 'sidebar' | 'topbar'
const COLLAPSED_STORAGE_KEY = 'sidebar-collapsed-mode' + '-' + window.loginAs; // 'collapsed' | 'expanded'
const layoutToggleBtn = document.getElementById('bnspLayoutToggle');
const layoutToggleIcon = layoutToggleBtn.querySelector('.material-symbols-outlined');

function updateToggleVisibility() {
    layoutToggleBtn.style.display = isDesktop() ? '' : 'none'; 
}

function buildTopNav() {
    const topnav = document.getElementById('bnspTopnav');
    const sourceNav = sidebar.querySelector(':scope > nav');
    if (!sourceNav) return; // tidak ada menu di sidebar
    Array.from(sourceNav.children).forEach(child => {
        const clone = child.cloneNode(true); 
        clone.querySelectorAll('.bnsp-flyout-title-item').forEach(li => li.remove());
        topnav.appendChild(clone);
    }); 
    bindNavParentClicks(topnav, false);

} 
function initTopnavOverflow() {
    const topnav = document.getElementById('bnspTopnav');
    if (!topnav) return;

    const brand = topnav.querySelector(':scope > .bnsp-topnav-brand');

    // Buat item "Lainnya" sekali saja, mengikuti pola struktur item lain
    let moreItem = document.getElementById('bnspTopnavMore');
    if (!moreItem) {
        moreItem = document.createElement('div');
        moreItem.id = 'bnspTopnavMore';
        moreItem.className = 'bnsp-nav-item bnsp-topnav-more';
        moreItem.innerHTML = `
            <a href="#" class="bnsp-nav-link bnsp-nav-parent">
                <span class="material-symbols-outlined">more_horiz</span>
                <span class="nav-label flex-grow-1">Lainnya</span>
                <span class="material-symbols-outlined chevron">chevron_right</span>
            </a>
            <ul class="bnsp-submenu bnsp-topnav-more-menu" data-flyout-text="Lainnya" data-flyout-title="Lainnya"></ul>
        `;
        topnav.appendChild(moreItem);
        bindNavParentClicks(moreItem, false); // sama seperti listener item topnav lain
    }
    const moreMenu = moreItem.querySelector('.bnsp-topnav-more-menu');

    // Simpan urutan asli item (di luar brand & tombol "Lainnya") satu kali saja,
    // supaya posisi tiap item bisa dikembalikan dengan tepat setiap recalculate
    if (!topnav._originalOrder) {
        topnav._originalOrder = Array.from(topnav.children).filter(
            el => el !== moreItem && el !== brand && el.classList.contains('bnsp-nav-item')
        );
    }

    function recalculate() {
        // Kembalikan semua item ke posisi asal sebelum dihitung ulang
        topnav._originalOrder.forEach(item => topnav.insertBefore(item, moreItem));
        moreMenu.innerHTML = '';
        moreItem.style.display = 'none';

        const containerWidth = topnav.clientWidth;
        const brandWidth = brand ? brand.offsetWidth : 0;
        const moreBtnWidth = moreItem.querySelector('a').offsetWidth || 110;

        let usedWidth = brandWidth;
        const overflowItems = [];

        topnav._originalOrder.forEach(item => {
            usedWidth += item.offsetWidth;
            if (usedWidth > containerWidth - moreBtnWidth) {
                overflowItems.push(item);
            }
        });

        if (overflowItems.length > 0) {
            moreItem.style.display = '';
            overflowItems.forEach(item => {
                // moreMenu adalah <ul>, jadi tiap item dibungkus <li> —
                // sama seperti pola submenu nested yang sudah ada (Dit, Master)
                const li = document.createElement('li');
                li.appendChild(item); // pindahkan node asli, bukan clone,
                                       // jadi semua data-ajax-url & submenu ikut terbawa
                moreMenu.appendChild(li);
            });
        }
    }

    // Observe parent container topnav (bukan topnav itu sendiri) supaya
    // tidak infinite loop, dan tetap reaktif saat sidebar collapse/expand
    // mengubah lebar area yang tersedia untuk topnav
    const resizeObserver = new ResizeObserver(() => {
        requestAnimationFrame(recalculate);
    });
    resizeObserver.observe(topnav.parentElement || topnav);

    requestAnimationFrame(recalculate);
    // window.location.reload();
}

buildTopNav();
// Panggil SETELAH buildTopNav(), supaya menu sudah ter-clone semua
initTopnavOverflow();

updateToggleVisibility();

// Pasang listener supaya reaktif terhadap perubahan ukuran window
const desktopMql = window.matchMedia('(min-width: 992px)');
desktopMql.addEventListener('change', updateToggleVisibility);

function applyLayoutMode(mode) {
    const isTopbar = mode === 'topbar';
    document.body.classList.toggle('bnsp-layout-topbar', isTopbar);
    layoutToggleIcon.textContent = isTopbar ? 'view_sidebar' : 'view_agenda';
    layoutToggleBtn.title = isTopbar ? 'Ganti ke tampilan sidebar' : 'Ganti ke tampilan menu atas'; 
    if (isTopbar) {
        sidebar.classList.remove('mobile-open');
        backdrop.classList.remove('show');
    }
}

function applySideBarMode(mode) {
    const isCollapsed = mode === 'collapsed';
    sidebar.classList.toggle('collapsed', isCollapsed);
    mainWrapper.classList.toggle('collapsed', isCollapsed);
}

const savedLayoutMode = localStorage.getItem(LAYOUT_STORAGE_KEY) || 'sidebar';
applyLayoutMode(savedLayoutMode);
const savedSidebarMode = localStorage.getItem(COLLAPSED_STORAGE_KEY) || 'expanded';
applySideBarMode(savedSidebarMode);
layoutToggleBtn.addEventListener('click', () => {
    const nextMode = document.body.classList.contains('bnsp-layout-topbar') ? 'sidebar' : 'topbar';
    applyLayoutMode(nextMode);
    localStorage.setItem(LAYOUT_STORAGE_KEY, nextMode);
    window.location.reload();
});

hamburger.addEventListener('click', () => {
    if (isDesktop()) {
        sidebar.classList.toggle('collapsed');
        mainWrapper.classList.toggle('collapsed');
    } else {
        sidebar.classList.toggle('mobile-open');
        backdrop.classList.toggle('show');
    } 
    let nextSidebarMode = sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded';
    applySideBarMode(nextSidebarMode);
    localStorage.setItem(COLLAPSED_STORAGE_KEY, nextSidebarMode);

});

backdrop.addEventListener('click', () => {
    sidebar.classList.remove('mobile-open');
    backdrop.classList.remove('show');
});

// reset mobile drawer state when resizing back to desktop
window.addEventListener('resize', () => {
    if (isDesktop()) {
        sidebar.classList.remove('mobile-open');
        backdrop.classList.remove('show');
    }
}); 
bindNavParentClicks(sidebar, true); 
// sidebar.querySelectorAll('.bnsp-nav-link:not(.bnsp-nav-parent)').forEach(link => {
//     link.addEventListener('click', () => {
//         if (!isDesktop()) {
//             sidebar.classList.remove('mobile-open');
//             backdrop.classList.remove('show');
//         }
//     });
// }); 
document.querySelectorAll('button, a').forEach(el => {
    el.addEventListener('click', function () {
        const ripple = document.createElement('div');
        ripple.style.position = 'absolute';
        ripple.style.borderRadius = '50%';
        ripple.style.background = '#003745';
        ripple.style.transform = 'scale(0)';
        ripple.style.animation = 'rippleEffect 0.6s ease-out';
        ripple.style.pointerEvents = 'none';
        this.style.position = 'relative';
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
});

// ===== Tandai Notifikasi Sudah Dibaca =====
document.querySelectorAll('.notification-item.unread').forEach(item => {
    item.addEventListener('click', () => {
        item.classList.remove('unread');
        updateNotificationBadge(-1);
    });
});

function updateNotificationBadge(change) {
    const badge = document.querySelector('.notification-badge');
    let current = parseInt(badge.textContent);
    current = Math.max(0, current + change);
    badge.textContent = current;
    if (current === 0) badge.style.display = 'none';
}
 
// document.addEventListener('click', (e) => {
//     if (!e.target.closest('.dropdown')) {
//         document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
//             menu.classList.remove('show');
//         });
//     }
//     if (!e.target.closest('.bnsp-nav-item')) {
//         document.querySelectorAll('.bnsp-nav-item.open').forEach(item => {
//             item.classList.remove('open');
//         });
//     }
// }); 

document.addEventListener('click', (e) => {
    // JANGAN tutup kalau klik di dalam nav-item — biarkan toggle() berjalan dulu
    if (!e.target.closest('.bnsp-nav-item')) {
        document.querySelectorAll('.bnsp-nav-item.open').forEach(item => {
            item.classList.remove('open');
        });
    }
    // ✅ Tambahan: di mobile, klik submenu-link JANGAN tutup parent
    // Hanya tutup sidebar jika klik link BUKAN parent
    if (!isDesktop()) {
        if (e.target.closest('.bnsp-nav-link') && !e.target.closest('.bnsp-nav-parent')) {
            sidebar.classList.remove('mobile-open');
            backdrop.classList.remove('show');
        }
    }
});

// ===== DETEKSI & ATUR POSISI SUBMENU =====
// function adjustSubmenuPosition(menuItem) {
//     const submenu = menuItem.querySelector(':scope > .bnsp-submenu');
//     if (!submenu) return;

//     const parentRect = menuItem.getBoundingClientRect();
    
//     // Sembunyikan sesaat, ukur, lalu tampilkan lagi
//     const wasVisible = submenu.classList.contains('bnsp-flyout-up');
//     submenu.style.display = 'block';
//     const submenuHeight = submenu.offsetHeight;
//     if (!wasVisible) submenu.style.display = '';

//     const spaceBelow = window.innerHeight - parentRect.bottom;
//     const spaceAbove = parentRect.top;

//     if (spaceBelow < submenuHeight && spaceAbove > submenuHeight) {
//         submenu.classList.add('bnsp-flyout-up');
//     } else {
//         submenu.classList.remove('bnsp-flyout-up');
//     }
// }

// ===== SESUAIKAN POSISI SUBMENU JIKA MELEWATI BATAS LAYAR =====
function adjustSubmenuPosition(menuItem) {
    const submenu = menuItem.querySelector(':scope > .bnsp-submenu');
    if (!submenu) return;

    // Ambil posisi menu parent relatif terhadap layar
    const parentRect = menuItem.getBoundingClientRect();
    
    // Beri kesempatan submenu tampil sebentar untuk ukur tingginya
    const tempDisplay = submenu.style.display;
    const tempPosition = submenu.style.position;
    const tempVisibility = submenu.style.visibility;
    
    submenu.style.display = 'block';
    submenu.style.position = 'absolute';
    submenu.style.visibility = 'hidden';
    
    const submenuHeight = submenu.offsetHeight;
    
    // Kembalikan keadaan semula
    submenu.style.display = tempDisplay;
    submenu.style.position = tempPosition;
    submenu.style.visibility = tempVisibility;

    // Hitung ruang yang tersedia
    const spaceBelow = window.innerHeight - parentRect.bottom;
    const spaceAbove = parentRect.top;

    // ✅ Logika: pilih arah yang punya ruang lebih besar
    if (spaceBelow < submenuHeight && spaceAbove > spaceBelow) {
        // Ruang di atas lebih banyak → tampilkan KE ATAS
        submenu.classList.add('bnsp-flyout-up');
    } else {
        // Ruang di bawah cukup atau sama-sama sempit → tampilkan KE BAWAH
        submenu.classList.remove('bnsp-flyout-up');
    }
}