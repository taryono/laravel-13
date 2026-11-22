<?php

namespace App\Modules\RoomConstraint\Controllers;

use App\DataTables\RoomConstraintDataTable;
use App\Http\Controllers\MainController;
use Models\RoomConstraint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RoomConstraintController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RoomConstraint());
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
        RoomConstraintDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /room-constraint?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RoomConstraint::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roomConstraint = new RoomConstraint();
        $result = $this->getForm($roomConstraint);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RoomConstraint::create',
            compact('roomConstraint', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'time_slot_start' => ['required', 'string', 'max:255'],
            'time_slot_end' => ['required', 'string', 'max:255'],
            'constraint_type' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RoomConstraint::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RoomConstraint berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RoomConstraint.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RoomConstraint gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $roomConstraint = $this->model->findOrFail($id);
        $result = $this->getForm($roomConstraint);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RoomConstraint::edit',
            compact('roomConstraint', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'time_slot_start' => ['required', 'string', 'max:255'],
            'time_slot_end' => ['required', 'string', 'max:255'],
            'constraint_type' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $roomConstraint = $this->model->findOrFail($id);
            DB::transaction(function () use ($roomConstraint, $validated) {
                $roomConstraint->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RoomConstraint berhasil diperbarui.',
                $roomConstraint
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RoomConstraint.',
                [
                    'exception' => $e,
                    'RoomConstraint_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RoomConstraint gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $roomConstraint = $this->model->findOrFail($id);

            DB::transaction(function () use ($roomConstraint) {
                $roomConstraint->delete();
            });

            return $this->successResponse(
                $request,
                'Data RoomConstraint berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $roomConstraint->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RoomConstraint.',
                [
                    'exception' => $e,
                    'RoomConstraint_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RoomConstraint gagal dihapus.'
            );
        }
    }
}
