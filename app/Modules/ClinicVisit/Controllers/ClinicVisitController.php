<?php

namespace App\Modules\ClinicVisit\Controllers;

use App\DataTables\ClinicVisitDataTable;
use App\Http\Controllers\MainController;
use Models\ClinicVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ClinicVisitController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ClinicVisit());
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
        ClinicVisitDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /clinic-visit?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ClinicVisit::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clinicVisit = new ClinicVisit();
        $result = $this->getForm($clinicVisit);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ClinicVisit::create',
            compact('clinicVisit', 'forms', 'groupedForms')
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
            'patient_type' => ['required', 'string', 'max:255'],
            'patient_id' => ['required', 'integer'],
            'visit_datetime' => ['required', 'string', 'max:255'],
            'symptoms' => ['required', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['required', 'string', 'max:255'],
            'rest_duration_minutes' => ['required', 'string', 'max:255'],
            'action_taken' => ['required', 'string', 'max:255'],
            'referral_hospital' => ['required', 'string', 'max:255'],
            'handled_by' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ClinicVisit::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ClinicVisit berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ClinicVisit.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClinicVisit gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $clinicVisit = $this->model->findOrFail($id);
        $result = $this->getForm($clinicVisit);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ClinicVisit::edit',
            compact('clinicVisit', 'forms', 'groupedForms')
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
            'patient_type' => ['required', 'string', 'max:255'],
            'patient_id' => ['required', 'integer'],
            'visit_datetime' => ['required', 'string', 'max:255'],
            'symptoms' => ['required', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['required', 'string', 'max:255'],
            'rest_duration_minutes' => ['required', 'string', 'max:255'],
            'action_taken' => ['required', 'string', 'max:255'],
            'referral_hospital' => ['required', 'string', 'max:255'],
            'handled_by' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $clinicVisit = $this->model->findOrFail($id);
            DB::transaction(function () use ($clinicVisit, $validated) {
                $clinicVisit->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ClinicVisit berhasil diperbarui.',
                $clinicVisit
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ClinicVisit.',
                [
                    'exception' => $e,
                    'ClinicVisit_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClinicVisit gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $clinicVisit = $this->model->findOrFail($id);

            DB::transaction(function () use ($clinicVisit) {
                $clinicVisit->delete();
            });

            return $this->successResponse(
                $request,
                'Data ClinicVisit berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $clinicVisit->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ClinicVisit.',
                [
                    'exception' => $e,
                    'ClinicVisit_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClinicVisit gagal dihapus.'
            );
        }
    }
}
