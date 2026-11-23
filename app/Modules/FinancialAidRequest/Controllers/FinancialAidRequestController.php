<?php

namespace App\Modules\FinancialAidRequest\Controllers;

use App\DataTables\FinancialAidRequestDataTable;
use App\Http\Controllers\MainController;
use Models\FinancialAidRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FinancialAidRequestController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FinancialAidRequest());
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
        FinancialAidRequestDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /financial-aid-request?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FinancialAidRequest::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $financialAidRequest = new FinancialAidRequest();
        $result = $this->getForm($financialAidRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FinancialAidRequest::create',
            compact('financialAidRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'fee_master_id' => ['required', 'integer'],
            'student_invoice_id' => ['required', 'integer'],
            'request_number' => ['required', 'string', 'max:255'],
            'aid_type' => ['required', 'string', 'max:255'],
            'requested_amount' => ['required', 'string', 'max:255'],
            'requested_percentage' => ['required', 'string', 'max:255'],
            'approved_amount' => ['required', 'string', 'max:255'],
            'approved_percentage' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:255'],
            'family_income' => ['required', 'string', 'max:255'],
            'family_dependents' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'father_occupation' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'mother_occupation' => ['required', 'string', 'max:255'],
            'supporting_documents' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'current_stage' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'reviewed_at' => ['required', 'string', 'max:255'],
            'reviewed_by' => ['required', 'string', 'max:255'],
            'review_notes' => ['required', 'string', 'max:255'],
            'decision' => ['required', 'string', 'max:255'],
            'decision_date' => ['required', 'string', 'max:255'],
            'decision_by' => ['required', 'string', 'max:255'],
            'decision_notes' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'valid_from' => ['required', 'string', 'max:255'],
            'valid_until' => ['required', 'string', 'max:255'],
            'installment_count' => ['required', 'string', 'max:255'],
            'installment_amount' => ['required', 'string', 'max:255'],
            'applied_to_invoice' => ['required', 'string', 'max:255'],
            'applied_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FinancialAidRequest::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FinancialAidRequest berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FinancialAidRequest.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FinancialAidRequest gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $financialAidRequest = $this->model->findOrFail($id);
        $result = $this->getForm($financialAidRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FinancialAidRequest::edit',
            compact('financialAidRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'fee_master_id' => ['required', 'integer'],
            'student_invoice_id' => ['required', 'integer'],
            'request_number' => ['required', 'string', 'max:255'],
            'aid_type' => ['required', 'string', 'max:255'],
            'requested_amount' => ['required', 'string', 'max:255'],
            'requested_percentage' => ['required', 'string', 'max:255'],
            'approved_amount' => ['required', 'string', 'max:255'],
            'approved_percentage' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:255'],
            'family_income' => ['required', 'string', 'max:255'],
            'family_dependents' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'father_occupation' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'mother_occupation' => ['required', 'string', 'max:255'],
            'supporting_documents' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'current_stage' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'reviewed_at' => ['required', 'string', 'max:255'],
            'reviewed_by' => ['required', 'string', 'max:255'],
            'review_notes' => ['required', 'string', 'max:255'],
            'decision' => ['required', 'string', 'max:255'],
            'decision_date' => ['required', 'string', 'max:255'],
            'decision_by' => ['required', 'string', 'max:255'],
            'decision_notes' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'valid_from' => ['required', 'string', 'max:255'],
            'valid_until' => ['required', 'string', 'max:255'],
            'installment_count' => ['required', 'string', 'max:255'],
            'installment_amount' => ['required', 'string', 'max:255'],
            'applied_to_invoice' => ['required', 'string', 'max:255'],
            'applied_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $financialAidRequest = $this->model->findOrFail($id);
            DB::transaction(function () use ($financialAidRequest, $validated) {
                $financialAidRequest->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FinancialAidRequest berhasil diperbarui.',
                $financialAidRequest
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui FinancialAidRequest.',
                [
                    'exception' => $e,
                    'FinancialAidRequest_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FinancialAidRequest gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $financialAidRequest = $this->model->findOrFail($id);

            DB::transaction(function () use ($financialAidRequest) {
                $financialAidRequest->delete();
            });

            return $this->successResponse(
                $request,
                'Data FinancialAidRequest berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $financialAidRequest->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FinancialAidRequest.',
                [
                    'exception' => $e,
                    'FinancialAidRequest_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FinancialAidRequest gagal dihapus.'
            );
        }
    }
}
