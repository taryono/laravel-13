<?php

namespace App\Modules\DigitalSignature\Controllers;

use App\DataTables\DigitalSignatureDataTable;
use App\Http\Controllers\MainController;
use Models\DigitalSignature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DigitalSignatureController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DigitalSignature());
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
        DigitalSignatureDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /digital-signature?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DigitalSignature::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $digitalSignature = new DigitalSignature();
        $result = $this->getForm($digitalSignature);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DigitalSignature::create',
            compact('digitalSignature', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_id' => ['required', 'integer'],
            'document_version_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'signer_user_id' => ['required', 'integer'],
            'signer_employee_id' => ['required', 'integer'],
            'signer_name' => ['required', 'string', 'max:255'],
            'signer_email' => ['required', 'string', 'max:255'],
            'signer_phone' => ['required', 'string', 'max:255'],
            'signer_position' => ['required', 'string', 'max:255'],
            'signer_nip' => ['required', 'string', 'max:255'],
            'signature_type' => ['required', 'string', 'max:255'],
            'signature_method' => ['required', 'string', 'max:255'],
            'signature_data' => ['required', 'string', 'max:255'],
            'signature_path' => ['required', 'string', 'max:255'],
            'certificate_path' => ['required', 'string', 'max:255'],
            'certificate_serial' => ['required', 'string', 'max:255'],
            'certificate_issuer' => ['required', 'string', 'max:255'],
            'certificate_valid_from' => ['required', 'string', 'max:255'],
            'certificate_valid_until' => ['required', 'string', 'max:255'],
            'signature_hash' => ['required', 'string', 'max:255'],
            'document_hash' => ['required', 'string', 'max:255'],
            'page_number' => ['required', 'string', 'max:255'],
            'position_x' => ['required', 'string', 'max:255'],
            'position_y' => ['required', 'string', 'max:255'],
            'position_width' => ['required', 'string', 'max:255'],
            'position_height' => ['required', 'string', 'max:255'],
            'signed_at' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'verification_status' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_notes' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'revocation_reason' => ['required', 'string', 'max:255'],
            'revoked_at' => ['required', 'string', 'max:255'],
            'revoked_by' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'device_identifier' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'otp_code' => ['required', 'string', 'max:255'],
            'otp_verified_at' => ['required', 'string', 'max:255'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DigitalSignature::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DigitalSignature berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DigitalSignature.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalSignature gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $digitalSignature = $this->model->findOrFail($id);
        $result = $this->getForm($digitalSignature);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DigitalSignature::edit',
            compact('digitalSignature', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'document_id' => ['required', 'integer'],
            'document_version_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'signer_user_id' => ['required', 'integer'],
            'signer_employee_id' => ['required', 'integer'],
            'signer_name' => ['required', 'string', 'max:255'],
            'signer_email' => ['required', 'string', 'max:255'],
            'signer_phone' => ['required', 'string', 'max:255'],
            'signer_position' => ['required', 'string', 'max:255'],
            'signer_nip' => ['required', 'string', 'max:255'],
            'signature_type' => ['required', 'string', 'max:255'],
            'signature_method' => ['required', 'string', 'max:255'],
            'signature_data' => ['required', 'string', 'max:255'],
            'signature_path' => ['required', 'string', 'max:255'],
            'certificate_path' => ['required', 'string', 'max:255'],
            'certificate_serial' => ['required', 'string', 'max:255'],
            'certificate_issuer' => ['required', 'string', 'max:255'],
            'certificate_valid_from' => ['required', 'string', 'max:255'],
            'certificate_valid_until' => ['required', 'string', 'max:255'],
            'signature_hash' => ['required', 'string', 'max:255'],
            'document_hash' => ['required', 'string', 'max:255'],
            'page_number' => ['required', 'string', 'max:255'],
            'position_x' => ['required', 'string', 'max:255'],
            'position_y' => ['required', 'string', 'max:255'],
            'position_width' => ['required', 'string', 'max:255'],
            'position_height' => ['required', 'string', 'max:255'],
            'signed_at' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'verification_status' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_notes' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'revocation_reason' => ['required', 'string', 'max:255'],
            'revoked_at' => ['required', 'string', 'max:255'],
            'revoked_by' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'device_identifier' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'otp_code' => ['required', 'string', 'max:255'],
            'otp_verified_at' => ['required', 'string', 'max:255'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
        ]);

        try {
            $digitalSignature = $this->model->findOrFail($id);
            DB::transaction(function () use ($digitalSignature, $validated) {
                $digitalSignature->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DigitalSignature berhasil diperbarui.',
                $digitalSignature
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DigitalSignature.',
                [
                    'exception' => $e,
                    'DigitalSignature_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalSignature gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $digitalSignature = $this->model->findOrFail($id);

            DB::transaction(function () use ($digitalSignature) {
                $digitalSignature->delete();
            });

            return $this->successResponse(
                $request,
                'Data DigitalSignature berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $digitalSignature->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DigitalSignature.',
                [
                    'exception' => $e,
                    'DigitalSignature_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalSignature gagal dihapus.'
            );
        }
    }
}
