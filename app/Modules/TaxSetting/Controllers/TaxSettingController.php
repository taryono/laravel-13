<?php

namespace App\Modules\TaxSetting\Controllers;

use App\DataTables\TaxSettingDataTable;
use App\Http\Controllers\MainController;
use Models\TaxSetting;
use Models\TaxType;
use Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TaxSettingController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TaxSetting());
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
        TaxSettingDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /tax-setting?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TaxSetting::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $taxSetting = new TaxSetting();
        $taxTypes = TaxType::all();
        $organizations = Organization::all();
        return view(
            'TaxSetting::create',
            compact('taxSetting', 'taxTypes', 'organizations')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tax_code' => ['required', 'string', 'max:255'],
            'tax_name' => ['required', 'string', 'max:255'],
            'tax_rate' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'string', 'max:255'],
            'max_amount' => ['required', 'string', 'max:255'],
            'effective_date' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TaxSetting::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TaxSetting berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TaxSetting.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TaxSetting gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $taxSetting = $this->model->findOrFail($id);
        $taxTypes = TaxType::all();
        $organizations = Organization::all();
        return view(
            'TaxSetting::edit',
            compact('taxSetting', 'taxTypes', 'organizations')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tax_code' => ['required', 'string', 'max:255'],
            'tax_name' => ['required', 'string', 'max:255'],
            'tax_rate' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'string', 'max:255'],
            'max_amount' => ['required', 'string', 'max:255'],
            'effective_date' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $taxSetting = $this->model->findOrFail($id);
            DB::transaction(function () use ($taxSetting, $validated) {
                $taxSetting->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TaxSetting berhasil diperbarui.',
                $taxSetting
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui TaxSetting.',
                [
                    'exception' => $e,
                    'TaxSetting_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TaxSetting gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $taxSetting = $this->model->findOrFail($id);

            DB::transaction(function () use ($taxSetting) {
                $taxSetting->delete();
            });

            return $this->successResponse(
                $request,
                'Data TaxSetting berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $taxSetting->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TaxSetting.',
                [
                    'exception' => $e,
                    'TaxSetting_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TaxSetting gagal dihapus.'
            );
        }
    }
}
