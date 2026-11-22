<?php

namespace App\Modules\CurriculumSubject\Controllers;

use App\DataTables\CurriculumSubjectDataTable;
use App\Http\Controllers\MainController;
use Models\CurriculumSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CurriculumSubjectController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CurriculumSubject());
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
        CurriculumSubjectDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /curriculum-subject?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CurriculumSubject::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $curriculumSubject = new CurriculumSubject();
        $result = $this->getForm($curriculumSubject);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumSubject::create',
            compact('curriculumSubject', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'is_required' => ['required', 'string', 'max:255'],
            'is_elective' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CurriculumSubject::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumSubject berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CurriculumSubject.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumSubject gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $curriculumSubject = $this->model->findOrFail($id);
        $result = $this->getForm($curriculumSubject);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumSubject::edit',
            compact('curriculumSubject', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'is_required' => ['required', 'string', 'max:255'],
            'is_elective' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $curriculumSubject = $this->model->findOrFail($id);
            DB::transaction(function () use ($curriculumSubject, $validated) {
                $curriculumSubject->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumSubject berhasil diperbarui.',
                $curriculumSubject
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CurriculumSubject.',
                [
                    'exception' => $e,
                    'CurriculumSubject_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumSubject gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $curriculumSubject = $this->model->findOrFail($id);

            DB::transaction(function () use ($curriculumSubject) {
                $curriculumSubject->delete();
            });

            return $this->successResponse(
                $request,
                'Data CurriculumSubject berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $curriculumSubject->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CurriculumSubject.',
                [
                    'exception' => $e,
                    'CurriculumSubject_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumSubject gagal dihapus.'
            );
        }
    }
}
