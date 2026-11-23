<?php

namespace App\Modules\Currency\Controllers;

use App\DataTables\CurrencyDataTable;
use App\Http\Controllers\MainController;
use Models\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CurrencyController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Currency());
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
        CurrencyDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /currency?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Currency::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $currency = new Currency();
        return view(
            'Currency::create',
            compact('currency')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:255'],
            'decimal_places' => ['required', 'string', 'max:255'],
            'is_base' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Currency::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Currency berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Currency.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Currency gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $currency = $this->model->findOrFail($id);
        return view(
            'Currency::edit',
            compact('currency')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:255'],
            'decimal_places' => ['required', 'string', 'max:255'],
            'is_base' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $currency = $this->model->findOrFail($id);
            DB::transaction(function () use ($currency, $validated) {
                $currency->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Currency berhasil diperbarui.',
                $currency
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Currency.',
                [
                    'exception' => $e,
                    'Currency_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Currency gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $currency = $this->model->findOrFail($id);

            DB::transaction(function () use ($currency) {
                $currency->delete();
            });

            return $this->successResponse(
                $request,
                'Data Currency berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $currency->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Currency.',
                [
                    'exception' => $e,
                    'Currency_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Currency gagal dihapus.'
            );
        }
    }
}
