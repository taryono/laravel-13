<?php

namespace App\Modules\PurchaseRequest\Controllers;

use App\DataTables\PurchaseRequestDataTable;
use App\Http\Controllers\MainController;
use Models\PurchaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PurchaseRequestController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PurchaseRequest());
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
        PurchaseRequestDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /purchase-request?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PurchaseRequest::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $purchaseRequest = new PurchaseRequest();
        $result = $this->getForm($purchaseRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PurchaseRequest::create',
            compact('purchaseRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'rkas_item_id' => ['required', 'integer'],
            'request_number' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'requested_by' => ['required', 'string', 'max:255'],
            'requested_at' => ['required', 'string', 'max:255'],
            'needed_by' => ['required', 'string', 'max:255'],
            'total_estimated_amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejection_notes' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PurchaseRequest::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PurchaseRequest berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PurchaseRequest.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseRequest gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $purchaseRequest = $this->model->findOrFail($id);
        $result = $this->getForm($purchaseRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PurchaseRequest::edit',
            compact('purchaseRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'rkas_item_id' => ['required', 'integer'],
            'request_number' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'requested_by' => ['required', 'string', 'max:255'],
            'requested_at' => ['required', 'string', 'max:255'],
            'needed_by' => ['required', 'string', 'max:255'],
            'total_estimated_amount' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejection_notes' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $purchaseRequest = $this->model->findOrFail($id);
            DB::transaction(function () use ($purchaseRequest, $validated) {
                $purchaseRequest->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PurchaseRequest berhasil diperbarui.',
                $purchaseRequest
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PurchaseRequest.',
                [
                    'exception' => $e,
                    'PurchaseRequest_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseRequest gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $purchaseRequest = $this->model->findOrFail($id);

            DB::transaction(function () use ($purchaseRequest) {
                $purchaseRequest->delete();
            });

            return $this->successResponse(
                $request,
                'Data PurchaseRequest berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $purchaseRequest->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PurchaseRequest.',
                [
                    'exception' => $e,
                    'PurchaseRequest_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseRequest gagal dihapus.'
            );
        }
    }
}
