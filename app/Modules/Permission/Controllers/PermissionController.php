<?php

namespace App\Modules\Permission\Controllers;

use App\DataTables\PermissionDataTable;
use App\Http\Controllers\MainController;
use Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PermissionController extends MainController
{

    public function __construct(){
        parent::__construct(new Permission());
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
        PermissionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /permission?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Permission::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {   
        $permission = new Permission();
        $forms = $this->getForm($permission);
        return view(
            'Permission::edit',
            compact('permission', 'forms')
        );
        return view('Permission::create');
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
                return Permission::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Permission berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Permission.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Permission gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {   $permission = $this->model->findOrFail($id);
        $forms = $this->getForm($permission);
        return view(
            'Permission::edit',
            compact('permission', 'forms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request,$id) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $permission = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $permission,
                $validated
            ) {
                $permission->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Permission berhasil diperbarui.',
                $permission
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui Permission.',
                [
                    'exception' => $e,
                    'Permission_id' => $permission->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Permission gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request,$id): JsonResponse|RedirectResponse {
        try {
             $permission = $this->model->findOrFail($id);
            DB::transaction(function () use ($permission) {
                $permission->delete();
            });

            return $this->successResponse(
                $request,
                'Data Permission berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus Permission.',
                [
                    'exception' => $e,
                    'Permission_id' => $permission->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Permission gagal dihapus.'
            );
        }
    }
 
}