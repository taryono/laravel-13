<?php

namespace App\Modules\ScholarshipRecipient\Controllers;

use App\DataTables\ScholarshipRecipientDataTable;
use App\Http\Controllers\MainController;
use Models\ScholarshipRecipient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ScholarshipRecipientController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ScholarshipRecipient());
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
        ScholarshipRecipientDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /scholarship-recipient?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ScholarshipRecipient::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $scholarshipRecipient = new ScholarshipRecipient();
        $result = $this->getForm($scholarshipRecipient);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScholarshipRecipient::create',
            compact('scholarshipRecipient', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'scholarship_id' => ['required', 'integer'],
            'scholarship_application_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'recipient_number' => ['required', 'string', 'max:255'],
            'benefit_type' => ['required', 'string', 'max:255'],
            'benefit_amount' => ['required', 'string', 'max:255'],
            'benefit_percentage' => ['required', 'string', 'max:255'],
            'benefit_currency_id' => ['required', 'integer'],
            'total_disbursed' => ['required', 'string', 'max:255'],
            'remaining_benefit' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'duration_semesters' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'suspension_reason' => ['required', 'string', 'max:255'],
            'termination_reason' => ['required', 'string', 'max:255'],
            'renewal_count' => ['required', 'string', 'max:255'],
            'last_renewal_date' => ['required', 'string', 'max:255'],
            'next_renewal_date' => ['required', 'string', 'max:255'],
            'gpa_current' => ['required', 'string', 'max:255'],
            'attendance_percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ScholarshipRecipient::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipRecipient berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ScholarshipRecipient.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipRecipient gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $scholarshipRecipient = $this->model->findOrFail($id);
        $result = $this->getForm($scholarshipRecipient);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScholarshipRecipient::edit',
            compact('scholarshipRecipient', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'scholarship_id' => ['required', 'integer'],
            'scholarship_application_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'recipient_number' => ['required', 'string', 'max:255'],
            'benefit_type' => ['required', 'string', 'max:255'],
            'benefit_amount' => ['required', 'string', 'max:255'],
            'benefit_percentage' => ['required', 'string', 'max:255'],
            'benefit_currency_id' => ['required', 'integer'],
            'total_disbursed' => ['required', 'string', 'max:255'],
            'remaining_benefit' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'duration_semesters' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'suspension_reason' => ['required', 'string', 'max:255'],
            'termination_reason' => ['required', 'string', 'max:255'],
            'renewal_count' => ['required', 'string', 'max:255'],
            'last_renewal_date' => ['required', 'string', 'max:255'],
            'next_renewal_date' => ['required', 'string', 'max:255'],
            'gpa_current' => ['required', 'string', 'max:255'],
            'attendance_percentage' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $scholarshipRecipient = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $scholarshipRecipient,
                $validated
            ) {
                $scholarshipRecipient->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipRecipient berhasil diperbarui.',
                $scholarshipRecipient
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ScholarshipRecipient.',
                [
                    'exception' => $e,
                    'ScholarshipRecipient_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipRecipient gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $scholarshipRecipient = $this->model->findOrFail($id);

            DB::transaction(function () use ($scholarshipRecipient) {
                $scholarshipRecipient->delete();
            });

            return $this->successResponse(
                $request,
                'Data ScholarshipRecipient berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $scholarshipRecipient->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ScholarshipRecipient.',
                [
                    'exception' => $e,
                    'ScholarshipRecipient_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScholarshipRecipient gagal dihapus.'
            );
        }
    } 
}
