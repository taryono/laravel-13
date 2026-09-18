<?php

namespace App\Modules\ApplicantInvoice\Controllers;

use App\DataTables\ApplicantInvoiceDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantInvoiceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantInvoice());
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
        ApplicantInvoiceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-invoice?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantInvoice::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantInvoice = new ApplicantInvoice();
        $result = $this->getForm($applicantInvoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantInvoice::create',
            compact('applicantInvoice', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'applicant_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'invoice_type' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'penalty_amount' => ['required', 'string', 'max:255'],
            'final_amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
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
                return ApplicantInvoice::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantInvoice berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantInvoice.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantInvoice gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantInvoice = $this->model->findOrFail($id);
        $result = $this->getForm($applicantInvoice);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantInvoice::edit',
            compact('applicantInvoice', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'applicant_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'invoice_type' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'penalty_amount' => ['required', 'string', 'max:255'],
            'final_amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantInvoice = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $applicantInvoice,
                $validated
            ) {
                $applicantInvoice->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantInvoice berhasil diperbarui.',
                $applicantInvoice
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ApplicantInvoice.',
                [
                    'exception' => $e,
                    'ApplicantInvoice_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantInvoice gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantInvoice = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantInvoice) {
                $applicantInvoice->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantInvoice berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $applicantInvoice->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantInvoice.',
                [
                    'exception' => $e,
                    'ApplicantInvoice_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantInvoice gagal dihapus.'
            );
        }
    } 
}
