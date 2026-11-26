<?php

namespace Database\Seeders;

use Models\CmsGallery;
use Models\CmsGalleryItem;
use Models\CmsMedia;
use Illuminate\Database\Seeder;

class CmsGalleryItemSeeder extends Seeder
{
    public function run(): void
    {
        // file_name => id
        $mediaIds = CmsMedia::pluck('id', 'file_name');

        if ($mediaIds->isEmpty()) {
            $this->command?->error('cms_media masih kosong. Jalankan CmsMediaSeeder terlebih dahulu.');
            return;
        }

        // slug galeri => daftar item.
        // 'media' = file_name di cms_media; 'image' = path langsung (tanpa media library).
        $map = [
            'kegiatan-sekolah' => [
                ['media' => 'upacara-bendera.jpg',   'caption' => 'Upacara bendera rutin setiap hari Senin'],
                ['media' => 'pentas-seni.jpg',       'caption' => 'Pentas seni tahunan'],
                ['media' => 'kegiatan-outbound.jpg', 'caption' => 'Outbound membangun kerja sama tim'],
                ['media' => 'lomba-17-agustus.jpg',  'caption' => 'Lomba memperingati Hari Kemerdekaan'],
            ],
            'wisuda-kelulusan' => [
                ['media' => 'wisuda-sma-2026.jpg', 'caption' => 'Prosesi wisuda lulusan SMA'],
                ['media' => 'toga-bersama.jpg',    'caption' => 'Foto bersama seusai wisuda'],
                ['media' => 'foto-angkatan.jpg',   'caption' => 'Foto angkatan'],
            ],
            'fasilitas-sekolah' => [
                ['media' => 'perpustakaan.jpg',          'caption' => 'Perpustakaan dengan koleksi lengkap'],
                ['media' => 'laboratorium-komputer.jpg', 'caption' => 'Laboratorium komputer'],
                ['media' => 'lapangan-olahraga.jpg',     'caption' => 'Lapangan serbaguna'],
                // Contoh item lama yang hanya memakai kolom `image` (tanpa media_id)
                ['image' => 'cms/gallery/legacy/ruang-kelas.jpg', 'caption' => 'Ruang kelas'],
            ],
            'prestasi-siswa' => [
                ['media' => 'olimpiade-sains.jpg', 'caption' => 'Juara olimpiade sains tingkat kota'],
                ['media' => 'juara-futsal.jpg',    'caption' => 'Tim futsal juara turnamen antar sekolah'],
            ],
        ];

        foreach ($map as $slug => $items) {
            $gallery = CmsGallery::where('slug', $slug)->first();

            if (!$gallery) {
                $this->command?->warn("Galeri '{$slug}' tidak ditemukan, dilewati. Jalankan CmsGallerySeeder dulu.");
                continue;
            }

            foreach ($items as $index => $item) {
                $mediaId = isset($item['media']) ? ($mediaIds[$item['media']] ?? null) : null;
                $image   = $item['image'] ?? null;

                if (!$mediaId && !$image) {
                    $this->command?->warn("Item pada galeri '{$slug}' dilewati: media/image tidak ditemukan.");
                    continue;
                }

                // Kunci pencocokan: galeri + (media atau image)
                $keys = ['gallery_id' => $gallery->id]
                    + ($mediaId ? ['media_id' => $mediaId] : ['image' => $image]);

                CmsGalleryItem::updateOrCreate($keys, [
                    'media_id'   => $mediaId,
                    'image'      => $mediaId ? null : $image,
                    'caption'    => $item['caption'] ?? null,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}