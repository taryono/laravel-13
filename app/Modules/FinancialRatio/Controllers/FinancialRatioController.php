<?php

namespace App\Modules\FinancialRatio\Controllers;

use App\DataTables\FinancialRatioDataTable;
use App\Http\Controllers\MainController;
use Models\FinancialRatio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 

class FinancialRatioController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FinancialRatio());
    }

    public function index(Request $request, FinancialRatioDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('System::financial_ratio.index');
    }

    public function create(): View
    {
        $ratio = new FinancialRatio();
        $result = $this->getForm($ratio);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::financial_ratio.edit', compact('ratio', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|date_format:Y-m',
            'ratio_type' => 'required|in:liquidity,solvency,profitability,efficiency,leverage',
            'ratio_name' => 'required|string|max:100',
            'value' => 'required|numeric',
            'formula' => 'nullable|string',
            'benchmark' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        try {
            $ratio = DB::transaction(function () use ($validated) {
                return FinancialRatio::create($validated);
            });

            return $this->successResponse($request, 'Data financial ratio berhasil ditambahkan.', $ratio);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan financial ratio.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data financial ratio gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $ratio = $this->model->findOrFail($id);
        $result = $this->getForm($ratio);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::financial_ratio.edit', compact('ratio', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $ratio = $this->model->findOrFail($id);

        $validated = $request->validate([
            'period' => 'required|date_format:Y-m',
            'ratio_type' => 'required|in:liquidity,solvency,profitability,efficiency,leverage',
            'ratio_name' => 'required|string|max:100',
            'value' => 'required|numeric',
            'formula' => 'nullable|string',
            'benchmark' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($ratio, $validated) {
                $ratio->update($validated);
            });

            return $this->successResponse($request, 'Data financial ratio berhasil diperbarui.', $ratio);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui financial ratio.', ['exception' => $e, 'ratio_id' => $id]);
            return $this->errorResponse($request, 'Data financial ratio gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $ratio = $this->model->findOrFail($id);
            DB::transaction(function () use ($ratio) {
                $ratio->delete();
            });

            return $this->successResponse($request, 'Data financial ratio berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus financial ratio.', ['exception' => $e, 'ratio_id' => $id]);
            return $this->errorResponse($request, 'Data financial ratio gagal dihapus.');
        }
    }

    public function calculate(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period');
            
            // Calculate ratios based on financial data
            // Implementasi perhitungan sesuai kebutuhan
            
            $ratios = [
                'liquidity' => [
                    'current_ratio' => 2.5,
                    'quick_ratio' => 1.8,
                ],
                'profitability' => [
                    'net_profit_margin' => 15.5,
                    'return_on_assets' => 12.3,
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $ratios,
                'period' => $period,
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menghitung financial ratio.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal menghitung financial ratio.'], 500);
        }
    }

    public function getByPeriod($period): JsonResponse
    {
        try {
            $ratios = FinancialRatio::where('period', $period)->get();
            return response()->json(['success' => true, 'data' => $ratios]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil financial ratio.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getByType($type): JsonResponse
    {
        try {
            $ratios = FinancialRatio::where('ratio_type', $type)
                ->orderBy('period', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $ratios]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil financial ratio.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
