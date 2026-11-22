<?php

namespace App\Modules\Room\Controllers;

use App\DataTables\RoomDataTable;
use App\Http\Controllers\MainController;
use Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RoomController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Room());
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
        RoomDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /room?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Room::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $room = new Room();
        $result = $this->getForm($room);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Room::create',
            compact('room', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'floor_id' => ['required', 'integer'],
            'room_number' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Room::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Room berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Room.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Room gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $room = $this->model->findOrFail($id);
        $result = $this->getForm($room);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Room::edit',
            compact('room', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'floor_id' => ['required', 'integer'],
            'room_number' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $room = $this->model->findOrFail($id);

            DB::transaction(function () use ($room, $validated) {
                $room->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Room berhasil diperbarui.',
                $room
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $room->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $room belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui Room.',
                [
                    'exception' => $e,
                    'Room_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Room gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $room = $this->model->findOrFail($id);

            DB::transaction(function () use ($room) {
                $room->delete();
            });

            return $this->successResponse(
                $request,
                'Data Room berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $room->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Room.',
                [
                    'exception' => $e,
                    'Room_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Room gagal dihapus.'
            );
        }
    }
}
