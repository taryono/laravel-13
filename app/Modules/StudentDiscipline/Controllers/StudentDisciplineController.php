<?php

namespace App\Modules\StudentDiscipline\Controllers;

use App\DataTables\StudentDisciplineDataTable;
use App\Http\Controllers\MainController;
use Models\StudentDiscipline;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentDisciplineController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentDiscipline());
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
        StudentDisciplineDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-discipline?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentDiscipline::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentDiscipline = new StudentDiscipline();
        $result = $this->getForm($studentDiscipline);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentDiscipline::create',
            compact('studentDiscipline', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'student_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'points' => ['required', 'string', 'max:255'],
            'action_taken' => ['required', 'string', 'max:255'],
            'incident_date' => ['required', 'string', 'max:255'],
            'reported_by' => ['required', 'string', 'max:255'],
            'witness' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'parent_notified' => ['required', 'string', 'max:255'],
            'parent_notified_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentDiscipline::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentDiscipline berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentDiscipline.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentDiscipline gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentDiscipline = $this->model->findOrFail($id);
        $result = $this->getForm($studentDiscipline);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentDiscipline::edit',
            compact('studentDiscipline', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'student_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'points' => ['required', 'string', 'max:255'],
            'action_taken' => ['required', 'string', 'max:255'],
            'incident_date' => ['required', 'string', 'max:255'],
            'reported_by' => ['required', 'string', 'max:255'],
            'witness' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'parent_notified' => ['required', 'string', 'max:255'],
            'parent_notified_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentDiscipline = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentDiscipline,
                $validated
            ) {
                $studentDiscipline->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentDiscipline berhasil diperbarui.',
                $studentDiscipline
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentDiscipline.',
                [
                    'exception' => $e,
                    'StudentDiscipline_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentDiscipline gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentDiscipline = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentDiscipline) {
                $studentDiscipline->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentDiscipline berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentDiscipline->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentDiscipline.',
                [
                    'exception' => $e,
                    'StudentDiscipline_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentDiscipline gagal dihapus.'
            );
        }
    } 
}
