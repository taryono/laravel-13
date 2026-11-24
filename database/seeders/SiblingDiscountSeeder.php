<?php

namespace Database\Seeders;

use App\Models\SiblingDiscountAllocation;
use App\Models\SiblingDiscountEligibility;
use App\Models\SiblingDiscountProgram;
use App\Models\SiblingDiscountRule;
use App\Models\StudentFamily;
use App\Models\StudentFamilyMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiblingDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Sesuaikan ID master berikut dengan data eksisting di database Anda
            $organizationId = 1;
            $schoolId       = 1;
            $academicYearId = 1;
            $systemUserId   = 1;

            // =========================================================================
            // 1. PROGRAM DISKON SAUDARA (TIERED)
            // =========================================================================
            $program = SiblingDiscountProgram::updateOrCreate(
                [
                    'organization_id' => $organizationId,
                    'code'            => 'SIB-TIERED-2026',
                ],
                [
                    'school_id'         => $schoolId,
                    'name'              => 'Program Beasiswa Saudara Kandung (Bertingkat)',
                    'description'       => 'Potongan biaya pendidikan berjenjang untuk keluarga dengan minimal 2 anak aktif di sekolah yang sama.',
                    'min_sibling_count' => 2,
                    'calculation_basis' => 'percentage',
                    'allocation_method' => 'tiered',
                    'start_date'        => '2026-07-01',
                    'end_date'          => '2027-06-30',
                    'is_active'         => true,
                    'created_by'        => $systemUserId,
                    'updated_by'        => $systemUserId,
                ]
            );

            // =========================================================================
            // 2. ATURAN DISKON (RULES) & TARGET ALOKASI (ALLOCATIONS)
            // =========================================================================

            // --- TIER 1: Keluarga dengan tepat 2 Anak ---
            $ruleTwoSiblings = SiblingDiscountRule::updateOrCreate(
                [
                    'sibling_discount_program_id' => $program->id,
                    'min_sibling_count'           => 2,
                    'max_sibling_count'           => 2,
                ],
                [
                    'discount_type'  => 'percentage',
                    'discount_value' => 10.00, // Default rule value
                    'priority'       => 1,
                    'is_active'      => true,
                    'created_by'     => $systemUserId,
                    'updated_by'     => $systemUserId,
                ]
            );

            // Alokasi Tier 1: Anak ke-1 (0%), Anak ke-2 (10%)
            SiblingDiscountAllocation::updateOrCreate(
                [
                    'sibling_discount_rule_id' => $ruleTwoSiblings->id,
                    'target_type'              => 'specific_order',
                    'sibling_order_from'       => 2,
                    'sibling_order_to'         => 2,
                ],
                [
                    'discount_type'  => 'percentage',
                    'discount_value' => 10.00,
                    'created_by'     => $systemUserId,
                    'updated_by'     => $systemUserId,
                ]
            );

            // --- TIER 2: Keluarga dengan 3 Anak atau lebih ---
            $ruleThreeOrMoreSiblings = SiblingDiscountRule::updateOrCreate(
                [
                    'sibling_discount_program_id' => $program->id,
                    'min_sibling_count'           => 3,
                    'max_sibling_count'           => null, // Tidak ada batas atas (3+)
                ],
                [
                    'discount_type'  => 'percentage',
                    'discount_value' => 15.00,
                    'priority'       => 2,
                    'is_active'      => true,
                    'created_by'     => $systemUserId,
                    'updated_by'     => $systemUserId,
                ]
            );

            // Alokasi Tier 2A: Anak ke-2 mendapat diskon 10%
            SiblingDiscountAllocation::updateOrCreate(
                [
                    'sibling_discount_rule_id' => $ruleThreeOrMoreSiblings->id,
                    'target_type'              => 'specific_order',
                    'sibling_order_from'       => 2,
                    'sibling_order_to'         => 2,
                ],
                [
                    'discount_type'  => 'percentage',
                    'discount_value' => 10.00,
                    'created_by'     => $systemUserId,
                    'updated_by'     => $systemUserId,
                ]
            );

            // Alokasi Tier 2B: Anak ke-3 dan seterusnya mendapat diskon 20%
            SiblingDiscountAllocation::updateOrCreate(
                [
                    'sibling_discount_rule_id' => $ruleThreeOrMoreSiblings->id,
                    'target_type'              => 'specific_order',
                    'sibling_order_from'       => 3,
                    'sibling_order_to'         => null,
                ],
                [
                    'discount_type'  => 'percentage',
                    'discount_value' => 20.00,
                    'created_by'     => $systemUserId,
                    'updated_by'     => $systemUserId,
                ]
            );

            // =========================================================================
            // 3. DATA KELUARGA (STUDENT FAMILIES) & ANGGOTA (MEMBERS)
            // =========================================================================

            // --- Keluarga A: 3 Anak (2 Siswa Aktif + 1 Pendaftar Baru/Applicant) ---
            $familyWijaya = StudentFamily::updateOrCreate(
                [
                    'organization_id' => $organizationId,
                    'family_code'     => 'FAM-2026-001',
                ],
                [
                    'family_name'       => 'Keluarga Budi Wijaya',
                    'primary_parent_id' => null, // Isi ID parent jika tabel parents sudah terisi
                    'notes'             => 'Keluarga dengan 3 anak, anak bungsu sedang proses PPDB.',
                    'is_active'         => true,
                    'created_by'        => $systemUserId,
                    'updated_by'        => $systemUserId,
                ]
            );

            $wijayaMembers = [
                [
                    'student_id'    => null, // Ganti dengan ID student ke-1 jika sudah ada
                    'applicant_id'  => null,
                    'sibling_order' => 1,
                    'relation'      => 'Anak Kandung',
                    'joined_at'     => '2023-07-10',
                    'is_eligible'   => false, // Anak pertama tidak mendapat potongan
                ],
                [
                    'student_id'    => null, // Ganti dengan ID student ke-2 jika sudah ada
                    'applicant_id'  => null,
                    'sibling_order' => 2,
                    'relation'      => 'Anak Kandung',
                    'joined_at'     => '2025-07-12',
                    'is_eligible'   => true, // Anak ke-2 mendapat 10%
                ],
                [
                    'student_id'    => null,
                    'applicant_id'  => null, // Ganti dengan ID applicant ke-3 jika sudah ada
                    'sibling_order' => 3,
                    'relation'      => 'Anak Kandung',
                    'joined_at'     => '2026-07-01',
                    'is_eligible'   => true, // Anak ke-3 mendapat 20%
                ],
            ];

            foreach ($wijayaMembers as $member) {
                $createdMember = StudentFamilyMember::create([
                    'organization_id'   => $organizationId,
                    'student_family_id' => $familyWijaya->id,
                    'student_id'        => $member['student_id'],
                    'applicant_id'      => $member['applicant_id'],
                    'sibling_order'     => $member['sibling_order'],
                    'relation'          => $member['relation'],
                    'is_active'         => true,
                    'joined_at'         => $member['joined_at'],
                    'created_by'        => $systemUserId,
                    'updated_by'        => $systemUserId,
                ]);

                // Snapshot Kelayakan per Tahun Ajaran
                SiblingDiscountEligibility::create([
                    'organization_id'     => $organizationId,
                    'academic_year_id'    => $academicYearId,
                    'student_family_id'   => $familyWijaya->id,
                    'student_id'          => $createdMember->student_id,
                    'applicant_id'        => $createdMember->applicant_id,
                    'sibling_count'       => 3,
                    'sibling_order'       => $createdMember->sibling_order,
                    'is_eligible'         => $member['is_eligible'],
                    'discount_program_id' => $member['is_eligible'] ? $program->id : null,
                    'snapshot_date'       => now()->toDateString(),
                    'created_by'          => $systemUserId,
                    'updated_by'          => $systemUserId,
                ]);
            }

            // --- Keluarga B: 2 Anak (Keduanya Siswa Aktif) ---
            $familySantoso = StudentFamily::updateOrCreate(
                [
                    'organization_id' => $organizationId,
                    'family_code'     => 'FAM-2026-002',
                ],
                [
                    'family_name'       => 'Keluarga Hendra Santoso',
                    'primary_parent_id' => null,
                    'notes'             => 'Keluarga dengan 2 siswa aktif.',
                    'is_active'         => true,
                    'created_by'        => $systemUserId,
                    'updated_by'        => $systemUserId,
                ]
            );

            $santosoMembers = [
                [
                    'student_id'    => null,
                    'sibling_order' => 1,
                    'relation'      => 'Anak Kandung',
                    'joined_at'     => '2024-07-15',
                    'is_eligible'   => false,
                ],
                [
                    'student_id'    => null,
                    'sibling_order' => 2,
                    'relation'      => 'Anak Kandung',
                    'joined_at'     => '2026-07-14',
                    'is_eligible'   => true, // Anak ke-2 mendapat 10%
                ],
            ];

            foreach ($santosoMembers as $member) {
                $createdMember = StudentFamilyMember::create([
                    'organization_id'   => $organizationId,
                    'student_family_id' => $familySantoso->id,
                    'student_id'        => $member['student_id'],
                    'applicant_id'      => null,
                    'sibling_order'     => $member['sibling_order'],
                    'relation'          => $member['relation'],
                    'is_active'         => true,
                    'joined_at'         => $member['joined_at'],
                    'created_by'        => $systemUserId,
                    'updated_by'        => $systemUserId,
                ]);

                SiblingDiscountEligibility::create([
                    'organization_id'     => $organizationId,
                    'academic_year_id'    => $academicYearId,
                    'student_family_id'   => $familySantoso->id,
                    'student_id'          => $createdMember->student_id,
                    'applicant_id'        => null,
                    'sibling_count'       => 2,
                    'sibling_order'       => $createdMember->sibling_order,
                    'is_eligible'         => $member['is_eligible'],
                    'discount_program_id' => $member['is_eligible'] ? $program->id : null,
                    'snapshot_date'       => now()->toDateString(),
                    'created_by'          => $systemUserId,
                    'updated_by'          => $systemUserId,
                ]);
            }
        });
    }
}

// php artisan db:seed --class=SiblingDiscountSeeder