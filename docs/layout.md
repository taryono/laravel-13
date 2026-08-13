🎨 Catatan Tambahan
Karena Bootstrap 5.3+ sudah mendukung data-bs-theme="dark", maka warna latar, teks, komponen formulir, dan tombol akan otomatis berubah sesuai tema gelap tanpa perlu menulis CSS tambahan yang banyak.
Jika ingin menyesuaikan warna khusus saat mode gelap, bisa tambahkan CSS seperti ini di bagian <style>:
css
[data-bs-theme="dark"] {
    --ink: #dee2e6;
    --muted: #9ea4b0;
    --soft-bg: #1e2436;
    --card-border: #343a40;
}