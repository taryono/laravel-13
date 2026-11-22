<?php

namespace App\Modules\BosRealization\Controllers;

use App\DataTables\BosRealizationDataTable;
use App\Http\Controllers\MainController;
use Models\BosRealization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BosRealizationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BosRealization());
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
        BosRealizationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bos-realization?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BosRealization::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bosRealization = new BosRealization();
        $result = $this->getForm($bosRealization);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosRealization::create',
            compact('bosRealization', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bos_period_id' => ['required', 'integer'],
            'bos_usage_plan_id' => ['required', 'integer'],
            'rkas_realization_id' => ['required', 'integer'],
            'journal_entry_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'vendor_invoice_id' => ['required', 'integer'],
            'receipt_number' => ['required', 'string', 'max:255'],
            'realization_date' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BosRealization::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosRealization berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BosRealization.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosRealization gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bosRealization = $this->model->findOrFail($id);
        $result = $this->getForm($bosRealization);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosRealization::edit',
            compact('bosRealization', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bos_period_id' => ['required', 'integer'],
            'bos_usage_plan_id' => ['required', 'integer'],
            'rkas_realization_id' => ['required', 'integer'],
            'journal_entry_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'vendor_invoice_id' => ['required', 'integer'],
            'receipt_number' => ['required', 'string', 'max:255'],
            'realization_date' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bosRealization = $this->model->findOrFail($id);
            DB::transaction(function () use ($bosRealization, $validated) {
                $bosRealization->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosRealization berhasil diperbarui.',
                $bosRealization
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BosRealization.',
                [
                    'exception' => $e,
                    'BosRealization_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosRealization gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bosRealization = $this->model->findOrFail($id);

            DB::transaction(function () use ($bosRealization) {
                $bosRealization->delete();
            });

            return $this->successResponse(
                $request,
                'Data BosRealization berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bosRealization->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BosRealization.',
                [
                    'exception' => $e,
                    'BosRealization_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosRealization gagal dihapus.'
            );
        }
    }
}
