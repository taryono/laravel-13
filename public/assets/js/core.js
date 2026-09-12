// public/assets/js/core.js
// ==============================================
// 1. MODULE PATTERN - Enkapsulasi kode
// ==============================================
const AppCore = (() => {
    'use strict';
    
    // ==============================================
    // 2. DEPENDENSI CHECK
    // ==============================================
    const checkDependencies = () => {
        const deps = [
            { name: 'axios', global: 'axios' },
            { name: 'SweetAlert2', global: 'Swal' },
            { name: 'jQuery', global: '$' }
        ];
        
        let missing = false;
        deps.forEach(dep => {
            if (typeof window[dep.global] === 'undefined') {
                console.error(`❌ ${dep.name} belum dimuat!`);
                missing = true;
            }
        });
        
        if (missing) {
            console.warn('⚠️ Beberapa dependensi tidak tersedia, beberapa fitur mungkin tidak berfungsi.');
        }
        
        return !missing;
    };

    // ==============================================
    // 3. UTILITY FUNCTIONS
    // ==============================================
    const utils = {
        getMeta: (name) => {
            const el = document.querySelector(`meta[name="${name}"]`);
            return el?.content || null;
        },
        
        parseError: (error) => {
            if (!error.response) return { status: null, message: 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.', isAuth: false };
            
            const { status, data } = error.response;
            const message = data?.message || 'Terjadi kesalahan tak dikenal.';
            
            const errorMap = {
                422: data?.errors 
                    ? Object.values(data.errors).flat().join('<br>') 
                    : 'Data yang dikirim tidak valid.',
                401: 'Sesi Anda telah berakhir. Silakan login kembali.',
                403: 'Anda tidak memiliki izin untuk aksi ini.',
                404: 'Halaman atau data tidak ditemukan.',
                419: 'Sesi keamanan habis. Silakan coba lagi.',
                500: message
            };
            
            return {
                status,
                message: errorMap[status] || message,
                isAuth: status === 401
            };
        }
    };

    // ==============================================
    // 4. AXIOS CONFIGURATION
    // ==============================================
    const axiosConfig = {
        setup: () => {
            if (typeof axios === 'undefined') return;
            
            const csrfToken = utils.getMeta('csrf-token');
            if (csrfToken) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
            }
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.withCredentials = true;
            
            // CSRF Token refresh interceptor
            axios.interceptors.response.use(
                response => response,
                async (error) => {
                    if (error.response?.status === 419) {
                        try {
                            await fetch('/sanctum/csrf-cookie', {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            });
                            const newToken = utils.getMeta('csrf-token');
                            if (newToken) {
                                axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;
                            }
                        } catch (e) {
                            console.error('Failed to refresh CSRF token:', e);
                        }
                    }
                    return Promise.reject(error);
                }
            );
        }
    };

    // ==============================================
    // 5. UI COMPONENTS
    // ==============================================
    const UI = {
        loading: {
            show: (message = 'Memproses...') => {
                if (typeof Swal === 'undefined') return;
                Swal.fire({
                    title: message,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            hide: () => {
                if (typeof Swal === 'undefined') return;
                Swal.close();
            }
        },
        
        notify: {
            toast: (icon, title, timer = 3000) => {
                if (typeof Swal === 'undefined') {
                    alert(title);
                    return;
                }
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon,
                    title,
                    showConfirmButton: false,
                    timer,
                    timerProgressBar: true
                });
            },
            success: (message) =>  UI.notify.toast('success', message),
            error: (message) => {
                if (typeof Swal === 'undefined') {
                    alert(message);
                    return;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: message
                });
            },
            warning: (message) =>  UI.notify.toast('warning', message),
            info: (message) =>  UI.notify.toast('info', message)
        },
        
        confirm: (options = {}) => {
            const defaults = {
                title: 'Anda yakin?',
                text: 'Data yang sudah diproses tidak bisa dikembalikan.',
                icon: 'warning',
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal'
            };
            
            const config = { ...defaults, ...options };
            
            if (typeof Swal === 'undefined') {
                return Promise.resolve(confirm(config.text));
            }
            
            return Swal.fire({
                ...config,
                showCancelButton: true,
                reverseButtons: true
            }).then(result => result.isConfirmed);
        }
    };

    // ==============================================
    // 6. AJAX HANDLER
    // ==============================================
    const ajax = {
        request: async (config) => {
            const {
                url,
                method = 'GET',
                data = null,
                showLoading = true,
                loadingMessage = 'Memproses...',
                successMessage = null,
                headers = {}
            } = config;
            
            if (typeof axios === 'undefined') {
                throw new Error('Axios belum dimuat.');
            }
            
            if (showLoading) UI.loading.show(loadingMessage);
            
            try {
                const response = await axios({ url, method, data, headers });
                console.log(response)
                if (showLoading) UI.loading.hide();
                if (successMessage) UI.notify.success(successMessage);
                return response.data;
            } catch (error) {
                if (showLoading) UI.loading.hide();
                const errorInfo = utils.parseError(error);
                
                if (errorInfo.isAuth) {
                    await UI.confirm({
                        title: 'Sesi Berakhir',
                        text: errorInfo.message,
                        icon: 'warning',
                        confirmButtonText: 'Login Ulang'
                    }).then(confirmed => {
                        if (confirmed) window.location.href = '/login';
                    });
                } else {
                    UI.notify.error(errorInfo.message);
                }
                
                throw error;
            }
        }
    };

    // ==============================================
    // 7. ELEMENT INITIALIZER (DENGAN CLEANUP)
    // ==============================================
    const ElementManager = {
        initialized: new WeakSet(),
        
         // PERBAIKI: Gunakan method shorthand (bukan arrow function)
        initDeleteButtons(container = document) {
            const buttons = container.querySelectorAll('.delete-button:not([data-initialized])');
            buttons.forEach(btn => {
                const handler = async (e) => {
                    e.preventDefault();
                    const confirmed = await UI.confirm({ title: 'Hapus data ini?' });
                    if (!confirmed) return;
                    
                    const url = btn.dataset.url || btn.getAttribute('href');
                    if (url) {
                        await ajax.request({
                            url,
                            method: 'DELETE',
                            successMessage: 'Data berhasil dihapus.'
                        });
                    }
                };
                
                btn.setAttribute('data-initialized', 'true');
                btn.addEventListener('click', handler);
                this.initialized.add(btn);
            });
        },
        
        initConfirmButtons(container = document) {
            const buttons = container.querySelectorAll('[data-confirm-delete]:not([data-initialized])');
            buttons.forEach(btn => {
                const handler = async (e) => {
                    e.preventDefault();
                    const confirmed = await UI.confirm();
                    if (!confirmed) return;
                    
                    await ajax.request({
                        url: btn.dataset.url,
                        method: 'DELETE',
                        successMessage: 'Data berhasil dihapus.'
                    });
                };
                
                btn.setAttribute('data-initialized', 'true');
                btn.addEventListener('click', handler);
                this.initialized.add(btn);
            });
        },
        
        initForms(container = document){
            const forms = container.querySelectorAll('.form-validation:not([data-initialized])');
            forms.forEach(form => {
                const handler = async (e) => {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const url = form.dataset.url;
                    const method = form.dataset.method || 'POST';
                    
                    await ajax.request({
                        url,
                        method,
                        data: formData,
                        successMessage: 'Data berhasil disimpan!'
                    });
                };
                
                form.setAttribute('data-initialized', 'true');
                form.addEventListener('submit', handler);
                this.initialized.add(form);
            });
        },
        
        initAll(container = document) {
            this.initDeleteButtons(container);
            this.initConfirmButtons(container);
            this.initForms(container);
        },
        
        // Cleanup function untuk menghindari memory leak
        cleanup: (container = document) => {
            // Hanya cleanup yang perlu
        }
    };

    // ==============================================
    // 8. PAGE LOADER (TANPA RELOAD)
    // ==============================================
    const PageLoader = {
        isLoading: false,
        container: null,
        
        init: () => {
            PageLoader.container = document.querySelector('main.main-content');
            
            // Event delegation untuk link
            document.addEventListener('click', (e) => {
                const link = e.target.closest('.edit[data-ajax-url]');
                if (!link) return;
                
                e.preventDefault();
                if (PageLoader.isLoading) return;
                
                const url = link.dataset.ajaxUrl;
                if (url) PageLoader.loadContent(url);
            });
            
            // Browser back/forward
            window.addEventListener('popstate', (e) => {
                if (e.state?.path) {
                    PageLoader.loadContent(e.state.path);
                }
            });
        },
        
        loadContent: async (url) => {
            if (!url || url === '#' || !PageLoader.container || PageLoader.isLoading) return;
            
            PageLoader.isLoading = true;
            
            // Show loading state
            PageLoader.container.innerHTML = `
                <div class="d-flex align-items-center justify-content-center py-5">
                    <div class="spinner-border text-primary me-3" role="status"></div>
                    <span>Sedang memuat halaman...</span>
                </div>
            `;
            
            try {
                const response = await axios.get(url);
                const parser = new DOMParser();
                const doc = parser.parseFromString(response.data, 'text/html');
                
                const newContent = doc.querySelector('.main-content')?.innerHTML 
                    || doc.body?.innerHTML 
                    || response.data;
                
                PageLoader.container.innerHTML = newContent;
                window.history.pushState({ path: url }, '', url);
                
                // Update title
                const title = doc.querySelector('title')?.textContent || document.title;
                document.title = title;
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Reinitialize elements
                ElementManager.initAll(PageLoader.container);
                
                // Trigger custom event for other scripts
                document.dispatchEvent(new CustomEvent('contentLoaded', { 
                    detail: { container: PageLoader.container, url } 
                }));
                
            } catch (error) {
                console.error('Failed to load page:', error);
                await UI.confirm({
                    title: 'Gagal Memuat',
                    text: 'Terjadi kesalahan saat memuat halaman.',
                    icon: 'error',
                    confirmButtonText: 'Muat Ulang'
                }).then(confirmed => {
                    if (confirmed) window.location.reload();
                });
            } finally {
                PageLoader.isLoading = false;
            }
        }
    };

    // ==============================================
    // 9. FORM VALIDATOR (JQUERY)
    // ==============================================
    const FormValidator = {
        messages: {
            required: "Wajib diisi",
            email: "Format email tidak valid",
            number: "Harus berupa angka",
            digits: "Harus berupa angka bilangan bulat",
            minlength: "Minimal {0} karakter",
            maxlength: "Maksimal {0} karakter",
            min: "Nilai minimal {0}",
            max: "Nilai maksimal {0}",
            equalTo: "Isian tidak cocok",
            remote: "Silakan perbaiki isian ini",
            url: "Format URL tidak valid"
        },
        
        customRules: {
            alphanumeric: {
                regex: /^[a-zA-Z0-9_]+$/,
                message: "Hanya huruf, angka, dan garis bawah"
            },
            phone_id: {
                regex: /^(\+62|62|0)[0-9]{9,13}$/,
                message: "Format nomor HP Indonesia tidak valid"
            },
            nip: {
                regex: /^[0-9]{18}$/,
                message: "NIP harus 18 digit angka"
            }
        },
        
        init: () => {
            if (typeof $ === 'undefined' || typeof $.validator === 'undefined') return;
            
            // Register custom rules
            Object.entries(FormValidator.customRules).forEach(([name, rule]) => {
                $.validator.addMethod(name, function(value) {
                    return this.optional(value) || rule.regex.test(value);
                }, rule.message);
            });
        },
        
        attach: (form) => {
            const $form = $(form);
            if ($form.data('validator-attached')) return;
            
            $form.data('validator-attached', true);
            
            $form.validate({
                errorElement: 'span',
                errorClass: 'text-danger small mt-1 d-block',
                highlight: (el) => $(el).addClass('is-invalid'),
                unhighlight: (el) => $(el).removeClass('is-invalid'),
                errorPlacement: (error, element) => error.insertAfter(element),
                messages: FormValidator.messages,
                
                submitHandler: async (form) => {
                    const formData = new FormData(form);
                    const url = form.dataset.url || form.action;
                    const method = form.dataset.method || form.method || 'POST';
                    
                    await ajax.request({
                        url,
                        method,
                        data: formData,
                        successMessage: form.dataset.successMessage || 'Data berhasil disimpan!'
                    });
                }
            });
        },
        
        reinit: (container = document) => {
            $(container).find('form[data-validate="true"]').each((_, form) => {
                FormValidator.attach(form);
            });
        }
    };

    // ==============================================
    // 10. PUBLIC API
    // ==============================================
    return {
        // Core utilities
        utils,
        axios: axiosConfig,
        ui: UI,
        ajax,
        
        // Initializers
        init: () => {
            if (!checkDependencies()) {
                console.warn('⚠️ Beberapa fitur mungkin tidak berfungsi karena dependensi yang hilang.');
            }
            
            // Setup axios
            axiosConfig.setup();
            
            // Setup global variables
            window._token = utils.getMeta('csrf-token');
            window.userId = utils.getMeta('user_id');
            window.loginAs = utils.getMeta('login_as');
            
            // Init form validator
            FormValidator.init();
            
            // Init elements
            ElementManager.initAll();
            FormValidator.reinit();
            
            // Init page loader
            PageLoader.init();
            
            // Auto-reinit on content loaded
            document.addEventListener('contentLoaded', (e) => {
                ElementManager.initAll(e.detail.container);
                FormValidator.reinit(e.detail.container);
            });
            
            console.log('✅ AppCore initialized successfully');
        },
        
        // Public methods
        refresh: (container = document) => {
            ElementManager.initAll(container);
            FormValidator.reinit(container);
        },
        
        // For backward compatibility
        get Legacy() {
            return {
                Loading: UI.loading,
                Notify: UI.notify,
                confirmAction: UI.confirm,
                handleAjaxError: (error) => {
                    const errorInfo = utils.parseError(error);
                    UI.notify.error(errorInfo.message);
                },
                request: ajax.request,
                initCoreElements: () => ElementManager.initAll(),
                FormValidator: {
                    reinitAll: FormValidator.reinit
                }
            };
        }
    };
})();

// ==============================================
// 11. INITIALIZE ON DOM READY
// ==============================================
document.addEventListener('DOMContentLoaded', () => {
    AppCore.init();
    
    // Dispatch event bahwa core sudah siap
    document.dispatchEvent(new CustomEvent('appcore:ready', {
        detail: { version: '1.0.0' }
    }));
});

// ==============================================
// 12. BACKWARD COMPATIBILITY
// ==============================================
// Tetap support kode lama yang menggunakan window.*
Object.assign(window, AppCore.Legacy);

// Export untuk modular usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AppCore;
} 
// 🚀 Cara Penggunaan
// Cara modern (recommended)
// AppCore.ajax.request({
//     url: '/api/users',
//     method: 'POST',
//     data: { name: 'John' },
//     successMessage: 'User created!'
// });

// Cara legacy (masih support)
// window.request({
//     url: '/api/users',
//     method: 'POST',
//     data: { name: 'John' },
//     successMessage: 'User created!'
// });

// Refresh elements setelah AJAX load
// AppCore.refresh(document.getElementById('new-content'));