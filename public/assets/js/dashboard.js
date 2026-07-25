// ===== Sidebar toggle (hamburger) =====
const sidebar = document.getElementById('bnspSidebar');
const mainWrapper = document.getElementById('bnspMainWrapper');
const hamburger = document.getElementById('bnspHamburger');
const backdrop = document.getElementById('bnspBackdrop');
const isDesktop = () => window.matchMedia('(min-width: 992px)').matches; 
var isCollapsed = false;
 
// ===== Judul flyout menu (mode sidebar menyusut) =====
// Menyisipkan <li> judul di awal setiap submenu yang punya atribut
// data-flyout-title, agar urutan tampil selalu benar di semua browser
// (tidak bergantung pada trik CSS ::after + order yang bisa meleset).
function initFlyoutTitles() {
    document.querySelectorAll('.bnsp-submenu[data-flyout-title]').forEach(submenu => {
        const li = document.createElement('li');
        li.className = 'bnsp-flyout-title-item';
        li.textContent = submenu.dataset.flyoutTitle;
        submenu.insertBefore(li, submenu.firstChild);
    });
}
initFlyoutTitles();

// ===== Mode layout: sidebar <-> topbar =====
// Menu HANYA ditulis sekali di dalam sidebar. buildTopNav() menyalin
// (clone) setiap item menu ke dalam #bnspTopnav, supaya kedua mode
// selalu sinkron tanpa perlu edit dua tempat.
// Memasang listener klik untuk toggle .open pada parent menu.
// respectSidebarCollapse: true HANYA untuk menu di dalam sidebar —
// saat sidebar menyusut (mode icon-rail), klik dilepas ke hover
// flyout. Menu di topnav tidak punya konsep "menyusut", jadi selalu
// toggle klik seperti biasa.
function bindNavParentClicks(container, respectSidebarCollapse) {
    container.querySelectorAll('.bnsp-nav-parent').forEach(link => {
        link.addEventListener('click', (e) => {
            if (respectSidebarCollapse && sidebar.classList.contains('collapsed') && isDesktop()) return;
            e.preventDefault();
            const item = link.closest('.bnsp-nav-item');
            item.classList.toggle('open');
        });
    });
}

const LAYOUT_STORAGE_KEY = 'bnsp-layout-mode'; // 'sidebar' | 'topbar'
const COLLAPSED_STORAGE_KEY = 'sidebar-collapsed-mode'; // 'collapsed' | 'expanded'
const layoutToggleBtn = document.getElementById('bnspLayoutToggle');
const layoutToggleIcon = layoutToggleBtn.querySelector('.material-symbols-outlined');


function updateToggleVisibility() {
    layoutToggleBtn.style.display = isDesktop() ? '' : 'none';
    // atau kalau mau invert logic-nya (tampil di mobile, sembunyi di desktop),
    // tinggal balik kondisinya
}

function buildTopNav() {
    const topnav = document.getElementById('bnspTopnav');
    const sourceNav = sidebar.querySelector(':scope > nav');
    if (!sourceNav) return; // tidak ada menu di sidebar
    Array.from(sourceNav.children).forEach(child => {
        const clone = child.cloneNode(true);
        // Judul flyout (dipakai khusus mode sidebar menyusut) tidak
        // relevan di topnav — dropdown topnav sudah punya konteksnya
        // sendiri dari parent link, jadi dibuang dari hasil kloningan.
        clone.querySelectorAll('.bnsp-flyout-title-item').forEach(li => li.remove());
        topnav.appendChild(clone);
    });

    // SATU-SATUNYA tempat listener klik dipasang untuk item topnav.
    // Jangan tambahkan binding .bnsp-nav-parent lain yang menyapu
    // seluruh dokumen (termasuk topnav) di bagian script manapun —
    // itu penyebab bug "klik dua kali ke-toggle, jadi kelihatan
    // tidak terjadi apa-apa" yang sempat muncul sebelumnya.
    bindNavParentClicks(topnav, false);
}
buildTopNav();
updateToggleVisibility();

// Pasang listener supaya reaktif terhadap perubahan ukuran window
const desktopMql = window.matchMedia('(min-width: 992px)');
desktopMql.addEventListener('change', updateToggleVisibility);


function applyLayoutMode(mode) {
    const isTopbar = mode === 'topbar';
    document.body.classList.toggle('bnsp-layout-topbar', isTopbar);
    layoutToggleIcon.textContent = isTopbar ? 'view_sidebar' : 'view_agenda';
    layoutToggleBtn.title = isTopbar ? 'Ganti ke tampilan sidebar' : 'Ganti ke tampilan menu atas';

    // Layout topbar tidak pakai konsep collapsed/expanded sidebar,
    // jadi pastikan state itu tidak "nyangkut" saat balik ke sidebar.
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
});

hamburger.addEventListener('click', () => {
    if (isDesktop()) {
        sidebar.classList.toggle('collapsed');
        mainWrapper.classList.toggle('collapsed');
    } else {
        sidebar.classList.toggle('mobile-open');
        backdrop.classList.toggle('show');
    }
    nextSidebarMode = sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded';
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

// ===== Multilevel accordion (expanded sidebar only; collapsed mode uses hover flyout) =====
// Dibatasi ke dalam sidebar saja (bindNavParentClicks(sidebar, ...)) —
// JANGAN pakai document.querySelectorAll('.bnsp-nav-parent') di sini,
// karena itu juga akan menangkap item hasil clone di #bnspTopnav yang
// sudah dipasangi listener-nya sendiri lewat buildTopNav(), sehingga
// klik di topnav ter-toggle dua kali (nyala lalu langsung mati lagi).
bindNavParentClicks(sidebar, true);

document.querySelectorAll('button, a').forEach(el => {
    el.addEventListener('click', function () {
        const ripple = document.createElement('div');
        ripple.classList.add('ripple');
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
});

// ===== Efek Ripple pada Tombol =====
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

// Tutup dropdown jika klik di luar area
document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});
