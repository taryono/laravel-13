<?php

namespace Database\Seeders;

use Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // Langkah 1: Organisasi Induk / Kantor Pusat
        // ==========================================
        $parent = Organization::updateOrCreate(
            ['code' => 'PT-UTAMA'],
            [
                'code'              => 'PT-UTAMA',
                'name'              => 'Perusahaan Utama',
                'legal_name'        => 'PT. Perusahaan Utama (Persero)',
                'organization_type' => 'company',
                'parent_id'         => null,
                'currency_code'     => 'IDR',
                'timezone'          => 'Asia/Jakarta',
                'is_active'         => 1,
            ]
        );

        $parentId = $parent->id;

        // ==========================================
        // Langkah 2: Unit / Cabang / Divisi di Bawahnya
        // ==========================================
        $children = [
            [
                'code'              => 'CAB-JKT',
                'name'              => 'Kantor Cabang Jakarta',
                'legal_name'        => 'PT. Perusahaan Utama - Cabang Jakarta',
                'organization_type' => 'unit',
                'currency_code'     => 'IDR',
                'timezone'          => 'Asia/Jakarta',
            ],
            [
                'code'              => 'CAB-SBY',
                'name'              => 'Kantor Cabang Surabaya',
                'legal_name'        => 'PT. Perusahaan Utama - Cabang Surabaya',
                'organization_type' => 'unit',
                'currency_code'     => 'IDR',
                'timezone'          => 'Asia/Jakarta',
            ],
            [
                'code'              => 'CAB-MDN',
                'name'              => 'Kantor Cabang Medan',
                'legal_name'        => 'PT. Perusahaan Utama - Cabang Medan',
                'organization_type' => 'unit',
                'currency_code'     => 'IDR',
                'timezone'          => 'Asia/Jakarta',
            ],
            [
                'code'              => 'DIV-PUSAT',
                'name'              => 'Divisi Pusat',
                'legal_name'        => null,
                'organization_type' => 'unit',
                'currency_code'     => 'IDR',
                'timezone'          => 'Asia/Jakarta',
            ],
            [
                'code'              => 'DIV-OPERASIONAL',
                'name'              => 'Divisi Operasional',
                'legal_name'        => null,
                'organization_type' => 'unit',
                'currency_code'     => 'IDR',
                'timezone'          => 'Asia/Jakarta',
            ],
        ];

        foreach ($children as $item) {
            Organization::updateOrCreate(
                ['code' => $item['code']],
                array_merge($item, ['parent_id' => $parentId, 'is_active' => 1])
            );
        }

        // ==========================================
        // Contoh: Organisasi dengan Mata Uang Berbeda
        // ==========================================
        Organization::updateOrCreate(
            ['code' => 'DIV-EXPORT'],
            [
                'code'              => 'DIV-EXPORT',
                'name'              => 'Divisi Ekspor / Internasional',
                'legal_name'        => 'PT. Perusahaan Utama - Divisi Ekspor',
                'organization_type' => 'unit',
                'parent_id'         => $parentId,
                'currency_code'     => 'USD',
                'timezone'          => 'Asia/Jakarta',
                'is_active'         => 1,
            ]
        );
    }
}