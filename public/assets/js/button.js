/**
 * public/assets/js/button.js
 * BUTTON.JS - REFACTORED 2026
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

    console.log('📦 Loading Button.js...');

    // =============================================
    // 2. WAIT FOR APPCORE (TAMBAHKAN INI)
    // =============================================
    function waitForAppCore(callback, retries = 30) {
        if (typeof AppCore !== 'undefined' && AppCore) {
            console.log('✅ AppCore detected');
            callback();
            return;
        }
        
        if (retries === 0) {
            console.warn('⚠️ AppCore not available after timeout, using fallback mode');
            callback();
            return;
        }
        
        console.log(`⏳ Waiting for AppCore... (${retries} retries left)`);
        setTimeout(() => {
            waitForAppCore(callback, retries - 1);
        }, 100);
    }

    // =============================================
    // 3. MAIN INITIALIZATION (DI DALAM WAIT)
    // =============================================
    function initializeButtonJS() {
        // Cek dependensi (AppCore optional)
        const deps = [
            { name: 'jQuery', global: '$' },
            { name: 'SweetAlert2', global: 'Swal' }
        ];

        const missingDeps = deps.filter(dep => typeof window[dep.global] === 'undefined');
        if (missingDeps.length > 0) {
            console.warn('⚠️ Button.js: Missing dependencies:', missingDeps.map(d => d.name).join(', '));
            // Tetap lanjutkan
        }

        const hasAppCore = typeof AppCore !== 'undefined' && AppCore;
        if (!hasAppCore) {
            console.warn('⚠️ Button.js: Running in fallback mode (without AppCore)');
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

        // ✅ TAMBAHKAN HELPER UNTUK DATATABLE
        const DataTableHelper = {
            /**
             * Reload DataTable dengan aman
             * Mendukung berbagai cara inisialisasi DataTable
             */
            reload: (table = null) => {
                // Coba dari parameter
                if (table && typeof table.draw === 'function') {
                    table.draw();
                    return true;
                }
                
                // Coba dari variabel global 'datatable'
                if (typeof datatable !== 'undefined' && datatable !== null && typeof datatable.draw === 'function') {
                    datatable.draw();
                    return true;
                }
                
                // Coba dari variabel global 'table'
                if (typeof table !== 'undefined' && table !== null && typeof table.draw === 'function') {
                    table.draw();
                    return true;
                }
                
                // Coba dari jQuery selector (cari DataTable yang aktif)
                const $tables = $('table.dataTable');
                if ($tables.length > 0) {
                    try {
                        const dt = $tables.DataTable();
                        if (dt && typeof dt.draw === 'function') {
                            dt.draw();
                            return true;
                        }
                    } catch (e) {
                        // Ignore
                    }
                }
                
                console.warn('⚠️ DataTable tidak ditemukan untuk di-reload');
                return false;
            },
            
            /**
             * Clear cache dan reload (untuk server-side processing)
             */
            reloadWithCacheClear: (table = null) => {
                // Coba clear cache dulu
                if (table && typeof table.clearCache === 'function') {
                    table.clearCache();
                    table.draw();
                    return true;
                }
                
                // Coba dari global datatable
                if (typeof datatable !== 'undefined' && datatable !== null) {
                    if (typeof datatable.clearCache === 'function') {
                        datatable.clearCache();
                    }
                    datatable.draw();
                    return true;
                }
                
                // Fallback ke reload biasa
                return DataTableHelper.reload(table);
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

            parseErrors: (xhr) => {
                if (xhr.responseJSON?.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    return errors.join('<br>');
                }
                return xhr.responseJSON?.message || xhr.statusText || 'Terjadi kesalahan';
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
            submitFormData: async ($form, opts = {}) => {
                const {
                    button = null,
                    closeButton = null,
                    executeDraw = false,
                    fallbackReload = false,
                    reloadDelay = CONFIG.RELOAD_DELAY,
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
                    console.log('📦 Submitting form...submitFormData');
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
                        
                        if (closeButton) $(closeButton).trigger('click');
                        
                        if (ctx.executeDraw) {
                            DataTableHelper.reloadWithCacheClear();
                        } else if (response.redirectTo) {
                            setTimeout(() => {
                                window.location.href = response.redirectTo;
                            }, reloadDelay);
                        } else if (response.reloadPage || fallbackReload) {
                            setTimeout(() => {
                                window.location.reload();
                            }, reloadDelay);
                        }
                        
                        UI.notify('success', response.message);
                        
                        if (onSuccess) onSuccess(response);
                    } else {
                        UI.notify('error', response.message || 'Terjadi kesalahan');
                        if (onError) onError(response);
                    } 
                    restoreButton();
                    $form.find('button[type="submit"]').prop('disabled', false);

                } catch (error) {
                    restoreButton();
                    $form.find('button[type="submit"]').prop('disabled', false);
                    
                    if (onError) onError(error);
                    UI.notify('error', error.response?.data?.message || 'Terjadi kesalahan');
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
                    console.log('📦 Submitting form...deleteWithConfirm');
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
                    UI.notify('error', error.response?.data?.message || 'Gagal menghapus data');
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
                    console.log('📦 Submitting form...getWithConfirm');
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
                    await Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
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
                            Gagal memuat konten: ${error.message || 'Terjadi kesalahan'}
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
                    .on('change', 'input.email_verification_status', this.handleEmailVerification);
            },

            handleModalSubmit: function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const $form = $button.closest('.modal-content').find('form');
                const $closeButton = $button.closest('.modal').find('button.btn-close');
                const executeDraw = utils.toBool($button.attr('data-is_execute_drawer'), true);
                
                 
                Ajax.submitFormData($form, {
                    button: $button,
                    closeButton: $closeButton,
                    executeDraw
                });
            },

            handleSubmitButton: function(e) {
                e.preventDefault();

                const $button = $(this);
                const formTarget = $button.attr('data-form-target');
                const $form = formTarget ? $(formTarget) : $button.closest('form');
                
                if (!$form.length) {
                    UI.notify('error', 'Form tidak ditemukan');
                    return;
                }

                const isNeedSign = utils.toBool($button.attr('data-is_need_sign'), false);
                const executeDraw = utils.toBool($button.attr('data-is_execute_drawer'), true);

                $form.data('submitCtx', {
                    targetElement: $button.data('target'),
                    button: $button,
                    closeButton: $button.closest('.modal').find('button.btn-close'),
                    executeDraw
                });

                if (!isNeedSign) {
                    $form.trigger('submit');
                    return;
                }

                this.proceedSignedSubmit($form, $button);
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
                        console.log('📦 Submitting form...handleFormSubmit');
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
                        
                        if (ctx.executeDraw && typeof datatable !== 'undefined' && datatable !== null) {
                            datatable.draw();
                        }
                    })
                    .catch(error => {
                        restoreButton();
                        UI.notify('error', error.response?.data?.message || 'Terjadi kesalahan');
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

            handleDeleteButton: function() {
                const url = $(this).data('url');
                Ajax.deleteWithConfirm(url, {
                    onSuccess: () => {
                         if (executeDraw) {
                            DataTableHelper.reloadWithCacheClear();
                        }
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
                        if (typeof datatable !== 'undefined') {
                            datatable.draw();
                        }
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
                        if (typeof datatable !== 'undefined') {
                            datatable.draw();
                        }
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
            console.log('✅ Button.js initialized successfully');
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