<?php

namespace Database\Seeders;

use Models\CmsGallery;
use Illuminate\Database\Seeder;

class CmsGallerySeeder extends Seeder
{
    public function run(): void
    {
        $galleries = [
            [
                'slug'        => 'kegiatan-sekolah',
                'title'       => 'Kegiatan Sekolah',
                'description' => 'Dokumentasi kegiatan rutin dan acara besar di lingkungan sekolah.',
                'cover_image' => 'cms/gallery/upacara-bendera.jpg',
                'is_active'   => true,
            ],
            [
                'slug'        => 'wisuda-kelulusan',
                'title'       => 'Wisuda & Kelulusan',
                'description' => 'Momen kebersamaan saat prosesi wisuda dan kelulusan siswa.',
                'cover_image' => 'cms/gallery/wisuda-sma-2026.jpg',
                'is_active'   => true,
            ],
            [
                'slug'        => 'fasilitas-sekolah',
                'title'       => 'Fasilitas Sekolah',
                'description' => 'Sarana dan prasarana yang mendukung kegiatan belajar mengajar.',
                'cover_image' => 'cms/gallery/perpustakaan.jpg',
                'is_active'   => true,
            ],
            [
                'slug'        => 'prestasi-siswa',
                'title'       => 'Prestasi Siswa',
                'description' => 'Pencapaian siswa di bidang akademik dan non-akademik.',
                'cover_image' => 'cms/gallery/olimpiade-sains.jpg',
                'is_active'   => true,
            ],
            [
                'slug'        => 'arsip-kegiatan-2023',
                'title'       => 'Arsip Kegiatan 2023',
                'description' => 'Arsip dokumentasi kegiatan tahun 2023 (contoh galeri nonaktif).',
                'cover_image' => null,
                'is_active'   => false,
            ],
        ];

        foreach ($galleries as $index => $data) {
            $slug = $data['slug'];
            unset($data['slug']);

            $gallery = CmsGallery::withTrashed()->updateOrCreate(
                ['slug' => $slug],
                $data + ['sort_order' => $index + 1]
            );

            if ($gallery->trashed()) {
                $gallery->restore();
            }
        }
    }
}