<?php

namespace App\Modules\ClinicPrescription\Controllers;

use App\DataTables\ClinicPrescriptionDataTable;
use App\Http\Controllers\MainController;
use Models\ClinicPrescription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ClinicPrescriptionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ClinicPrescription());
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
        ClinicPrescriptionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /clinic-prescription?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ClinicPrescription::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clinicPrescription = new ClinicPrescription();
        $result = $this->getForm($clinicPrescription);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ClinicPrescription::create',
            compact('clinicPrescription', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'clinic_visit_id' => ['required', 'integer'],
            'clinic_medicine_id' => ['required', 'integer'],
            'qty_given' => ['required', 'string', 'max:255'],
            'dosage_instructions' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ClinicPrescription::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ClinicPrescription berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ClinicPrescription.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClinicPrescription gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $clinicPrescription = $this->model->findOrFail($id);
        $result = $this->getForm($clinicPrescription);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ClinicPrescription::edit',
            compact('clinicPrescription', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'clinic_visit_id' => ['required', 'integer'],
            'clinic_medicine_id' => ['required', 'integer'],
            'qty_given' => ['required', 'string', 'max:255'],
            'dosage_instructions' => ['required', 'string', 'max:255'],
        ]);

        try {
            $clinicPrescription = $this->model->findOrFail($id);
            DB::transaction(function () use ($clinicPrescription, $validated) {
                $clinicPrescription->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ClinicPrescription berhasil diperbarui.',
                $clinicPrescription
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ClinicPrescription.',
                [
                    'exception' => $e,
                    'ClinicPrescription_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClinicPrescription gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $clinicPrescription = $this->model->findOrFail($id);

            DB::transaction(function () use ($clinicPrescription) {
                $clinicPrescription->delete();
            });

            return $this->successResponse(
                $request,
                'Data ClinicPrescription berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $clinicPrescription->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ClinicPrescription.',
                [
                    'exception' => $e,
                    'ClinicPrescription_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClinicPrescription gagal dihapus.'
            );
        }
    }
}
