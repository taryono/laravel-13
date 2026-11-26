<?php

namespace Database\Seeders;

use Models\CmsMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsMediaSeeder extends Seeder
{
    public function run(): void
    {
        // user_id boleh null; diisi user pertama jika ada
        $userId = DB::table('users')->value('id');

        $files = [
            ['file_name' => 'upacara-bendera.jpg',       'name' => 'Upacara Bendera',        'size' => 312450, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Siswa mengikuti upacara bendera di lapangan sekolah', 'caption' => 'Upacara bendera rutin setiap hari Senin'],
            ['file_name' => 'pentas-seni.jpg',           'name' => 'Pentas Seni',            'size' => 428900, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Penampilan tari siswa di atas panggung', 'caption' => 'Pentas seni tahunan'],
            ['file_name' => 'kegiatan-outbound.jpg',     'name' => 'Kegiatan Outbound',      'size' => 389120, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Siswa bermain permainan kelompok di alam terbuka', 'caption' => 'Outbound membangun kerja sama tim'],
            ['file_name' => 'lomba-17-agustus.jpg',      'name' => 'Lomba 17 Agustus',       'size' => 365780, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Siswa mengikuti lomba tarik tambang', 'caption' => 'Lomba memperingati Hari Kemerdekaan'],
            ['file_name' => 'wisuda-sma-2026.jpg',       'name' => 'Wisuda SMA 2026',        'size' => 451230, 'width' => 1920, 'height' => 1280, 'alt_text' => 'Prosesi wisuda lulusan SMA', 'caption' => 'Prosesi wisuda lulusan SMA'],
            ['file_name' => 'toga-bersama.jpg',          'name' => 'Foto Toga Bersama',      'size' => 398640, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Lulusan berfoto bersama mengenakan toga', 'caption' => 'Foto bersama seusai wisuda'],
            ['file_name' => 'foto-angkatan.jpg',         'name' => 'Foto Angkatan',          'size' => 512300, 'width' => 2400, 'height' => 1200, 'alt_text' => 'Foto seluruh peserta wisuda satu angkatan', 'caption' => 'Foto angkatan'],
            ['file_name' => 'perpustakaan.jpg',          'name' => 'Perpustakaan',           'size' => 276540, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Rak buku dan area baca perpustakaan', 'caption' => 'Perpustakaan dengan koleksi lengkap'],
            ['file_name' => 'laboratorium-komputer.jpg', 'name' => 'Laboratorium Komputer',  'size' => 301870, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Deretan komputer di laboratorium', 'caption' => 'Laboratorium komputer'],
            ['file_name' => 'lapangan-olahraga.jpg',     'name' => 'Lapangan Olahraga',      'size' => 344910, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Lapangan serbaguna untuk olahraga', 'caption' => 'Lapangan serbaguna'],
            ['file_name' => 'olimpiade-sains.jpg',       'name' => 'Olimpiade Sains',        'size' => 287650, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Siswa menerima medali olimpiade sains', 'caption' => 'Juara olimpiade sains tingkat kota'],
            ['file_name' => 'juara-futsal.jpg',          'name' => 'Juara Futsal',           'size' => 333210, 'width' => 1600, 'height' => 1067, 'alt_text' => 'Tim futsal sekolah membawa piala', 'caption' => 'Tim futsal juara turnamen antar sekolah'],
        ];

        foreach ($files as $file) {
            $media = CmsMedia::withTrashed()->updateOrCreate(
                ['path' => 'cms/gallery/' . $file['file_name']],
                [
                    'user_id'   => $userId,
                    'name'      => $file['name'],
                    'file_name' => $file['file_name'],
                    'disk'      => 'public',
                    'mime_type' => 'image/jpeg',
                    'size'      => $file['size'],
                    'width'     => $file['width'],
                    'height'    => $file['height'],
                    'alt_text'  => $file['alt_text'],
                    'caption'   => $file['caption'],
                ]
            );

            if ($media->trashed()) {
                $media->restore();
            }
        }
    }
}