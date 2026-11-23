<?php

namespace App\Modules\RkasReportItem\Controllers;

use App\DataTables\RkasReportItemDataTable;
use App\Http\Controllers\MainController;
use Models\RkasReportItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasReportItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasReportItem());
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
        RkasReportItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-report-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasReportItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasReportItem = new RkasReportItem();
        $result = $this->getForm($rkasReportItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasReportItem::create',
            compact('rkasReportItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rkas_report_id' => ['required', 'integer'],
            'rkas_component_id' => ['required', 'integer'],
            'rkas_source_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
            'budget_amount' => ['required', 'string', 'max:255'],
            'realization_amount' => ['required', 'string', 'max:255'],
            'remaining_amount' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'item_data' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasReportItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasReportItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasReportItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasReportItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasReportItem = $this->model->findOrFail($id);
        $result = $this->getForm($rkasReportItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasReportItem::edit',
            compact('rkasReportItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rkas_report_id' => ['required', 'integer'],
            'rkas_component_id' => ['required', 'integer'],
            'rkas_source_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
            'budget_amount' => ['required', 'string', 'max:255'],
            'realization_amount' => ['required', 'string', 'max:255'],
            'remaining_amount' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'string', 'max:255'],
            'item_data' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasReportItem = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasReportItem, $validated) {
                $rkasReportItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasReportItem berhasil diperbarui.',
                $rkasReportItem
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasReportItem.',
                [
                    'exception' => $e,
                    'RkasReportItem_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasReportItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasReportItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasReportItem) {
                $rkasReportItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasReportItem berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasReportItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasReportItem.',
                [
                    'exception' => $e,
                    'RkasReportItem_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasReportItem gagal dihapus.'
            );
        }
    }
}
