<?php

namespace App\Modules\StudentKhs\Controllers;

use App\DataTables\StudentKhsDataTable;
use App\Http\Controllers\MainController;
use Models\StudentKhs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentKhsController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentKhs());
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
        StudentKhsDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-khs?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentKhs::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentKhs = new StudentKhs();
        $result = $this->getForm($studentKhs);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentKhs::create',
            compact('studentKhs', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'student_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'sks_semester' => ['required', 'string', 'max:255'],
            'sks_cumulative' => ['required', 'string', 'max:255'],
            'gpa_semester' => ['required', 'string', 'max:255'],
            'gpa_cumulative' => ['required', 'string', 'max:255'],
            'max_sks_next_semester' => ['required', 'string', 'max:255'],
            'academic_status' => ['required', 'string', 'max:255'],
            'printed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentKhs::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentKhs berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentKhs.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentKhs gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentKhs = $this->model->findOrFail($id);
        $result = $this->getForm($studentKhs);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentKhs::edit',
            compact('studentKhs', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'student_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'sks_semester' => ['required', 'string', 'max:255'],
            'sks_cumulative' => ['required', 'string', 'max:255'],
            'gpa_semester' => ['required', 'string', 'max:255'],
            'gpa_cumulative' => ['required', 'string', 'max:255'],
            'max_sks_next_semester' => ['required', 'string', 'max:255'],
            'academic_status' => ['required', 'string', 'max:255'],
            'printed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentKhs = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentKhs,
                $validated
            ) {
                $studentKhs->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentKhs berhasil diperbarui.',
                $studentKhs
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentKhs.',
                [
                    'exception' => $e,
                    'StudentKhs_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentKhs gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentKhs = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentKhs) {
                $studentKhs->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentKhs berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentKhs->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentKhs.',
                [
                    'exception' => $e,
                    'StudentKhs_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentKhs gagal dihapus.'
            );
        }
    } 
}
