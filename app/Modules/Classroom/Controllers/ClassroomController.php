<?php

namespace App\Modules\Classroom\Controllers;

use App\DataTables\ClassroomDataTable;
use App\Http\Controllers\MainController;
use Models\Classroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ClassroomController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Classroom());
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
        ClassroomDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /classroom?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Classroom::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $classroom = new Classroom();
        $result = $this->getForm($classroom);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Classroom::create',
            compact('classroom', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Classroom::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Classroom berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Classroom.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Classroom gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $classroom = $this->model->findOrFail($id);
        $result = $this->getForm($classroom);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Classroom::edit',
            compact('classroom', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $classroom = $this->model->findOrFail($id);
            DB::transaction(function () use ($classroom, $validated) {
                $classroom->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Classroom berhasil diperbarui.',
                $classroom
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Classroom.',
                [
                    'exception' => $e,
                    'Classroom_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Classroom gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $classroom = $this->model->findOrFail($id);

            DB::transaction(function () use ($classroom) {
                $classroom->delete();
            });

            return $this->successResponse(
                $request,
                'Data Classroom berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $classroom->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Classroom.',
                [
                    'exception' => $e,
                    'Classroom_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Classroom gagal dihapus.'
            );
        }
    }
}
