// UPDATE DOLA 


// ==============================================
// 1. CEK DEPENDENSI
// ==============================================
if (typeof axios === 'undefined') {
    console.error('❌ Axios belum dimuat! Pastikan bootstrap.js dimuat duluan.');
}
if (typeof Swal === 'undefined') {
    console.error('❌ SweetAlert2 belum dimuat!');
}

// ==============================================
// 2. FUNGSI BANTUAN AMBIL META TAG
// ==============================================
function getMeta(name) {
    const el = document.querySelector(`meta[name="${name}"]`);
    return el ? el.content : null;
}

// ==============================================
// 3. KONFIGURASI AXIOS GLOBAL (SETELAH AXIOS ADA)
// ==============================================
if (typeof axios !== 'undefined') {
    const csrfToken = getMeta('csrf-token');
    if (csrfToken) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    }
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.withCredentials = true;

    // ➕ BARU: Perbarui Token CSRF Otomatis
    axios.interceptors.response.use(
        response => response,
        error => {
            if (error.response?.status === 419) {
                return fetch('/sanctum/csrf-cookie', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(() => {
                    const newToken = getMeta('csrf-token');
                    if (newToken) axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;
                    return Promise.reject(error);
                });
            }
            return Promise.reject(error);
        }
    );
}

// ==============================================
// 4. VARIABEL GLOBAL (DAFTARKAN KE WINDOW)
// ==============================================
window._token = getMeta('csrf-token');
window.userId = getMeta('user_id');
window.loginAs = getMeta('login_as');

// ==============================================
// 5. KOMPONEN LOADING
// ==============================================
window.Loading = {
    show(message = 'Memproses...') {
        if (!Swal) return;
        Swal.fire({
            title: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading(),
        });
    },
    hide() {
        if (!Swal) return;
        Swal.close();
    },
};

// ==============================================
// 6. KOMPONEN NOTIFIKASI
// ==============================================
window.Notify = {
    toast(icon, title, timer = 3000) {
        if (!Swal) return;
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon,
            title,
            showConfirmButton: false,
            timer,
            timerProgressBar: true,
        });
    },
    success(message) { this.toast('success', message); },
    error(message) {
        if (!Swal) return alert(message);
        Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: message });
    },
    warning(message) { this.toast('warning', message); },
    info(message) { this.toast('info', message); },
};

// ==============================================
// 7. KONFIRMASI AKSI
// ==============================================
window.confirmAction = function ({
    title = 'Anda yakin?',
    text = 'Data yang sudah diproses tidak bisa dikembalikan.',
    icon = 'warning',
    confirmButtonText = 'Ya, lanjutkan',
    cancelButtonText = 'Batal',
} = {}) {
    if (!Swal) return confirm(text);
    return Swal.fire({
        title, text, icon,
        showCancelButton: true,
        confirmButtonText, cancelButtonText,
        reverseButtons: true,
    }).then(result => result.isConfirmed);
};

// ==============================================
// 8. PENANGAN ERROR AJAX
// ==============================================
window.handleAjaxError = function (error) {
    if (!error.response) {
        Notify.error('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
        return;
    }

    const { status, data } = error.response;
    switch (status) {
        case 422:
            const errors = data.errors
                ? Object.values(data.errors).flat().join('<br>')
                : data.message || 'Data yang dikirim tidak valid.';
            Swal.fire({ icon: 'warning', title: 'Validasi Gagal', html: errors });
            break;
        case 401:
            Swal.fire({ icon: 'warning', title: 'Sesi Berakhir', text: 'Silakan login kembali.', timer: 2500 })
               .then(() => window.location.href = '/login');
            break;
        case 403: Notify.error('Anda tidak memiliki izin untuk aksi ini.'); break;
        case 404: Notify.error('Halaman atau data tidak ditemukan.'); break;
        case 419: Notify.error('Sesi keamanan habis. Silakan coba lagi.'); break;
        case 500: Notify.error(data?.message || 'Terjadi kesalahan server.'); break;
        default: Notify.error(data?.message || 'Terjadi kesalahan tak dikenal.');
    }
};

// ==============================================
// 9. FUNGSI REQUEST SERAGAM
// ==============================================
window.request = async function ({
    url, method = 'GET', data = null,
    showLoading = true, loadingMessage = 'Memproses...',
    successMessage = null, headers = {},
}) {
    if (!axios) throw new Error('Axios belum dimuat.');

    if (showLoading) Loading.show(loadingMessage);

    try {
        const response = await axios({ url, method, data, headers });
        if (showLoading) Loading.hide();
        if (successMessage) Notify.success(successMessage);
        return response.data;
    } catch (error) {
        if (showLoading) Loading.hide();
        handleAjaxError(error);
        throw error;
    }
};

// ==============================================
// 10. INISIALISASI ELEMEN DINAMIS (DIPANGGIL ULANG OTOMATIS)
// ==============================================
window.initCoreElements = function () {
    // Tombol Hapus Tunggal
    document.querySelectorAll('#btn-delete:not([data-initialized])').forEach(btn => {
        btn.setAttribute('data-initialized', 'true');
        btn.addEventListener('click', async e => {
            e.preventDefault();
            const ok = await confirmAction({ title: 'Hapus data ini?' });
            if (!ok) return;
            const url = btn.dataset.url || btn.getAttribute('href');
            if (url) await request({ url, method: 'DELETE', successMessage: 'Data berhasil dihapus.' });
        });
    });

    // Tombol Hapus Banyak / Konfirmasi
    document.querySelectorAll('[data-confirm-delete]:not([data-initialized])').forEach(btn => {
        btn.setAttribute('data-initialized', 'true');
        btn.addEventListener('click', async e => {
            e.preventDefault();
            const ok = await confirmAction();
            if (!ok) return;
            await request({
                url: btn.dataset.url, method: 'DELETE',
                successMessage: 'Data berhasil dihapus.'
            });
        });
    });

    // Form Submit Otomatis
    document.querySelectorAll('#form-validation:not([data-initialized])').forEach(form => {
        form.setAttribute('data-initialized', 'true');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = this.dataset.url;
            const method = this.dataset.method || 'POST';
            await request({ url, method, data: formData, successMessage: 'Data berhasil disimpan!' });
        });
    });
};

// ==============================================
// 11. FITUR MUAT HALAMAN TANPA RELOAD
// ==============================================
document.addEventListener('DOMContentLoaded', () => {
    const contentContainer = document.querySelector('main.main-content');
    const menuLinks = document.querySelectorAll('.bnsp-nav-link:not(.bnsp-nav-parent)');
    let isLoading = false;

    async function loadContent(url) {
        if (!url || url === '#' || isLoading || !contentContainer) return;

        isLoading = true;
        contentContainer.innerHTML = `
            <div class="d-flex align-items-center justify-content-center py-5">
                <div class="spinner-border text-primary me-3" role="status"></div>
                <span>Sedang memuat halaman...</span>
            </div>
        `;

        try {
            const response = await axios.get(url);
            // ⭐ AMBIL HANYA BAGIAN .main-content SAJA
            const parser = new DOMParser();
            const doc = parser.parseFromString(response.data, 'text/html');
            
            const kontenBaru = doc.querySelector('.main-content')?.innerHTML 
                || doc.body?.innerHTML 
                || response.data 
                || '<p class="text-danger">Konten tidak ditemukan</p>';
            contentContainer.innerHTML = kontenBaru;
            window.history.pushState({ path: url }, '', url);

            // ➕ BARU: Baca judul dari meta atau header
            const pageTitle = response.headers['x-page-title'] 
                || new DOMParser().parseFromString(response.data, 'text/html').querySelector('title')?.textContent 
                || document.title;
            document.title = pageTitle;

            // ➕ BARU: Scroll ke atas saat ganti halaman
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Jalankan ulang semua fungsi pendukung
            reinitializeScripts();

        } catch (error) {
            console.error('Gagal memuat halaman:', error);
            Swal.fire({
                icon: 'error', title: 'Gagal Memuat',
                text: 'Terjadi kesalahan saat memuat halaman.',
                confirmButtonText: 'Baik'
            });
        } finally {
            isLoading = false;
        }
    }

    // Tangan klik pada link menu
    menuLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            if (isLoading) return; // ➕ BARU: Cegah klik beruntun
            const url = link.dataset.ajaxUrl;
            console.log(url)
            // Hapus status aktif dari semua menu
            document.querySelectorAll('.bnsp-nav-link').forEach(el => el.classList.remove('active'));
             // Beri status aktif pada menu yang diklik
            link.classList.add('active');
            // Muat konten
            loadContent(url);
        });
    });

    // Tangani tombol maju/mundur browser
    window.addEventListener('popstate', e => {
        if (e.state?.path) {
            loadContent(e.state.path);
            // Sinkronkan status menu aktif
            syncActiveMenu(e.state.path);
        }
    });

    // Sinkronkan menu aktif berdasarkan URL saat ini
    function syncActiveMenu(currentUrl) {
        document.querySelectorAll('.bnsp-nav-link').forEach(link => { 
            link.classList.toggle('active', link.dataset.ajaxUrl === currentUrl);
        });
    }

    // Jalankan ulang inisialisasi script setelah konten dimuat
    function reinitializeScripts() {
        // Jalankan fungsi global lain
        if (typeof initModals === 'function') initModals();
        if (typeof initDataTables === 'function') initDataTables();
        if (typeof initFormValidations === 'function') initFormValidations();
        // Tambahkan fungsi lain yang perlu dijalankan ulang sesuai kebutuhan
        FormValidator.reinitAll(contentContainer);
        // ➕ BARU: Jalankan inisialisasi tombol & form yang sudah kita buat
        initCoreElements();

         // ✅ Jalankan ulang inisialisasi DataTables
        // ✅ HANYA jalankan jika DataTables BENAR-BENAR ada
        if ($.fn.DataTable && window.LaravelDataTables) {
            Object.keys(window.LaravelDataTables).forEach(id => {
                console.log(id)
                const table = window.LaravelDataTables[id];
                console.log(table)
                const el = document.getElementById(id);
                if (el && !$.fn.dataTable.isDataTable(el)) {
                    table.draw();
                } else {
                    table.ajax.reload(null, false);
                }
            });
        }
    }

    // Jalankan pertama kali saat halaman dibuka
    initCoreElements();
});

// ==============================================
// ✅ VALIDASI FORM JQUERY (DINAMIS & KUSTOM)
// ==============================================
window.FormValidator = {
    // Pesan standar Bahasa Indonesia
    messagesId: {
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

    // Daftar aturan KUSTOM yang bisa dipakai di semua form
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
        // ➕ Tambah aturan lain sesuai kebutuhan
    },

    initCustomRules() {
        // Daftarkan semua aturan kustom ke plugin jQuery Validate
        $.each(this.customRules, (name, rule) => {
            $.validator.addMethod(name, function(value) {
                return this.optional(value) || rule.regex.test(value);
            }, rule.message);
        });
    },

    // Jalankan validasi pada form yang BELUM terinisialisasi
    attach(formEl) {
        const $form = $(formEl);
        if ($form.data('validator-attached')) return; // Lewati jika sudah ada

        $form.data('validator-attached', true);

        $form.validate({
            errorElement: 'span',
            errorClass: 'text-danger small mt-1 d-block',
            highlight: el => $(el).addClass('is-invalid'),
            unhighlight: el => $(el).removeClass('is-invalid'),
            errorPlacement: (err, el) => err.insertAfter(el),
            messages: this.messagesId,

            // Saat form valid: jalankan fungsi request() kita
            submitHandler: async (form) => {
                e.preventDefault();
                const formData = new FormData(form);
                const url = form.dataset.url || form.action;
                const method = form.dataset.method || form.method || 'POST';

                await request({
                    url, method, data: formData,
                    successMessage: form.dataset.successMessage || 'Data berhasil disimpan!'
                });
            }
        });
    },

    // Jalankan ulang untuk SEMUA form baru yang muncul (termasuk hasil AJAX)
    reinitAll(container = document) {
        $(container).find('form[data-validate="true"]').each((_, f) => this.attach(f));
    }
};

// Jalankan pendaftaran aturan kustom satu kali saja
if (typeof $ !== 'undefined' && $.validator) {
    FormValidator.initCustomRules();
}

var form =`<form data-validate="true" data-url="/user/simpan" data-success-message="Pengguna berhasil disimpan">
    <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" 
               data-rule-required="true" data-rule-minlength="3">
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" 
               data-rule-required="true" data-rule-email="true">
    </div>
    <div class="mb-3">
        <label>No. HP</label>
        <input type="text" name="hp" class="form-control" 
               data-rule-required="true" data-rule-phone_id="true">
    </div>
    <div class="mb-3">
        <label>NIP</label>
        <input type="text" name="nip" class="form-control" 
               data-rule-nip="true">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>`;