<?php

namespace App\Modules\PaymentGateway\Controllers;

use App\DataTables\PaymentGatewayDataTable;
use App\Http\Controllers\MainController;
use Models\PaymentGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PaymentGatewayController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PaymentGateway());
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
        PaymentGatewayDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payment-gateway?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PaymentGateway::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $paymentGateway = new PaymentGateway();
        $result = $this->getForm($paymentGateway);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentGateway::create',
            compact('paymentGateway', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'provider' => ['required', 'string', 'max:255'],
            'environment' => ['required', 'string', 'max:255'],
            'merchant_id' => ['required', 'integer'],
            'client_key' => ['required', 'string', 'max:255'],
            'server_key' => ['required', 'string', 'max:255'],
            'is_production' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
            'default_currency_id' => ['required', 'integer'],
            'expiry_minutes' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PaymentGateway::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentGateway berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PaymentGateway.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentGateway gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paymentGateway = $this->model->findOrFail($id);
        $result = $this->getForm($paymentGateway);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentGateway::edit',
            compact('paymentGateway', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'provider' => ['required', 'string', 'max:255'],
            'environment' => ['required', 'string', 'max:255'],
            'merchant_id' => ['required', 'integer'],
            'client_key' => ['required', 'string', 'max:255'],
            'server_key' => ['required', 'string', 'max:255'],
            'is_production' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'string', 'max:255'],
            'default_currency_id' => ['required', 'integer'],
            'expiry_minutes' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $paymentGateway = $this->model->findOrFail($id);
            DB::transaction(function () use ($paymentGateway, $validated) {
                $paymentGateway->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentGateway berhasil diperbarui.',
                $paymentGateway
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PaymentGateway.',
                [
                    'exception' => $e,
                    'PaymentGateway_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentGateway gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $paymentGateway = $this->model->findOrFail($id);

            DB::transaction(function () use ($paymentGateway) {
                $paymentGateway->delete();
            });

            return $this->successResponse(
                $request,
                'Data PaymentGateway berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $paymentGateway->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PaymentGateway.',
                [
                    'exception' => $e,
                    'PaymentGateway_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentGateway gagal dihapus.'
            );
        }
    }
}
