// ===== Sidebar toggle (hamburger) =====
const sidebar = document.getElementById('bnspSidebar');
const mainWrapper = document.getElementById('bnspMainWrapper');
const hamburger = document.getElementById('bnspHamburger');
const backdrop = document.getElementById('bnspBackdrop');
const isDesktop = () => window.matchMedia('(min-width: 992px)').matches;
var isCollapsed = false;
let tooltipTimer = null;
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
    // Nav level teratas sidebar — dipakai untuk cek apakah sebuah
    // .bnsp-nav-item ada di level 1 (langsung di dalam <nav>) atau
    // nested (di dalam .bnsp-submenu)
    const topLevelNav = respectSidebarCollapse ? sidebar.querySelector(':scope > nav') : null;

    container.querySelectorAll('.bnsp-nav-parent').forEach(link => {
        link.addEventListener('click', (e) => {
            if (respectSidebarCollapse && sidebar.classList.contains('collapsed') && isDesktop()) return;
            e.preventDefault();
            const item = link.closest('.bnsp-nav-item');
            item.classList.toggle('open');
        });

        const item = link.closest('.bnsp-nav-item');
        const isTopLevelSidebarItem = !!topLevelNav && item.parentElement === topLevelNav;

        // ✅ Saat hover di desktop collapsed → cek posisi
        item.addEventListener('mouseenter', () => {
            if (isDesktop() && sidebar.classList.contains('collapsed')) {
                if (isTopLevelSidebarItem) {
                    // Level teratas: pindahkan submenu ke <body> (portal)
                    // supaya tidak ke-clip oleh <nav> yang overflow:auto
                    console.log('📊 Pindah submenu ke portal...');
                    openTopFlyout(item);
                } else {
                    requestAnimationFrame(() => {
                        adjustSubmenuPosition(item);
                    });
                }
            }
        });

        if (isTopLevelSidebarItem) {
            item.addEventListener('mouseleave', () => {
                scheduleCloseTopFlyout(item);
            });

            item.addEventListener('mouseleave', (e) => {

                const related = e.relatedTarget;

                /*
                * Jika mouse berpindah ke sesuatu yang masih
                * berada di dalam item, jangan close.
                */
                if (related && item.contains(related)) {
                    return;
                }

                /*
                * Kalau mouse berpindah langsung ke submenu portal,
                * jangan close.
                */
                if (
                    flyoutOpenSubmenu &&
                    related &&
                    flyoutOpenSubmenu.contains(related)
                ) {
                    clearTimeout(flyoutHideTimer);
                    return;
                }

                scheduleCloseTopFlyout(item);
            });
        }
    });
}

function bindNavParentClicksNew(container, respectSidebarCollapse) {
    const topLevelNav = respectSidebarCollapse ? sidebar.querySelector(':scope > nav') : null;

    container.querySelectorAll('.bnsp-nav-parent').forEach(link => {
        // ===== EVENT CLICK =====
        link.addEventListener('click', (e) => {
            if (respectSidebarCollapse && sidebar.classList.contains('collapsed') && isDesktop()) {
                // HENTIKAN perilaku default di collapsed mode
                // JANGAN toggle open
                e.preventDefault();
                return;
            }
            e.preventDefault();
            const item = link.closest('.bnsp-nav-item');
            item.classList.toggle('open');
        });

        const item = link.closest('.bnsp-nav-item');
        const isTopLevelSidebarItem = !!topLevelNav && item.parentElement === topLevelNav;

        // ===== EVENT MOUSEENTER (HANYA SATU) =====
        item.addEventListener('mouseenter', () => {
            if (isDesktop() && sidebar.classList.contains('collapsed')) {
                if (isTopLevelSidebarItem) {
                    // HANYA untuk level teratas - pakai portal
                    console.log('📊 Pindah submenu ke portal...');
                    openTopFlyoutNew(item); // PAKAI YANG BARU
                } else {
                    requestAnimationFrame(() => {
                        adjustSubmenuPosition(item);
                    });
                }
            }
        });

        // ===== EVENT MOUSELEAVE (HANYA SATU) =====
        if (isTopLevelSidebarItem) {
            item.addEventListener('mouseleave', (e) => {
                const related = e.relatedTarget;
                
                // Jika mouse pindah ke dalam item sendiri
                if (related && item.contains(related)) {
                    return;
                }
                
                // Jika mouse pindah ke submenu portal
                if (flyoutOpenSubmenu && related && flyoutOpenSubmenu.contains(related)) {
                    clearTimeout(flyoutHideTimer);
                    return;
                }
                
                scheduleCloseTopFlyout(item);
            });
        }
    });
}

// ===== FLYOUT SUBMENU PORTAL untuk item level teratas =====
let flyoutHideTimer = null;
let flyoutOpenItem = null;
let flyoutOpenSubmenu = null;

function closeTopFlyout() {
    if (flyoutOpenSubmenu && flyoutOpenItem) {
        flyoutOpenSubmenu.classList.remove('bnsp-flyout-detached');
        flyoutOpenSubmenu.style.position = '';
        flyoutOpenSubmenu.style.top = '';
        flyoutOpenSubmenu.style.left = '';
        flyoutOpenSubmenu.style.bottom = '';
        // Kembalikan submenu ke posisi asal di dalam nav-item-nya
        const preBrowser = flyoutOpenItem.querySelector(':scope > .pre-browser');
        if (preBrowser) {
            flyoutOpenItem.insertBefore(flyoutOpenSubmenu, preBrowser);
        } else {
            flyoutOpenItem.appendChild(flyoutOpenSubmenu);
        }
    }
    flyoutOpenItem = null;
    flyoutOpenSubmenu = null;
}

function openTopFlyout(item) {
    const submenu = item.querySelector(':scope > .bnsp-submenu');

    console.log('📊 openTopFlyout Pindah submenu ke portal...',submenu);
    if (!submenu) return;

    clearTimeout(flyoutHideTimer);

    if (flyoutOpenItem === item) return; // sudah kebuka untuk item ini
    if (flyoutOpenItem && flyoutOpenItem !== item) closeTopFlyout();

    flyoutOpenItem = item;
    flyoutOpenSubmenu = submenu;

    document.body.appendChild(submenu);
    submenu.classList.add('bnsp-flyout-detached');

    const gapVar = getComputedStyle(document.documentElement).getPropertyValue('--bnsp-flyout-gap');
    const gap = parseFloat(gapVar) || 12;
    const rect = item.getBoundingClientRect();

    submenu.style.left = (rect.right + gap) + 'px';
    submenu.style.top = (rect.top - 8) + 'px';
    submenu.style.bottom = 'auto';

    // Kalau ruang di bawah tidak cukup & ruang di atas lebih luas, flip ke atas
    const submenuHeight = submenu.offsetHeight;
    const spaceBelow = window.innerHeight - rect.top;
    const spaceAbove = rect.bottom;
    if (spaceBelow < submenuHeight && spaceAbove > spaceBelow) {
        submenu.style.top = 'auto';
        submenu.style.bottom = (window.innerHeight - rect.bottom - 8) + 'px';
    }

    // Bind sekali saja per elemen submenu, supaya listener tidak menumpuk
    if (!submenu._flyoutBound) {
        submenu.addEventListener('mouseenter', () => clearTimeout(flyoutHideTimer));
        submenu.addEventListener('mouseleave', () => {
            if (flyoutOpenItem) scheduleCloseTopFlyout(flyoutOpenItem);
        });
        submenu._flyoutBound = true;
    }
}

function openTopFlyoutNew(item) {
    // CEK: apakah item memiliki submenu?
    const submenu = item.querySelector(':scope > .bnsp-submenu');
    if (!submenu) return;

    // CEK: apakah ini parent menu? (harusnya sudah dicek sebelumnya)
    if (!item.querySelector('.bnsp-nav-parent')) return;

    clearTimeout(flyoutHideTimer);

    // Sudah terbuka untuk item ini
    if (flyoutOpenItem === item && flyoutOpenSubmenu === submenu) {
        return;
    }

    // Kalau ada flyout lain yang masih terbuka, tutup dulu
    if (flyoutOpenItem && flyoutOpenItem !== item) {
        closeTopFlyout();
    }

    // ===== PASTIKAN SUBMENU TIDAK TAMPIL DI CSS =====
    // Hapus style inline yang mungkin tersisa
    submenu.style.display = '';
    submenu.style.maxHeight = '';

    flyoutOpenItem = item;
    flyoutOpenSubmenu = submenu;

    /*
     * Simpan posisi asli submenu supaya nanti
     * bisa dikembalikan ke parent.
     */
    if (!submenu._originalParent) {
        submenu._originalParent = submenu.parentElement;
        submenu._originalNextSibling = submenu.nextSibling;
    }

    /*
     * Pindahkan ke body.
     * Ini membuat flyout bebas dari overflow:auto
     * milik sidebar/nav.
     */
    document.body.appendChild(submenu);

    submenu.classList.add('bnsp-flyout-detached');

    const gapVar = getComputedStyle(document.documentElement)
        .getPropertyValue('--bnsp-flyout-gap');

    const gap = parseFloat(gapVar) || 12;

    const rect = item.getBoundingClientRect();

    /*
     * Beri posisi awal.
     */
    submenu.style.left = `${rect.right + gap}px`;
    submenu.style.top = `${rect.top - 8}px`;
    submenu.style.bottom = 'auto';

    /*
     * Pastikan browser sudah menghitung ukuran submenu.
     */
    const submenuRect = submenu.getBoundingClientRect();

    /*
     * ===== VERTICAL POSITION =====
     */

    const spaceBelow = window.innerHeight - rect.top;
    const spaceAbove = rect.bottom;

    if (
        spaceBelow < submenuRect.height &&
        spaceAbove > spaceBelow
    ) {
        submenu.style.top = 'auto';

        const bottom = window.innerHeight - rect.bottom - 8;

        submenu.style.bottom = `${Math.max(8, bottom)}px`;
    } else {
        /*
         * Jangan biarkan submenu keluar bawah layar.
         */
        const maxTop = window.innerHeight - submenuRect.height - 8;

        const top = Math.min(
            Math.max(8, rect.top - 8),
            maxTop
        );

        submenu.style.top = `${top}px`;
    }

    /*
     * ===== HORIZONTAL POSITION =====
     *
     * Kalau submenu terlalu ke kanan,
     * tampilkan ke kiri parent.
     */

    const finalRect = submenu.getBoundingClientRect();

    if (finalRect.right > window.innerWidth - 8) {
        submenu.style.left = `${Math.max(
            8,
            rect.left - finalRect.width - gap
        )}px`;

        submenu.classList.add('bnsp-flyout-left');
    } else {
        submenu.classList.remove('bnsp-flyout-left');
    }

    /*
     * Bind listener hanya sekali.
     */
    if (!submenu._flyoutBound) {

        submenu.addEventListener('mouseenter', () => {
            clearTimeout(flyoutHideTimer);
        });

        submenu.addEventListener('mouseleave', (e) => {
            /*
             * Kalau mouse berpindah ke element lain
             * yang masih merupakan bagian dari submenu,
             * jangan close.
             */
            const related = e.relatedTarget;

            if (related && submenu.contains(related)) {
                return;
            }

            scheduleCloseTopFlyout(item);
        });

        submenu._flyoutBound = true;
    }
}

function scheduleCloseTopFlyoutOld(item) {
    clearTimeout(flyoutHideTimer);
    flyoutHideTimer = setTimeout(() => {
        if (flyoutOpenItem === item) closeTopFlyout();
    }, 200);
}
function scheduleCloseTopFlyout(item) {
    clearTimeout(flyoutHideTimer);

    flyoutHideTimer = setTimeout(() => {

        if (flyoutOpenItem !== item) {
            return;
        }

        /*
         * Jangan tutup jika mouse sedang berada
         * di parent atau submenu portal.
         */
        const submenu = flyoutOpenSubmenu;

        if (!submenu) {
            closeTopFlyout();
            return;
        }

        const parentRect = item.getBoundingClientRect();
        const submenuRect = submenu.getBoundingClientRect();

        const mouseX = window._bnspMouseX;
        const mouseY = window._bnspMouseY;

        const insideParent =
            mouseX >= parentRect.left &&
            mouseX <= parentRect.right &&
            mouseY >= parentRect.top &&
            mouseY <= parentRect.bottom;

        const insideSubmenu =
            mouseX >= submenuRect.left &&
            mouseX <= submenuRect.right &&
            mouseY >= submenuRect.top &&
            mouseY <= submenuRect.bottom;

        if (insideParent || insideSubmenu) {
            return;
        }

        closeTopFlyout();

    }, 250);
}

document.addEventListener('mousemove', (e) => {
    window._bnspMouseX = e.clientX;
    window._bnspMouseY = e.clientY;
});

const LAYOUT_STORAGE_KEY = 'bnsp-layout-mode'; // 'sidebar' | 'topbar'
const COLLAPSED_STORAGE_KEY = 'sidebar-collapsed-mode'; // 'collapsed' | 'expanded' 


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
// Pasang listener supaya reaktif terhadap perubahan ukuran window
const desktopMql = window.matchMedia('(min-width: 992px)');

function applyLayoutMode(mode) {
    const isTopbar = mode === 'topbar';
    document.body.classList.toggle('bnsp-layout-topbar', isTopbar);
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

const savedLayoutMode = localStorage.getItem('sidebar') || 'sidebar';
applyLayoutMode(savedLayoutMode);
const savedSidebarMode = localStorage.getItem(COLLAPSED_STORAGE_KEY) || 'expanded';
applySideBarMode(savedSidebarMode);


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
document.addEventListener('click', (e) => {
    if (!e.target || typeof e.target.closest !== 'function') return;
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
    // Tutup flyout portal kalau klik sebuah link navigasi di dalamnya
    // (bukan link parent yang cuma toggle submenu lagi)
    if (e.target.closest('.bnsp-nav-link') && !e.target.closest('.bnsp-nav-parent')) {
        closeTopFlyout();
    }
});

// Tutup flyout portal saat nav collapsed di-scroll, sidebar
// di-toggle collapse/expand, atau window di-resize — supaya
// tidak nyangkut di posisi lama yang sudah tidak relevan
// document.querySelector('#bnspSidebar > nav')?.addEventListener('scroll', closeTopFlyout);
// sidebar.addEventListener('scroll', closeTopFlyout, true);
// sidebar.addEventListener('transitionend', closeTopFlyout);
window.addEventListener('resize', closeTopFlyout);

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

// ===== TOOLTIP PORTAL =====
// Menggantikan tooltip ::after lama. Elemen dibuat sekali lalu
// di-append ke <body>, jadi posisinya lepas dari <nav> collapsed
// yang sekarang overflow-nya "auto" (bisa discroll). Dengan begitu
// tooltip tidak pernah ke-clip walau nav sedang dalam kondisi scroll.
(function initTooltipPortal() {
    const portal = document.createElement('div');
    portal.className = 'bnsp-tooltip-portal';
    document.body.appendChild(portal);

    let currentTarget = null;

    function showTooltip(el) {
        clearTimeout(tooltipTimer);
        // Hanya aktif saat sidebar collapsed & desktop, dan bukan
        // item parent (item yang punya child pakai flyout submenu,
        // bukan tooltip biasa)
        if (!sidebar.classList.contains('collapsed') || !isDesktop()) return;
        if (el.classList.contains('bnsp-nav-parent')) return;
        // Tooltip untuk item DI DALAM flyout submenu (bnsp-submenu)
        // tetap dimatikan, sama seperti perilaku lama
        if (el.closest('.bnsp-submenu')) return;

        const text = el.getAttribute('data-tooltip');
        if (!text) return;

        currentTarget = el;
        portal.textContent = text;

        const rect = el.getBoundingClientRect();
        portal.style.top = (rect.top + rect.height / 2) + 'px';
        portal.style.left = (rect.right + 10) + 'px';
        portal.style.transform = 'translateY(-50%)';
        portal.classList.add('show');
    }

    function hideTooltip() {
        clearTimeout(tooltipTimer);
        tooltipTimer = setTimeout(() => {
            currentTarget = null;
            portal.classList.remove('show');
        }, 100);
    }

    // Event delegation di document supaya tetap jalan untuk item
    // yang dipindah/di-clone (mis. topnav "Lainnya")
    document.addEventListener('mouseenter', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;
        const el = e.target.closest('[data-tooltip]');
        if (el) showTooltip(el);
    }, true);

    document.addEventListener('mouseleave', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;
        const el = e.target.closest('[data-tooltip]');
        if (!el || el !== currentTarget) return;
        // PENTING: mouseenter/mouseleave tidak bubble dan trigger per
        // elemen. Kalau kursor cuma pindah dari ikon ke label nav-link
        // (masih anak dari elemen [data-tooltip] yang sama), relatedTarget
        // masih ada DI DALAM el — jangan dianggap "keluar", supaya tooltip
        // tidak langsung hilang sepersekian detik setelah muncul.
        const to = e.relatedTarget;
        if (to && el.contains(to)) return;
        clearTimeout(tooltipTimer);
        tooltipTimer = setTimeout(hideTooltip, 100);
    }, true);

    // Sembunyikan tooltip saat nav di-scroll (posisi target berubah),
    // saat sidebar toggle collapse/expand, atau saat window di-resize
    document.querySelector('#bnspSidebar > nav')?.addEventListener('scroll', hideTooltip);
    sidebar.addEventListener('scroll', hideTooltip, true);
    sidebar.addEventListener('transitionend', hideTooltip);
    window.addEventListener('resize', hideTooltip);
})();