<?php

namespace App\Modules\DormitoryMovement\Controllers;

use App\DataTables\DormitoryMovementDataTable;
use App\Http\Controllers\MainController;
use Models\DormitoryMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DormitoryMovementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DormitoryMovement());
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
        DormitoryMovementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /dormitory-movement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DormitoryMovement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dormitoryMovement = new DormitoryMovement();
        $result = $this->getForm($dormitoryMovement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DormitoryMovement::create',
            compact('dormitoryMovement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dormitory_assignment_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'from_dormitory_id' => ['required', 'integer'],
            'from_room_id' => ['required', 'integer'],
            'to_dormitory_id' => ['required', 'integer'],
            'to_room_id' => ['required', 'integer'],
            'movement_type' => ['required', 'string', 'max:255'],
            'movement_date' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'from_bed_number' => ['required', 'string', 'max:255'],
            'to_bed_number' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DormitoryMovement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DormitoryMovement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DormitoryMovement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryMovement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dormitoryMovement = $this->model->findOrFail($id);
        $result = $this->getForm($dormitoryMovement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DormitoryMovement::edit',
            compact('dormitoryMovement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'dormitory_assignment_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'from_dormitory_id' => ['required', 'integer'],
            'from_room_id' => ['required', 'integer'],
            'to_dormitory_id' => ['required', 'integer'],
            'to_room_id' => ['required', 'integer'],
            'movement_type' => ['required', 'string', 'max:255'],
            'movement_date' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'from_bed_number' => ['required', 'string', 'max:255'],
            'to_bed_number' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dormitoryMovement = $this->model->findOrFail($id);
            DB::transaction(function () use ($dormitoryMovement, $validated) {
                $dormitoryMovement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DormitoryMovement berhasil diperbarui.',
                $dormitoryMovement
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DormitoryMovement.',
                [
                    'exception' => $e,
                    'DormitoryMovement_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryMovement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dormitoryMovement = $this->model->findOrFail($id);

            DB::transaction(function () use ($dormitoryMovement) {
                $dormitoryMovement->delete();
            });

            return $this->successResponse(
                $request,
                'Data DormitoryMovement berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $dormitoryMovement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DormitoryMovement.',
                [
                    'exception' => $e,
                    'DormitoryMovement_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryMovement gagal dihapus.'
            );
        }
    }
}
