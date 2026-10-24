<?php

namespace App\Modules\ExtracurricularCoach\Controllers;

use App\DataTables\ExtracurricularCoachDataTable;
use App\Http\Controllers\MainController;
use Models\ExtracurricularCoach;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExtracurricularCoachController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExtracurricularCoach());
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
        ExtracurricularCoachDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /extracurricular-coach?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExtracurricularCoach::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $extracurricularCoach = new ExtracurricularCoach();
        $result = $this->getForm($extracurricularCoach);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularCoach::create',
            compact('extracurricularCoach', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'extracurricular_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'coach_type' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'photo_path' => ['required', 'string', 'max:255'],
            'certification' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'joined_at' => ['required', 'string', 'max:255'],
            'resigned_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExtracurricularCoach::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularCoach berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExtracurricularCoach.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularCoach gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $extracurricularCoach = $this->model->findOrFail($id);
        $result = $this->getForm($extracurricularCoach);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularCoach::edit',
            compact('extracurricularCoach', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'extracurricular_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'coach_type' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'photo_path' => ['required', 'string', 'max:255'],
            'certification' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'joined_at' => ['required', 'string', 'max:255'],
            'resigned_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $extracurricularCoach = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $extracurricularCoach,
                $validated
            ) {
                $extracurricularCoach->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularCoach berhasil diperbarui.',
                $extracurricularCoach
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ExtracurricularCoach.',
                [
                    'exception' => $e,
                    'ExtracurricularCoach_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularCoach gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $extracurricularCoach = $this->model->findOrFail($id);

            DB::transaction(function () use ($extracurricularCoach) {
                $extracurricularCoach->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularCoach berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $extracurricularCoach->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExtracurricularCoach.',
                [
                    'exception' => $e,
                    'ExtracurricularCoach_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularCoach gagal dihapus.'
            );
        }
    } 
}
