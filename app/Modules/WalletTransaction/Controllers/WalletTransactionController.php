<?php

namespace App\Modules\WalletTransaction\Controllers;

use App\DataTables\WalletTransactionDataTable;
use App\Http\Controllers\MainController;
use Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WalletTransactionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WalletTransaction());
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
        WalletTransactionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /wallet-transaction?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WalletTransaction::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $walletTransaction = new WalletTransaction();
        $result = $this->getForm($walletTransaction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WalletTransaction::create',
            compact('walletTransaction', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'digital_wallet_id' => ['required', 'integer'],
            'transaction_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'balance_before' => ['required', 'string', 'max:255'],
            'balance_after' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WalletTransaction::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WalletTransaction berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WalletTransaction.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WalletTransaction gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $walletTransaction = $this->model->findOrFail($id);
        $result = $this->getForm($walletTransaction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WalletTransaction::edit',
            compact('walletTransaction', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'digital_wallet_id' => ['required', 'integer'],
            'transaction_type' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'balance_before' => ['required', 'string', 'max:255'],
            'balance_after' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
        ]);

        try {
            $walletTransaction = $this->model->findOrFail($id);
            DB::transaction(function () use ($walletTransaction, $validated) {
                $walletTransaction->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WalletTransaction berhasil diperbarui.',
                $walletTransaction
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui WalletTransaction.',
                [
                    'exception' => $e,
                    'WalletTransaction_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WalletTransaction gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $walletTransaction = $this->model->findOrFail($id);

            DB::transaction(function () use ($walletTransaction) {
                $walletTransaction->delete();
            });

            return $this->successResponse(
                $request,
                'Data WalletTransaction berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $walletTransaction->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WalletTransaction.',
                [
                    'exception' => $e,
                    'WalletTransaction_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WalletTransaction gagal dihapus.'
            );
        }
    }
}
