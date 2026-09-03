<?php

namespace App\Modules\SalaryStructure\Controllers;

use App\DataTables\SalaryStructureDataTable;
use App\Http\Controllers\MainController;
use Models\SalaryStructure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SalaryStructureController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SalaryStructure());
    }

    public function index(Request $request, SalaryStructureDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('SalaryStructure::index');
    }

    public function create(): View
    {
        $structure = new SalaryStructure();
        $positions = Position::where('is_active', 1)->pluck('name', 'id');
        $components = SalaryComponent::where('is_active', 1)->get();

        $result = $this->getForm($structure);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('SalaryStructure::edit', compact('structure', 'forms', 'groupedForms', 'positions', 'components'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:salary_structures,code',
            'name' => 'required|string|max:255',
            'position_id' => 'nullable|exists:positions,id',
            'description' => 'nullable|string',
            'components' => 'nullable|array',
            'components.*.id' => 'exists:salary_components,id',
            'components.*.value' => 'required|numeric|min:0',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            $structure = DB::transaction(function () use ($validated) {
                $structure = SalaryStructure::create($validated);

                // Attach components
                if (!empty($validated['components'])) {
                    foreach ($validated['components'] as $component) {
                        $structure->components()->attach($component['id'], [
                            'value' => $component['value'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                return $structure;
            });

            return $this->successResponse($request, 'Data struktur gaji berhasil ditambahkan.', $structure);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan struktur gaji.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data struktur gaji gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $structure = $this->model->with('components')->findOrFail($id);
        $positions = Position::where('is_active', 1)->pluck('name', 'id');
        $components = SalaryComponent::where('is_active', 1)->get();

        $result = $this->getForm($structure);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('SalaryStructure::edit', compact('structure', 'forms', 'groupedForms', 'positions', 'components'));
    }

    public function update(Request $request, $id)
    {
        $structure = $this->model->findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:salary_structures,code,' . $id,
            'name' => 'required|string|max:255',
            'position_id' => 'nullable|exists:positions,id',
            'description' => 'nullable|string',
            'components' => 'nullable|array',
            'components.*.id' => 'exists:salary_components,id',
            'components.*.value' => 'required|numeric|min:0',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($structure, $validated) {
                $structure->update($validated);

                // Sync components
                $syncData = [];
                if (!empty($validated['components'])) {
                    foreach ($validated['components'] as $component) {
                        $syncData[$component['id']] = ['value' => $component['value']];
                    }
                }
                $structure->components()->sync($syncData);
            });

            return $this->successResponse($request, 'Data struktur gaji berhasil diperbarui.', $structure);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui struktur gaji.', ['exception' => $e, 'structure_id' => $id]);
            return $this->errorResponse($request, 'Data struktur gaji gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $structure = $this->model->findOrFail($id);

            DB::transaction(function () use ($structure) {
                $structure->components()->detach();
                $structure->delete();
            });

            return $this->successResponse($request, 'Data struktur gaji berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus struktur gaji.', ['exception' => $e, 'structure_id' => $id]);
            return $this->errorResponse($request, 'Data struktur gaji gagal dihapus.');
        }
    }

    public function duplicate(Request $request, $id)
    {
        try {
            $original = $this->model->with('components')->findOrFail($id);

            $newStructure = DB::transaction(function () use ($original) {
                // Create new structure
                $new = $original->replicate();
                $new->code = $original->code . '-copy';
                $new->name = $original->name . ' (Copy)';
                $new->is_default = false;
                $new->save();

                // Copy components
                foreach ($original->components as $component) {
                    $new->components()->attach($component->id, [
                        'value' => $component->pivot->value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                return $new;
            });

            return $this->successResponse(
                $request,
                'Data struktur gaji berhasil diduplikasi.',
                $newStructure
            );
        } catch (Throwable $e) {
            Log::error('Gagal menduplikasi struktur gaji.', ['exception' => $e, 'structure_id' => $id]);
            return $this->errorResponse($request, 'Gagal menduplikasi struktur gaji.');
        }
    }
}