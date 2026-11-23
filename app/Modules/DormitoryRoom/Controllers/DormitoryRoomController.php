<?php

namespace App\Modules\DormitoryRoom\Controllers;

use App\DataTables\DormitoryRoomDataTable;
use App\Http\Controllers\MainController;
use Models\DormitoryRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DormitoryRoomController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DormitoryRoom());
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
        DormitoryRoomDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /dormitory-room?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DormitoryRoom::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dormitoryRoom = new DormitoryRoom();
        $result = $this->getForm($dormitoryRoom);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DormitoryRoom::create',
            compact('dormitoryRoom', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dormitory_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'building_id' => ['required', 'integer'],
            'room_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'floor' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'occupied' => ['required', 'string', 'max:255'],
            'room_type' => ['required', 'string', 'max:255'],
            'facilities' => ['required', 'string', 'max:255'],
            'monthly_fee' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'condition' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DormitoryRoom::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DormitoryRoom berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DormitoryRoom.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryRoom gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dormitoryRoom = $this->model->findOrFail($id);
        $result = $this->getForm($dormitoryRoom);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DormitoryRoom::edit',
            compact('dormitoryRoom', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'dormitory_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'building_id' => ['required', 'integer'],
            'room_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'floor' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'string', 'max:255'],
            'occupied' => ['required', 'string', 'max:255'],
            'room_type' => ['required', 'string', 'max:255'],
            'facilities' => ['required', 'string', 'max:255'],
            'monthly_fee' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'condition' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dormitoryRoom = $this->model->findOrFail($id);
            DB::transaction(function () use ($dormitoryRoom, $validated) {
                $dormitoryRoom->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DormitoryRoom berhasil diperbarui.',
                $dormitoryRoom
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DormitoryRoom.',
                [
                    'exception' => $e,
                    'DormitoryRoom_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryRoom gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dormitoryRoom = $this->model->findOrFail($id);

            DB::transaction(function () use ($dormitoryRoom) {
                $dormitoryRoom->delete();
            });

            return $this->successResponse(
                $request,
                'Data DormitoryRoom berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $dormitoryRoom->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DormitoryRoom.',
                [
                    'exception' => $e,
                    'DormitoryRoom_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DormitoryRoom gagal dihapus.'
            );
        }
    }
}
