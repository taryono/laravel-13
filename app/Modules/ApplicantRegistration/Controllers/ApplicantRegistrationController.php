<?php

namespace App\Modules\ApplicantRegistration\Controllers;

use App\DataTables\ApplicantRegistrationDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantRegistrationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantRegistration());
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
        ApplicantRegistrationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-registration?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantRegistration::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantRegistration = new ApplicantRegistration();
        $result = $this->getForm($applicantRegistration);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantRegistration::create',
            compact('applicantRegistration', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'applicant_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'registration_number' => ['required', 'string', 'max:255'],
            'registration_date' => ['required', 'string', 'max:255'],
            'registration_method' => ['required', 'string', 'max:255'],
            'registration_status' => ['required', 'string', 'max:255'],
            'officer_id' => ['required', 'integer'],
            'location' => ['required', 'string', 'max:255'],
            'proof_url' => ['required', 'string', 'max:255'],
            'uniform_size' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantRegistration::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantRegistration berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantRegistration.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantRegistration gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantRegistration = $this->model->findOrFail($id);
        $result = $this->getForm($applicantRegistration);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantRegistration::edit',
            compact('applicantRegistration', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'applicant_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'registration_number' => ['required', 'string', 'max:255'],
            'registration_date' => ['required', 'string', 'max:255'],
            'registration_method' => ['required', 'string', 'max:255'],
            'registration_status' => ['required', 'string', 'max:255'],
            'officer_id' => ['required', 'integer'],
            'location' => ['required', 'string', 'max:255'],
            'proof_url' => ['required', 'string', 'max:255'],
            'uniform_size' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantRegistration = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $applicantRegistration,
                $validated
            ) {
                $applicantRegistration->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantRegistration berhasil diperbarui.',
                $applicantRegistration
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ApplicantRegistration.',
                [
                    'exception' => $e,
                    'ApplicantRegistration_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantRegistration gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantRegistration = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantRegistration) {
                $applicantRegistration->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantRegistration berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $applicantRegistration->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantRegistration.',
                [
                    'exception' => $e,
                    'ApplicantRegistration_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantRegistration gagal dihapus.'
            );
        }
    } 
}
