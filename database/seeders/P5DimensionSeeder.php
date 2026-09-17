<?php

namespace Database\Seeders;

use Models\P5Dimension;
use Models\P5Element;
use Illuminate\Database\Seeder;

class P5DimensionSeeder extends Seeder
{
    public function run(): void
    {
        $dimensions = [
            [
                'code' => 'beriman',
                'name' => 'Beriman, Bertakwa kepada Tuhan YME, dan Berakhlak Mulia',
                'name_en' => 'Faith in God Almighty and Noble Character',
                'description' => 'Pelajar Indonesia yang beriman, bertakwa kepada Tuhan YME, dan berakhlak mulia.',
                'icon' => 'bi-heart',
                'color' => '#10b981',
                'order' => 1,
                'elements' => [
                    ['code' => 'akhlak_beragama', 'name' => 'Akhlak Beragama', 'order' => 1],
                    ['code' => 'akhlak_pribadi', 'name' => 'Akhlak Pribadi', 'order' => 2],
                    ['code' => 'akhlak_sesama', 'name' => 'Akhlak kepada Manusia', 'order' => 3],
                    ['code' => 'akhlak_alam', 'name' => 'Akhlak kepada Alam', 'order' => 4],
                    ['code' => 'akhlak_negara', 'name' => 'Akhlak Bernegara', 'order' => 5],
                ],
            ],
            [
                'code' => 'berkebhinekaan',
                'name' => 'Berkebhinekaan Global',
                'name_en' => 'Global Diversity',
                'description' => 'Pelajar Indonesia yang mempertahankan budaya luhur, lokalitas, dan identitasnya, namun tetap berpikiran terbuka.',
                'icon' => 'bi-globe2',
                'color' => '#3b82f6',
                'order' => 2,
                'elements' => [
                    ['code' => 'mengenal_budaya', 'name' => 'Mengenal dan Menghargai Budaya', 'order' => 1],
                    ['code' => 'kemampuan_komunikasi', 'name' => 'Kemampuan Komunikasi dan Interaksi Antar Budaya', 'order' => 2],
                    ['code' => 'refleksi_tanggung_jawab', 'name' => 'Refleksi dan Tanggung Jawab terhadap Pengalaman Kebinekaan', 'order' => 3],
                    ['code' => 'keadilan_sosial', 'name' => 'Berkeadilan Sosial', 'order' => 4],
                ],
            ],
            [
                'code' => 'gotong_royong',
                'name' => 'Bergotong Royong',
                'name_en' => 'Mutual Cooperation',
                'description' => 'Pelajar Indonesia yang memiliki kemampuan bergotong royong, yaitu kemampuan untuk melakukan kegiatan secara bersama-sama.',
                'icon' => 'bi-people-fill',
                'color' => '#14b8a6',
                'order' => 3,
                'elements' => [
                    ['code' => 'kolaborasi', 'name' => 'Kolaborasi', 'order' => 1],
                    ['code' => 'kepedulian', 'name' => 'Kepedulian', 'order' => 2],
                    ['code' => 'berbagi', 'name' => 'Berbagi', 'order' => 3],
                ],
            ],
            [
                'code' => 'mandiri',
                'name' => 'Mandiri',
                'name_en' => 'Independence',
                'description' => 'Pelajar Indonesia yang mandiri, yaitu pelajar yang bertanggung jawab atas proses dan hasil belajarnya.',
                'icon' => 'bi-person-check',
                'color' => '#f59e0b',
                'order' => 4,
                'elements' => [
                    ['code' => 'kesadaran_diri', 'name' => 'Pemahaman Diri dan Situasi yang Dihadapi', 'order' => 1],
                    ['code' => 'regulasi_diri', 'name' => 'Regulasi Diri', 'order' => 2],
                ],
            ],
            [
                'code' => 'bernalar_kritis',
                'name' => 'Bernalar Kritis',
                'name_en' => 'Critical Reasoning',
                'description' => 'Pelajar yang mampu secara objektif memproses informasi baik kualitatif maupun kuantitatif.',
                'icon' => 'bi-lightbulb',
                'color' => '#8b5cf6',
                'order' => 5,
                'elements' => [
                    ['code' => 'memperoleh_informasi', 'name' => 'Memperoleh dan Memproses Informasi dan Gagasan', 'order' => 1],
                    ['code' => 'menganalisis', 'name' => 'Menganalisis dan Mengevaluasi Penalaran', 'order' => 2],
                    ['code' => 'refleksi_pemikiran', 'name' => 'Merefleksi dan Mengevaluasi Pemikirannya Sendiri', 'order' => 3],
                    ['code' => 'mengambil_keputusan', 'name' => 'Mengambil Keputusan', 'order' => 4],
                ],
            ],
            [
                'code' => 'kreatif',
                'name' => 'Kreatif',
                'name_en' => 'Creativity',
                'description' => 'Pelajar yang kreatif, yaitu pelajar yang mampu memodifikasi dan menghasilkan sesuatu yang orisinal, bermakna, bermanfaat, dan berdampak.',
                'icon' => 'bi-palette',
                'color' => '#ec4899',
                'order' => 6,
                'elements' => [
                    ['code' => 'menghasilkan_gagasan', 'name' => 'Menghasilkan Gagasan yang Orisinal', 'order' => 1],
                    ['code' => 'menghasilkan_karya', 'name' => 'Menghasilkan Karya dan Tindakan yang Orisinal', 'order' => 2],
                    ['code' => 'keluwesan_berpikir', 'name' => 'Memiliki Keluwesan Berpikir dalam Mencari Alternatif Solusi', 'order' => 3],
                ],
            ],
        ];

        foreach ($dimensions as $dimData) {
            $elements = $dimData['elements'];
            unset($dimData['elements']);

            $dimension = P5Dimension::updateOrCreate(
                ['code' => $dimData['code']],
                $dimData
            );

            foreach ($elements as $element) {
                P5Element::updateOrCreate(
                    [
                        'dimension_id' => $dimension->id,
                        'code' => $element['code'],
                    ],
                    [
                        'name' => $element['name'],
                        'order' => $element['order'],
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ 6 dimensi P5 + elemen berhasil di-seed.');
    }
}
