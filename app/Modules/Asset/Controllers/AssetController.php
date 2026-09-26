<?php

namespace App\Modules\Asset\Controllers;

use App\DataTables\AssetDataTable;
use App\Http\Controllers\MainController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\Asset;
use Models\ChartOfAccount;
use Throwable;
use App\Services\AssetDepreciationService;

class AssetController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Asset());
    }

    /* -----------------------------------------------------------------
     |  Index
     | ----------------------------------------------------------------- */
    public function index(Request $request, AssetDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Asset::index');
    }

    /* -----------------------------------------------------------------
     |  Create
     | ----------------------------------------------------------------- */
    public function create(): View
    {
        return view('Asset::create', $this->formData(new Asset()));
    }

    /* -----------------------------------------------------------------
     |  Store
     | ----------------------------------------------------------------- */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        try {
            $asset = DB::transaction(function () use ($validated) {
                // book_value TIDAK perlu di-set manual — sudah dihitung accessor
                return Asset::create($validated);
            });

            return $this->successResponse($request, 'Data aset tetap berhasil ditambahkan.', $asset);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan aset tetap.', [
                'exception' => $e,
                'data'      => $validated,
            ]);

            return $this->errorResponse($request, 'Data aset tetap gagal ditambahkan.');
        }
    }

    /* -----------------------------------------------------------------
     |  Edit
     | ----------------------------------------------------------------- */
    public function edit($id): View
    {
        $asset = Asset::findOrFail($id);

        return view('Asset::edit', array_merge(
            $this->formData($asset),
            compact('asset')
        ));
    }
    /* -----------------------------------------------------------------
     |  Update
     | ----------------------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $validated = $request->validate($this->rules($asset->id));

        try {
            DB::transaction(function () use ($asset, $validated) {
                $asset->update($validated);
            });

            return $this->successResponse($request, 'Data aset tetap berhasil diperbarui.', $asset->fresh());
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui aset tetap.', [
                'exception' => $e,
                'asset_id'  => $id,
            ]);

            return $this->errorResponse($request, 'Data aset tetap gagal diperbarui.');
        }
    }

    /* -----------------------------------------------------------------
     |  Show
     | ----------------------------------------------------------------- */
    public function show($id): View
    {
        $asset = Asset::with(['supplier', 'location', 'category', 'school', 'organization'])
            ->findOrFail($id);

        return view('Asset::show', compact('asset'));
    }

    /* -----------------------------------------------------------------
     |  Destroy
     | ----------------------------------------------------------------- */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $asset = Asset::findOrFail($id);

            if ($asset->depreciationLogs()->exists()) {
                return $this->errorResponse(
                    $request,
                    'Aset tidak dapat dihapus karena sudah memiliki penyusutan.'
                );
            }

            DB::transaction(fn() => $asset->delete());

            return $this->successResponse($request, 'Data aset tetap berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus aset tetap.', [
                'exception' => $e,
                'asset_id'  => $id,
            ]);

            return $this->errorResponse($request, 'Data aset tetap gagal dihapus.');
        }
    }

    /* -----------------------------------------------------------------
     |  Calculate Depreciation (single asset)
     | ----------------------------------------------------------------- */
    public function calculateDepreciation($id, AssetDepreciationService $service): JsonResponse
    {
        try {
            $asset = Asset::findOrFail($id);
            $now   = now();

            $calc = $service->calculateForPeriod($asset, $now->year, $now->month);

            return response()->json([
                'success' => true,
                'data'    => [
                    'monthly_depreciation' => $calc['depreciation_amount'],
                    'annual_depreciation'  => $calc['depreciation_amount'] * 12,
                    'current_book_value'   => $calc['closing_book_value'],
                    'accumulated'          => $calc['accumulated_depreciation'],
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menghitung penyusutan.', ['exception' => $e, 'asset_id' => $id]);
            return response()->json(['success' => false, 'message' => 'Gagal menghitung penyusutan.'], 500);
        }
    }

    /**
     * Endpoint untuk trigger batch posting bulanan.
     * Bisa dipanggil dari scheduler / manual.
     */
    public function postMonthlyDepreciation(Request $request, AssetDepreciationService $service): JsonResponse
    {
        $validated = $request->validate([
            'year'  => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $stats = $service->postMonthlyBatch(
            $validated['year'],
            $validated['month'],
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => "Posting selesai. Processed: {$stats['processed']}, Skipped: {$stats['skipped']}, Failed: {$stats['failed']}",
            'data'    => $stats,
        ]);
    }

    /* -----------------------------------------------------------------
     |  Get Assets by Category
     | ----------------------------------------------------------------- */
    public function getByCategory($category): JsonResponse
    {
        try {
            // FIX: kolom yang benar adalah asset_category_id, bukan 'category'
            $assets = Asset::where('asset_category_id', $category)
                ->where('status', 'active')
                ->get();

            return response()->json(['success' => true, 'data' => $assets]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil aset per kategori.', [
                'exception' => $e,
                'category'  => $category,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data.',
            ], 500);
        }
    }

    /* -----------------------------------------------------------------
     |  Private Helpers
     | ----------------------------------------------------------------- */

    /**
     * Aturan validasi bersama untuk store & update.
     */
    private function rules(?int $ignoreId = null): array
    {
        return [
            'organization_id' => 'nullable|exists:organizations,id',
            'school_id'       => 'nullable|exists:schools,id',

            'code'            => 'required|string|max:50|unique:assets,code' . ($ignoreId ? ",{$ignoreId}" : ''),
            'name'            => 'required|string|max:200',
            'asset_category_id' => 'required|exists:asset_categories,id',
            'supplier_id'     => 'nullable|exists:suppliers,id',
            'location_id'     => 'nullable|exists:locations,id',

            'brand'           => 'nullable|string|max:100',
            'model'           => 'nullable|string|max:100',
            'specification'   => 'nullable|string',
            'serial_number'   => 'nullable|string|max:100',

            'purchase_date'   => 'required|date',
            'purchase_price'  => 'required|numeric|min:0',
            'salvage_value'   => 'nullable|numeric|min:0',
            'useful_life_months' => 'required|integer|min:1',
            'depreciation_method' => 'required|in:straight_line,declining_balance,sum_of_years,double_declining',

            'asset_chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'accumulated_depreciation_chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'depreciation_expense_chart_of_account_id'     => 'required|exists:chart_of_accounts,id',

            'status'          => 'required|string|max:50',
            'condition'       => 'required|string|max:20',
            'assigned_to'     => 'nullable|exists:users,id',
            'warranty_end'    => 'nullable|date',

            'photo_url'       => 'nullable|string',
            'notes'           => 'nullable|string',
        ];
    }

    /**
     * Daftar akun COA untuk dropdown.
     */
    private function getAccountOptions()
    {
        return ChartOfAccount::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');
    }

    /**
     * Data dropdown untuk form create & edit.
     */
    private function formData(Asset $asset): array
    {
        return [
            'asset'         => $asset,
            'accounts'      => $this->getAccountOptions(),
            'categories'    => \Models\AssetCategory::orderBy('name')->pluck('name', 'id'),
            'suppliers'     => \Models\Supplier::orderBy('name')->pluck('name', 'id'),
            'locations'     => \Models\Location::orderBy('name')->pluck('name', 'id'),
            'organizations' => \Models\Organization::orderBy('name')->pluck('name', 'id'),
            'schools'       => \Models\School::orderBy('name')->pluck('name', 'id'),
            'users'         => \App\Models\User::orderBy('name')->pluck('name', 'id'),
        ];
    }
}
