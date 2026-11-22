<?php

namespace App\Modules\PaymentChannel\Controllers;

use App\DataTables\PaymentChannelDataTable;
use App\Http\Controllers\MainController;
use Models\PaymentChannel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PaymentChannelController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PaymentChannel());
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
        PaymentChannelDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /payment-channel?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PaymentChannel::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $paymentChannel = new PaymentChannel();
        $result = $this->getForm($paymentChannel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentChannel::create',
            compact('paymentChannel', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_gateway_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'channel_type' => ['required', 'string', 'max:255'],
            'bank_code' => ['required', 'string', 'max:255'],
            'logo_url' => ['required', 'string', 'max:255'],
            'admin_fee' => ['required', 'string', 'max:255'],
            'admin_fee_type' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'string', 'max:255'],
            'max_amount' => ['required', 'string', 'max:255'],
            'expiry_minutes' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PaymentChannel::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentChannel berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PaymentChannel.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentChannel gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $paymentChannel = $this->model->findOrFail($id);
        $result = $this->getForm($paymentChannel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PaymentChannel::edit',
            compact('paymentChannel', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_gateway_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'channel_type' => ['required', 'string', 'max:255'],
            'bank_code' => ['required', 'string', 'max:255'],
            'logo_url' => ['required', 'string', 'max:255'],
            'admin_fee' => ['required', 'string', 'max:255'],
            'admin_fee_type' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'string', 'max:255'],
            'max_amount' => ['required', 'string', 'max:255'],
            'expiry_minutes' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'config' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $paymentChannel = $this->model->findOrFail($id);
            DB::transaction(function () use ($paymentChannel, $validated) {
                $paymentChannel->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PaymentChannel berhasil diperbarui.',
                $paymentChannel
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PaymentChannel.',
                [
                    'exception' => $e,
                    'PaymentChannel_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentChannel gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $paymentChannel = $this->model->findOrFail($id);

            DB::transaction(function () use ($paymentChannel) {
                $paymentChannel->delete();
            });

            return $this->successResponse(
                $request,
                'Data PaymentChannel berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $paymentChannel->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PaymentChannel.',
                [
                    'exception' => $e,
                    'PaymentChannel_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PaymentChannel gagal dihapus.'
            );
        }
    }
}
