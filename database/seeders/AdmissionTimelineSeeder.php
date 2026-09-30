<?php
// database/seeders/AdmissionTimelineSeeder.php

namespace Database\Seeders;

use Models\Admission;
use Models\AdmissionTimeline;
use Illuminate\Database\Seeder;

class AdmissionTimelineSeeder extends Seeder
{
    public function run(): void
    {
        $admission = Admission::first();
        if (!$admission) return;

        $phases = [
            ['Pendaftaran Online', 0, 5, 'Layanan 24 Jam (Ditutup pkl 23:59 WIB)'],
            ['Verifikasi Berkas & Dokumen', 6, 9, 'Jam Kerja Tim Panitia: 08:00 - 15:30 WIB'],
            ['Pengumuman Kelulusan', 10, 10, 'Diumumkan serentak pukul 10:00 WIB'],
            ['Daftar Ulang Fisik di Sekolah', 11, 14, 'Membawa berkas fisik asli ke sekolah tujuan'],
        ];

        foreach ($phases as $i => [$name, $startOffset, $endOffset, $desc]) {
            AdmissionTimeline::updateOrCreate(
                [
                    'admission_id' => $admission->id,
                    'phase_name' => $name,
                ],
                [
                    'start_date' => $admission->start_date->copy()->addDays($startOffset),
                    'end_date' => $admission->start_date->copy()->addDays($endOffset),
                    'description' => $desc,
                    'order_number' => $i + 1,
                    'is_active' => 1,
                ]
            );
        }
    }
}
