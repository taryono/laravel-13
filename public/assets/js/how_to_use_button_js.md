# Cara Menggunakan `core.js` dan `button.js` di Setiap Proses

Saya sudah baca kedua file kamu. Sebenarnya **arsitektur kamu sudah bagus** — `core.js` sebagai fondasi dan `button.js` sebagai handler UI. Tapi ada beberapa hal yang perlu kamu pahami agar pemakaiannya konsisten.

---

## 📋 Ringkasan Peran Masing-Masing File

| File | Peran |
|------|-------|
| **`core.js`** (`AppCore`) | Fondasi: axios setup, CSRF, loading, notifikasi, confirm, AJAX wrapper, page loader, form validator |
| **`button.js`** (`ButtonJS`) | Handler event siap pakai: klik tombol modal, submit form, delete, inline nav, dark mode, DataTable reload |

`button.js` **sudah bergantung pada `AppCore`** (lihat `waitForAppCore()`), jadi urutan load wajib: **`core.js` dulu → `button.js` kemudian**.

---

## 1️⃣ Setup HTML (Urutan Script Wajib)

```html
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user_id" content="{{ auth()->id() }}">
    <meta name="login_as" content="{{ session('login_as') }}">
</head>

<body>
    <!-- Konten utama WAJIB punya class .main-content agar PageLoader bekerja -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- 1. Dependensi eksternal -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/datatables.min.js') }}"></script>

    <!-- 2. Core DULU -->
    <script src="{{ asset('assets/js/core.js') }}"></script>

    <!-- 3. Button.js SETELAH core -->
    <script src="{{ asset('assets/js/button.js') }}"></script>

    <!-- 4. Script halaman spesifik (opsional) -->
    @stack('scripts')
</body>
```

> ⚠️ **Catatan penting**: `button.js` menganggap `AppCore` sudah ada. Kalau `core.js` di-load pakai `defer`, pastikan `button.js` juga `defer` dan urutannya benar.

---

## 2️⃣ Pola Pemakaian Sehari-Hari

### A. Tombol Delete (paling sering)

Cukup beri class `.delete-button` + `data-url`:

```html
<button class="btn btn-danger delete-button"
        data-url="{{ route('users.destroy', $user->id) }}"
        data-datatable-id="users-table"
        data-is_execute_drawer="true">
    Hapus
</button>
```

`button.js` otomatis: konfirmasi → DELETE → reload DataTable → toast.

---

### B. Form di dalam Modal

```html
<div class="modal fade" id="modalUser">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Tambah User</h5>
      <button class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form action="{{ route('users.store') }}" method="POST" data-validate="true">
      @csrf
      <div class="modal-body"> ... </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary modal-submit"
                data-datatable-id="users-table"
                data-is_execute_drawer="true">
            Simpan
        </button>
      </div>
    </form>
  </div>
</div>
```

Klik `.modal-submit` → `button.js` ambil context, submit via `AppCore.ajax`, close modal, reload DataTable, toast.

---

### C. Form Biasa (non-modal)

```html
<form action="{{ route('users.store') }}" method="POST"
      class="form-horizontal" data-validate="true">
    @csrf
    ...
    <button type="submit" class="btn btn-primary submit-button">Simpan</button>
</form>
```

Atau tanpa `submit-button` pun tetap jalan karena `button.js` listen `submit` pada `form.form-horizontal`.

---

### D. Tombol dengan Konfirmasi GET (retry, verifikasi email, dll.)

```html
<button id="retry-observation"
        data-url="{{ route('observations.retry', $id) }}">
    Ulangi Observasi
</button>
```

---

### E. Inline Navigation (create/show/edit tanpa reload)

```html
<a href="#" class="edit-inline"
   data-url="{{ route('users.edit', $user->id) }}">
    Edit
</a>
```

`button.js` akan panggil `loadSliceData()` (kalau ada) atau fallback redirect biasa.

---

### F. Set Context Manual dari HTML

Kalau butuh override table target:

```html
<button data-datatable-context='{"executeDraw":true,"tableId":"action-table","reloadDelay":500}'>
    Aksi
</button>
```

---

## 3️⃣ Pemakaian Manual via JavaScript (untuk kasus custom)

Kalau kamu butuh panggil AJAX dari script halaman sendiri, **jangan pakai `$.ajax` mentah**. Gunakan salah satu dari ini:

### Opsi 1 — Lewat `AppCore` (paling low-level, fleksibel)

```js
AppCore.ajax.request({
    url: '/api/users',
    method: 'POST',
    data: { name: 'John' },
    showLoading: true,
    loadingMessage: 'Menyimpan...',
    successMessage: 'User berhasil dibuat!'
}).then(res => {
    console.log(res);
}).catch(err => {
    // Error sudah otomatis di-toast oleh AppCore
});
```

### Opsi 2 — Lewat `ButtonJS` (kalau sudah di-load)

```js
// Submit form secara programatik
ButtonJS.submitFormData($('#myForm'), {
    button: $('#btnSave'),
    closeButton: $('#modalUser .btn-close'),
    executeDraw: true,
    tableId: 'users-table'
});

// Delete dengan konfirmasi
ButtonJS.deleteWithConfirm('/users/1', {
    onSuccess: () => ButtonJS.DataTableHelper.reload()
});

// Konfirmasi GET
ButtonJS.getWithConfirm('/users/1/verify', {
    confirmTitle: 'Verifikasi?',
    onSuccess: () => { /* ... */ }
});

// Load konten ke modal
ButtonJS.loadModalContent('/users/1/edit', $('#modalUser'), 'Edit User');
```

### Opsi 3 — Notifikasi & Confirm manual

```js
AppCore.ui.notify.success('Berhasil!');
AppCore.ui.notify.error('Gagal!');
AppCore.ui.loading.show('Memuat...');
AppCore.ui.loading.hide();

const ok = await AppCore.ui.confirm({ title: 'Yakin?' });
if (ok) { /* ... */ }
```

---

## 4️⃣ Kalau Konten Di-load Dinamis (SPA-like)

Karena `button.js` pakai **event delegation** (`$(document).on(...)`), handler-nya otomatis jalan di elemen baru. Tapi kalau kamu pakai plugin seperti selectpicker / datepicker / validator, panggil:

```js
AppCore.refresh(document.getElementById('new-content'));
```

Atau event `contentLoaded` sudah otomatis dipancarkan oleh `PageLoader.loadContent()`:

```js
document.addEventListener('contentLoaded', (e) => {
    // re-init plugin custom kamu di sini
    initDatePickers(e.detail.container);
});
```

---

## 5️⃣ Alur Rekomendasi untuk Proses Baru

Kalau kamu bikin fitur baru, ikuti pola ini:

```
1. Buat route + controller (return JSON: {status:'success', message:'...', data:...})
2. Buat view dengan:
   - <table id="xxx-table" class="dataTable"> ... </table>
   - <button class="delete-button" data-url="..." data-datatable-id="xxx-table">
   - <form action="..." class="form-horizontal" data-validate="true">
   - <button class="modal-submit" data-datatable-id="xxx-table">
3. SELESAI. Tidak perlu nulis JS baru.
```

Kalau butuh behavior khusus (misal setelah sukses update chart), baru tambahkan script:

```html
@push('scripts')
<script>
$(document).on('form:saved', function(e, formId) {
    if (formId === 'formUser') {
        updateChart();
    }
});
</script>
@endpush
```

Event `form:saved` sudah di-trigger oleh `handleFormSubmit`.

---

## 6️⃣ Checklist Cepat

- [x] `core.js` di-load **sebelum** `button.js`
- [x] Ada `<meta name="csrf-token">` di `<head>`
- [x] Konten utama punya class `main-content`
- [x] Tabel punya `id` + class `dataTable`
- [x] Tombol delete pakai class `.delete-button` + `data-url`
- [x] Form pakai class `.form-horizontal` + `data-validate="true"`
- [x] Tombol submit dalam modal pakai `.modal-submit`
- [x] Untuk custom JS, pakai `AppCore.ajax` / `ButtonJS.Ajax` (jangan `$.ajax` mentah)

---

## 7️⃣ Satu Hal yang Perlu Diperbaiki

Di `core.js` ada baris:

```js
error: (message) => {
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan core',  // <-- hardcoded "core"
        text: message
    });
}
```

Judul `"Terjadi Kesalahan core"` sebaiknya diganti jadi `"Terjadi Kesalahan"` saja — karena `button.js` juga memanggil `AppCore.ui.notify.error()`, jadi user akan lihat kata "core" di UI, kurang profesional.

---

Kalau kamu mau, saya bisa bantu buatkan **contoh CRUD lengkap (Controller + Blade)** yang pakai kedua file ini tanpa nulis JS sama sekali. Mau saya buatkan?