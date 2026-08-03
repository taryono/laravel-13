/**
 * public/assets/js/button.js
 * BUTTON.JS - REFACTORED 2026 (v2 - bugfix pass)
 * =======================================================================
 *
 * Perubahan dari versi sebelumnya:
 * 1. handleModalSubmit yang double-defined -> disatukan jadi satu definisi.
 * 2. DataTableContext.restore() sebelumnya dipanggil DUA KALI untuk satu
 *    DataTableContext.set() (sekali di dalam Ajax.submitFormData, sekali
 *    lagi di callback onSuccess/onError pemanggilnya). Ini bikin stack
 *    context ke-pop dua kali dan bisa salah restore context punya
 *    operasi lain yang sedang berjalan bersamaan. Sekarang aturan
 *    tunggal: SIAPA YANG MEMANGGIL set() -> DIA YANG restore(). Ajax
 *    helper generik (submitFormData/deleteWithConfirm) tidak lagi
 *    melakukan restore sendiri.
 * 3. Semua reload tabel disatukan lewat DataTableHelper.reload() /
 *    reloadWithContext(), termasuk yang sebelumnya masih pakai variabel
 *    global `datatable` secara langsung (handleFormSubmit,
 *    handleDeleteFile, handleRetryObservation).
 * 4. utils.parseErrors sekarang benar-benar dipakai di semua path error,
 *    bukan dead code lagi.
 * 5. console.log di-gate lewat helper log() supaya tidak berisik di
 *    production; aktifkan lewat window.APP_DEBUG = true.
 * =======================================================================
 */

(function() {
    'use strict';

    // =============================================
    // 1. GUARD
    // =============================================
    if (window.__buttonJsInitialized) {
        console.log('✅ Button.js already initialized, skipping...');
        return;
    }
    window.__buttonJsInitialized = true;

    // =============================================
    // 1.5 DEBUG-GATED LOGGER
    // =============================================
    const DEBUG = window.APP_DEBUG === true;
    const log = (...args) => { if (DEBUG) console.log(...args); };
    const warn = (...args) => { if (DEBUG) console.warn(...args); };

    log('📦 Loading Button.js...');

    // =============================================
    // 2. WAIT FOR APPCORE
    // =============================================
    function waitForAppCore(callback, retries = 30) {
        if (typeof AppCore !== 'undefined' && AppCore) {
            log('✅ AppCore detected');
            callback();
            return;
        }

        if (retries === 0) {
            warn('⚠️ AppCore not available after timeout, using fallback mode');
            callback();
            return;
        }

        log(`⏳ Waiting for AppCore... (${retries} retries left)`);
        setTimeout(() => {
            waitForAppCore(callback, retries - 1);
        }, 100);
    }

    // =============================================
    // 3. MAIN INITIALIZATION
    // =============================================
    function initializeButtonJS() {
        // Cek dependensi (AppCore optional)
        const deps = [
            { name: 'jQuery', global: '$' },
            { name: 'SweetAlert2', global: 'Swal' }
        ];

        const missingDeps = deps.filter(dep => typeof window[dep.global] === 'undefined');
        if (missingDeps.length > 0) {
            warn('⚠️ Button.js: Missing dependencies:', missingDeps.map(d => d.name).join(', '));
            // Tetap lanjutkan
        }

        const hasAppCore = typeof AppCore !== 'undefined' && AppCore;
        if (!hasAppCore) {
            warn('⚠️ Button.js: Running in fallback mode (without AppCore)');
        }

        // =============================================
        // 4. KONSTANTA
        // =============================================
        const CONFIG = {
            SPINNER_HTML: '<i class="fa fa-spinner fa-spin me-2"></i>Loading...',
            TOAST_DURATION: 5000,
            RELOAD_DELAY: 1000,
            DEFAULT_CONFIRM_TITLE: 'Apakah Anda Yakin?',
            DEFAULT_DELETE_TITLE: 'Apakah Anda Yakin Untuk Hapus Data ini?',
        };

        // =============================================
        // 4.5. GLOBAL DATA TABLE CONTEXT
        // =============================================
        const DataTableContext = {
            // Default context
            _context: {
                executeDraw: true,
                tableId: null,
                reloadDelay: 1000,
                autoCloseModal: true,
                showNotification: true
            },

            // Stack untuk menyimpan multiple context (jika ada banyak tabel)
            _stack: [],

            /**
             * Set context untuk operasi saat ini.
             * PENTING: setiap pemanggilan set() WAJIB dipasangkan dengan
             * TEPAT SATU pemanggilan restore() oleh pemanggil yang sama,
             * supaya stack tidak desync.
             */
            set: function(options = {}) {
                // Simpan context lama ke stack
                this._stack.push({ ...this._context });

                // Update dengan options baru
                this._context = {
                    ...this._context,
                    ...options
                };

                log('📊 DataTable Context Set:', this._context);
                return this._context;
            },

            /**
             * Reset ke context sebelumnya.
             */
            restore: function() {
                if (this._stack.length > 0) {
                    this._context = this._stack.pop();
                    log('📊 DataTable Context Restored:', this._context);
                }
                return this._context;
            },

            /**
             * Get current context
             */
            get: function() {
                return this._context;
            },

            /**
             * Get specific value
             */
            getValue: function(key, defaultValue = null) {
                return this._context[key] !== undefined ? this._context[key] : defaultValue;
            },

            /**
             * Clear all context
             */
            clear: function() {
                this._context = {
                    executeDraw: true,
                    tableId: null,
                    reloadDelay: 1000,
                    autoCloseModal: true,
                    showNotification: true
                };
                this._stack = [];
                log('📊 DataTable Context Cleared');
            },

            /**
             * Auto-detect table ID dari DOM
             */
            detectTableId: function() {
                // Coba dari element yang aktif
                const $activeTable = $('table.dataTable:visible').first();
                if ($activeTable.length) {
                    const id = $activeTable.attr('id');
                    if (id) {
                        this._context.tableId = id;
                        return id;
                    }
                }

                // Coba dari URL atau meta
                const url = window.location.href;
                const match = url.match(/table[-_]?id=([^&]+)/i);
                if (match) {
                    this._context.tableId = match[1];
                    return match[1];
                }

                return null;
            }
        };

        // Expose ke global
        window.DataTableContext = DataTableContext;

        // =============================================
        // 4.6. DATATABLE HELPER
        // =============================================
        const DataTableHelper = {
            /**
             * Get target table berdasarkan context atau parameter
             */
            _getTargetTable: function(table = null) {
                // 1. Dari parameter
                if (table && typeof table.draw === 'function') {
                    return table;
                }

                // 2. Dari context tableId
                const tableId = DataTableContext.getValue('tableId');
                if (tableId) {
                    const $table = $(`#${tableId}`);
                    if ($table.length) {
                        try {
                            const dt = $table.DataTable();
                            if (dt && typeof dt.draw === 'function') {
                                return dt;
                            }
                        } catch (e) {}
                    }
                }

                // 3. Dari variabel global
                if (typeof datatable !== 'undefined' && datatable !== null && typeof datatable.draw === 'function') {
                    return datatable;
                }

                if (typeof table !== 'undefined' && table !== null && typeof table.draw === 'function') {
                    return table;
                }

                // 4. Auto-detect dari DOM
                const $tables = $('table.dataTable');
                if ($tables.length > 0) {
                    try {
                        const dt = $tables.first().DataTable();
                        if (dt && typeof dt.draw === 'function') {
                            // Simpan ID ke context
                            const id = $tables.first().attr('id');
                            if (id) {
                                DataTableContext.set({ tableId: id });
                            }
                            return dt;
                        }
                    } catch (e) {}
                }

                warn('⚠️ DataTable tidak ditemukan untuk di-reload');
                return null;
            },

            /**
             * Reload DataTable dengan aman
             */
            reload: function(table = null) {
                const dt = this._getTargetTable(table);
                if (dt) {
                    dt.draw();
                    return true;
                }
                return false;
            },

            /**
             * Reload dengan clear cache
             */
            reloadWithCacheClear: function(table = null) {
                const dt = this._getTargetTable(table);
                if (dt) {
                    if (typeof dt.clearCache === 'function') {
                        dt.clearCache();
                    }
                    dt.draw();
                    return true;
                }
                return false;
            },

            /**
             * Reload dengan delay (untuk kasus tertentu)
             */
            reloadDelayed: function(delay = null, table = null) {
                const delayTime = delay || DataTableContext.getValue('reloadDelay', 1000);
                setTimeout(() => {
                    this.reload(table);
                }, delayTime);
            },

            /**
             * Reload dengan semua opsi dari context
             */
            reloadWithContext: function(table = null) {
                const context = DataTableContext.get();
                const dt = this._getTargetTable(table);

                if (!dt) return false;

                // Clear cache jika diminta
                if (context.clearCache) {
                    if (typeof dt.clearCache === 'function') {
                        dt.clearCache();
                    }
                }

                // Draw dengan delay
                if (context.reloadDelay > 0) {
                    setTimeout(() => {
                        dt.draw();
                    }, context.reloadDelay);
                } else {
                    dt.draw();
                }

                return true;
            }
        };

        // =============================================
        // 5. UTILITY FUNCTIONS
        // =============================================
        const utils = {
            csrfToken: () => {
                const token = $('meta[name="csrf-token"]').attr('content');
                if (token) return token;

                if (hasAppCore && AppCore.utils) {
                    return AppCore.utils.getMeta('csrf-token');
                }
                return null;
            },

            toBool: (value, defaultValue = false) => {
                if (value === undefined || value === null || value === '') return defaultValue;
                return value === true || value === 1 || value === '1' || value === 'true' || value === 'on';
            },

            setButtonLoading: ($button, loadingHtml = CONFIG.SPINNER_HTML) => {
                if (!$button || !$button.length) return () => {};

                const originalHtml = $button.html();
                const originalDisabled = $button.prop('disabled');

                $button.prop('disabled', true)
                       .addClass('loading')
                       .html(loadingHtml);

                return () => {
                    $button.prop('disabled', originalDisabled)
                           .removeClass('loading')
                           .html(originalHtml);
                };
            },

            refreshSelectpicker: ($el) => {
                if ($el && $el.selectpicker) {
                    $el.selectpicker('refresh');
                }
            },

            /**
             * Ambil pesan error yang paling relevan, baik dari response
             * jQuery XHR (xhr.responseJSON) maupun dari error object hasil
             * fetch/AppCore (error.response.data). Dipakai konsisten di
             * semua path error di bawah - sebelumnya fungsi ini dead code.
             */
            parseErrors: (error) => {
                // Bentuk jQuery XHR langsung
                if (error?.responseJSON?.errors) {
                    return Object.values(error.responseJSON.errors).flat().join('<br>');
                }
                if (error?.responseJSON?.message) {
                    return error.responseJSON.message;
                }

                // Bentuk AppCore / axios-like: error.response.data
                if (error?.response?.data?.errors) {
                    return Object.values(error.response.data.errors).flat().join('<br>');
                }
                if (error?.response?.data?.message) {
                    return error.response.data.message;
                }

                return error?.statusText || error?.message || 'Terjadi kesalahan';
            }
        };

        // =============================================
        // 6. UI HELPERS
        // =============================================
        const UI = {
            notify: (status, message) => {
                if (!message) return;

                if (hasAppCore && AppCore.ui) {
                    if (status === 'success') {
                        AppCore.ui.notify.success(message);
                    } else if (status === 'error') {
                        AppCore.ui.notify.error(message);
                    } else if (status === 'warning') {
                        AppCore.ui.notify.warning(message);
                    } else {
                        AppCore.ui.notify.info(message);
                    }
                    return;
                }

                // Fallback
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: status,
                        title: message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    alert(message);
                }
            },

            confirm: (options = {}) => {
                if (hasAppCore && AppCore.ui) {
                    return AppCore.ui.confirm(options);
                }

                if (typeof Swal !== 'undefined') {
                    const defaults = {
                        title: 'Anda yakin?',
                        text: 'Data yang sudah diproses tidak bisa dikembalikan.',
                        icon: 'warning',
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    };
                    const config = { ...defaults, ...options };

                    return Swal.fire({
                        ...config,
                        showCancelButton: true,
                        reverseButtons: true
                    }).then(result => result.isConfirmed);
                }

                return Promise.resolve(confirm(options.text || 'Anda yakin?'));
            },

            warn: (title, options = {}) => {
                if (typeof Swal === 'undefined') {
                    alert(title);
                    return Promise.resolve();
                }

                return Swal.fire({
                    icon: 'warning',
                    title,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                    ...options
                });
            }
        };

        // =============================================
        // 7. AJAX HELPERS
        // =============================================
        const Ajax = {
            /**
             * Catatan: fungsi ini TIDAK melakukan DataTableContext.restore()
             * sendiri. Siapa pun yang men-set context sebelum memanggil
             * fungsi ini bertanggung jawab me-restore-nya (lihat
             * EventHandlers.handleModalSubmit sebagai contoh pola yang
             * benar). Ini mencegah double-restore yang bisa membuat stack
             * context desync ketika ada beberapa operasi berjalan hampir
             * bersamaan.
             */
            submitFormData: async ($form, opts = {}) => {
                const {
                    button = null,
                    closeButton = null,
                    executeDraw = true,
                    tableId = null,
                    reloadDelay = 1000,
                    autoCloseModal = true,
                    showNotification = true,
                    onSuccess = null,
                    onError = null
                } = opts;

                const $button = button ? $(button) : null;
                const restoreButton = $button ? utils.setButtonLoading($button) : (() => {});

                const formData = new FormData($form[0]);
                const url = $form.attr('action');
                const method = $form.attr('method') || 'POST';

                try {
                    if ($form.validate && $form.data('validator')) {
                        if (!$form.valid()) {
                            UI.notify('error', 'Form Tidak Valid');
                            restoreButton();
                            return;
                        }
                    }
                    log('📦 Submitting form...submitFormData');
                    let response;
                    if (hasAppCore && AppCore.ajax) {
                        response = await AppCore.ajax.request({
                            url,
                            method,
                            data: formData,
                            showLoading: false,
                            successMessage: null
                        });
                    } else {
                        response = await new Promise((resolve, reject) => {
                            $.ajax({
                                url,
                                method,
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: resolve,
                                error: reject
                            });
                        });
                    }

                    if (response.status === 'success' || response.success === true) {
                        $form[0].reset();

                        // Auto close modal
                        if (autoCloseModal && closeButton) {
                            $(closeButton).trigger('click');
                        }

                        // Reload DataTable
                        if (executeDraw) {
                            if (tableId) {
                                DataTableHelper.reloadDelayed(reloadDelay, $(`#${tableId}`).DataTable());
                            } else {
                                DataTableHelper.reloadWithContext();
                            }
                        }

                        // Redirect jika ada
                        if (response.redirectTo) {
                            setTimeout(() => {
                                window.location.href = response.redirectTo;
                            }, reloadDelay);
                        }

                        // Notification
                        if (showNotification) {
                            UI.notify('success', response.message);
                        }

                    } else {
                        UI.notify('error', response.message || 'Terjadi kesalahan');
                        if (onError) onError(response);
                    }
                    restoreButton();
                    if (onSuccess) onSuccess(response);
                    $form.find('button[type="submit"]').prop('disabled', false);

                } catch (error) {
                    restoreButton();
                    $form.find('button[type="submit"]').prop('disabled', false);

                    if (onError) onError(error);
                    UI.notify('error', utils.parseErrors(error));
                }
            },

            deleteWithConfirm: async (url, opts = {}) => {
                const {
                    confirmTitle = CONFIG.DEFAULT_DELETE_TITLE,
                    confirmButtonText = 'Ya, hapus!',
                    onSuccess = null,
                    onError = null
                } = opts;

                const confirmed = await UI.confirm({
                    title: confirmTitle,
                    confirmButtonText
                });

                if (!confirmed) return;

                try {
                    let response;
                    log('📦 Submitting form...deleteWithConfirm');
                    if (hasAppCore && AppCore.ajax) {
                        response = await AppCore.ajax.request({
                            url,
                            method: 'DELETE',
                            showLoading: true,
                            loadingMessage: 'Menghapus data...'
                        });
                    } else {
                        response = await new Promise((resolve, reject) => {
                            $.ajax({
                                url,
                                method: 'DELETE',
                                data: { _token: utils.csrfToken() },
                                success: resolve,
                                error: reject
                            });
                        });
                    }

                    UI.notify('success', response.message || 'Data berhasil dihapus');
                    if (onSuccess) onSuccess(response);
                    return response;

                } catch (error) {
                    if (onError) onError(error);
                    UI.notify('error', utils.parseErrors(error));
                    throw error;
                }
            },

            getWithConfirm: async (url, opts = {}) => {
                const {
                    confirmTitle = 'Konfirmasi',
                    confirmText = '',
                    confirmButtonText = 'Ya',
                    cancelButtonText = 'Batal',
                    onSuccess = null,
                    onCancelled = null
                } = opts;

                const confirmed = await UI.confirm({
                    title: confirmTitle,
                    text: confirmText,
                    confirmButtonText,
                    cancelButtonText
                });

                if (!confirmed) {
                    if (onCancelled) onCancelled();
                    return;
                }

                try {
                    let response;
                    log('📦 Submitting form...getWithConfirm');
                    if (hasAppCore && AppCore.ajax) {
                        response = await AppCore.ajax.request({
                            url,
                            method: 'GET',
                            showLoading: true,
                            loadingMessage: 'Memproses...'
                        });
                    } else {
                        response = await new Promise((resolve, reject) => {
                            $.ajax({
                                url,
                                method: 'GET',
                                success: resolve,
                                error: reject
                            });
                        });
                    }

                    if (response.status === 'success' || response.success === true) {
                        await Swal.fire('Berhasil!', response.message, 'success');
                        if (onSuccess) onSuccess(response);
                    } else {
                        await Swal.fire('Gagal!', response.message, 'error');
                    }

                    return response;

                } catch (error) {
                    await Swal.fire('Error!', utils.parseErrors(error), 'error');
                    throw error;
                }
            },

            loadModalContent: async (url, $modal, title, opts = {}) => {
                const $target = $modal.find('.modal-body');
                const $title = $modal.find('h5.modal-title');

                if ($title.length) {
                    $title.html('<span></span>').find('span').text(title || '');
                }

                $target.addClass('loading').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');

                try {
                    let response;
                    if (hasAppCore && AppCore.ajax) {
                        response = await AppCore.ajax.request({
                            url,
                            method: 'GET',
                            showLoading: false
                        });
                    } else {
                        response = await new Promise((resolve, reject) => {
                            $.ajax({
                                url,
                                method: 'GET',
                                success: resolve,
                                error: reject
                            });
                        });
                    }

                    if (typeof response === 'string') {
                        $target.removeClass('loading').html(response);
                    } else {
                        $target.removeClass('loading').html(response.html || response);
                    }

                    if (opts.afterLoad) opts.afterLoad($target);

                    if (hasAppCore && AppCore.refresh) {
                        AppCore.refresh($target[0]);
                    }

                } catch (error) {
                    $target.removeClass('loading').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Gagal memuat konten: ${utils.parseErrors(error)}
                        </div>
                    `);
                }
            }
        };

        // =============================================
        // 8. EVENT HANDLERS
        // =============================================
        const EventHandlers = {
            init: function() {
                $(document)
                    .on('click', '.modal-submit', this.handleModalSubmit)
                    .on('click', '.submit-button', this.handleSubmitButton)
                    .on('submit', 'form.form-horizontal', this.handleFormSubmit)
                    .on('click', '.create-inline, .show-inline, .edit-inline, .anonimous-inline', this.handleInlineNavigation)
                    .on('click', 'button.back', this.handleBackButton)
                    .on('click', '.delete-button', this.handleDeleteButton)
                    .on('click', '.deleteFile', this.handleDeleteFile)
                    .on('click', '.deleteIconFile', this.handleDeleteIconFile)
                    .on('click', '#retry-observation', this.handleRetryObservation)
                    .on('change', 'input.email_verification_status', this.handleEmailVerification)
                    .on('click', '[data-datatable-context]', this.handleSetContext)
                    .on('click', '[data-datatable-reset]', this.handleResetContext);
            },

            /**
             * HANDLE SET CONTEXT DARI ATTRIBUTE
             * Contoh penggunaan:
             * <button data-datatable-context='{"executeDraw":true,"tableId":"action-table"}'>
             */
            handleSetContext: function(e) {
                const $button = $(this);
                const contextData = $button.data('datatable-context');

                if (contextData) {
                    if (typeof contextData === 'string') {
                        try {
                            const parsed = JSON.parse(contextData);
                            DataTableContext.set(parsed);
                        } catch (e) {
                            warn('⚠️ Invalid JSON in data-datatable-context');
                        }
                    } else {
                        DataTableContext.set(contextData);
                    }
                }
            },

            /**
             * HANDLE RESET CONTEXT
             */
            handleResetContext: function(e) {
                e.preventDefault();
                DataTableContext.restore();
            },

            /**
             * Handle klik tombol submit di dalam modal.
             *
             * Pola yang benar untuk context stack:
             * set() dipanggil sekali di sini -> restore() juga dipanggil
             * TEPAT SEKALI di sini (lewat onSuccess/onError), dan
             * Ajax.submitFormData TIDAK ikut melakukan restore.
             */
            handleModalSubmit: function(e) {
                e.preventDefault();

                const $button = $(this);
                const $form = $button.closest('.modal-content').find('form');
                const $closeButton = $button.closest('.modal').find('button.btn-close');

                // Ambil dari context atau attribute
                let executeDraw = DataTableContext.getValue('executeDraw', true);
                let tableId = DataTableContext.getValue('tableId');

                // Override dari attribute jika ada
                if ($button.attr('data-is_execute_drawer') !== undefined) {
                    executeDraw = utils.toBool($button.attr('data-is_execute_drawer'), true);
                }
                if ($button.attr('data-datatable-id')) {
                    tableId = $button.attr('data-datatable-id');
                }

                // Set context sebelum submit
                DataTableContext.set({
                    executeDraw,
                    tableId,
                    autoCloseModal: DataTableContext.getValue('autoCloseModal', true)
                });

                Ajax.submitFormData($form, {
                    button: $button,
                    closeButton: $closeButton,
                    executeDraw,
                    tableId,
                    onSuccess: function(response) {
                        DataTableContext.restore();
                    },
                    onError: function(error) {
                        DataTableContext.restore();
                    }
                });
            },

            /**
             * Handle klik tombol dengan class .submit-button.
             *
             * Sebelumnya method ini di-reference di init() tapi tidak
             * pernah didefinisikan (this.handleSubmitButton === undefined),
             * yang membuat jQuery .on() menerima handler undefined dan
             * salah menafsirkan selector '.submit-button' sebagai handler
             * -> crash "Cannot create property 'guid' on string".
             *
             * Implementasi ini menyimpan context tombol (button, tombol
             * close modal, flag executeDraw) ke $form.data('submitCtx'),
             * lalu men-trigger event native 'submit' pada form terdekat.
             * handleFormSubmit sudah dirancang untuk membaca
             * $form.data('submitCtx') persis untuk kasus ini.
             */
            handleSubmitButton: function(e) {
                e.preventDefault();

                const $button = $(this);
                const $form = $button.closest('form');

                if (!$form.length) {
                    warn('⚠️ .submit-button diklik tapi tidak ditemukan <form> terdekat');
                    return;
                }

                $form.data('submitCtx', {
                    targetElement: $form.data('target'),
                    button: $button,
                    closeButton: $button.closest('.modal').find('button.btn-close'),
                    executeDraw: utils.toBool($button.attr('data-is_execute_drawer'), true)
                });

                $form.trigger('submit');
            },

            proceedSignedSubmit: async function($form, $button) {
                const confirmed = await UI.confirm({
                    title: 'Apakah Anda Yakin Untuk Menandatangani Formulir Ini?',
                    confirmButtonText: 'Ya, Tandatangani'
                });

                if (!confirmed) return;
                $form.trigger('submit');
            },

            handleFormSubmit: function(e) {
                e.preventDefault();

                const $form = $(this);
                const ctx = $form.data('submitCtx') || {
                    targetElement: $form.data('target'),
                    button: $form.find('button[type="submit"]').first(),
                    closeButton: $form.closest('.modal').find('button.btn-close'),
                    executeDraw: utils.toBool($form.attr('data-is_execute_drawer'), false)
                };

                $form.removeData('submitCtx');

                if ($form.validate && $form.data('validator')) {
                    if (!$form.valid()) {
                        UI.notify('error', 'Form Tidak Valid');
                        return;
                    }
                }

                if ($form.hasClass('uploadForm')) {
                    Ajax.submitFormData($form, {
                        button: ctx.button,
                        closeButton: ctx.closeButton,
                        executeDraw: ctx.executeDraw,
                        fallbackReload: true
                    });
                    return;
                }

                const url = $form.attr('action');
                const method = $form.attr('method') || 'POST';
                const $button = ctx.button && ctx.button.length ? ctx.button : null;
                const restoreButton = $button ? utils.setButtonLoading($button) : (() => {});

                const ajaxPromise = (hasAppCore && AppCore.ajax)
                    ? AppCore.ajax.request({
                        url,
                        method,
                        data: $form.serialize(),
                        showLoading: false,
                        successMessage: null
                    })
                    : new Promise((resolve, reject) => {
                        $.ajax({
                            url,
                            method,
                            data: $form.serialize(),
                            success: resolve,
                            error: reject
                        });
                    });

                ajaxPromise
                    .then(response => {
                        restoreButton();
                        log('📦 Submitting form...handleFormSubmit');
                        if (response.status === 'success' || response.success === true) {
                            UI.notify('success', response.message || 'Data berhasil disimpan');
                            $(document).trigger('form:saved', [$form.attr('id')]);

                            if (ctx.targetElement && response.data) {
                                $(ctx.targetElement).html(response.data);
                            } else if (response.redirectTo) {
                                if ($form.hasClass('uploadForm')) {
                                    window.history.pushState({ urlPath: response.redirectTo }, '', response.redirectTo);
                                    if (typeof loadSliceData === 'function') {
                                        loadSliceData(response.redirectTo, null, 'div.main-content');
                                    }
                                } else {
                                    window.location.href = response.redirectTo;
                                }
                            }
                        } else {
                            UI.notify('error', response.message || 'Terjadi kesalahan');
                        }

                        if (ctx.closeButton && ctx.closeButton.length) {
                            ctx.closeButton.trigger('click');
                        }

                        // Disatukan lewat DataTableHelper (sebelumnya
                        // langsung memanggil variabel global `datatable`).
                        if (ctx.executeDraw) {
                            DataTableHelper.reload();
                        }
                    })
                    .catch(error => {
                        restoreButton();
                        UI.notify('error', utils.parseErrors(error));
                    });
            },

            handleInlineNavigation: function(e) {
                e.preventDefault();

                const href = $(this).attr('data-url');
                if (href && href.length > 0 && href !== '#') {
                    $('.btn-close').trigger('click');
                }

                if (typeof loadSliceData === 'function') {
                    loadSliceData(href, null, 'div.main-content');
                } else {
                    window.location.href = href;
                }
            },

            handleBackButton: function(e) {
                e.preventDefault();

                const href = $(this).attr('data-url');
                if (href && href.length > 0 && href !== '#') {
                    window.history.pushState({ urlPath: href }, '', href);

                    if (typeof loadSliceData === 'function') {
                        loadSliceData(href, null, 'div.main-content');
                    } else {
                        window.location.href = href;
                    }
                }
            },

            /**
             * Handle klik tombol delete.
             *
             * Sama seperti handleModalSubmit: set() dan restore() dipanggil
             * di tempat yang sama (di sini), Ajax.deleteWithConfirm tidak
             * ikut me-restore context.
             */
            handleDeleteButton: function() {
                const $button = $(this);
                const url = $button.data('url');

                // Ambil dari attribute atau context
                let executeDraw = utils.toBool($button.attr('data-is_execute_drawer'), true);
                let tableId = $button.attr('data-datatable-id') || DataTableContext.getValue('tableId');

                // Jika executeDraw dari context tapi tidak override
                if ($button.attr('data-is_execute_drawer') === undefined) {
                    executeDraw = DataTableContext.getValue('executeDraw', true);
                }

                // Set context sebelum delete
                DataTableContext.set({
                    executeDraw,
                    tableId
                });

                Ajax.deleteWithConfirm(url, {
                    onSuccess: function(response) {
                        if (executeDraw) {
                            if (tableId) {
                                DataTableHelper.reload($(`#${tableId}`).DataTable());
                            } else {
                                DataTableHelper.reloadWithContext();
                            }
                        }
                        DataTableContext.restore();
                    },
                    onError: function(error) {
                        DataTableContext.restore();
                    }
                });
            },

            handleDeleteFile: function(e) {
                e.preventDefault();

                const $row = $(this).closest('tr');
                const url = $(this).parent().attr('data-url') || $(this).attr('data-url');

                if (!url) {
                    UI.notify('error', 'URL tidak ditemukan');
                    return;
                }

                Ajax.deleteWithConfirm(url, {
                    onSuccess: () => {
                        $row.remove();
                        // Disatukan lewat DataTableHelper (sebelumnya
                        // langsung memanggil variabel global `datatable`).
                        DataTableHelper.reload();
                    }
                });
            },

            handleDeleteIconFile: function(e) {
                e.preventDefault();

                const $target = $(this).parent();
                const $downloadLink = $(this).closest('form').find('.download-link');
                const url = $(this).attr('data-url');

                Ajax.deleteWithConfirm(url, {
                    confirmTitle: 'Apakah Anda Yakin Untuk Hapus Gambar ini?',
                    onSuccess: () => {
                        if ($downloadLink.length > 0) {
                            $downloadLink.attr('data-url', '');
                            $downloadLink.html('<i class="fa fa-trash"></i> File Telah Dihapus.!');
                        }
                        $target.html('<img src="/assets/images/no_image.jpg" width="100px" height="100px" />');
                    }
                });
            },

            handleRetryObservation: function() {
                const url = $(this).data('url');

                Ajax.getWithConfirm(url, {
                    confirmTitle: 'Konfirmasi',
                    confirmText: 'Apakah Anda yakin ingin mengulang observasi?',
                    confirmButtonText: 'Ya, Ulangi',
                    onSuccess: (response) => {
                        // Disatukan lewat DataTableHelper (sebelumnya
                        // langsung memanggil variabel global `datatable`).
                        DataTableHelper.reload();
                    }
                });
            },

            handleEmailVerification: function() {
                const $checkbox = $(this);
                const url = $checkbox.data('url');
                const isChecked = $checkbox.is(':checked');

                Ajax.getWithConfirm(url, {
                    confirmTitle: 'Konfirmasi Verifikasi Email',
                    confirmText: isChecked ? 'Aktifkan verifikasi email?' : 'Nonaktifkan verifikasi email?',
                    confirmButtonText: isChecked ? 'Ya, Aktifkan' : 'Ya, Nonaktifkan',
                    onSuccess: () => {},
                    onCancelled: () => {
                        $checkbox.prop('checked', !isChecked);
                    }
                });
            }
        };

        // =============================================
        // 9. DARK MODE TOGGLE
        // =============================================
        const DarkMode = {
            init: function() {
                const toggleBtn = document.getElementById('darkModeToggle');
                const icon = document.getElementById('darkModeIcon');
                const body = document.body;

                if (!toggleBtn || !icon) return;

                const savedMode = localStorage.getItem('theme') || 'light';
                this.applyMode(savedMode, body, icon, toggleBtn);

                toggleBtn.addEventListener('click', function() {
                    const isDark = body.classList.contains('dark-mode');
                    const newMode = isDark ? 'light' : 'dark';
                    DarkMode.applyMode(newMode, body, icon, toggleBtn);
                    localStorage.setItem('theme', newMode);
                });
            },

            applyMode: function(mode, body, icon, toggleBtn) {
                if (mode === 'dark') {
                    body.classList.add('dark-mode');
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                    body.classList.remove('light-mode');
                    if (icon) icon.textContent = 'light_mode';
                    if (toggleBtn) {
                        toggleBtn.classList.remove('btn-light');
                        toggleBtn.classList.add('btn-dark');
                    }
                } else {
                    document.documentElement.setAttribute('data-bs-theme', 'light');
                    body.classList.remove('dark-mode');
                    body.classList.add('light-mode');
                    if (icon) icon.textContent = 'dark_mode';
                    if (toggleBtn) {
                        toggleBtn.classList.remove('btn-dark');
                        toggleBtn.classList.add('btn-light');
                    }
                }
            }
        };

        // =============================================
        // 10. INITIALIZATION
        // =============================================
        function init() {
            EventHandlers.init();
            DarkMode.init();
            log('✅ Button.js initialized successfully');
        }

        // =============================================
        // 11. EXPOSE PUBLIC API
        // =============================================
        window.ButtonJS = {
            utils,
            UI,
            Ajax,
            DataTableHelper,
            submitFormData: Ajax.submitFormData,
            deleteWithConfirm: Ajax.deleteWithConfirm,
            getWithConfirm: Ajax.getWithConfirm,
            loadModalContent: Ajax.loadModalContent,
            notify: UI.notify,
            confirm: UI.confirm,
            init: init
        };

        // =============================================
        // 12. AUTO-INITIALIZATION
        // =============================================
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        window.reloadDataTable = DataTableHelper.reload;
        window.reloadDataTableWithCache = DataTableHelper.reloadWithCacheClear;
    }

    // =============================================
    // START - Tunggu AppCore
    // =============================================
    waitForAppCore(initializeButtonJS);

})();