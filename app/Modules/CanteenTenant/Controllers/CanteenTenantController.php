<?php

namespace App\Modules\CanteenTenant\Controllers;

use App\DataTables\CanteenTenantDataTable;
use App\Http\Controllers\MainController;
use Models\CanteenTenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CanteenTenantController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CanteenTenant());
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
        CanteenTenantDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /canteen-tenant?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CanteenTenant::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $canteenTenant = new CanteenTenant();
        $result = $this->getForm($canteenTenant);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenTenant::create',
            compact('canteenTenant', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'stall_location' => ['required', 'string', 'max:255'],
            'commission_rate' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CanteenTenant::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenTenant berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CanteenTenant.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenTenant gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $canteenTenant = $this->model->findOrFail($id);
        $result = $this->getForm($canteenTenant);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenTenant::edit',
            compact('canteenTenant', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'stall_location' => ['required', 'string', 'max:255'],
            'commission_rate' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $canteenTenant = $this->model->findOrFail($id);
            DB::transaction(function () use ($canteenTenant, $validated) {
                $canteenTenant->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenTenant berhasil diperbarui.',
                $canteenTenant
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CanteenTenant.',
                [
                    'exception' => $e,
                    'CanteenTenant_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenTenant gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $canteenTenant = $this->model->findOrFail($id);

            DB::transaction(function () use ($canteenTenant) {
                $canteenTenant->delete();
            });

            return $this->successResponse(
                $request,
                'Data CanteenTenant berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $canteenTenant->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CanteenTenant.',
                [
                    'exception' => $e,
                    'CanteenTenant_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenTenant gagal dihapus.'
            );
        }
    }
}
