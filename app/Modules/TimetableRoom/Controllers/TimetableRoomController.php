<?php

namespace App\Modules\TimetableRoom\Controllers;

use App\DataTables\TimetableRoomDataTable;
use App\Http\Controllers\MainController;
use Models\TimetableRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TimetableRoomController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TimetableRoom());
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
        TimetableRoomDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /timetable-room?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TimetableRoom::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $timetableRoom = new TimetableRoom();
        $result = $this->getForm($timetableRoom);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TimetableRoom::create',
            compact('timetableRoom', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'room_code' => ['required', 'string', 'max:255'],
            'room_name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'room_type' => ['required', 'string', 'max:255'],
            'floor_number' => ['required', 'string', 'max:255'],
            'building' => ['required', 'string', 'max:255'],
            'facilities' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TimetableRoom::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TimetableRoom berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TimetableRoom.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableRoom gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $timetableRoom = $this->model->findOrFail($id);
        $result = $this->getForm($timetableRoom);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TimetableRoom::edit',
            compact('timetableRoom', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'room_code' => ['required', 'string', 'max:255'],
            'room_name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'room_type' => ['required', 'string', 'max:255'],
            'floor_number' => ['required', 'string', 'max:255'],
            'building' => ['required', 'string', 'max:255'],
            'facilities' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $timetableRoom = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $timetableRoom,
                $validated
            ) {
                $timetableRoom->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TimetableRoom berhasil diperbarui.',
                $timetableRoom
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui TimetableRoom.',
                [
                    'exception' => $e,
                    'TimetableRoom_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableRoom gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $timetableRoom = $this->model->findOrFail($id);

            DB::transaction(function () use ($timetableRoom) {
                $timetableRoom->delete();
            });

            return $this->successResponse(
                $request,
                'Data TimetableRoom berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $timetableRoom->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TimetableRoom.',
                [
                    'exception' => $e,
                    'TimetableRoom_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableRoom gagal dihapus.'
            );
        }
    } 
}
