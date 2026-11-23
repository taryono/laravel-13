<?php

namespace App\Modules\Admission\Controllers;

use App\DataTables\AdmissionDataTable;
use App\Http\Controllers\MainController;
use Models\Admission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Admission());
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
        AdmissionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Admission::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admission = new Admission();
        $result = $this->getForm($admission);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Admission::create',
            compact('admission', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'registration_fee' => ['required', 'string', 'max:255'],
            'min_age' => ['required', 'string', 'max:255'],
            'max_age' => ['required', 'string', 'max:255'],
            'requirements' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Admission::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Admission berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Admission.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Admission gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admission = $this->model->findOrFail($id);
        $result = $this->getForm($admission);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Admission::edit',
            compact('admission', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'registration_fee' => ['required', 'string', 'max:255'],
            'min_age' => ['required', 'string', 'max:255'],
            'max_age' => ['required', 'string', 'max:255'],
            'requirements' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admission = $this->model->findOrFail($id);
            DB::transaction(function () use ($admission, $validated) {
                $admission->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Admission berhasil diperbarui.',
                $admission
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Admission.',
                [
                    'exception' => $e,
                    'Admission_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Admission gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admission = $this->model->findOrFail($id);

            DB::transaction(function () use ($admission) {
                $admission->delete();
            });

            return $this->successResponse(
                $request,
                'Data Admission berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $admission->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Admission.',
                [
                    'exception' => $e,
                    'Admission_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Admission gagal dihapus.'
            );
        }
    }
}
