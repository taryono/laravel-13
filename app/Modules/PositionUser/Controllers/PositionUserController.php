<?php

namespace App\Modules\PositionUser\Controllers;

use App\DataTables\PositionUserDataTable;
use App\Http\Controllers\MainController;
use Models\PositionUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PositionUserController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PositionUser());
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
        PositionUserDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /position-user?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PositionUser::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $positionUser = new PositionUser();
        $result = $this->getForm($positionUser);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PositionUser::create',
            compact('positionUser', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'user_id' => ['required', 'integer'],
            'position_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PositionUser::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PositionUser berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PositionUser.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PositionUser gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $positionUser = $this->model->findOrFail($id);
        $result = $this->getForm($positionUser);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PositionUser::edit',
            compact('positionUser', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'user_id' => ['required', 'integer'],
            'position_id' => ['required', 'integer'],
        ]);

        try {
            $positionUser = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $positionUser,
                $validated
            ) {
                $positionUser->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PositionUser berhasil diperbarui.',
                $positionUser
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui PositionUser.',
                [
                    'exception' => $e,
                    'PositionUser_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PositionUser gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $positionUser = $this->model->findOrFail($id);

            DB::transaction(function () use ($positionUser) {
                $positionUser->delete();
            });

            return $this->successResponse(
                $request,
                'Data PositionUser berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $positionUser->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PositionUser.',
                [
                    'exception' => $e,
                    'PositionUser_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PositionUser gagal dihapus.'
            );
        }
    } 
}
