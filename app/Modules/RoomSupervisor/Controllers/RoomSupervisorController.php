<?php

namespace App\Modules\RoomSupervisor\Controllers;

use App\DataTables\RoomSupervisorDataTable;
use App\Http\Controllers\MainController;
use Models\RoomSupervisor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RoomSupervisorController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RoomSupervisor());
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
        RoomSupervisorDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /room-supervisor?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RoomSupervisor::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roomSupervisor = new RoomSupervisor();
        $result = $this->getForm($roomSupervisor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RoomSupervisor::create',
            compact('roomSupervisor', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'roomname' => ['required', 'string', 'max:255'],
            'employeename' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RoomSupervisor::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RoomSupervisor berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RoomSupervisor.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RoomSupervisor gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $roomSupervisor = $this->model->findOrFail($id);
        $result = $this->getForm($roomSupervisor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RoomSupervisor::edit',
            compact('roomSupervisor', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'roomname' => ['required', 'string', 'max:255'],
            'employeename' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $roomSupervisor = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $roomSupervisor,
                $validated
            ) {
                $roomSupervisor->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RoomSupervisor berhasil diperbarui.',
                $roomSupervisor
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui RoomSupervisor.',
                [
                    'exception' => $e,
                    'RoomSupervisor_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RoomSupervisor gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $roomSupervisor = $this->model->findOrFail($id);

            DB::transaction(function () use ($roomSupervisor) {
                $roomSupervisor->delete();
            });

            return $this->successResponse(
                $request,
                'Data RoomSupervisor berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $roomSupervisor->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RoomSupervisor.',
                [
                    'exception' => $e,
                    'RoomSupervisor_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RoomSupervisor gagal dihapus.'
            );
        }
    } 
}
