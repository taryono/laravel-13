<?php

namespace App\Modules\Teacher\Controllers;

use App\DataTables\TeacherDataTable;
use App\Http\Controllers\MainController;
use Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TeacherController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Teacher());
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
        TeacherDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /teacher?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Teacher::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $teacher = new Teacher();
        $organizations = \Models\Organization::select('id', 'name')->get();
        $schools = \Models\School::select('id', 'name')->get();
        $users = \App\Models\User::select('id', 'email')->get();
        return view(
            'Teacher::create',
            compact('teacher', 'organizations', 'schools', 'users')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'nip' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Teacher::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Teacher berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Teacher.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Teacher gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $teacher = $this->model->findOrFail($id);
        $organizations = \Models\Organization::select('id', 'name')->get();
        $schools = \Models\School::select('id', 'name')->get();
        $users = \App\Models\User::select('id', 'email')->get();
        return view(
            'Teacher::edit',
            compact('teacher', 'organizations', 'schools', 'users')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'nip' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
        ]);

        try {
            $teacher = $this->model->findOrFail($id);
            DB::transaction(function () use ($teacher, $validated) {
                $teacher->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Teacher berhasil diperbarui.',
                $teacher
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Teacher.',
                [
                    'exception' => $e,
                    'Teacher_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Teacher gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $teacher = $this->model->findOrFail($id);

            DB::transaction(function () use ($teacher) {
                $teacher->delete();
            });

            return $this->successResponse(
                $request,
                'Data Teacher berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $teacher->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Teacher.',
                [
                    'exception' => $e,
                    'Teacher_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Teacher gagal dihapus.'
            );
        }
    }
}
