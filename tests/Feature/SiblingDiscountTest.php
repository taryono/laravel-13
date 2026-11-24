<?php
// php artisan test --filter=SiblingDiscountTest
namespace Tests\Feature;

use Models\AcademicYear;
use Models\Organization;
use Models\School;
use Models\SiblingDiscountAllocation;
use Models\SiblingDiscountProgram;
use Models\SiblingDiscountRule;
use Models\Student;
use Models\StudentFamily;
use Models\StudentFamilyMember;
use Models\User;
use App\Observers\StudentFamilyMemberObserver;
use App\Services\SiblingDiscountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiblingDiscountTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Organization $organization;
    protected School $school;
    protected AcademicYear $academicYear;
    protected SiblingDiscountProgram $program;
    protected SiblingDiscountService $service;

    protected function setUp(): void
    {
        parent::setUp();

        StudentFamilyMember::observe(StudentFamilyMemberObserver::class);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // Sesuaikan kolom wajib pada tabel organizations, schools, dan academic_years di database Anda
        $this->organization = Organization::create([
            'name' => 'Yayasan Nusantara',
            'code' => 'YN',
        ]);

        $this->school = School::create([
            'organization_id' => $this->organization->id,
            'name'            => 'SMA 1',
            'code'            => 'SMA1',
        ]);

        $this->academicYear = AcademicYear::create([
            'organization_id' => $this->organization->id,
            'name'            => '2026/2027',
            'is_active'       => true,
        ]);

        $this->program = SiblingDiscountProgram::create([
            'organization_id'   => $this->organization->id,
            'school_id'         => $this->school->id,
            'code'              => 'SIB-TIERED-2026',
            'name'              => 'Diskon Saudara Bertingkat',
            'min_sibling_count' => 2,
            'calculation_basis' => 'percentage',
            'allocation_method' => 'tiered',
            'start_date'        => '2026-07-01',
            'end_date'          => '2027-06-30',
            'is_active'         => true,
        ]);

        // Tier 1 (2 Anak -> Anak ke-2 diskon 10%)
        $ruleTwo = SiblingDiscountRule::create([
            'sibling_discount_program_id' => $this->program->id,
            'min_sibling_count'           => 2,
            'max_sibling_count'           => 2,
            'discount_type'               => 'percentage',
            'discount_value'              => 10.00,
            'priority'                    => 1,
            'is_active'                   => true,
        ]);

        SiblingDiscountAllocation::create([
            'sibling_discount_rule_id' => $ruleTwo->id,
            'target_type'              => 'specific_order',
            'sibling_order_from'       => 2,
            'sibling_order_to'         => 2,
            'discount_type'            => 'percentage',
            'discount_value'           => 10.00,
        ]);

        // Tier 2 (3+ Anak -> Anak ke-2 diskon 10%, Anak ke-3+ diskon 20%)
        $ruleThree = SiblingDiscountRule::create([
            'sibling_discount_program_id' => $this->program->id,
            'min_sibling_count'           => 3,
            'max_sibling_count'           => null,
            'discount_type'               => 'percentage',
            'discount_value'              => 15.00,
            'priority'                    => 2,
            'is_active'                   => true,
        ]);

        SiblingDiscountAllocation::create([
            'sibling_discount_rule_id' => $ruleThree->id,
            'target_type'              => 'specific_order',
            'sibling_order_from'       => 2,
            'sibling_order_to'         => 2,
            'discount_type'            => 'percentage',
            'discount_value'           => 10.00,
        ]);

        SiblingDiscountAllocation::create([
            'sibling_discount_rule_id' => $ruleThree->id,
            'target_type'              => 'specific_order',
            'sibling_order_from'       => 3,
            'sibling_order_to'         => null,
            'discount_type'            => 'percentage',
            'discount_value'           => 20.00,
        ]);

        $this->service = app(SiblingDiscountService::class);
    }

    public function test_observer_automatically_orders_siblings_by_joined_date(): void
    {
        $family = StudentFamily::create([
            'organization_id' => $this->organization->id,
            'family_code'     => 'FAM-001',
            'family_name'     => 'Keluarga Budi',
            'is_active'       => true,
        ]);

        $s1 = Student::create(['organization_id' => $this->organization->id, 'name' => 'Anak 1']);
        $s2 = Student::create(['organization_id' => $this->organization->id, 'name' => 'Anak 2']);

        $m2 = StudentFamilyMember::create([
            'organization_id'   => $this->organization->id,
            'student_family_id' => $family->id,
            'student_id'        => $s2->id,
            'joined_at'         => '2026-07-01',
            'is_active'         => true,
        ]);

        $m1 = StudentFamilyMember::create([
            'organization_id'   => $this->organization->id,
            'student_family_id' => $family->id,
            'student_id'        => $s1->id,
            'joined_at'         => '2024-07-01',
            'is_active'         => true,
        ]);

        $this->assertEquals(1, $m1->fresh()->sibling_order);
        $this->assertEquals(2, $m2->fresh()->sibling_order);
        $this->assertEquals($this->user->id, $m1->created_by);
    }

    public function test_tiered_discount_calculation_and_eligibility_snapshot(): void
    {
        $family = StudentFamily::create([
            'organization_id' => $this->organization->id,
            'family_code'     => 'FAM-002',
            'family_name'     => 'Keluarga Wijaya',
            'is_active'       => true,
        ]);

        $s1 = Student::create(['organization_id' => $this->organization->id, 'name' => 'Siswa 1']);
        $s2 = Student::create(['organization_id' => $this->organization->id, 'name' => 'Siswa 2']);
        $s3 = Student::create(['organization_id' => $this->organization->id, 'name' => 'Siswa 3']);

        $m1 = StudentFamilyMember::create([
            'organization_id'   => $this->organization->id,
            'student_family_id' => $family->id,
            'student_id'        => $s1->id,
            'joined_at'         => '2024-07-01',
            'is_active'         => true,
        ]);

        $m2 = StudentFamilyMember::create([
            'organization_id'   => $this->organization->id,
            'student_family_id' => $family->id,
            'student_id'        => $s2->id,
            'joined_at'         => '2025-07-01',
            'is_active'         => true,
        ]);

        $m3 = StudentFamilyMember::create([
            'organization_id'   => $this->organization->id,
            'student_family_id' => $family->id,
            'student_id'        => $s3->id,
            'joined_at'         => '2026-07-01',
            'is_active'         => true,
        ]);

        $refDate = now()->parse('2026-08-01');
        $this->service->syncFamilyEligibilities($family, $this->academicYear->id, $this->school->id, $refDate);

        // Verifikasi snapshot eligibility di database
        $this->assertDatabaseHas('sibling_discount_eligibilities', [
            'student_id'          => $s1->id,
            'sibling_count'       => 3,
            'sibling_order'       => 1,
            'is_eligible'         => 0,
            'discount_program_id' => null,
        ]);

        $this->assertDatabaseHas('sibling_discount_eligibilities', [
            'student_id'          => $s3->id,
            'sibling_count'       => 3,
            'sibling_order'       => 3,
            'is_eligible'         => 1,
            'discount_program_id' => $this->program->id,
        ]);

        // Verifikasi nominal potongan SPP Rp 1.000.000
        $resultS2 = $this->service->calculateDiscountForMember($m2->fresh(), 1000000, $this->school->id, $refDate);
        $resultS3 = $this->service->calculateDiscountForMember($m3->fresh(), 1000000, $this->school->id, $refDate);

        $this->assertEquals(100000.0, $resultS2['discount_amount']);
        $this->assertEquals(900000.0, $resultS2['final_amount']);

        $this->assertEquals(200000.0, $resultS3['discount_amount']);
        $this->assertEquals(800000.0, $resultS3['final_amount']);
    }
}