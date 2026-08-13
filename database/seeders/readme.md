php artisan migrate --path=/database/migrations/2026_01_01_000000_create_education_levels_table.php
🚀 Cara Menjalankan Seeder
1. Jalankan Semua Seeder Sekaligus:
bash
php artisan db:seed
2. Atau Refresh Migrasi + Seeder (Reset Database):
bash
php artisan migrate:fresh --seed
3. Jalankan Seeder Tertentu:
bash
php artisan db:seed --class=FacilityCategorySeeder
📊 Hasil Data yang Akan Masuk
Tabel	Jumlah Data
facility_categories	3 data
buildings	8 data
floors	26 data (total dari semua gedung)
rooms	± 70 data
facilities	± 150+ data
⚠️ Catatan Penting
Pastikan Relasi Berurutan: Jalankan seeder sesuai urutan yang benar (categories → buildings → floors → rooms → facilities) karena ada foreign key.

Sesuaikan dengan Data Asli: Data di atas adalah contoh. Anda bisa menyesuaikan nama gedung, ruangan, dan fasilitas dengan kondisi riil di yayasan Anda.

Tambahkan Timestamps: Semua seeder sudah menyertakan created_at dan updated_at.

Soft Deletes: Karena semua tabel menggunakan softDeletes(), Anda bisa menghapus data tanpa kehilangan histori.