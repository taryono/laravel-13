<?php

namespace App\Services;

use App\Models\SiblingDiscountAllocation;
use App\Models\SiblingDiscountEligibility;
use App\Models\SiblingDiscountProgram;
use App\Models\SiblingDiscountRule;
use App\Models\StudentFamily;
use App\Models\StudentFamilyMember;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SiblingDiscountService
{
    /**
     * 1. Mengurutkan ulang sibling_order seluruh anggota aktif dalam satu keluarga.
     * Urutan didasarkan pada tanggal bergabung (joined_at ASC), lalu id ASC.
     */
    public function recalculateSiblingOrders(StudentFamily $family): Collection
    {
        return DB::transaction(function () use ($family) {
            $activeMembers = $family->members()
                ->where('is_active', true)
                ->orderByRaw('COALESCE(joined_at, created_at) ASC')
                ->orderBy('id', 'ASC')
                ->get();

            $order = 1;
            foreach ($activeMembers as $member) {
                if ((int) $member->sibling_order !== $order) {
                    $member->update(['sibling_order' => $order]);
                }
                $order++;
            }

            // Reset sibling_order untuk anggota yang sudah tidak aktif
            $family->members()
                ->where('is_active', false)
                ->whereNotNull('sibling_order')
                ->update(['sibling_order' => null]);

            return $activeMembers;
        });
    }

    /**
     * 2. Sinkronisasi dan snapshot kelayakan diskon satu keluarga untuk Tahun Ajaran tertentu.
     */
    public function syncFamilyEligibilities(
        StudentFamily $family,
        int $academicYearId,
        ?int $schoolId = null,
        ?Carbon $referenceDate = null
    ): Collection {
        $referenceDate = $referenceDate ?? now();

        return DB::transaction(function () use ($family, $academicYearId, $schoolId, $referenceDate) {
            // Pastikan urutan anak mutakhir
            $activeMembers = $this->recalculateSiblingOrders($family);
            $siblingCount  = $activeMembers->count();

            // Cari program diskon aktif yang memenuhi syarat jumlah saudara
            $program = $this->resolveApplicableProgram(
                organizationId: $family->organization_id,
                schoolId: $schoolId,
                siblingCount: $siblingCount,
                referenceDate: $referenceDate
            );

            $rule = $program ? $this->resolveApplicableRule($program, $siblingCount) : null;

            $eligibilities = collect();

            foreach ($activeMembers as $member) {
                $allocation = ($rule)
                    ? $this->resolveMemberAllocation($rule, $member->sibling_order, $siblingCount)
                    : null;

                $isEligible = $program !== null && $rule !== null && $allocation !== null;

                $eligibility = SiblingDiscountEligibility::updateOrCreate(
                    [
                        'academic_year_id'  => $academicYearId,
                        'student_family_id' => $family->id,
                        'student_id'        => $member->student_id,
                    ],
                    [
                        'organization_id'     => $family->organization_id,
                        'applicant_id'        => $member->applicant_id,
                        'sibling_count'       => $siblingCount,
                        'sibling_order'       => $member->sibling_order,
                        'is_eligible'         => $isEligible,
                        'discount_program_id' => $isEligible ? $program->id : null,
                        'snapshot_date'       => $referenceDate->toDateString(),
                    ]
                );

                $eligibilities->push($eligibility);
            }

            return $eligibilities;
        });
    }

    /**
     * 3. Menghitung nominal diskon spesifik untuk satu anggota keluarga berdasarkan biaya dasar (baseFee).
     *
     * @return array{
     *     is_eligible: bool,
     *     program_code: ?string,
     *     sibling_count: int,
     *     sibling_order: int,
     *     discount_type: ?string,
     *     discount_value: float,
     *     discount_amount: float,
     *     final_amount: float
     * }
     */
    public function calculateDiscountForMember(
        StudentFamilyMember $member,
        float $baseFee,
        ?int $schoolId = null,
        ?Carbon $referenceDate = null
    ): array {
        $referenceDate = $referenceDate ?? now();
        $family        = $member->family;

        $defaultResponse = [
            'is_eligible'     => false,
            'program_code'    => null,
            'sibling_count'   => 0,
            'sibling_order'   => (int) ($member->sibling_order ?? 0),
            'discount_type'   => null,
            'discount_value'  => 0.0,
            'discount_amount' => 0.0,
            'final_amount'    => max(0.0, $baseFee),
        ];

        if (!$family || !$family->is_active || !$member->is_active) {
            return $defaultResponse;
        }

        $activeMembers = $family->members()->where('is_active', true)->get();
        $siblingCount  = $activeMembers->count();
        $siblingOrder  = (int) $member->sibling_order;

        $defaultResponse['sibling_count'] = $siblingCount;

        // 1. Cari Program
        $program = $this->resolveApplicableProgram(
            organizationId: $family->organization_id,
            schoolId: $schoolId,
            siblingCount: $siblingCount,
            referenceDate: $referenceDate
        );

        if (!$program) {
            return $defaultResponse;
        }

        // 2. Cari Rule berdasarkan jumlah saudara
        $rule = $this->resolveApplicableRule($program, $siblingCount);
        if (!$rule) {
            return $defaultResponse;
        }

        // 3. Validasi Allocation sesuai target_type & sibling_order
        $allocation = $this->resolveMemberAllocation($rule, $siblingOrder, $siblingCount);
        if (!$allocation) {
            return $defaultResponse;
        }

        // 4. Hitung nominal diskon
        $discountType  = $allocation->discount_type;
        $discountValue = (float) $allocation->discount_value;

        $discountAmount = match ($discountType) {
            'percentage'   => round(($baseFee * $discountValue) / 100, 2),
            'fixed_amount' => min($baseFee, $discountValue),
            default        => 0.0,
        };

        return [
            'is_eligible'     => $discountAmount > 0,
            'program_code'    => $program->code,
            'sibling_count'   => $siblingCount,
            'sibling_order'   => $siblingOrder,
            'discount_type'   => $discountType,
            'discount_value'  => $discountValue,
            'discount_amount' => $discountAmount,
            'final_amount'    => max(0.0, round($baseFee - $discountAmount, 2)),
        ];
    }

    /**
     * Mencari program diskon aktif yang berlaku (memprioritaskan program spesifik sekolah di atas program organisasi).
     */
    public function resolveApplicableProgram(
        int $organizationId,
        ?int $schoolId,
        int $siblingCount,
        Carbon $referenceDate
    ): ?SiblingDiscountProgram {
        $dateString = $referenceDate->toDateString();

        return SiblingDiscountProgram::query()
            ->where('organization_id', $organizationId)
            ->where('is_active', true)
            ->where('min_sibling_count', '<=', $siblingCount)
            ->where(function ($query) use ($schoolId) {
                $query->whereNull('school_id');
                if ($schoolId !== null) {
                    $query->orWhere('school_id', $schoolId);
                }
            })
            ->where(function ($query) use ($dateString) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', $dateString);
            })
            ->where(function ($query) use ($dateString) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', $dateString);
            })
            // Prioritaskan yang memiliki school_id spesifik dibanding global (NULL)
            ->orderByRaw('school_id IS NULL ASC')
            ->orderByDesc('min_sibling_count')
            ->first();
    }

    /**
     * Mencari rule terbaik dalam program berdasarkan jumlah saudara dan priority.
     */
    public function resolveApplicableRule(SiblingDiscountProgram $program, int $siblingCount): ?SiblingDiscountRule
    {
        return $program->rules()
            ->where('is_active', true)
            ->where('min_sibling_count', '<=', $siblingCount)
            ->where(function ($query) use ($siblingCount) {
                $query->whereNull('max_sibling_count')
                      ->orWhere('max_sibling_count', '>=', $siblingCount);
            })
            ->orderByDesc('priority')
            ->orderByDesc('min_sibling_count')
            ->first();
    }

    /**
     * Memvalidasi dan mengambil allocation yang cocok dengan urutan anak (sibling_order).
     */
    public function resolveMemberAllocation(
        SiblingDiscountRule $rule,
        int $siblingOrder,
        int $totalSiblings
    ): ?SiblingDiscountAllocation {
        if ($siblingOrder < 1 || $totalSiblings < 1) {
            return null;
        }

        $allocations = $rule->allocations;

        foreach ($allocations as $allocation) {
            $matches = match ($allocation->target_type) {
                'all_members'    => true,
                'second_onward'  => $siblingOrder >= 2,
                'oldest'         => $siblingOrder === 1,
                'youngest'       => $siblingOrder === $totalSiblings,
                'specific_order' => $this->matchesOrderRange(
                    $siblingOrder,
                    $allocation->sibling_order_from,
                    $allocation->sibling_order_to
                ),
                default          => false,
            };

            if ($matches) {
                return $allocation;
            }
        }

        return null;
    }

    /**
     * Helper pengecekan rentang urutan anak (from - to).
     */
    protected function matchesOrderRange(int $order, ?int $from, ?int $to): bool
    {
        if ($from !== null && $order < $from) {
            return false;
        }

        if ($to !== null && $order > $to) {
            return false;
        }

        return $from !== null || $to !== null;
    }
}

// Contoh Penggunaan di Controller / Action PHP

// use App\Models\StudentFamily;
// use App\Models\StudentFamilyMember;
// use App\Services\SiblingDiscountService;

// class InvoiceDiscountController extends Controller
// {
//     public function __construct(
//         protected SiblingDiscountService $siblingDiscountService
//     ) {}

//     public function previewMemberDiscount(StudentFamilyMember $member)
//     {
//         // 1. Sinkronkan kelayakan seluruh anggota keluarga untuk tahun ajaran ID 1
//         $this->siblingDiscountService->syncFamilyEligibilities(
//             family: $member->family,
//             academicYearId: 1,
//             schoolId: 1
//         );

//         // 2. Hitung potongan terhadap biaya SPP Rp 1.500.000
//         $result = $this->siblingDiscountService->calculateDiscountForMember(
//             member: $member->fresh(),
//             baseFee: 1500000,
//             schoolId: 1
//         );

//         return response()->json($result);
//         /* Output untuk anak ke-3 (Tier 20%):
//         {
//             "is_eligible": true,
//             "program_code": "SIB-TIERED-2026",
//             "sibling_count": 3,
//             "sibling_order": 3,
//             "discount_type": "percentage",
//             "discount_value": 20,
//             "discount_amount": 300000,
//             "final_amount": 1200000
//         }
//         */
//     }
// }