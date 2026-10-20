<?php

namespace App\Modules\Vendor\Controllers;

use App\DataTables\VendorDataTable;
use App\Http\Controllers\MainController;
use Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class VendorController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Vendor());
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
        VendorDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /vendor?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Vendor::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vendor = new Vendor();
        $result = $this->getForm($vendor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Vendor::create',
            compact('vendor', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['required', 'string', 'max:255'],
            'vendor_type' => ['required', 'string', 'max:255'],
            'npwp' => ['required', 'string', 'max:255'],
            'is_pkp' => ['required', 'string', 'max:255'],
            'pkp_number' => ['required', 'string', 'max:255'],
            'ppn_rate' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'website' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'payment_terms_days' => ['required', 'string', 'max:255'],
            'payable_account_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Vendor::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Vendor berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Vendor.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Vendor gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $vendor = $this->model->findOrFail($id);
        $result = $this->getForm($vendor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Vendor::edit',
            compact('vendor', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['required', 'string', 'max:255'],
            'vendor_type' => ['required', 'string', 'max:255'],
            'npwp' => ['required', 'string', 'max:255'],
            'is_pkp' => ['required', 'string', 'max:255'],
            'pkp_number' => ['required', 'string', 'max:255'],
            'ppn_rate' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'website' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'payment_terms_days' => ['required', 'string', 'max:255'],
            'payable_account_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $vendor = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $vendor,
                $validated
            ) {
                $vendor->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Vendor berhasil diperbarui.',
                $vendor
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Vendor.',
                [
                    'exception' => $e,
                    'Vendor_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Vendor gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $vendor = $this->model->findOrFail($id);

            DB::transaction(function () use ($vendor) {
                $vendor->delete();
            });

            return $this->successResponse(
                $request,
                'Data Vendor berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $vendor->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Vendor.',
                [
                    'exception' => $e,
                    'Vendor_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Vendor gagal dihapus.'
            );
        }
    } 
}
