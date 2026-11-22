<?php

namespace App\Modules\WalletTopup\Controllers;

use App\DataTables\WalletTopupDataTable;
use App\Http\Controllers\MainController;
use Models\WalletTopup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WalletTopupController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WalletTopup());
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
        WalletTopupDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /wallet-topup?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WalletTopup::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $walletTopup = new WalletTopup();
        $result = $this->getForm($walletTopup);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WalletTopup::create',
            compact('walletTopup', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'digital_wallet_id' => ['required', 'integer'],
            'amount' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'payment_reference' => ['required', 'string', 'max:255'],
            'expires_at' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'wallet_transaction_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WalletTopup::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WalletTopup berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WalletTopup.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WalletTopup gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $walletTopup = $this->model->findOrFail($id);
        $result = $this->getForm($walletTopup);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WalletTopup::edit',
            compact('walletTopup', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'digital_wallet_id' => ['required', 'integer'],
            'amount' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'payment_reference' => ['required', 'string', 'max:255'],
            'expires_at' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'wallet_transaction_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $walletTopup = $this->model->findOrFail($id);
            DB::transaction(function () use ($walletTopup, $validated) {
                $walletTopup->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WalletTopup berhasil diperbarui.',
                $walletTopup
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui WalletTopup.',
                [
                    'exception' => $e,
                    'WalletTopup_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WalletTopup gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $walletTopup = $this->model->findOrFail($id);

            DB::transaction(function () use ($walletTopup) {
                $walletTopup->delete();
            });

            return $this->successResponse(
                $request,
                'Data WalletTopup berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $walletTopup->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WalletTopup.',
                [
                    'exception' => $e,
                    'WalletTopup_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WalletTopup gagal dihapus.'
            );
        }
    }
}
