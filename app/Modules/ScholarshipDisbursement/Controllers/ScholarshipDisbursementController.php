<?php

namespace App\Modules\ScholarshipDisbursement\Controllers;

use App\DataTables\ScholarshipDisbursementDataTable;
use App\Http\Controllers\MainController;
use Models\ScholarshipDisbursement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ScholarshipDisbursementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ScholarshipDisbursement());
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
        ScholarshipDisbursementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /scholarship-disbursement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ScholarshipDisbursement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $scholarshipDisbursement = new ScholarshipDisbursement();
        $result = $this->getForm($scholarshipDisbursement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScholarshipDisbursement::create',
            compact('scholarshipDisbursement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholarship_recipient_id' => ['required', 'integer'],
            'scholarship_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'disbursement_number' => ['required', 'string', 'max:255'],
            'period_label' => ['required', 'string', 'max:255'],
            'period_month' => ['required', 'string', 'max:255'],
            'period_year' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'amount_in_base_currency' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_channel_id' => ['required', 'integer'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'scheduled_date' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'processed_by' => ['required', 'string', 'max:255'],
            'payment_proof_path' => ['required', 'string', 'max:255'],
            'failure_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ScholarshipDisbursement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipDisbursement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ScholarshipDisbursement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipDisbursement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $scholarshipDisbursement = $this->model->findOrFail($id);
        $result = $this->getForm($scholarshipDisbursement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScholarshipDisbursement::edit',
            compact('scholarshipDisbursement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'scholarship_recipient_id' => ['required', 'integer'],
            'scholarship_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'disbursement_number' => ['required', 'string', 'max:255'],
            'period_label' => ['required', 'string', 'max:255'],
            'period_month' => ['required', 'string', 'max:255'],
            'period_year' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'amount_in_base_currency' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_channel_id' => ['required', 'integer'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'scheduled_date' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'processed_by' => ['required', 'string', 'max:255'],
            'payment_proof_path' => ['required', 'string', 'max:255'],
            'failure_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $scholarshipDisbursement = $this->model->findOrFail($id);
            DB::transaction(function () use ($scholarshipDisbursement, $validated) {
                $scholarshipDisbursement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipDisbursement berhasil diperbarui.',
                $scholarshipDisbursement
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ScholarshipDisbursement.',
                [
                    'exception' => $e,
                    'ScholarshipDisbursement_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipDisbursement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $scholarshipDisbursement = $this->model->findOrFail($id);

            DB::transaction(function () use ($scholarshipDisbursement) {
                $scholarshipDisbursement->delete();
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipDisbursement berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $scholarshipDisbursement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ScholarshipDisbursement.',
                [
                    'exception' => $e,
                    'ScholarshipDisbursement_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipDisbursement gagal dihapus.'
            );
        }
    }
}
