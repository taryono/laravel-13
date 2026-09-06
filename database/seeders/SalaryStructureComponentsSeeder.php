<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryStructureComponentsSeeder extends Seeder
{
    public function run()
    {
        $organizationId = 1;

        // Ambil ID salary_components berdasarkan code
        $components = DB::table('salary_components')
            ->where('organization_id', $organizationId)
            ->pluck('id', 'code');

        // Ambil ID salary_structures berdasarkan code
        $structures = DB::table('salary_structures')
            ->where('organization_id', $organizationId)
            ->pluck('id', 'code');

        if ($components->isEmpty() || $structures->isEmpty()) {
            $this->command->warn('⚠️ Data salary_components atau salary_structures tidak ditemukan.');
            return;
        }

        $structureComponents = [];

        // ================================================================
        // 1. STRUCT_GURU_TK (Guru TK)
        // ================================================================
        $structId = $structures['STRUCT_GURU_TK'] ?? null;
        if ($structId) {
            $structureComponents = array_merge($structureComponents, [
                // Earning
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['SALARY_BASE'],
                    'calculation_type' => 'fixed',
                    'value' => 4000000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 1,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_POSITION'],
                    'calculation_type' => 'fixed',
                    'value' => 500000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 2,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_TRANSPORT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 3,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_MEAL'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 4,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Deduction
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 10,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 11,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_TAX_PPH21'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 12,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Company Contribution
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 20,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 21,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // ================================================================
        // 2. STRUCT_GURU_SERTIFIKASI_TK (Guru TK Sertifikasi)
        // ================================================================
        $structId = $structures['STRUCT_GURU_SERTIFIKASI_TK'] ?? null;
        if ($structId) {
            $structureComponents = array_merge($structureComponents, [
                // Earning
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['SALARY_BASE'],
                    'calculation_type' => 'fixed',
                    'value' => 4500000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 1,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_POSITION'],
                    'calculation_type' => 'fixed',
                    'value' => 500000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 2,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_CERTIFICATION'],
                    'calculation_type' => 'fixed',
                    'value' => 1000000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 3,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_TRANSPORT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 4,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_MEAL'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 5,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Deduction
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 10,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 11,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_TAX_PPH21'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 12,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Company Contribution
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 20,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 21,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // ================================================================
        // 3. STRUCT_DOSEN_TETAP (Dosen Tetap)
        // ================================================================
        $structId = $structures['STRUCT_DOSEN_TETAP'] ?? null;
        if ($structId) {
            $structureComponents = array_merge($structureComponents, [
                // Earning
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['SALARY_BASE'],
                    'calculation_type' => 'fixed',
                    'value' => 8000000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 1,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_POSITION'],
                    'calculation_type' => 'fixed',
                    'value' => 1000000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 2,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_FAMILY'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 3,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_FUNCTIONAL'],
                    'calculation_type' => 'fixed',
                    'value' => 1500000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 4,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_PROFESSION'],
                    'calculation_type' => 'fixed',
                    'value' => 2000000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 5,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_TRANSPORT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 6,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Deduction
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 10,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 11,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_TAX_PPH21'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 12,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Company Contribution
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 20,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 21,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // ================================================================
        // 4. STRUCT_STAFF_ADMIN (Staf Administrasi)
        // ================================================================
        $structId = $structures['STRUCT_STAFF_ADMIN'] ?? null;
        if ($structId) {
            $structureComponents = array_merge($structureComponents, [
                // Earning
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['SALARY_BASE'],
                    'calculation_type' => 'fixed',
                    'value' => 3500000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 1,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_POSITION'],
                    'calculation_type' => 'fixed',
                    'value' => 300000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 2,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_TRANSPORT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 3,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Deduction
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 10,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 11,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_TAX_PPH21'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 12,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Company Contribution
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 20,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 21,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // ================================================================
        // 5. STRUCT_KEPALA_SEKOLAH (Kepala Sekolah)
        // ================================================================
        $structId = $structures['STRUCT_KEPALA_SEKOLAH'] ?? null;
        if ($structId) {
            $structureComponents = array_merge($structureComponents, [
                // Earning
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['SALARY_BASE'],
                    'calculation_type' => 'fixed',
                    'value' => 7000000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 1,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_POSITION'],
                    'calculation_type' => 'fixed',
                    'value' => 2000000.0000,
                    'calculation_base' => null,
                    'calculation_order' => 2,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['ALLOWANCE_TRANSPORT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 3,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Deduction
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 10,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 11,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['DEDUCTION_TAX_PPH21'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 12,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Company Contribution
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_HEALTH'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 20,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_structure_id' => $structId,
                    'salary_component_id' => $components['COMPANY_BPJS_EMPLOYMENT'],
                    'calculation_type' => 'inherit',
                    'value' => 0.0000,
                    'calculation_base' => null,
                    'calculation_order' => 21,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // ================================================================
        // INSERT DATA
        // ================================================================

        // Cek existing data (cek kombinasi salary_structure_id + salary_component_id)
        $existing = DB::table('salary_structure_components')
            ->select('salary_structure_id', 'salary_component_id')
            ->get()
            ->map(function ($item) {
                return $item->salary_structure_id . '|' . $item->salary_component_id;
            })
            ->toArray();

        $newData = array_filter($structureComponents, function ($item) use ($existing) {
            $key = $item['salary_structure_id'] . '|' . $item['salary_component_id'];
            return !in_array($key, $existing);
        });

        if (!empty($newData)) {
            $chunks = array_chunk($newData, 100);
            foreach ($chunks as $chunk) {
                DB::table('salary_structure_components')->insert($chunk);
            }
            $this->command->info('✅ ' . count($newData) . ' salary structure component(s) berhasil ditambahkan.');
        } else {
            $this->command->info('ℹ️ Tidak ada salary structure component baru yang ditambahkan.');
        }

        // ================================================================
        // STATISTIK
        // ================================================================
        $totalStructures = DB::table('salary_structures')
            ->where('organization_id', $organizationId)
            ->where('is_active', 1)
            ->count();

        $totalComponents = DB::table('salary_structure_components')
            ->where('is_active', 1)
            ->count();

        $this->command->info('📊 Statistik:');
        $this->command->info('   - Total Salary Structures: ' . $totalStructures);
        $this->command->info('   - Total Structure Components: ' . $totalComponents);
        $this->command->info('   - Average Components per Structure: ' . round($totalComponents / max($totalStructures, 1), 1));
    }
}