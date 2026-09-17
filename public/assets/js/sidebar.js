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
    const topLevelNav = respectSidebarCollapse ? sidebar.querySelector(':scope > nav') : null;

    container.querySelectorAll('.bnsp-nav-parent').forEach(link => {
        const item = link.closest('.bnsp-nav-item');
        const isTopLevelSidebarItem = !!topLevelNav && item.parentElement === topLevelNav;

        // ===== CLICK EVENT =====
        link.addEventListener('click', (e) => {
            // Di mode collapsed, parent click hanya untuk navigasi
            if (respectSidebarCollapse && sidebar.classList.contains('collapsed') && isDesktop()) {
                e.preventDefault();
                // JANGAN toggle open, biarkan JS yang handle via hover
                return;
            }
            e.preventDefault();
            item.classList.toggle('open');
        });

        // ===== MOUSEENTER (HANYA SATU) =====
        item.addEventListener('mouseenter', (e) => {
            console.log('🟢 MOUSEENTER:', item.querySelector('.nav-label')?.textContent);
            
            if (isDesktop() && sidebar.classList.contains('collapsed')) {
                if (isTopLevelSidebarItem) {
                    console.log('📊 Membuka flyout portal untuk:', item.querySelector('.nav-label')?.textContent);
                    // PAKAI YANG BARU - openTopFlyoutNew
                    openTopFlyoutNew(item);
                } else {
                    console.log('📊 Menyesuaikan posisi submenu nested');
                    
                    requestAnimationFrame(() => {
                        adjustSubmenuPosition(item);
                    });
                }
            }
        });

        // ===== MOUSELEAVE (HANYA SATU) =====
        if (isTopLevelSidebarItem) {
            item.addEventListener('mouseleave', (e) => {
                console.log('🔴 MOUSELEAVE:', item.querySelector('.nav-label')?.textContent);
                
                const related = e.relatedTarget;
                
                // Jika mouse pindah ke dalam item sendiri
                if (related && item.contains(related)) {
                    console.log('⏭️ Masih di dalam item, skip close');
                    return;
                }
                
                // Jika mouse pindah ke submenu portal
                if (flyoutOpenSubmenu && related && flyoutOpenSubmenu.contains(related)) {
                    console.log('⏭️ Masuk ke submenu portal, skip close');
                    clearTimeout(flyoutHideTimer);
                    return;
                }
                
                scheduleCloseTopFlyout(item);
            });
        }
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
sidebar.querySelectorAll('.bnsp-nav-link:not(.bnsp-nav-parent)').forEach(link => {
    link.addEventListener('click', () => {
        if (!isDesktop()) {
            sidebar.classList.remove('mobile-open');
            backdrop.classList.remove('show');
        }
    });
}); 
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
    }else{
        console.log("tidak ada");
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

    const parentRect = menuItem.getBoundingClientRect();
    
    // Sembunyikan sebentar untuk ukur
    const tempDisplay = submenu.style.display;
    submenu.style.display = 'block';
    const submenuHeight = submenu.offsetHeight;
    submenu.style.display = tempDisplay;

    const spaceBelow = window.innerHeight - parentRect.bottom;
    const spaceAbove = parentRect.top;

    if (spaceBelow < submenuHeight && spaceAbove > spaceBelow) {
        submenu.classList.add('bnsp-flyout-up');
    } else {
        submenu.classList.remove('bnsp-flyout-up');
    }
}

// Tutup flyout portal saat nav collapsed di-scroll, sidebar
// di-toggle collapse/expand, atau window di-resize — supaya
// tidak nyangkut di posisi lama yang sudah tidak relevan
// document.querySelector('#bnspSidebar > nav')?.addEventListener('scroll', closeTopFlyout);
// sidebar.addEventListener('scroll', closeTopFlyout, true);
// sidebar.addEventListener('transitionend', closeTopFlyout);
window.addEventListener('resize', closeTopFlyout);

// ===== FLYOUT SUBMENU PORTAL untuk item level teratas =====
let flyoutHideTimer = null;
let flyoutOpenItem = null;
let flyoutOpenSubmenu = null;

function closeTopFlyout() {
    console.log('🔒 closeTopFlyout dipanggil');
    
    if (flyoutOpenSubmenu && flyoutOpenItem) {
        flyoutOpenSubmenu.classList.remove('bnsp-flyout-detached');
        flyoutOpenSubmenu.classList.remove('bnsp-flyout-up');
        flyoutOpenSubmenu.classList.remove('bnsp-flyout-left');
        flyoutOpenSubmenu.style.position = '';
        flyoutOpenSubmenu.style.top = '';
        flyoutOpenSubmenu.style.left = '';
        flyoutOpenSubmenu.style.bottom = '';
        flyoutOpenSubmenu.style.display = '';
        flyoutOpenSubmenu.style.maxHeight = '';
        flyoutOpenSubmenu.style.visibility = '';
        flyoutOpenSubmenu.style.opacity = '';

        // Kembalikan ke parent asli
        const parent = flyoutOpenSubmenu._originalParent || flyoutOpenItem;
        const nextSibling = flyoutOpenSubmenu._originalNextSibling || null;
        
        if (nextSibling) {
            parent.insertBefore(flyoutOpenSubmenu, nextSibling);
        } else {
            parent.appendChild(flyoutOpenSubmenu);
        }
        
        console.log('✅ Submenu dikembalikan ke parent');
    }
    flyoutOpenItem = null;
    flyoutOpenSubmenu = null;
}

function openTopFlyout(item) {
    const submenu = item.querySelector(':scope > .bnsp-submenu');
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
    console.log('🚀 openTopFlyoutNew dipanggil untuk:', item.querySelector('.nav-label')?.textContent);
    
    // CEK: apakah item memiliki submenu?
    const submenu = item.querySelector(':scope > .bnsp-submenu');
    if (!submenu) {
        console.log('❌ Tidak ada submenu ditemukan');
        return;
    }

    // CEK: apakah ini parent menu?
    if (!item.querySelector('.bnsp-nav-parent')) {
        console.log('❌ Bukan parent menu');
        return;
    }

    clearTimeout(flyoutHideTimer);

    // Sudah terbuka untuk item ini
    if (flyoutOpenItem === item && flyoutOpenSubmenu === submenu) {
        console.log('ℹ️ Sudah terbuka untuk item ini');
        return;
    }

    // Kalau ada flyout lain yang masih terbuka, tutup dulu
    if (flyoutOpenItem && flyoutOpenItem !== item) {
        console.log('🔒 Menutup flyout sebelumnya');
        closeTopFlyout();
    }

    // ===== PASTIKAN SUBMENU TIDAK TAMPIL DI CSS =====
    submenu.style.display = 'block';
    submenu.style.maxHeight = 'none';
    submenu.style.visibility = 'visible';
    submenu.style.opacity = '1';

    // Simpan posisi asli
    if (!submenu._originalParent) {
        submenu._originalParent = submenu.parentElement;
        submenu._originalNextSibling = submenu.nextSibling;
    }

    // Pindahkan ke body
    document.body.appendChild(submenu);
    submenu.classList.add('bnsp-flyout-detached');

    flyoutOpenItem = item;
    flyoutOpenSubmenu = submenu;

    // ===== POSISIKAN =====
    const gapVar = getComputedStyle(document.documentElement).getPropertyValue('--bnsp-flyout-gap');
    const gap = parseFloat(gapVar) || 12;
    const rect = item.getBoundingClientRect();

    // Posisi default (ke kanan)
    let leftPos = rect.right + gap;
    let topPos = rect.top - 8;
    let bottomPos = 'auto';

    // Cek ruang horizontal
    submenu.style.left = leftPos + 'px';
    submenu.style.top = topPos + 'px';
    submenu.style.bottom = bottomPos;

    // Tunggu browser render
    requestAnimationFrame(() => {
        const submenuRect = submenu.getBoundingClientRect();
        
        // ===== CEK VERTICAL =====
        const spaceBelow = window.innerHeight - rect.top;
        const spaceAbove = rect.bottom;
        
        if (spaceBelow < submenuRect.height && spaceAbove > spaceBelow) {
            // Tampilkan ke atas
            submenu.style.top = 'auto';
            const bottom = window.innerHeight - rect.bottom - 8;
            submenu.style.bottom = Math.max(8, bottom) + 'px';
            submenu.classList.add('bnsp-flyout-up');
        } else {
            // Tampilkan ke bawah dengan batas
            const maxTop = window.innerHeight - submenuRect.height - 8;
            const finalTop = Math.min(Math.max(8, rect.top - 8), maxTop);
            submenu.style.top = finalTop + 'px';
            submenu.classList.remove('bnsp-flyout-up');
        }

        // ===== CEK HORIZONTAL =====
        const finalRect = submenu.getBoundingClientRect();
        if (finalRect.right > window.innerWidth - 8) {
            // Tampilkan ke kiri
            submenu.style.left = Math.max(8, rect.left - finalRect.width - gap) + 'px';
            submenu.classList.add('bnsp-flyout-left');
        } else {
            submenu.classList.remove('bnsp-flyout-left');
        }
    });

    // ===== BIND LISTENER =====
    if (!submenu._flyoutBound) {
        submenu.addEventListener('mouseenter', () => {
            console.log('🟢 Mouse masuk ke submenu portal');
            clearTimeout(flyoutHideTimer);
        });

        submenu.addEventListener('mouseleave', (e) => {
            console.log('🔴 Mouse keluar dari submenu portal');
            const related = e.relatedTarget;
            if (related && submenu.contains(related)) {
                console.log('⏭️ Masih di dalam submenu');
                return;
            }
            scheduleCloseTopFlyout(item);
        });

        submenu._flyoutBound = true;
        console.log('✅ Listener submenu terpasang');
    }
}

function scheduleCloseTopFlyout(item) {
    clearTimeout(flyoutHideTimer);
    flyoutHideTimer = setTimeout(() => {
        if (flyoutOpenItem !== item) {
            console.log('⏭️ Item sudah berubah, skip close');
            return;
        }

        const submenu = flyoutOpenSubmenu;
        if (!submenu) {
            closeTopFlyout();
            return;
        }

        // Cek posisi mouse terakhir
        const mouseX = window._bnspMouseX;
        const mouseY = window._bnspMouseY;
        
        if (mouseX === undefined || mouseY === undefined) {
            console.log('❌ Posisi mouse tidak diketahui, close');
            closeTopFlyout();
            return;
        }

        const parentRect = item.getBoundingClientRect();
        const submenuRect = submenu.getBoundingClientRect();

        const insideParent = mouseX >= parentRect.left && mouseX <= parentRect.right &&
                             mouseY >= parentRect.top && mouseY <= parentRect.bottom;
        const insideSubmenu = mouseX >= submenuRect.left && mouseX <= submenuRect.right &&
                              mouseY >= submenuRect.top && mouseY <= submenuRect.bottom;

        if (insideParent || insideSubmenu) {
            console.log('⏭️ Mouse masih di area, skip close');
            return;
        }

        console.log('🔒 Menutup flyout karena mouse di luar area');
        closeTopFlyout();
    }, 250);
}
document.addEventListener('mousemove', (e) => {
    window._bnspMouseX = e.clientX;
    window._bnspMouseY = e.clientY;
});

// ===== TOOLTIP PORTAL =====
// Menggantikan tooltip ::after lama. Elemen dibuat sekali lalu
// di-append ke <body>, jadi posisinya lepas dari <nav> collapsed
// yang sekarang overflow-nya "auto" (bisa discroll). Dengan begitu
// tooltip tidak pernah ke-clip walau nav sedang dalam kondisi scroll.
// ===== TOOLTIP PORTAL =====
(function initTooltipPortal() {
    const portal = document.createElement('div');
    portal.className = 'bnsp-tooltip-portal';
    document.body.appendChild(portal);

    let currentTarget = null;
    let tooltipTimeout = null;

    function showTooltip(el) {
        clearTimeout(tooltipTimeout);
        
        // Debug
        console.log('🔵 SHOW TOOLTIP:', {
            element: el,
            text: el.getAttribute('data-tooltip'),
            isParent: el.classList.contains('bnsp-nav-parent'),
            isCollapsed: sidebar.classList.contains('collapsed'),
            isDesktop: isDesktop()
        });

        // Hanya aktif saat sidebar collapsed & desktop
        if (!sidebar.classList.contains('collapsed') || !isDesktop()) {
            console.log('❌ Skip: sidebar tidak collapsed atau bukan desktop');
            return;
        }

        // Skip parent menu (yang punya submenu)
        if (el.classList.contains('bnsp-nav-parent')) {
            console.log('❌ Skip: ini parent menu (punya submenu)');
            return;
        }

        // Skip item di dalam submenu
        if (el.closest('.bnsp-submenu')) {
            console.log('❌ Skip: item di dalam submenu');
            return;
        }

        const text = el.getAttribute('data-tooltip');
        if (!text) {
            console.log('❌ Skip: tidak ada data-tooltip');
            return;
        }

        currentTarget = el;
        portal.textContent = text;

        const rect = el.getBoundingClientRect();
        
        // Posisi di sebelah kanan
        let left = rect.right + 10;
        let top = rect.top + rect.height / 2;

        // Cek apakah tooltip keluar layar di kanan
        const portalWidth = text.length * 8 + 24; // perkiraan lebar
        if (left + portalWidth > window.innerWidth - 10) {
            // Tampilkan di kiri
            left = rect.left - portalWidth - 10;
        }

        // Cek apakah tooltip keluar layar di atas/bawah
        if (top < 10) top = 10;
        if (top > window.innerHeight - 10) top = window.innerHeight - 10;

        portal.style.left = left + 'px';
        portal.style.top = top + 'px';
        portal.style.transform = 'translateY(-50%)';
        
        // Tampilkan tooltip
        portal.classList.add('show');
        console.log('✅ Tooltip ditampilkan:', text);
    }

    function hideTooltip() {
        clearTimeout(tooltipTimeout);
        tooltipTimeout = setTimeout(() => {
            if (currentTarget) {
                portal.classList.remove('show');
                currentTarget = null;
                console.log('🔴 Tooltip disembunyikan');
            }
        }, 100);
    }

    // ===== EVENT LISTENER =====
    document.addEventListener('mouseenter', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;
        
        // Cari elemen dengan data-tooltip
        const el = e.target.closest('[data-tooltip]');
        if (el) {
            showTooltip(el);
        }
    }, true);

    document.addEventListener('mouseleave', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;
        
        const el = e.target.closest('[data-tooltip]');
        if (!el || el !== currentTarget) return;
        
        const to = e.relatedTarget;
        if (to && el.contains(to)) {
            console.log('⏭️ Masih di dalam elemen, skip hide');
            return;
        }
        
        hideTooltip();
    }, true);

    // ===== SEMBUNYIKAN SAAT SCROLL/RESIZE =====
    document.querySelector('#bnspSidebar > nav')?.addEventListener('scroll', () => {
        portal.classList.remove('show');
        currentTarget = null;
    });
    
    sidebar.addEventListener('scroll', () => {
        portal.classList.remove('show');
        currentTarget = null;
    }, true);
    
    sidebar.addEventListener('transitionend', () => {
        portal.classList.remove('show');
        currentTarget = null;
    });
    
    window.addEventListener('resize', () => {
        portal.classList.remove('show');
        currentTarget = null;
    });

    console.log('✅ Tooltip portal initialized');
})();

document.addEventListener('DOMContentLoaded', function () {
    // Cari dropdown yang berisi #profileDropdown
    const profileToggle = document.getElementById('profileDropdown');
    if (!profileToggle) return;

    const dropdown = profileToggle.closest('.dropdown');
    const menu = dropdown.querySelector('.dropdown-menu');
    const bsDropdown = bootstrap.Dropdown.getOrCreateInstance(profileToggle);

    let hideTimer = null;

    dropdown.addEventListener('mouseenter', function () {
        clearTimeout(hideTimer);
        bsDropdown.show();
    });

    dropdown.addEventListener('mouseleave', function () {
        hideTimer = setTimeout(function () {
            bsDropdown.hide();
        }, 100);
    });
});