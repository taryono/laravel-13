/**
 * modal.js
 * Modal Bootstrap 5 terpusat — full vanilla JS (tanpa jQuery)
 *
 * Cara pakai di Blade:
 * <button
 *     data-toggle="ajax-modal"
 *     data-size="lg"
 *     data-method="GET"
 *     data-title="Detail Produk #{{ $product->id }}"
 *     data-url="{{ route('products.modal-detail', $product->id) }}"
 *     data-submit='<button class="btn btn-primary" type="button" onclick="saveProduct({{ $product->id }})">Simpan</button>'
 *     data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
 *     Lihat Detail
 * </button>
 */

(function () {
    'use strict';

    const MODAL_ID = 'dynamicModal';
    let currentAbortController = null; // untuk cancel request lama kalau modal dibuka ulang cepat

    function getMeta(name) {
        const el = document.querySelector(`meta[name="${name}"]`);
        return el ? el.content : null;
    }

    /**
     * Pastikan struktur modal ada di DOM. Kalau belum ada, buat sekali saja.
     */
    function ensureModalExists() {
        let modalEl = document.getElementById(MODAL_ID);
        if (modalEl) return modalEl;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div class="modal fade" id="${MODAL_ID}" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
                <div class="modal-dialog" id="modalSize">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modalContent"></div>
                        <div class="modal-footer" id="modalFooter"></div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(wrapper.firstElementChild);
        return document.getElementById(MODAL_ID);
    }

    /**
     * Ambil daftar tombol footer dari data-attribute tombol trigger.
     */
    function extractButtonOptions(triggerEl) {
        const buttons = [];
        if (triggerEl.dataset.submit) buttons.push(triggerEl.dataset.submit);
        if (triggerEl.dataset.cancel) buttons.push(triggerEl.dataset.cancel);
        if (triggerEl.dataset.close) buttons.push(triggerEl.dataset.close);
        return buttons;
    }

    /**
     * Set ukuran modal-dialog berdasarkan opsi size.
     */
    function applyModalSize(size) {
        const dialogEl = document.getElementById('modalSize');
        dialogEl.className = 'modal-dialog'; // reset dulu

        const sizeMap = {
            sm: 'modal-sm',
            lg: 'modal-lg',
            l: 'modal-lg', // alias biar kompatibel sama kode lama kamu
            xl: 'modal-xl',
        };

        if (sizeMap[size]) {
            dialogEl.classList.add(sizeMap[size]);
        }
    }

    /**
     * Tampilkan modal dan load konten via AJAX (fetch).
     */
    async function openDynamicModalWithAjax(options) {
        const modalEl = ensureModalExists();

        applyModalSize(options.size);

        document.getElementById('modalTitle').innerHTML = options.title || '';
        document.getElementById('modalContent').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        document.getElementById('modalFooter').innerHTML = (options.buttons || []).join('');

        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();

        // Batalkan request sebelumnya kalau ada (misal user klik cepat 2 tombol berbeda)
        if (currentAbortController) {
            currentAbortController.abort();
        }
        currentAbortController = new AbortController();

        try {
            const response = await fetch(options.url, {
                method: options.method || 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getMeta('_token'),
                },
                signal: currentAbortController.signal,
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status} ${response.statusText}`);
            }

            const html = await response.text();
            document.getElementById('modalContent').innerHTML = html;

            if (typeof options.onContentLoaded === 'function') {
                options.onContentLoaded(html);
            }
        } catch (error) {
            // Abort tidak dianggap error sungguhan (misal modal ditutup/diganti cepat)
            if (error.name === 'AbortError') return;

            document.getElementById('modalContent').innerHTML = `
                <div class="alert alert-danger mb-0">
                    Gagal memuat konten: ${error.message}
                </div>
            `;
        }
    }

    /**
     * Bind semua tombol trigger modal yang ada di halaman saat ini.
     * Pakai event delegation supaya tombol yang di-render dinamis
     * (misal lewat AJAX/datatable) tetap otomatis ke-bind.
     */
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-toggle="ajax-modal"]');
        if (!trigger) return;

        e.preventDefault();

        openDynamicModalWithAjax({
            size: trigger.dataset.size,
            title: trigger.dataset.title,
            url: trigger.dataset.url,
            method: trigger.dataset.method || 'GET',
            buttons: extractButtonOptions(trigger),
        });
    });

    // Expose ke global scope kalau ada kebutuhan panggil manual dari file lain
    window.openDynamicModalWithAjax = openDynamicModalWithAjax;
})();