<?php

namespace App\Modules\StudentFamilyMember\Controllers;

use App\DataTables\StudentFamilyMemberDataTable;
use App\Http\Controllers\MainController;
use Models\StudentFamilyMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentFamilyMemberController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentFamilyMember());
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
        StudentFamilyMemberDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-family-member?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentFamilyMember::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentFamilyMember = new StudentFamilyMember();
        $result = $this->getForm($studentFamilyMember);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentFamilyMember::create',
            compact('studentFamilyMember', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'student_family_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'sibling_order' => ['required', 'string', 'max:255'],
            'relation' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'joined_at' => ['required', 'string', 'max:255'],
            'left_at' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentFamilyMember::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentFamilyMember berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentFamilyMember.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentFamilyMember gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentFamilyMember = $this->model->findOrFail($id);
        $result = $this->getForm($studentFamilyMember);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentFamilyMember::edit',
            compact('studentFamilyMember', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'student_family_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'sibling_order' => ['required', 'string', 'max:255'],
            'relation' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'joined_at' => ['required', 'string', 'max:255'],
            'left_at' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentFamilyMember = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentFamilyMember,
                $validated
            ) {
                $studentFamilyMember->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentFamilyMember berhasil diperbarui.',
                $studentFamilyMember
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentFamilyMember.',
                [
                    'exception' => $e,
                    'StudentFamilyMember_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentFamilyMember gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentFamilyMember = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentFamilyMember) {
                $studentFamilyMember->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentFamilyMember berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentFamilyMember->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentFamilyMember.',
                [
                    'exception' => $e,
                    'StudentFamilyMember_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentFamilyMember gagal dihapus.'
            );
        }
    } 
}
