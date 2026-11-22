<?php

namespace App\Modules\AdmissionDocumentVerification\Controllers;

use App\DataTables\AdmissionDocumentVerificationDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionDocumentVerification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionDocumentVerificationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionDocumentVerification());
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
        AdmissionDocumentVerificationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-document-verification?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionDocumentVerification::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionDocumentVerification = new AdmissionDocumentVerification();
        $result = $this->getForm($admissionDocumentVerification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionDocumentVerification::create',
            compact('admissionDocumentVerification', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'application_document_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'document_type' => ['required', 'string', 'max:255'],
            'document_number' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'verification_status' => ['required', 'string', 'max:255'],
            'verification_method' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'rejected_by' => ['required', 'string', 'max:255'],
            'rejected_at' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'revision_notes' => ['required', 'string', 'max:255'],
            'revision_deadline' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_valid' => ['required', 'string', 'max:255'],
            'valid_until' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionDocumentVerification::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionDocumentVerification berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionDocumentVerification.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionDocumentVerification gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionDocumentVerification = $this->model->findOrFail($id);
        $result = $this->getForm($admissionDocumentVerification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionDocumentVerification::edit',
            compact('admissionDocumentVerification', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'application_document_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'document_type' => ['required', 'string', 'max:255'],
            'document_number' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'verification_status' => ['required', 'string', 'max:255'],
            'verification_method' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'rejected_by' => ['required', 'string', 'max:255'],
            'rejected_at' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'revision_notes' => ['required', 'string', 'max:255'],
            'revision_deadline' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_valid' => ['required', 'string', 'max:255'],
            'valid_until' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionDocumentVerification = $this->model->findOrFail($id);
            DB::transaction(function () use ($admissionDocumentVerification, $validated) {
                $admissionDocumentVerification->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionDocumentVerification berhasil diperbarui.',
                $admissionDocumentVerification
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AdmissionDocumentVerification.',
                [
                    'exception' => $e,
                    'AdmissionDocumentVerification_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionDocumentVerification gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionDocumentVerification = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionDocumentVerification) {
                $admissionDocumentVerification->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionDocumentVerification berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $admissionDocumentVerification->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionDocumentVerification.',
                [
                    'exception' => $e,
                    'AdmissionDocumentVerification_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionDocumentVerification gagal dihapus.'
            );
        }
    }
}
