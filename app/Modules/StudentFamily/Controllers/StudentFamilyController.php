<?php

namespace App\Modules\StudentFamily\Controllers;

use App\DataTables\StudentFamilyDataTable;
use App\Http\Controllers\MainController;
use Models\StudentFamily;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentFamilyController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentFamily());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        StudentFamilyDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-family?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentFamily::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentFamily = new StudentFamily();
        $result = $this->getForm($studentFamily);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentFamily::create',
            compact('studentFamily', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'family_code' => ['required', 'string', 'max:255'],
            'family_name' => ['required', 'string', 'max:255'],
            'primary_parent_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentFamily::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentFamily berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentFamily.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentFamily gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentFamily = $this->model->findOrFail($id);
        $result = $this->getForm($studentFamily);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentFamily::edit',
            compact('studentFamily', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'family_code' => ['required', 'string', 'max:255'],
            'family_name' => ['required', 'string', 'max:255'],
            'primary_parent_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentFamily = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentFamily,
                $validated
            ) {
                $studentFamily->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentFamily berhasil diperbarui.',
                $studentFamily
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentFamily.',
                [
                    'exception' => $e,
                    'StudentFamily_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentFamily gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentFamily = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentFamily) {
                $studentFamily->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentFamily berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentFamily->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentFamily.',
                [
                    'exception' => $e,
                    'StudentFamily_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentFamily gagal dihapus.'
            );
        }
    } 

    /**
     * Menampilkan detail keluarga beserta daftar anggota & kelayakan diskon.
     */
    public function show(Request $request, StudentFamily $studentFamily): View|JsonResponse
    {
        $studentFamily->load([
            'primaryParent',
            'members' => fn ($q) => $q->with(['student', 'applicant'])->orderBy('sibling_order'),
            'eligibilities.discountProgram',
        ]);

        if ($request->wantsJson()) {
            return response()->json($studentFamily);
        }

        return view('student-families.show', compact('studentFamily'));
    }

    // =========================================================================
    // MANAJEMEN ANGGOTA KELUARGA (STUDENT FAMILY MEMBERS)
    // =========================================================================

    /**
     * Menambahkan siswa atau pendaftar baru ke dalam keluarga.
     * Urutan sibling_order & kelayakan otomatis dihitung oleh StudentFamilyMemberObserver.
     */
    public function storeMember(
        StoreStudentFamilyMemberRequest $request,
        StudentFamily $studentFamily
    ): JsonResponse|RedirectResponse {
        $member = DB::transaction(function () use ($request, $studentFamily) {
            return $studentFamily->members()->create([
                'organization_id' => $studentFamily->organization_id,
                'student_id'      => $request->student_id,
                'applicant_id'    => $request->applicant_id,
                'relation'        => $request->relation ?? 'Anak Kandung',
                'is_active'       => $request->boolean('is_active', true),
                'joined_at'       => $request->joined_at ?? now()->toDateString(),
                'left_at'         => $request->left_at,
            ]);
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Anggota keluarga berhasil ditambahkan.',
                'data'    => $member->fresh(['student', 'applicant']),
            ], 201);
        }

        return back()->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    /**
     * Memperbarui data anggota keluarga (status aktif, tanggal masuk/keluar, dll).
     */
    public function updateMember(
        UpdateStudentFamilyMemberRequest $request,
        StudentFamily $studentFamily,
        StudentFamilyMember $member
    ): JsonResponse|RedirectResponse {
        abort_if($member->student_family_id !== $studentFamily->id, 404);

        DB::transaction(function () use ($request, $member) {
            $member->update($request->validated());
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Data anggota keluarga berhasil diperbarui.',
                'data'    => $member->fresh(['student', 'applicant']),
            ]);
        }

        return back()->with('success', 'Data anggota keluarga berhasil diperbarui.');
    }

    /**
     * Menghapus anggota dari keluarga (soft delete).
     */
    public function destroyMember(
        Request $request,
        StudentFamily $studentFamily,
        StudentFamilyMember $member
    ): JsonResponse|RedirectResponse {
        abort_if($member->student_family_id !== $studentFamily->id, 404);

        DB::transaction(function () use ($member) {
            $member->delete();
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Anggota keluarga berhasil dihapus.']);
        }

        return back()->with('success', 'Anggota keluarga berhasil dihapus.');
    }
}
