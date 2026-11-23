<?php

namespace App\Modules\StudyProgram\Controllers;

use App\DataTables\StudyProgramDataTable;
use App\Http\Controllers\MainController;
use Models\StudyProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudyProgramController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudyProgram());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        StudyProgramDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /study-program?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudyProgram::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studyProgram = new StudyProgram();
        $result = $this->getForm($studyProgram);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudyProgram::create',
            compact('studyProgram', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'faculty_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'degree_level' => ['required', 'string', 'max:255'],
            'accreditation' => ['required', 'string', 'max:255'],
            'head_of_program_id' => ['required', 'integer'],
            'total_sks_required' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudyProgram::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudyProgram berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudyProgram.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudyProgram gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studyProgram = $this->model->findOrFail($id);
        $result = $this->getForm($studyProgram);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudyProgram::edit',
            compact('studyProgram', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'faculty_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'degree_level' => ['required', 'string', 'max:255'],
            'accreditation' => ['required', 'string', 'max:255'],
            'head_of_program_id' => ['required', 'integer'],
            'total_sks_required' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studyProgram = $this->model->findOrFail($id);
            DB::transaction(function () use ($studyProgram, $validated) {
                $studyProgram->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudyProgram berhasil diperbarui.',
                $studyProgram
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudyProgram.',
                [
                    'exception' => $e,
                    'StudyProgram_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudyProgram gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studyProgram = $this->model->findOrFail($id);

            DB::transaction(function () use ($studyProgram) {
                $studyProgram->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudyProgram berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studyProgram->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudyProgram.',
                [
                    'exception' => $e,
                    'StudyProgram_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudyProgram gagal dihapus.'
            );
        }
    }
}
