<?php

namespace App\Modules\ExchangeRate\Controllers;

use App\DataTables\ExchangeRateDataTable;
use App\Http\Controllers\MainController;
use Models\ExchangeRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExchangeRateController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExchangeRate());
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
        ExchangeRateDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /exchange-rate?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExchangeRate::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $exchangeRate = new ExchangeRate();
        $result = $this->getForm($exchangeRate);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExchangeRate::create',
            compact('exchangeRate', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency_id' => ['required', 'integer'],
            'base_currency_id' => ['required', 'integer'],
            'rate_date' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExchangeRate::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExchangeRate berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExchangeRate.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExchangeRate gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $exchangeRate = $this->model->findOrFail($id);
        $result = $this->getForm($exchangeRate);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExchangeRate::edit',
            compact('exchangeRate', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'currency_id' => ['required', 'integer'],
            'base_currency_id' => ['required', 'integer'],
            'rate_date' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $exchangeRate = $this->model->findOrFail($id);
            DB::transaction(function () use ($exchangeRate, $validated) {
                $exchangeRate->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExchangeRate berhasil diperbarui.',
                $exchangeRate
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ExchangeRate.',
                [
                    'exception' => $e,
                    'ExchangeRate_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExchangeRate gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $exchangeRate = $this->model->findOrFail($id);

            DB::transaction(function () use ($exchangeRate) {
                $exchangeRate->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExchangeRate berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $exchangeRate->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExchangeRate.',
                [
                    'exception' => $e,
                    'ExchangeRate_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExchangeRate gagal dihapus.'
            );
        }
    }
}
