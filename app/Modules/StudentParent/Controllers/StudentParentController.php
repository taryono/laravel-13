<?php

namespace App\Modules\StudentParent\Controllers;

use App\DataTables\StudentParentDataTable;
use App\Http\Controllers\MainController;
use Models\StudentParent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentParentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentParent());
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
        StudentParentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-parent?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentParent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentParent = new StudentParent();
        $result = $this->getForm($studentParent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentParent::create',
            compact('studentParent', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'is_primary' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentParent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentParent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentParent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentParent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentParent = $this->model->findOrFail($id);
        $result = $this->getForm($studentParent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentParent::edit',
            compact('studentParent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'is_primary' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentParent = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentParent, $validated) {
                $studentParent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentParent berhasil diperbarui.',
                $studentParent
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentParent.',
                [
                    'exception' => $e,
                    'StudentParent_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentParent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentParent = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentParent) {
                $studentParent->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentParent berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentParent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentParent.',
                [
                    'exception' => $e,
                    'StudentParent_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentParent gagal dihapus.'
            );
        }
    }
}
