<?php

namespace App\Modules\ApplicantPayment\Controllers;

use App\DataTables\ApplicantPaymentDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantPaymentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantPayment());
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
        ApplicantPaymentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-payment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantPayment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantPayment = new ApplicantPayment();
        $result = $this->getForm($applicantPayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantPayment::create',
            compact('applicantPayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'payment_proof_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_date' => ['required', 'string', 'max:255'],
            'refund_date' => ['required', 'string', 'max:255'],
            'refund_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantPayment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantPayment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantPayment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantPayment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantPayment = $this->model->findOrFail($id);
        $result = $this->getForm($applicantPayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantPayment::edit',
            compact('applicantPayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'payment_proof_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_date' => ['required', 'string', 'max:255'],
            'refund_date' => ['required', 'string', 'max:255'],
            'refund_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantPayment = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicantPayment, $validated) {
                $applicantPayment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantPayment berhasil diperbarui.',
                $applicantPayment
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApplicantPayment.',
                [
                    'exception' => $e,
                    'ApplicantPayment_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantPayment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantPayment = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantPayment) {
                $applicantPayment->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantPayment berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicantPayment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantPayment.',
                [
                    'exception' => $e,
                    'ApplicantPayment_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantPayment gagal dihapus.'
            );
        }
    }
}
