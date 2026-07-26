// ==============================================
// 1. PASTIKAN SEMUA DEPENDENSI SUDAH TERSEDIA
// ==============================================
if (typeof axios === 'undefined') {
    console.error('❌ Axios belum dimuat! Pastikan bootstrap.js di-import duluan.');
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
    // ✅ Laravel standar pakai nama meta "csrf-token", bukan "_token"
    const csrfToken = getMeta('csrf-token');
    if (csrfToken) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    }
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.withCredentials = true; // Wajib untuk request lintas domain/sesi
}

// ==============================================
// 4. VARIABEL GLOBAL (DAFTARKAN KE WINDOW)
// ==============================================
window._token = getMeta('csrf-token'); // Bisa dipanggil langsung _token di mana saja
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
    success(message) {
        this.toast('success', message);
    },
    error(message) {
        if (!Swal) return alert(message); // Fallback jika Swal error
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: message,
        });
    },
    warning(message) {
        this.toast('warning', message);
    },
    info(message) {
        this.toast('info', message);
    },
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
    if (!Swal) return confirm(text); // Fallback konfirmasi bawaan browser
    return Swal.fire({
        title,
        text,
        icon,
        showCancelButton: true,
        confirmButtonText,
        cancelButtonText,
        reverseButtons: true,
    }).then((result) => result.isConfirmed);
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
        case 422: {
            const errors = data.errors
                ? Object.values(data.errors).flat().join('<br>')
                : data.message || 'Data yang dikirim tidak valid.';
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal',
                html: errors,
            });
            break;
        }
        case 401:
            Swal.fire({
                icon: 'warning',
                title: 'Sesi Berakhir',
                text: 'Silakan login kembali.',
                timer: 2500,
            }).then(() => window.location.href = '/login');
            break;
        case 403:
            Notify.error('Anda tidak memiliki izin untuk melakukan aksi ini.');
            break;
        case 404:
            Notify.error('Halaman atau data yang diminta tidak ditemukan.');
            break;
        case 419: // Khusus Laravel: CSRF kadaluarsa
            Notify.error('Sesi keamanan habis. Silakan muat ulang halaman.');
            break;
        case 500:
            Notify.error(data?.message || 'Terjadi kesalahan pada server. Coba lagi nanti.');
            break;
        default:
            Notify.error(data?.message || 'Terjadi kesalahan yang tidak diketahui.');
            break;
    }
};

// ==============================================
// 9. FUNGSI REQUEST AJAX SERAGAM
// ==============================================
window.request = async function ({
    url,
    method = 'GET',
    data = null,
    showLoading = true,
    loadingMessage = 'Memproses...',
    successMessage = null,
    headers = {}, // Bisa tambah header khusus jika perlu
}) {
    if (!axios) throw new Error('Axios belum dimuat.');

    if (showLoading) Loading.show(loadingMessage);

    try {
        const response = await axios({
            url,
            method,
            data,
            headers,
        });

        if (showLoading) Loading.hide();
        if (successMessage) Notify.success(successMessage);

        return response.data;
    } catch (error) {
        if (showLoading) Loading.hide();
        handleAjaxError(error);
        throw error; // Lempar ulang agar pemanggil bisa tambah logika jika perlu
    }
};

// ==============================================
// 10. INISIALISASI OTOMATIS (JALANKAN SETELAH HALAMAN SIAP)
// ==============================================
document.addEventListener('DOMContentLoaded', () => {
    // Tombol hapus tunggal (perbaiki bug rekursif)
    const btnDelete = document.querySelector('#btn-delete');
    if (btnDelete) {
        btnDelete.addEventListener('click', async (e) => {
            e.preventDefault(); // ❗ PENTING: hentikan klik asli
            const ok = await confirmAction({ title: 'Hapus data ini?' });
            if (!ok) return;
            
            // Ambil URL dari atribut data-url, jangan klik ulang tombolnya!
            const url = btnDelete.dataset.url || btnDelete.getAttribute('href');
            if (url) {
                await request({ url, method: 'DELETE', successMessage: 'Data berhasil dihapus.' });
            }
        });
    }

    // Tombol hapus banyak pakai atribut data-confirm-delete
    document.querySelectorAll('[data-confirm-delete]').forEach((btn) => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const ok = await confirmAction();
            if (!ok) return;
            await request({
                url: btn.dataset.url,
                method: 'DELETE',
                successMessage: 'Data berhasil dihapus.',
            });
        });
    });

    // Form submit otomatis pakai request()
    const form = document.querySelector('#form-validation');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = this.dataset.url;
            const method = this.dataset.method || 'POST';

            await request({
                url,
                method,
                data: formData,
                successMessage: 'Data berhasil disimpan!',
            });
        });
    }
});

// assets/js/core.js
document.addEventListener('DOMContentLoaded', function () {
    const contentContainer = document.querySelector('main.flex-grow-1');
    const menuLinks = document.querySelectorAll('.bnsp-nav-link:not(.bnsp-nav-parent)');
    let isLoading = false;

    // Fungsi memuat konten lewat AJAX
    async function loadContent(url) {
        if (!url || url === '#' || isLoading) return;

        isLoading = true;
        // Tampilkan indikator loading
        contentContainer.innerHTML = `
            <div class="d-flex align-items-center justify-content-center py-5">
                <div class="spinner-border text-primary me-3" role="status">
                    <span class="visually-hidden">Memuat...</span>
                </div>
                <span>Sedang memuat halaman...</span>
            </div>
        `;

        try {
            const response = await axios.get(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Masukkan konten yang diterima
            contentContainer.innerHTML = response.data;
            // Perbarui URL browser tanpa reload
            window.history.pushState({ path: url }, '', url);
            // Perbarui judul halaman (jika dikirim dari controller)
            if (response.headers['x-page-title']) {
                document.title = response.headers['x-page-title'];
            }
            // Set ulang event untuk elemen baru
            reinitializeScripts();

        } catch (error) {
            console.error('Gagal memuat halaman:', error);
            await Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat',
                text: 'Terjadi kesalahan saat memuat halaman. Silakan coba lagi.',
                confirmButtonText: 'Baik'
            }); 
        } finally {
            isLoading = false;
            // show warning 
            contentContainer.innerHTML = `
                <div class="d-flex align-items-center justify-content-center py-5">    
                    <span class="material-symbols-outlined">warning</span>                 
                    <span>Gagal memuat halaman...</span> 

                </div>
            `;
        }
    }

    // Tangan klik pada link menu
    menuLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.dataset.ajaxUrl;

            // Hapus status aktif dari semua menu
            document.querySelectorAll('.bnsp-nav-link').forEach(el => el.classList.remove('active'));
            // Beri status aktif pada menu yang diklik
            this.classList.add('active');
            // Muat konten
            loadContent(url);
        });
    });

    // Tangani tombol maju/mundur browser
    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.path) {
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
        // Panggil fungsi inisialisasi yang ada di dashboard.js / modal.js dll
        if (typeof initModals === 'function') initModals();
        if (typeof initDataTables === 'function') initDataTables();
        if (typeof initFormValidations === 'function') initFormValidations();
        // Tambahkan fungsi lain yang perlu dijalankan ulang sesuai kebutuhan
    }

    // Tombol hapus tunggal (perbaiki bug rekursif)
    const btnDelete = document.querySelector('#btn-delete');
    if (btnDelete) {
        btnDelete.addEventListener('click', async (e) => {
            e.preventDefault(); // ❗ PENTING: hentikan klik asli
            const ok = await confirmAction({ title: 'Hapus data ini?' });
            if (!ok) return;
            
            // Ambil URL dari atribut data-url, jangan klik ulang tombolnya!
            const url = btnDelete.dataset.url || btnDelete.getAttribute('href');
            if (url) {
                await request({ url, method: 'DELETE', successMessage: 'Data berhasil dihapus.' });
            }
        });
    }

    // Tombol hapus banyak pakai atribut data-confirm-delete
    document.querySelectorAll('[data-confirm-delete]').forEach((btn) => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const ok = await confirmAction();
            if (!ok) return;
            await request({
                url: btn.dataset.url,
                method: 'DELETE',
                successMessage: 'Data berhasil dihapus.',
            });
        });
    });

    // Form submit otomatis pakai request()
    const form = document.querySelector('#form-validation');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = this.dataset.url;
            const method = this.dataset.method || 'POST';

            await request({
                url,
                method,
                data: formData,
                successMessage: 'Data berhasil disimpan!',
            });
        });
    }
    
});