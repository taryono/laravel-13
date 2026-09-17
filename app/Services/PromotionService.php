<?php

namespace App\Services;

use Models\ClassLevel;
use Models\Classroom;
use Models\PromotionBatch;
use Models\PromotionCriteria;
use Models\Student;
use Models\StudentClassroom;
use Models\StudentPromotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PromotionService
{
    /**
     * Buat batch promosi baru.
     */
    public function createBatch(array $data): PromotionBatch
    {
        return DB::transaction(function () use ($data) {
            return PromotionBatch::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'from_academic_year_id' => $data['from_academic_year_id'],
                'to_academic_year_id' => $data['to_academic_year_id'],
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);
        });
    }

    /**
     * Generate draft promosi untuk semua siswa aktif di batch.
     */
    public function generateDrafts(PromotionBatch $batch): int
    {
        return DB::transaction(function () use ($batch) {
            $batch->update(['status' => 'processing', 'started_at' => now()]);

            // Ambil semua siswa aktif di tahun ajaran asal
            $students = Student::whereHas('classrooms', function ($q) use ($batch) {
                $q->where('academic_year_id', $batch->from_academic_year_id)
                    ->where('status', 'active');
            })->with(['classrooms' => function ($q) use ($batch) {
                $q->where('academic_year_id', $batch->from_academic_year_id)
                    ->where('status', 'active');
            }])->get();

            $count = 0;

            foreach ($students as $student) {
                $currentClassroom = $student->classrooms->first();
                if (!$currentClassroom) {
                    continue;
                }

                // Skip kalau sudah ada promosi untuk siswa ini di batch
                $exists = StudentPromotion::where('student_id', $student->id)
                    ->where('from_academic_year_id', $batch->from_academic_year_id)
                    ->where('to_academic_year_id', $batch->to_academic_year_id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                // Hitung nilai
                $metrics = $this->calculateStudentMetrics($student, $batch->from_academic_year_id);

                // Ambil kriteria
                $criteria = PromotionCriteria::active()
                    ->forYear($batch->from_academic_year_id)
                    ->forLevel($currentClassroom->class_level_id)
                    ->first();

                // Tentukan keputusan
                $decision = $this->determineDecision(
                    $student,
                    $currentClassroom,
                    $metrics,
                    $criteria,
                    $batch->to_academic_year_id
                );

                // Buat draft promosi
                StudentPromotion::create([
                    'student_id' => $student->id,
                    'batch_id' => $batch->id,
                    'from_academic_year_id' => $batch->from_academic_year_id,
                    'to_academic_year_id' => $batch->to_academic_year_id,
                    'from_classroom_id' => $currentClassroom->classroom_id,
                    'from_class_level_id' => $currentClassroom->class_level_id,
                    'to_classroom_id' => $decision['to_classroom_id'],
                    'to_class_level_id' => $decision['to_class_level_id'],
                    'promotion_status' => $decision['status'],
                    'final_score' => $metrics['final_score'],
                    'attendance_rate' => $metrics['attendance_rate'],
                    'behavior_score' => $metrics['behavior_score'],
                    'decision_reason' => $decision['reason'],
                    'status' => 'draft',
                    'created_by' => Auth::id(),
                ]);

                $count++;
            }

            $batch->recalculateStats();
            $batch->update([
                'status' => $count > 0 ? 'completed' : 'draft',
                'completed_at' => now(),
            ]);

            return $count;
        });
    }

    /**
     * Override keputusan promosi secara manual.
     */
    public function overrideDecision(
        StudentPromotion $promotion,
        string $newStatus,
        ?int $toClassroomId = null,
        ?string $reason = null,
        ?int $userId = null
    ): StudentPromotion {
        return DB::transaction(function () use ($promotion, $newStatus, $toClassroomId, $reason, $userId) {
            $userId = $userId ?? Auth::id();

            // Simpan snapshot untuk rollback
            $snapshot = $promotion->only([
                'promotion_status',
                'to_classroom_id',
                'to_class_level_id',
                'decision_reason',
                'is_manual',
            ]);

            // Tentukan class_level tujuan dari classroom
            $toClassLevelId = $promotion->to_class_level_id;
            if ($toClassroomId) {
                $classroom = Classroom::find($toClassroomId);
                $toClassLevelId = $classroom?->class_level_id;
            }

            $promotion->update([
                'promotion_status' => $newStatus,
                'to_classroom_id' => $toClassroomId,
                'to_class_level_id' => $toClassLevelId,
                'decision_reason' => $reason ?? $promotion->decision_reason,
                'is_manual' => true,
                'decided_by' => $userId,
                'decided_at' => now(),
                'previous_state' => $snapshot,
            ]);

            return $promotion->fresh();
        });
    }

    /**
     * Finalize batch — apply ke student_classrooms.
     */
    public function finalizeBatch(PromotionBatch $batch, ?int $userId = null): array
    {
        return DB::transaction(function () use ($batch, $userId) {
            $userId = $userId ?? Auth::id();
            $stats = ['processed' => 0, 'errors' => 0, 'messages' => []];

            $promotions = $batch->student_promotions()
                ->where('status', 'draft')
                ->orWhere('status', 'processed')
                ->get();

            foreach ($promotions as $promotion) {
                try {
                    $this->applyPromotion($promotion, $userId);
                    $stats['processed']++;
                } catch (\Throwable $e) {
                    $stats['errors']++;
                    $stats['messages'][] = "Student #{$promotion->student_id}: {$e->getMessage()}";
                    Log::error('Promotion finalize error', [
                        'promotion_id' => $promotion->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $batch->update([
                'status' => 'completed',
                'completed_at' => now(),
                'updated_by' => $userId,
            ]);
            $batch->recalculateStats();

            return $stats;
        });
    }

    /**
     * Apply satu promosi ke student_classrooms.
     */
    protected function applyPromotion(StudentPromotion $promotion, int $userId): void
    {
        // 1. Tutup record kelas lama
        StudentClassroom::where('student_id', $promotion->student_id)
            ->where('academic_year_id', $promotion->from_academic_year_id)
            ->where('status', 'active')
            ->update([
                'status' => $this->mapStatusToClassroom($promotion->promotion_status),
                'end_date' => now()->toDateString(),
            ]);

        // 2. Buat record baru untuk tahun ajaran tujuan (kecuali graduated/transferred)
        if (in_array($promotion->promotion_status, ['promoted', 'retained']) && $promotion->to_classroom_id) {
            StudentClassroom::create([
                'student_id' => $promotion->student_id,
                'classroom_id' => $promotion->to_classroom_id,
                'academic_year_id' => $promotion->to_academic_year_id,
                'class_level_id' => $promotion->to_class_level_id,
                'start_date' => now()->toDateString(),
                'status' => 'active',
            ]);
        }

        // 3. Update status siswa jika lulus
        if ($promotion->promotion_status === 'graduated') {
            Student::where('id', $promotion->student_id)
                ->update(['status' => 'graduated']);
        }

        // 4. Update status promosi
        $promotion->update([
            'status' => 'finalized',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Hitung metrik siswa (nilai, kehadiran, sikap).
     * Sesuaikan dengan struktur tabel Anda.
     */
    protected function calculateStudentMetrics(Student $student, int $academicYearId): array
    {
        // TODO: sesuaikan query dengan tabel Anda
        return [
            'final_score' => 0,
            'attendance_rate' => 100,
            'behavior_score' => 0,
            'failed_subjects' => 0,
        ];
    }

    /**
     * Tentukan keputusan promosi.
     */
    protected function determineDecision(
        Student $student,
        $currentClassroom,
        array $metrics,
        ?PromotionCriteria $criteria,
        int $toAcademicYearId
    ): array {
        $currentLevel = ClassLevel::find($currentClassroom->class_level_id);

        // Cek apakah level tertinggi → lulus
        $isHighestLevel = ClassLevel::where('order', '>', $currentLevel->order ?? 0)
            ->where('is_active', true)
            ->doesntExist();

        if ($isHighestLevel) {
            return [
                'status' => 'graduated',
                'to_classroom_id' => null,
                'to_class_level_id' => null,
                'reason' => 'Siswa telah menyelesaikan tingkat tertinggi.',
            ];
        }

        // Cek kriteria
        $eligible = true;
        $reason = 'Memenuhi kriteria kenaikan kelas.';

        if ($criteria) {
            $eligible = $criteria->isEligibleForPromotion(
                (float) $metrics['final_score'],
                (float) $metrics['attendance_rate'],
                (float) $metrics['behavior_score'],
                (int) $metrics['failed_subjects']
            );

            if (!$eligible) {
                $reason = 'Tidak memenuhi kriteria kenaikan kelas.';
            }
        }

        if ($eligible) {
            // Cari kelas tujuan di tahun ajaran baru
            $nextLevel = ClassLevel::where('order', '>', $currentLevel->order ?? 0)
                ->orderBy('order')
                ->first();

            return [
                'status' => 'promoted',
                'to_classroom_id' => null, // assign manual / auto
                'to_class_level_id' => $nextLevel?->id,
                'reason' => $reason,
            ];
        }

        return [
            'status' => 'retained',
            'to_classroom_id' => $currentClassroom->classroom_id,
            'to_class_level_id' => $currentClassroom->class_level_id,
            'reason' => $reason,
        ];
    }

    protected function mapStatusToClassroom(string $promotionStatus): string
    {
        return match ($promotionStatus) {
            'promoted' => 'promoted',
            'retained' => 'retained',
            'graduated' => 'graduated',
            'transferred' => 'transferred',
            'dropped_out' => 'dropped_out',
            default => 'inactive',
        };
    }
}
