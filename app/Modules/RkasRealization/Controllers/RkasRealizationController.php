<?php

namespace App\Modules\RkasRealization\Controllers;

use App\DataTables\RkasRealizationDataTable;
use App\Http\Controllers\MainController;
use Models\RkasRealization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasRealizationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasRealization());
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
        RkasRealizationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-realization?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasRealization::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasRealization = new RkasRealization();
        $result = $this->getForm($rkasRealization);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasRealization::create',
            compact('rkasRealization', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rkas_item_id' => ['required', 'integer'],
            'rkas_source_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'journal_entry_id' => ['required', 'integer'],
            'realization_date' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'vendor_id' => ['required', 'integer'],
            'vendor_invoice_id' => ['required', 'integer'],
            'payment_reference' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasRealization::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasRealization berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasRealization.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasRealization gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasRealization = $this->model->findOrFail($id);
        $result = $this->getForm($rkasRealization);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasRealization::edit',
            compact('rkasRealization', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rkas_item_id' => ['required', 'integer'],
            'rkas_source_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'journal_entry_id' => ['required', 'integer'],
            'realization_date' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'vendor_id' => ['required', 'integer'],
            'vendor_invoice_id' => ['required', 'integer'],
            'payment_reference' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasRealization = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasRealization, $validated) {
                $rkasRealization->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasRealization berhasil diperbarui.',
                $rkasRealization
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasRealization.',
                [
                    'exception' => $e,
                    'RkasRealization_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasRealization gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasRealization = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasRealization) {
                $rkasRealization->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasRealization berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasRealization->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasRealization.',
                [
                    'exception' => $e,
                    'RkasRealization_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasRealization gagal dihapus.'
            );
        }
    }
}
