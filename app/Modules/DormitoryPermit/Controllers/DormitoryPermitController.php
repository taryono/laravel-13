<?php

namespace App\Modules\DormitoryPermit\Controllers;

use App\DataTables\DormitoryPermitDataTable;
use App\Http\Controllers\MainController;
use Models\DormitoryPermit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DormitoryPermitController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DormitoryPermit());
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
        DormitoryPermitDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /dormitory-permit?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DormitoryPermit::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dormitoryPermit = new DormitoryPermit();
        $result = $this->getForm($dormitoryPermit);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DormitoryPermit::create',
            compact('dormitoryPermit', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'dormitory_assignment_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'dormitory_id' => ['required', 'integer'],
            'permit_number' => ['required', 'string', 'max:255'],
            'permit_type' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'start_datetime' => ['required', 'string', 'max:255'],
            'end_datetime' => ['required', 'string', 'max:255'],
            'actual_return_datetime' => ['required', 'string', 'max:255'],
            'duration_hours' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejected_by' => ['required', 'string', 'max:255'],
            'rejected_at' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['required', 'string', 'max:255'],
            'guardian_relationship' => ['required', 'string', 'max:255'],
            'escort_name' => ['required', 'string', 'max:255'],
            'escort_phone' => ['required', 'string', 'max:255'],
            'contact_during_leave' => ['required', 'string', 'max:255'],
            'address_during_leave' => ['required', 'string', 'max:255'],
            'return_notes' => ['required', 'string', 'max:255'],
            'late_return_reason' => ['required', 'string', 'max:255'],
            'sanction_applied' => ['required', 'string', 'max:255'],
            'sanction_description' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DormitoryPermit::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DormitoryPermit berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DormitoryPermit.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryPermit gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dormitoryPermit = $this->model->findOrFail($id);
        $result = $this->getForm($dormitoryPermit);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DormitoryPermit::edit',
            compact('dormitoryPermit', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'dormitory_assignment_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'dormitory_id' => ['required', 'integer'],
            'permit_number' => ['required', 'string', 'max:255'],
            'permit_type' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'start_datetime' => ['required', 'string', 'max:255'],
            'end_datetime' => ['required', 'string', 'max:255'],
            'actual_return_datetime' => ['required', 'string', 'max:255'],
            'duration_hours' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejected_by' => ['required', 'string', 'max:255'],
            'rejected_at' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['required', 'string', 'max:255'],
            'guardian_relationship' => ['required', 'string', 'max:255'],
            'escort_name' => ['required', 'string', 'max:255'],
            'escort_phone' => ['required', 'string', 'max:255'],
            'contact_during_leave' => ['required', 'string', 'max:255'],
            'address_during_leave' => ['required', 'string', 'max:255'],
            'return_notes' => ['required', 'string', 'max:255'],
            'late_return_reason' => ['required', 'string', 'max:255'],
            'sanction_applied' => ['required', 'string', 'max:255'],
            'sanction_description' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dormitoryPermit = $this->model->findOrFail($id);
            DB::transaction(function () use ($dormitoryPermit, $validated) {
                $dormitoryPermit->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DormitoryPermit berhasil diperbarui.',
                $dormitoryPermit
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DormitoryPermit.',
                [
                    'exception' => $e,
                    'DormitoryPermit_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryPermit gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dormitoryPermit = $this->model->findOrFail($id);

            DB::transaction(function () use ($dormitoryPermit) {
                $dormitoryPermit->delete();
            });

            return $this->successResponse(
                $request,
                'Data DormitoryPermit berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $dormitoryPermit->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DormitoryPermit.',
                [
                    'exception' => $e,
                    'DormitoryPermit_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryPermit gagal dihapus.'
            );
        }
    }
}
