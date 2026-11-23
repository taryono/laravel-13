<?php

namespace App\Modules\HomeroomTeacher\Controllers;

use App\DataTables\HomeroomTeacherDataTable;
use App\Http\Controllers\MainController;
use Models\HomeroomTeacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class HomeroomTeacherController extends MainController
{
    public function __construct()
    {
        parent::__construct(new HomeroomTeacher());
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
        HomeroomTeacherDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /homeroom-teacher?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'HomeroomTeacher::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $homeroomTeacher = new HomeroomTeacher();
        $result = $this->getForm($homeroomTeacher);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'HomeroomTeacher::create',
            compact('homeroomTeacher', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'assignment_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return HomeroomTeacher::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data HomeroomTeacher berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan HomeroomTeacher.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data HomeroomTeacher gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $homeroomTeacher = $this->model->findOrFail($id);
        $result = $this->getForm($homeroomTeacher);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'HomeroomTeacher::edit',
            compact('homeroomTeacher', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'assignment_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $homeroomTeacher = $this->model->findOrFail($id);
            DB::transaction(function () use ($homeroomTeacher, $validated) {
                $homeroomTeacher->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data HomeroomTeacher berhasil diperbarui.',
                $homeroomTeacher
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui HomeroomTeacher.',
                [
                    'exception' => $e,
                    'HomeroomTeacher_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data HomeroomTeacher gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $homeroomTeacher = $this->model->findOrFail($id);

            DB::transaction(function () use ($homeroomTeacher) {
                $homeroomTeacher->delete();
            });

            return $this->successResponse(
                $request,
                'Data HomeroomTeacher berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $homeroomTeacher->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus HomeroomTeacher.',
                [
                    'exception' => $e,
                    'HomeroomTeacher_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data HomeroomTeacher gagal dihapus.'
            );
        }
    }
}
