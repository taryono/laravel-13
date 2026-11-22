<?php

namespace App\Modules\BosReceipt\Controllers;

use App\DataTables\BosReceiptDataTable;
use App\Http\Controllers\MainController;
use Models\BosReceipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BosReceiptController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BosReceipt());
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
        BosReceiptDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bos-receipt?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BosReceipt::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bosReceipt = new BosReceipt();
        $result = $this->getForm($bosReceipt);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosReceipt::create',
            compact('bosReceipt', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bos_period_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'receipt_number' => ['required', 'string', 'max:255'],
            'receipt_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'transfer_reference' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'transfer_proof_url' => ['required', 'string', 'max:255'],
            'received_by' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BosReceipt::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosReceipt berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BosReceipt.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosReceipt gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bosReceipt = $this->model->findOrFail($id);
        $result = $this->getForm($bosReceipt);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BosReceipt::edit',
            compact('bosReceipt', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bos_period_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'receipt_number' => ['required', 'string', 'max:255'],
            'receipt_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'transfer_reference' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'transfer_proof_url' => ['required', 'string', 'max:255'],
            'received_by' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bosReceipt = $this->model->findOrFail($id);
            DB::transaction(function () use ($bosReceipt, $validated) {
                $bosReceipt->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BosReceipt berhasil diperbarui.',
                $bosReceipt
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BosReceipt.',
                [
                    'exception' => $e,
                    'BosReceipt_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosReceipt gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bosReceipt = $this->model->findOrFail($id);

            DB::transaction(function () use ($bosReceipt) {
                $bosReceipt->delete();
            });

            return $this->successResponse(
                $request,
                'Data BosReceipt berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bosReceipt->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BosReceipt.',
                [
                    'exception' => $e,
                    'BosReceipt_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BosReceipt gagal dihapus.'
            );
        }
    }
}
