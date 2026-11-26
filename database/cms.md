### 📊 Ringkasan Kesesuaian Desain vs Database

| Fitur di UI           | Tabel/ Kolom yang Ada               | Status |
| --------------------- | ----------------------------------- | ------ |
| Konten Terbit / Draft | `cms_posts`, `cms_pages`            | ✅     |
| Pengunjung Bulan Ini  | `cms_visitor_stats`                 | ✅     |
| Penyimpanan Media     | `cms_media`                         | ✅     |
| Tulis Berita          | `cms_posts`                         | ✅     |
| Buat Pengumuman       | `cms_posts` (kategori)              | ✅     |
| Unggah Galeri         | `cms_galleries`                     | ✅     |
| Tambah Agenda         | `academic_calendar_events`          | ✅     |
| Berita & Artikel      | `cms_posts`                         | ✅     |
| Pengumuman            | `cms_posts`                         | ✅     |
| Galeri Foto & Video   | `cms_galleries`                     | ✅     |
| Agenda Kegiatan       | `academic_calendar_events`          | ✅     |
| Profil Sekolah        | `cms_pages`                         | ✅     |
| Banner & Slider       | `cms_banners`                       | ✅     |
| Dokumen & Unduhan     | `cms_media` (dengan tambahan kolom) | ✅     |
| Prestasi & Testimoni  | `achievements`                      | ✅     |
| Menu & Navigasi       | `cms_menus`, `cms_menu_items`       | ✅     |
| Media Library         | `cms_media`                         | ✅     |
| SEO & Metadata        | `cms_posts`, `cms_pages`            | ✅     |
| Tema & Identitas      | `cms_settings`                      | ✅     |
| Konten Terbaru        | `cms_posts`                         | ✅     |
| Menunggu Review       | `cms_posts` (perlu tambahan status) | ✅     |
| Notifikasi (lonceng)  | `cms_notifications`                 | ✅     |
| Status Website Online | `cms_settings`                      | ✅     |
