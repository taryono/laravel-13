<?php

namespace App\Modules\Graduation\Controllers;

use App\DataTables\GraduationDataTable;
use App\Http\Controllers\MainController;
use Models\Graduation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GraduationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Graduation());
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
        GraduationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /graduation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Graduation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $graduation = new Graduation();
        $result = $this->getForm($graduation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Graduation::create',
            compact('graduation', 'forms', 'groupedForms')
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
            'academic_year_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'graduation_date' => ['required', 'string', 'max:255'],
            'certificate_number' => ['required', 'string', 'max:255'],
            'certificate_issued_at' => ['required', 'string', 'max:255'],
            'skhu_number' => ['required', 'string', 'max:255'],
            'skhu_issued_at' => ['required', 'string', 'max:255'],
            'transcript_number' => ['required', 'string', 'max:255'],
            'final_score_average' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Graduation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Graduation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Graduation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Graduation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $graduation = $this->model->findOrFail($id);
        $result = $this->getForm($graduation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Graduation::edit',
            compact('graduation', 'forms', 'groupedForms')
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
            'academic_year_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'graduation_date' => ['required', 'string', 'max:255'],
            'certificate_number' => ['required', 'string', 'max:255'],
            'certificate_issued_at' => ['required', 'string', 'max:255'],
            'skhu_number' => ['required', 'string', 'max:255'],
            'skhu_issued_at' => ['required', 'string', 'max:255'],
            'transcript_number' => ['required', 'string', 'max:255'],
            'final_score_average' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $graduation = $this->model->findOrFail($id);
            DB::transaction(function () use ($graduation, $validated) {
                $graduation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Graduation berhasil diperbarui.',
                $graduation
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Graduation.',
                [
                    'exception' => $e,
                    'Graduation_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Graduation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $graduation = $this->model->findOrFail($id);

            DB::transaction(function () use ($graduation) {
                $graduation->delete();
            });

            return $this->successResponse(
                $request,
                'Data Graduation berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $graduation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Graduation.',
                [
                    'exception' => $e,
                    'Graduation_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Graduation gagal dihapus.'
            );
        }
    }
}
