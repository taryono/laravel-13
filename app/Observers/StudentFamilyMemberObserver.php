<?php

namespace App\Observers;

use App\Models\AcademicYear;
use App\Models\StudentFamily;
use App\Models\StudentFamilyMember;
use App\Services\SiblingDiscountService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class StudentFamilyMemberObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct(
        protected SiblingDiscountService $siblingDiscountService
    ) {}

    /**
     * Dipicu setelah anggota keluarga baru berhasil dibuat.
     */
    public function created(StudentFamilyMember $member): void
    {
        $this->syncFamily($member->family);
    }

    /**
     * Dipicu setelah data anggota keluarga diperbarui.
     * Hanya berjalan jika kolom yang memengaruhi urutan/kelayakan ikut berubah (mencegah infinite loop).
     */
    public function updated(StudentFamilyMember $member): void
    {
        $relevantFields = [
            'is_active',
            'joined_at',
            'left_at',
            'student_family_id',
            'student_id',
            'applicant_id',
        ];

        if (!$member->wasChanged($relevantFields)) {
            return;
        }

        // Jika anggota dipindahkan ke keluarga lain, sinkronkan juga keluarga lamanya
        if ($member->wasChanged('student_family_id')) {
            $oldFamilyId = $member->getOriginal('student_family_id');
            if ($oldFamilyId) {
                $oldFamily = StudentFamily::find($oldFamilyId);
                $this->syncFamily($oldFamily);
            }
        }

        $this->syncFamily($member->family);
    }

    /**
     * Dipicu setelah anggota keluarga dihapus (soft delete atau force delete).
     */
    public function deleted(StudentFamilyMember $member): void
    {
        // Hindari memanggil update() pada record yang baru saja di-delete secara rekursif
        $this->syncFamily($member->family);
    }

    /**
     * Dipicu setelah anggota keluarga yang di-soft delete dipulihkan (restore).
     */
    public function restored(StudentFamilyMember $member): void
    {
        $this->syncFamily($member->family);
    }

    /**
     * Helper terpusat untuk menghitung ulang urutan saudara & kelayakan tahun ajaran aktif.
     */
    protected function syncFamily(?StudentFamily $family): void
    {
        if (!$family) {
            return;
        }

        // 1. Hitung ulang sibling_order seluruh anggota aktif
        // (Aman dari loop karena updated() hanya memantau $relevantFields, bukan sibling_order)
        $this->siblingDiscountService->recalculateSiblingOrders($family);

        // 2. Sinkronkan snapshot kelayakan jika tahun ajaran aktif tersedia
        $activeAcademicYearId = $this->resolveActiveAcademicYearId($family->organization_id);
        if ($activeAcademicYearId) {
            $this->siblingDiscountService->syncFamilyEligibilities(
                family: $family,
                academicYearId: $activeAcademicYearId
            );
        }
    }

    /**
     * Mengambil ID Tahun Ajaran aktif untuk organisasi terkait.
     * Sesuaikan nama kolom status aktif dengan tabel academic_years Anda.
     */
    protected function resolveActiveAcademicYearId(int $organizationId): ?int
    {
        return AcademicYear::query()
            ->where('organization_id', $organizationId)
            ->where('is_active', true)
            ->value('id');
    }
}