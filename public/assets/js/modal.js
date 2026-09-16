/**
 * public/assets/js/modal.js
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
 *     data-submit='<button class="btn btn-primary" type="button">Simpan</button>'
 *     data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
 *     Lihat Detail
 * </button>
 */

(function () {
    'use strict';

    const MODAL_ID = 'dynamicModal';
    let currentAbortController = null;

    function getMeta(name) {
        const el = document.querySelector(`meta[name="${name}"]`);
        return el ? el.content : null;
    }

    function ensureModalExists() {
        if (document.getElementById(MODAL_ID)) return;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div class="modal fade modal-custom" id="${MODAL_ID}" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
                <div class="modal-dialog" id="modalSize">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body" id="modalContent"></div>
                        <div class="modal-footer" id="modalFooter"></div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(wrapper.firstElementChild);
    }

    function extractButtonOptions(triggerEl) {
        const buttons = [];
        if (triggerEl.dataset.submit) buttons.push(triggerEl.dataset.submit);
        if (triggerEl.dataset.cancel) buttons.push(triggerEl.dataset.cancel);
        if (triggerEl.dataset.close) buttons.push(triggerEl.dataset.close);
        return buttons;
    }

    function applyModalSize(size) {
        const dialogEl = document.getElementById('modalSize');
        dialogEl.className = 'modal-dialog';

        const sizeMap = {
            sm: 'modal-sm',
            lg: 'modal-lg',
            l: 'modal-lg',
            xl: 'modal-xl',
        };

        if (sizeMap[size]) dialogEl.classList.add(sizeMap[size]);
    }

    async function openDynamicModalWithAjax(options) {
        ensureModalExists();
        const modalEl = document.getElementById(MODAL_ID);

        applyModalSize(options.size);
        document.getElementById('modalTitle').innerHTML = options.title || '';
        document.getElementById('modalContent').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Memuat...</span>
                </div>
            </div>
        `;
        document.getElementById('modalFooter').innerHTML = (options.buttons || []).join('');

        // Cek apakah Bootstrap sudah dimuat
        if (!window.bootstrap) {
            console.error('Bootstrap tidak ditemukan! Pastikan bootstrap.bundle.min.js sudah dimuat.');
            return;
        }

        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();

        // Batalkan request lama
        if (currentAbortController) currentAbortController.abort();
        currentAbortController = new AbortController();

        try {
            const response = await fetch(options.url, {
                method: options.method || 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getMeta('_token'),
                    'Accept': 'text/html',
                },
                signal: currentAbortController.signal,
            });

            if (!response.ok) throw new Error(`Kesalahan: ${response.status} ${response.statusText}`);

            const html = await response.text();
            document.getElementById('modalContent').innerHTML = html;

            if (typeof options.onContentLoaded === 'function') {
                options.onContentLoaded(html);
            }
        } catch (error) {
            if (error.name === 'AbortError') return;

            document.getElementById('modalContent').innerHTML = `
                <div class="alert alert-danger mb-0">
                    Gagal memuat konten: ${error.message}
                </div>
            `;
        }
    }

    // Event klik yang konsisten dengan atribut data-toggle="ajax-modal"
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
            // 🔥 INI YANG DITAMBAHKAN
            onContentLoaded: function (html) {
                // Dispatch custom event supaya bisa didengar dari luar
                document.dispatchEvent(new CustomEvent('ajax-modal:loaded', {
                    detail: { modalId: MODAL_ID }
                }));
            }

        });
    });

    window.openDynamicModalWithAjax = openDynamicModalWithAjax;
})();