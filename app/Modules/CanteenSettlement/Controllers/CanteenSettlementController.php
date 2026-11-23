<?php

namespace App\Modules\CanteenSettlement\Controllers;

use App\DataTables\CanteenSettlementDataTable;
use App\Http\Controllers\MainController;
use Models\CanteenSettlement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CanteenSettlementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CanteenSettlement());
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
        CanteenSettlementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /canteen-settlement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CanteenSettlement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $canteenSettlement = new CanteenSettlement();
        $result = $this->getForm($canteenSettlement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenSettlement::create',
            compact('canteenSettlement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'canteen_tenant_id' => ['required', 'integer'],
            'settlement_number' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'total_gross_sales' => ['required', 'string', 'max:255'],
            'total_commission' => ['required', 'string', 'max:255'],
            'net_payout' => ['required', 'string', 'max:255'],
            'payment_status' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CanteenSettlement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenSettlement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CanteenSettlement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenSettlement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $canteenSettlement = $this->model->findOrFail($id);
        $result = $this->getForm($canteenSettlement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenSettlement::edit',
            compact('canteenSettlement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'canteen_tenant_id' => ['required', 'integer'],
            'settlement_number' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'total_gross_sales' => ['required', 'string', 'max:255'],
            'total_commission' => ['required', 'string', 'max:255'],
            'net_payout' => ['required', 'string', 'max:255'],
            'payment_status' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $canteenSettlement = $this->model->findOrFail($id);
            DB::transaction(function () use ($canteenSettlement, $validated) {
                $canteenSettlement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenSettlement berhasil diperbarui.',
                $canteenSettlement
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CanteenSettlement.',
                [
                    'exception' => $e,
                    'CanteenSettlement_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenSettlement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $canteenSettlement = $this->model->findOrFail($id);

            DB::transaction(function () use ($canteenSettlement) {
                $canteenSettlement->delete();
            });

            return $this->successResponse(
                $request,
                'Data CanteenSettlement berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $canteenSettlement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CanteenSettlement.',
                [
                    'exception' => $e,
                    'CanteenSettlement_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenSettlement gagal dihapus.'
            );
        }
    }
}
