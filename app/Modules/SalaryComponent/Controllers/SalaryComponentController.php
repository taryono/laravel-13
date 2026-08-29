<?php

namespace App\Modules\SalaryComponent\Controllers;

use App\DataTables\SalaryComponentDataTable;
use App\Http\Controllers\MainController;
use Models\SalaryComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 
class SalaryComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SalaryComponent());
    }

    public function index(Request $request, SalaryComponentDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::salary_component.index');
    }

    public function create(): View
    {
        $component = new SalaryComponent();
        $result = $this->getForm($component);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::salary_component.edit', compact('component', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:salary_components,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:penghasilan,potongan',
            'nature' => 'required|in:tetap,tidak_tetap',
            'calculation_method' => 'required|in:fixed,percentage,formula',
            'default_value' => 'nullable|numeric|min:0',
            'formula' => 'nullable|string|required_if:calculation_method,formula',
            'is_taxable' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            $component = DB::transaction(function () use ($validated) {
                return SalaryComponent::create($validated);
            });

            return $this->successResponse($request, 'Data komponen gaji berhasil ditambahkan.', $component);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan komponen gaji.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data komponen gaji gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $component = $this->model->findOrFail($id);
        $result = $this->getForm($component);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::salary_component.edit', compact('component', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $component = $this->model->findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:salary_components,code,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:penghasilan,potongan',
            'nature' => 'required|in:tetap,tidak_tetap',
            'calculation_method' => 'required|in:fixed,percentage,formula',
            'default_value' => 'nullable|numeric|min:0',
            'formula' => 'nullable|string|required_if:calculation_method,formula',
            'is_taxable' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($component, $validated) {
                $component->update($validated);
            });

            return $this->successResponse($request, 'Data komponen gaji berhasil diperbarui.', $component);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui komponen gaji.', ['exception' => $e, 'component_id' => $id]);
            return $this->errorResponse($request, 'Data komponen gaji gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $component = $this->model->findOrFail($id);

            if ($component->employeeComponents()->exists()) {
                return $this->errorResponse($request, 'Komponen gaji tidak dapat dihapus karena masih digunakan.');
            }

            DB::transaction(function () use ($component) {
                $component->delete();
            });

            return $this->successResponse($request, 'Data komponen gaji berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus komponen gaji.', ['exception' => $e, 'component_id' => $id]);
            return $this->errorResponse($request, 'Data komponen gaji gagal dihapus.');
        }
    }

    public function toggleActive(Request $request, $id)
    {
        try {
            $component = $this->model->findOrFail($id);
            $component->update(['is_active' => !$component->is_active]);

            return $this->successResponse(
                $request,
                'Status komponen gaji berhasil diubah.',
                $component
            );
        } catch (Throwable $e) {
            Log::error('Gagal mengubah status komponen gaji.', ['exception' => $e, 'component_id' => $id]);
            return $this->errorResponse($request, 'Gagal mengubah status komponen gaji.');
        }
    }
}