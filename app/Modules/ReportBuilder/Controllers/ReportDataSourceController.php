<?php

namespace App\Modules\ReportBuilder\Controllers;

use App\Http\Controllers\MainController;
use Models\ReportDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ReportDataSourceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportDataSource());
    }

    /**
     * Display a listing of data sources.
     */
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $search = $request->query('search');

        $query = ReportDataSource::query()->orderBy('order')->orderBy('name');

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('key', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $dataSources = $query->get();

        $categories = ReportDataSource::distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->filter()
            ->values()
            ->toArray();

        $stats = [
            'total'      => ReportDataSource::count(),
            'active'     => ReportDataSource::active()->count(),
            'categories' => count($categories),
            'system'     => ReportDataSource::where('is_system', true)->count(),
        ];

        return view('ReportBuilder::data-sources.index', compact(
            'dataSources',
            'categories',
            'category',
            'search',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new data source.
     */
    public function create(): View
    {
        $dataSource = new ReportDataSource([
            'category'    => 'custom',
            'is_active'   => true,
            'is_system'   => false,
            'order'       => ReportDataSource::max('order') + 1,
            'fields'      => [
                ['field' => 'index', 'label' => 'No', 'type' => 'number', 'default_width' => '8%', 'default_align' => 'center'],
                ['field' => 'name', 'label' => 'Nama / Judul', 'type' => 'string', 'default_width' => '50%', 'default_align' => 'left'],
                ['field' => 'score', 'label' => 'Nilai', 'type' => 'number', 'default_width' => '20%', 'default_align' => 'center'],
                ['field' => 'description', 'label' => 'Keterangan', 'type' => 'string', 'default_width' => '22%', 'default_align' => 'left'],
            ],
            'sample_data' => [
                ['index' => 1, 'name' => 'Contoh Baris 1', 'score' => 90, 'description' => 'Sangat Baik'],
                ['index' => 2, 'name' => 'Contoh Baris 2', 'score' => 85, 'description' => 'Baik'],
            ],
        ]);

        $categories = ['academic', 'pesantren', 'tahfidz', 'attendance', 'character', 'custom'];

        return view('ReportBuilder::data-sources.create', compact('dataSource', 'categories'));
    }

    /**
     * Store a newly created data source in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key'         => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/', 'unique:report_data_sources,key'],
            'name'        => ['required', 'string', 'max:100'],
            'category'    => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'fields'      => ['nullable'],
            'sample_data' => ['nullable'],
            'is_active'   => ['nullable'],
            'order'       => ['nullable', 'integer', 'min:0'],
        ]);

        // Process fields JSON or Array
        if (!empty($validated['fields']) && is_string($validated['fields'])) {
            $decoded = json_decode($validated['fields'], true);
            $validated['fields'] = is_array($decoded) ? $decoded : [];
        }

        // Process sample_data JSON or Array
        if (!empty($validated['sample_data']) && is_string($validated['sample_data'])) {
            $decoded = json_decode($validated['sample_data'], true);
            $validated['sample_data'] = is_array($decoded) ? $decoded : [];
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_system'] = false;
        $validated['order'] = (int) ($validated['order'] ?? 0);
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        try {
            $model = ReportDataSource::create($validated);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sumber Data berhasil ditambahkan.',
                    'data'    => $model,
                ]);
            }

            return redirect()->route('admin.report-builder.data-sources.index')
                ->with('success', "Sumber Data '{$model->name}' berhasil ditambahkan.");
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan ReportDataSource.', [
                'exception' => $e,
                'data'      => $validated,
                'user_id'   => Auth::id(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan Sumber Data: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal menambahkan Sumber Data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified data source.
     */
    public function edit($dataSource): View
    {
        if (!($dataSource instanceof ReportDataSource)) {
            $dataSource = ReportDataSource::findOrFail($dataSource);
        }

        $categories = ['academic', 'pesantren', 'tahfidz', 'attendance', 'character', 'custom'];

        return view('ReportBuilder::data-sources.edit', compact('dataSource', 'categories'));
    }

    /**
     * Update the specified data source in storage.
     */
    public function update(Request $request, $dataSource)
    {
        if (!($dataSource instanceof ReportDataSource)) {
            $dataSource = ReportDataSource::findOrFail($dataSource);
        }

        $validated = $request->validate([
            'key'         => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/', Rule::unique('report_data_sources', 'key')->ignore($dataSource->id)],
            'name'        => ['required', 'string', 'max:100'],
            'category'    => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'fields'      => ['nullable'],
            'sample_data' => ['nullable'],
            'is_active'   => ['nullable'],
            'order'       => ['nullable', 'integer', 'min:0'],
        ]);

        if (!empty($validated['fields']) && is_string($validated['fields'])) {
            $decoded = json_decode($validated['fields'], true);
            $validated['fields'] = is_array($decoded) ? $decoded : [];
        }

        if (!empty($validated['sample_data']) && is_string($validated['sample_data'])) {
            $decoded = json_decode($validated['sample_data'], true);
            $validated['sample_data'] = is_array($decoded) ? $decoded : [];
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = (int) ($validated['order'] ?? 0);
        $validated['updated_by'] = Auth::id();

        try {
            $dataSource->update($validated);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sumber Data berhasil diperbarui.',
                    'data'    => $dataSource,
                ]);
            }

            return redirect()->route('admin.report-builder.data-sources.index')
                ->with('success', "Sumber Data '{$dataSource->name}' berhasil diperbarui.");
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui ReportDataSource.', [
                'exception' => $e,
                'id'        => $dataSource->id,
                'data'      => $validated,
                'user_id'   => Auth::id(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui Sumber Data: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal memperbarui Sumber Data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified data source from storage (Soft Delete).
     */
    public function destroy(Request $request, $dataSource)
    {
        if (!($dataSource instanceof ReportDataSource)) {
            $dataSource = ReportDataSource::findOrFail($dataSource);
        }

        try {
            $name = $dataSource->name;
            $dataSource->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Sumber Data '{$name}' berhasil dihapus.",
                ]);
            }

            return redirect()->route('admin.report-builder.data-sources.index')
                ->with('success', "Sumber Data '{$name}' berhasil dihapus.");
        } catch (Throwable $e) {
            Log::error('Gagal menghapus ReportDataSource.', [
                'exception' => $e,
                'id'        => $dataSource->id,
                'user_id'   => Auth::id(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus Sumber Data.',
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus Sumber Data.');
        }
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($dataSource): JsonResponse
    {
        if (!($dataSource instanceof ReportDataSource)) {
            $dataSource = ReportDataSource::findOrFail($dataSource);
        }

        $dataSource->is_active = !$dataSource->is_active;
        $dataSource->updated_by = Auth::id();
        $dataSource->save();

        return response()->json([
            'success'   => true,
            'is_active' => $dataSource->is_active,
            'message'   => 'Status Sumber Data berhasil diperbarui.',
        ]);
    }

    /**
     * API: List of active data sources for client-side consumption.
     */
    public function apiList(): JsonResponse
    {
        $sources = ReportDataSource::active()
            ->orderBy('order')
            ->orderBy('name')
            ->get(['id', 'key', 'name', 'category', 'description', 'fields', 'sample_data']);

        return response()->json([
            'success' => true,
            'data'    => $sources,
        ]);
    }
}
