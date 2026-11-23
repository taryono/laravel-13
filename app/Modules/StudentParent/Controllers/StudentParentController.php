<?php
namespace App\Modules\StudentParent\Controllers;

use App\DataTables\StudentParentDataTable;
use App\Http\Controllers\MainController;
use App\Http\Requests\StudentParentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\ParentModel;   // sesuaikan dengan nama model tabel `parents`
use Models\Student;
use Models\StudentParent;
use Throwable;

class StudentParentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentParent());
    }

    /**
     * Daftar relasi (halaman + AJAX Yajra DataTables).
     * DataTable::render() otomatis membalas JSON saat request AJAX,
     * jadi pengecekan manual `draw` tidak diperlukan.
     */
    public function index(
        Request $request,
        StudentParentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-learning-plan?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentParent::index'
        );
    }

    public function create(): View
    {
        return view('StudentParent::create', $this->formOptions());
    }

    public function store(StudentParentRequest $request)
    {
        // Sudah divalidasi oleh StudentParentRequest -> tidak perlu $request->validate() lagi
        $data = $request->validated();

        try {
            $model = DB::transaction(function () use ($data, $request) {
                $studentId = $data['student_id'];
                $parentId = $data['parent_id'];
                $isPrimary = $request->boolean('is_primary');

                // di dalam transaksi: kalau insert gagal, primary lama tidak ikut hilang
                if ($isPrimary) {
                    $this->clearPrimary($studentId);
                }

                $payload = $this->payload($data['relationship'], $isPrimary);

                $existsIncludingTrashed = StudentParent::withTrashed()
                    ->where('student_id', $studentId)
                    ->where('parent_id', $parentId)
                    ->exists();

                if ($existsIncludingTrashed) {
                    // Pernah dihapus (soft delete) -> pulihkan, karena PK komposit sama
                    StudentParent::withTrashed()
                        ->where('student_id', $studentId)
                        ->where('parent_id', $parentId)
                        ->update($payload + ['deleted_at' => null]);
                } else {
                    StudentParent::create($payload + [
                        'student_id' => $studentId,
                        'parent_id' => $parentId,
                        'created_by' => Auth::id(),
                    ]);
                }

                return $this->findRelation($studentId, $parentId);
            });

            return $this->successResponse($request, 'Data StudentParent berhasil ditambahkan.', $model);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan StudentParent.', [
                'exception' => $e,
                'data' => $data,
                'user_id' => $request->user()?->id,
            ]);

            return $this->errorResponse($request, 'Data StudentParent gagal ditambahkan.');
        }
    }

    public function edit($student, $parent): View
    {
        $student_parent = $this->findRelation($student, $parent);

        return view('StudentParent::edit', ['student_parent' => $student_parent] + $this->formOptions());
    }

    public function update(StudentParentRequest $request, $student, $parent)
    {
        // 404 bila tidak ada; sengaja di luar try agar tidak tertelan sebagai error 500
        $this->findRelation($student, $parent);

        $data = $request->validated();

        try {
            $model = DB::transaction(function () use ($data, $request, $student, $parent) {
                $isPrimary = $request->boolean('is_primary');

                if ($isPrimary) {
                    $this->clearPrimary($student);
                }

                // PK komposit -> update lewat query, bukan $model->update()
                StudentParent::where('student_id', $student)
                    ->where('parent_id', $parent)
                    ->update($this->payload($data['relationship'], $isPrimary));

                return $this->findRelation($student, $parent);
            });

            return $this->successResponse($request, 'Data StudentParent berhasil diperbarui.', $model);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui StudentParent.', [
                'exception' => $e,
                'student_id' => $student,
                'parent_id' => $parent,
                'data' => $data,
                'user_id' => $request->user()?->id,
            ]);

            return $this->errorResponse($request, 'Data StudentParent gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $student, $parent)
    {
        $this->findRelation($student, $parent);

        try {
            DB::transaction(function () use ($student, $parent) {
                $query = StudentParent::where('student_id', $student)->where('parent_id', $parent);

                // kosongkan is_primary supaya baris soft-delete tidak menghalangi primary baru
                (clone $query)->update(['is_primary' => null]);
                $query->delete();
            });

            return $this->successResponse($request, 'Data StudentParent berhasil dihapus.', null);
        } catch (Throwable $e) {
            Log::error('Gagal menghapus StudentParent.', [
                'exception' => $e,
                'student_id' => $student,
                'parent_id' => $parent,
                'user_id' => $request->user()?->id,
            ]);

            return $this->errorResponse($request, 'Data StudentParent gagal dihapus.');
        }
    }

    /* ---------- helpers ---------- */

    private function formOptions(): array
    {
        return [
            'students' => Student::orderBy('name')->pluck('name', 'id')->toArray(),
            'parents' => ParentModel::orderBy('name')->pluck('name', 'id')->toArray(),
        ];
    }

    private function findRelation($student, $parent): StudentParent
    {
        return StudentParent::where('student_id', $student)
            ->where('parent_id', $parent)
            ->firstOrFail();
    }

    /** Field yang boleh diubah user; audit field diisi server, bukan dari input. */
    private function payload(string $relationship, bool $isPrimary): array
    {
        return [
            'relationship' => $relationship,
            // non-primary disimpan NULL agar tidak bentrok dengan UNIQUE (student_id, is_primary)
            'is_primary' => $isPrimary ? 1 : null,
            'updated_by' => Auth::id(),
        ];
    }

    private function clearPrimary($studentId): void
    {
        StudentParent::where('student_id', $studentId)
            ->where('is_primary', 1)
            ->update(['is_primary' => null]);
    }
}