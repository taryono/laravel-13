<?php

namespace App\Modules\Department\Controllers;

use App\DataTables\DepartmentDataTable;
use App\Http\Controllers\MainController;
use Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 
class DepartmentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Department());
    }

    public function index(Request $request, DepartmentDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Department::index');
    }

    public function create(): View
    {
        $department = new Department();
        $parents = Department::where('is_active', 1)->pluck('name', 'id');

        $result = $this->getForm($department);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Department::create', compact('department', 'forms', 'groupedForms', 'parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:departments,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);

        try {
            $department = DB::transaction(function () use ($validated) {
                return Department::create($validated);
            });

            return $this->successResponse($request, 'Data departemen berhasil ditambahkan.', $department);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan departemen.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data departemen gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $department = $this->model->findOrFail($id);
        $parents = Department::where('is_active', 1)
            ->where('id', '!=', $id)
            ->pluck('name', 'id');

        $result = $this->getForm($department);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Department::edit', compact('department', 'forms', 'groupedForms', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $department = $this->model->findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:departments,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($department, $validated) {
                $department->update($validated);
            });

            return $this->successResponse($request, 'Data departemen berhasil diperbarui.', $department);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui departemen.', ['exception' => $e, 'department_id' => $id]);
            return $this->errorResponse($request, 'Data departemen gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $department = $this->model->findOrFail($id);

            if ($department->employees()->exists()) {
                return $this->errorResponse($request, 'Departemen tidak dapat dihapus karena masih memiliki karyawan.');
            }

            DB::transaction(function () use ($department) {
                $department->delete();
            });

            return $this->successResponse($request, 'Data departemen berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus departemen.', ['exception' => $e, 'department_id' => $id]);
            return $this->errorResponse($request, 'Data departemen gagal dihapus.');
        }
    }
}
