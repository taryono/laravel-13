<?php

namespace Database\Seeders;

use Models\CostCenter;
use Models\Organization;
use Models\Department;
use Illuminate\Database\Seeder;

class CostCenterSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil organisasi utama (sesuaikan kode/nama dengan datamu)
        $org = Organization::first() ?? Organization::create(['name' => 'PT. Perusahaan Contoh', 'code' => 'PT-001']);
        $orgId = $org->id;

        // Ambil departemen untuk relasi department_id
        // Pastikan tabel departments sudah terisi SEBELUM seeder ini berjalan!
        $deptHr = Department::where('code', 'HR-PST')->orWhere('name', 'HRD / Personalia')->first()?->id
               ?? Department::first()?->id;
        $deptFin = Department::where('code', 'FIN-PST')->orWhere('name', 'Keuangan & Akuntansi')->first()?->id
                ?? Department::skip(1)->first()?->id;
        $deptMkt = Department::where('code', 'MKT-PST')->orWhere('name', 'Pemasaran')->first()?->id
                ?? Department::skip(2)->first()?->id;
        $deptProd = Department::where('code', 'IT-PST')->orWhere('name', 'Produksi')->first()?->id
                 ?? Department::skip(3)->first()?->id;
        $deptIt = Department::where('code', 'IT-DEV')->orWhere('name', 'Teknologi Informasi')->first()?->id
                ?? Department::skip(4)->first()?->id;
        $deptGen = Department::where('code', 'IT-SUP')->orWhere('name', 'Umum & Logistik')->first()?->id
                 ?? Department::skip(5)->first()?->id;

        // ==========================================
        // Langkah 1: Buat Pusat Biaya Level Atas
        // ==========================================
        $parents = [
            ['code' => 'DIR', 'name' => 'Direksi & Kantor Pusat', 'department_id' => null],
            ['code' => 'DIV-FIN', 'name' => 'Divisi Keuangan', 'department_id' => $deptFin],
            ['code' => 'DIV-HR', 'name' => 'Divisi Sumber Daya Manusia', 'department_id' => $deptHr],
            ['code' => 'DIV-MKT', 'name' => 'Divisi Pemasaran & Penjualan', 'department_id' => $deptMkt],
            ['code' => 'DIV-PROD', 'name' => 'Divisi Produksi & Operasional', 'department_id' => $deptProd],
        ];

        $parentIds = [];
        foreach ($parents as $item) {
            $cc = CostCenter::updateOrCreate(
                ['organization_id' => $orgId, 'code' => $item['code']],
                [
                    'organization_id' => $orgId,
                    'code' => $item['code'],
                    'name' => $item['name'],
                    'department_id' => $item['department_id'],
                    'parent_id' => null,
                    'is_active' => 1,
                ]
            );
            $parentIds[$item['code']] = $cc->id;
        }

        // ==========================================
        // Langkah 2: Buat Pusat Biaya Bertingkat
        // ==========================================
        $children = [
            // Di bawah Divisi Keuangan
            ['code' => 'FIN-ACC', 'name' => 'Bagian Akuntansi & Pembukuan', 'parent_code' => 'DIV-FIN', 'department_id' => $deptFin],
            ['code' => 'FIN-TRE', 'name' => 'Bagian Kas & Perbendaharaan', 'parent_code' => 'DIV-FIN', 'department_id' => $deptFin],
            ['code' => 'FIN-BUD', 'name' => 'Bagian Anggaran & Biaya', 'parent_code' => 'DIV-FIN', 'department_id' => $deptFin],
            // Di bawah Divisi HR
            ['code' => 'HR-EMP', 'name' => 'Bagian Kepegawaian', 'parent_code' => 'DIV-HR', 'department_id' => $deptHr],
            ['code' => 'HR-PAY', 'name' => 'Bagian Penggajian & Tunjangan', 'parent_code' => 'DIV-HR', 'department_id' => $deptHr],
            ['code' => 'HR-DEV', 'name' => 'Bagian Pengembangan SDM', 'parent_code' => 'DIV-HR', 'department_id' => $deptHr],
            // Di bawah Divisi Pemasaran
            ['code' => 'MKT-MRK', 'name' => 'Bagian Pemasaran & Promosi', 'parent_code' => 'DIV-MKT', 'department_id' => $deptMkt],
            ['code' => 'MKT-SAL', 'name' => 'Bagian Penjualan', 'parent_code' => 'DIV-MKT', 'department_id' => $deptMkt],
            // Di bawah Divisi Produksi
            ['code' => 'PROD-OPR', 'name' => 'Bagian Operasional Produksi', 'parent_code' => 'DIV-PROD', 'department_id' => $deptProd],
            ['code' => 'PROD-QC', 'name' => 'Bagian Pengendalian Kualitas', 'parent_code' => 'DIV-PROD', 'department_id' => $deptProd],
            // Bagian IT & Umum (di bawah Direksi)
            ['code' => 'SP-IT', 'name' => 'Unit Teknologi Informasi', 'parent_code' => 'DIR', 'department_id' => $deptIt],
            ['code' => 'SP-UMUM', 'name' => 'Bagian Umum, Perlengkapan & Logistik', 'parent_code' => 'DIR', 'department_id' => $deptGen],
        ];

        foreach ($children as $item) {
            CostCenter::updateOrCreate(
                ['organization_id' => $orgId, 'code' => $item['code']],
                [
                    'organization_id' => $orgId,
                    'code' => $item['code'],
                    'name' => $item['name'],
                    'parent_id' => $parentIds[$item['parent_code']] ?? null,
                    'department_id' => $item['department_id'],
                    'is_active' => 1,
                ]
            );
        }
    }
}