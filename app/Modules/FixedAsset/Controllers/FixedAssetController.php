<?php

namespace App\Modules\FixedAsset\Controllers;

use App\DataTables\FixedAssetDataTable;
use App\Http\Controllers\MainController;
use Models\FixedAsset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Models\ChartOfAccount;

class FixedAssetController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FixedAsset());
    }

    public function index(Request $request, FixedAssetDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::fixed_asset.index');
    }

    public function create(): View
    {
        $asset = new FixedAsset();
        $accounts = ChartOfAccount::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($asset);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::fixed_asset.edit', compact('asset', 'forms', 'groupedForms', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_code' => 'required|string|max:255|unique:fixed_assets,asset_code',
            'asset_name' => 'required|string|max:255',
            'category' => 'required|in:building,vehicle,equipment,furniture,computer,software,other',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'salvage_value' => 'nullable|numeric|min:0',
            'useful_life' => 'required|integer|min:1',
            'depreciation_method' => 'required|in:straight_line,declining_balance,sum_of_years,double_declining',
            'chart_of_account_id' => 'required|exists:accounts,id',
            'depreciation_chart_of_account_id' => 'required|exists:accounts,id',
            'status' => 'required|in:active,depreciated,sold,disposed',
            'notes' => 'nullable|string',
        ]);

        try {
            $asset = DB::transaction(function () use ($validated) {
                $validated['book_value'] = $validated['purchase_price'];
                return FixedAsset::create($validated);
            });

            return $this->successResponse($request, 'Data aset tetap berhasil ditambahkan.', $asset);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan aset tetap.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data aset tetap gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $asset = $this->model->findOrFail($id);
        $accounts = ChartOfAccount::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($asset);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::fixed_asset.edit', compact('asset', 'forms', 'groupedForms', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $asset = $this->model->findOrFail($id);

        $validated = $request->validate([
            'asset_code' => 'required|string|max:255|unique:fixed_assets,asset_code,' . $id,
            'asset_name' => 'required|string|max:255',
            'category' => 'required|in:building,vehicle,equipment,furniture,computer,software,other',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'salvage_value' => 'nullable|numeric|min:0',
            'useful_life' => 'required|integer|min:1',
            'depreciation_method' => 'required|in:straight_line,declining_balance,sum_of_years,double_declining',
            'chart_of_account_id' => 'required|exists:accounts,id',
            'depreciation_chart_of_account_id' => 'required|exists:accounts,id',
            'status' => 'required|in:active,depreciated,sold,disposed',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($asset, $validated) {
                $asset->update($validated);
            });

            return $this->successResponse($request, 'Data aset tetap berhasil diperbarui.', $asset);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui aset tetap.', ['exception' => $e, 'asset_id' => $id]);
            return $this->errorResponse($request, 'Data aset tetap gagal diperbarui.');
        }
    }

    public function show($id): View
    {
        $asset = $this->model->with('depreciationLogs')->findOrFail($id);
        return view('Finance::fixed_asset.show', compact('asset'));
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $asset = $this->model->findOrFail($id);

            if ($asset->depreciationLogs()->exists()) {
                return $this->errorResponse($request, 'Aset tidak dapat dihapus karena sudah memiliki penyusutan.');
            }

            DB::transaction(function () use ($asset) {
                $asset->delete();
            });

            return $this->successResponse($request, 'Data aset tetap berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus aset tetap.', ['exception' => $e, 'asset_id' => $id]);
            return $this->errorResponse($request, 'Data aset tetap gagal dihapus.');
        }
    }

    public function calculateDepreciation($id): JsonResponse
    {
        try {
            $asset = $this->model->findOrFail($id);
            $depreciation = $this->calculateDepreciationAmount($asset);

            return response()->json([
                'success' => true,
                'data' => [
                    'annual_depreciation' => $depreciation,
                    'monthly_depreciation' => $depreciation / 12,
                    'current_book_value' => $asset->book_value,
                ]
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menghitung penyusutan.', ['exception' => $e, 'asset_id' => $id]);
            return response()->json(['success' => false, 'message' => 'Gagal menghitung penyusutan.'], 500);
        }
    }

    private function calculateDepreciationAmount(FixedAsset $asset): float
    {
        $depreciableAmount = $asset->purchase_price - $asset->salvage_value;

        switch ($asset->depreciation_method) {
            case 'straight_line':
                return $depreciableAmount / $asset->useful_life;

            case 'declining_balance':
                $rate = 1 - pow($asset->salvage_value / $asset->purchase_price, 1 / $asset->useful_life);
                return $asset->book_value * $rate;

            case 'sum_of_years':
                $sumYears = ($asset->useful_life * ($asset->useful_life + 1)) / 2;
                $remainingYears = $asset->useful_life - $this->getDepreciationYears($asset);
                return $depreciableAmount * ($remainingYears / $sumYears);

            case 'double_declining':
                $rate = 2 / $asset->useful_life;
                return $asset->book_value * $rate;

            default:
                return 0;
        }
    }

    private function getDepreciationYears(FixedAsset $asset): int
    {
        // Hitung tahun penyusutan yang sudah berjalan
        return $asset->depreciationLogs()->count();
    }

    public function getByCategory($category): JsonResponse
    {
        try {
            $assets = FixedAsset::where('category', $category)
                ->where('status', 'active')
                ->get();

            return response()->json(['success' => true, 'data' => $assets]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil aset per kategori.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
