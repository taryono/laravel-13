<?php

namespace App\Modules\CanteenTenantUser\Controllers;

use App\DataTables\CanteenTenantUserDataTable;
use App\Http\Controllers\MainController;
use Models\CanteenTenantUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CanteenTenantUserController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CanteenTenantUser());
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
        CanteenTenantUserDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /canteen-tenant-user?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CanteenTenantUser::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $canteenTenantUser = new CanteenTenantUser();
        $result = $this->getForm($canteenTenantUser);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenTenantUser::create',
            compact('canteenTenantUser', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'canteen_tenant_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CanteenTenantUser::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenTenantUser berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CanteenTenantUser.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenTenantUser gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $canteenTenantUser = $this->model->findOrFail($id);
        $result = $this->getForm($canteenTenantUser);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenTenantUser::edit',
            compact('canteenTenantUser', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'canteen_tenant_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        try {
            $canteenTenantUser = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $canteenTenantUser,
                $validated
            ) {
                $canteenTenantUser->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenTenantUser berhasil diperbarui.',
                $canteenTenantUser
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CanteenTenantUser.',
                [
                    'exception' => $e,
                    'CanteenTenantUser_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenTenantUser gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $canteenTenantUser = $this->model->findOrFail($id);

            DB::transaction(function () use ($canteenTenantUser) {
                $canteenTenantUser->delete();
            });

            return $this->successResponse(
                $request,
                'Data CanteenTenantUser berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $canteenTenantUser->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CanteenTenantUser.',
                [
                    'exception' => $e,
                    'CanteenTenantUser_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenTenantUser gagal dihapus.'
            );
        }
    } 
}
