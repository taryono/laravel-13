<?php

namespace App\Modules\AdmissionRegistrationFee\Controllers;

use App\DataTables\AdmissionRegistrationFeeDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionRegistrationFee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionRegistrationFeeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionRegistrationFee());
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
        AdmissionRegistrationFeeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-registration-fee?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionRegistrationFee::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionRegistrationFee = new AdmissionRegistrationFee();
        $result = $this->getForm($admissionRegistrationFee);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionRegistrationFee::create',
            compact('admissionRegistrationFee', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'fee_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_refundable' => ['required', 'string', 'max:255'],
            'early_bird_amount' => ['required', 'string', 'max:255'],
            'early_bird_until' => ['required', 'string', 'max:255'],
            'effective_from' => ['required', 'string', 'max:255'],
            'effective_until' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionRegistrationFee::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionRegistrationFee berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionRegistrationFee.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionRegistrationFee gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionRegistrationFee = $this->model->findOrFail($id);
        $result = $this->getForm($admissionRegistrationFee);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionRegistrationFee::edit',
            compact('admissionRegistrationFee', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'admission_path_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'fee_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_refundable' => ['required', 'string', 'max:255'],
            'early_bird_amount' => ['required', 'string', 'max:255'],
            'early_bird_until' => ['required', 'string', 'max:255'],
            'effective_from' => ['required', 'string', 'max:255'],
            'effective_until' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionRegistrationFee = $this->model->findOrFail($id);
            DB::transaction(function () use ($admissionRegistrationFee, $validated) {
                $admissionRegistrationFee->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionRegistrationFee berhasil diperbarui.',
                $admissionRegistrationFee
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AdmissionRegistrationFee.',
                [
                    'exception' => $e,
                    'AdmissionRegistrationFee_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionRegistrationFee gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionRegistrationFee = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionRegistrationFee) {
                $admissionRegistrationFee->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionRegistrationFee berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $admissionRegistrationFee->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionRegistrationFee.',
                [
                    'exception' => $e,
                    'AdmissionRegistrationFee_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionRegistrationFee gagal dihapus.'
            );
        }
    }
}
