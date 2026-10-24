<?php

namespace App\Modules\RkasItem\Controllers;

use App\DataTables\RkasItemDataTable;
use App\Http\Controllers\MainController;
use Models\RkasItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasItem());
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
        RkasItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasItem = new RkasItem();
        $result = $this->getForm($rkasItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasItem::create',
            compact('rkasItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'rkas_sub_activity_id' => ['required', 'integer'],
            'rkas_component_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'volume' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'shipping_amount' => ['required', 'string', 'max:255'],
            'admin_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'execution_month' => ['required', 'string', 'max:255'],
            'execution_quarter' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasItem = $this->model->findOrFail($id);
        $result = $this->getForm($rkasItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasItem::edit',
            compact('rkasItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'rkas_sub_activity_id' => ['required', 'integer'],
            'rkas_component_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'volume' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'shipping_amount' => ['required', 'string', 'max:255'],
            'admin_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'execution_month' => ['required', 'string', 'max:255'],
            'execution_quarter' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasItem = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $rkasItem,
                $validated
            ) {
                $rkasItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasItem berhasil diperbarui.',
                $rkasItem
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui RkasItem.',
                [
                    'exception' => $e,
                    'RkasItem_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasItem) {
                $rkasItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasItem berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $rkasItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasItem.',
                [
                    'exception' => $e,
                    'RkasItem_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasItem gagal dihapus.'
            );
        }
    } 
}
