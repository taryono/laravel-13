<?php

namespace App\Modules\WalletCard\Controllers;

use App\DataTables\WalletCardDataTable;
use App\Http\Controllers\MainController;
use Models\WalletCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WalletCardController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WalletCard());
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
        WalletCardDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /wallet-card?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WalletCard::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $walletCard = new WalletCard();
        $result = $this->getForm($walletCard);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WalletCard::create',
            compact('walletCard', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'digital_wallet_id' => ['required', 'integer'],
            'card_uid' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'issued_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WalletCard::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WalletCard berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WalletCard.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WalletCard gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $walletCard = $this->model->findOrFail($id);
        $result = $this->getForm($walletCard);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WalletCard::edit',
            compact('walletCard', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'digital_wallet_id' => ['required', 'integer'],
            'card_uid' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'issued_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $walletCard = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $walletCard,
                $validated
            ) {
                $walletCard->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WalletCard berhasil diperbarui.',
                $walletCard
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui WalletCard.',
                [
                    'exception' => $e,
                    'WalletCard_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WalletCard gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $walletCard = $this->model->findOrFail($id);

            DB::transaction(function () use ($walletCard) {
                $walletCard->delete();
            });

            return $this->successResponse(
                $request,
                'Data WalletCard berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $walletCard->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WalletCard.',
                [
                    'exception' => $e,
                    'WalletCard_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WalletCard gagal dihapus.'
            );
        }
    } 
}
