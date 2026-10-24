<?php

namespace App\Modules\DormitoryAssignment\Controllers;

use App\DataTables\DormitoryAssignmentDataTable;
use App\Http\Controllers\MainController;
use Models\DormitoryAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DormitoryAssignmentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DormitoryAssignment());
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
        DormitoryAssignmentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /dormitory-assignment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DormitoryAssignment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dormitoryAssignment = new DormitoryAssignment();
        $result = $this->getForm($dormitoryAssignment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DormitoryAssignment::create',
            compact('dormitoryAssignment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'dormitory_id' => ['required', 'integer'],
            'dormitory_room_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'assignment_number' => ['required', 'string', 'max:255'],
            'bed_number' => ['required', 'string', 'max:255'],
            'locker_number' => ['required', 'string', 'max:255'],
            'check_in_date' => ['required', 'string', 'max:255'],
            'check_out_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['required', 'string', 'max:255'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:255'],
            'health_notes' => ['required', 'string', 'max:255'],
            'special_needs' => ['required', 'string', 'max:255'],
            'monthly_fee' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'is_fee_paid' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'assigned_by' => ['required', 'string', 'max:255'],
            'assigned_at' => ['required', 'string', 'max:255'],
            'checked_out_by' => ['required', 'string', 'max:255'],
            'checked_out_at' => ['required', 'string', 'max:255'],
            'checkout_reason' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DormitoryAssignment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DormitoryAssignment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DormitoryAssignment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryAssignment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dormitoryAssignment = $this->model->findOrFail($id);
        $result = $this->getForm($dormitoryAssignment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DormitoryAssignment::edit',
            compact('dormitoryAssignment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'dormitory_id' => ['required', 'integer'],
            'dormitory_room_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'assignment_number' => ['required', 'string', 'max:255'],
            'bed_number' => ['required', 'string', 'max:255'],
            'locker_number' => ['required', 'string', 'max:255'],
            'check_in_date' => ['required', 'string', 'max:255'],
            'check_out_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['required', 'string', 'max:255'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:255'],
            'health_notes' => ['required', 'string', 'max:255'],
            'special_needs' => ['required', 'string', 'max:255'],
            'monthly_fee' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'is_fee_paid' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'assigned_by' => ['required', 'string', 'max:255'],
            'assigned_at' => ['required', 'string', 'max:255'],
            'checked_out_by' => ['required', 'string', 'max:255'],
            'checked_out_at' => ['required', 'string', 'max:255'],
            'checkout_reason' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dormitoryAssignment = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $dormitoryAssignment,
                $validated
            ) {
                $dormitoryAssignment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DormitoryAssignment berhasil diperbarui.',
                $dormitoryAssignment
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui DormitoryAssignment.',
                [
                    'exception' => $e,
                    'DormitoryAssignment_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryAssignment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dormitoryAssignment = $this->model->findOrFail($id);

            DB::transaction(function () use ($dormitoryAssignment) {
                $dormitoryAssignment->delete();
            });

            return $this->successResponse(
                $request,
                'Data DormitoryAssignment berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $dormitoryAssignment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DormitoryAssignment.',
                [
                    'exception' => $e,
                    'DormitoryAssignment_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryAssignment gagal dihapus.'
            );
        }
    } 
}
