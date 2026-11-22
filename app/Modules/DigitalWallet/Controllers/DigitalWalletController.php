<?php

namespace App\Modules\DigitalWallet\Controllers;

use App\DataTables\DigitalWalletDataTable;
use App\Http\Controllers\MainController;
use Models\DigitalWallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DigitalWalletController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DigitalWallet());
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
        DigitalWalletDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /digital-wallet?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DigitalWallet::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $digitalWallet = new DigitalWallet();
        $result = $this->getForm($digitalWallet);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DigitalWallet::create',
            compact('digitalWallet', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'owner_type' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'integer'],
            'wallet_number' => ['required', 'string', 'max:255'],
            'balance' => ['required', 'string', 'max:255'],
            'daily_spending_limit' => ['required', 'string', 'max:255'],
            'is_locked' => ['required', 'string', 'max:255'],
            'lock_reason' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DigitalWallet::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DigitalWallet berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DigitalWallet.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalWallet gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $digitalWallet = $this->model->findOrFail($id);
        $result = $this->getForm($digitalWallet);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DigitalWallet::edit',
            compact('digitalWallet', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'owner_type' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'integer'],
            'wallet_number' => ['required', 'string', 'max:255'],
            'balance' => ['required', 'string', 'max:255'],
            'daily_spending_limit' => ['required', 'string', 'max:255'],
            'is_locked' => ['required', 'string', 'max:255'],
            'lock_reason' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
        ]);

        try {
            $digitalWallet = $this->model->findOrFail($id);
            DB::transaction(function () use ($digitalWallet, $validated) {
                $digitalWallet->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DigitalWallet berhasil diperbarui.',
                $digitalWallet
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DigitalWallet.',
                [
                    'exception' => $e,
                    'DigitalWallet_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalWallet gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $digitalWallet = $this->model->findOrFail($id);

            DB::transaction(function () use ($digitalWallet) {
                $digitalWallet->delete();
            });

            return $this->successResponse(
                $request,
                'Data DigitalWallet berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $digitalWallet->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DigitalWallet.',
                [
                    'exception' => $e,
                    'DigitalWallet_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalWallet gagal dihapus.'
            );
        }
    }
}
