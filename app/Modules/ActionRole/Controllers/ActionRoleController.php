<?php

namespace App\Modules\ActionRole\Controllers;

use App\DataTables\ActionRoleDataTable;
use App\Http\Controllers\MainController;
use Models\ActionRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ActionRoleController extends MainController
{

    public function __construct()
    {
        parent::__construct(new ActionRole());
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
        ActionRoleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /action-role?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ActionRole::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $actionRole = new ActionRole();
        $forms = $this->getForm($actionRole);
        return view(
            'ActionRole::create',
            compact('actionRole', 'forms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ActionRole::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ActionRole berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ActionRole.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ActionRole gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $actionRole = $this->model->findOrFail($id);
        $forms = $this->getForm($actionRole);
        return view(
            'ActionRole::edit',
            compact('actionRole', 'forms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $actionRole = $this->model->findOrFail($id);
            DB::transaction(function () use ($actionRole, $validated) {
                $actionRole->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ActionRole berhasil diperbarui.',
                $actionRole
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui ActionRole.',
                [
                    'exception' => $e,
                    'ActionRole_id' => $actionRole->getKey(),
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ActionRole gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $actionRole = $this->model->findOrFail($id);
            DB::transaction(function () use ($actionRole) {
                $actionRole->delete();
            });

            return $this->successResponse(
                $request,
                'Data ActionRole berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus ActionRole.',
                [
                    'exception' => $e,
                    'ActionRole_id' => $actionRole->getKey(),
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ActionRole gagal dihapus.'
            );
        }
    }

}