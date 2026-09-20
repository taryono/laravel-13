<?php

namespace App\Modules\Role\Controllers;

use Models\Role;
use App\DataTables\RoleDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\MainController;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RoleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Role());
    }

    public function index(Request $request, RoleDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }
        // Menggunakan namespace kustom modul untuk Blade view
        return $dataTable->render('Role::index', ['title' => 'Data Role']);
    }

    public function create()
    {
        $parentOptions = Role::getFlatTree(Auth::user()->current_organization_id);
        return view('Role::create', compact('parentOptions'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                Role::create($validated);
            });

            return $this->successResponse($request, 'Data Role berhasil ditambahkan.', $request);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan role.', ['exception' => $e]);
            return $this->errorResponse($request, 'Data Role gagal ditambahkan.');
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Gunakan getFlatTree() yang sudah ada properti level
        $parentOptions = Role::getFlatTree(Auth::user()->current_organization_id);

        // Hilangkan dirinya sendiri dari daftar
        $parentOptions = $parentOptions->where('id', '!=', $role->id)->values();

        return view('Role::edit', compact('role', 'parentOptions'));
    }


    public function update(Request $request, $id)
    {
        $validated = $this->validated($request, $id);

        $role = $this->model->findOrFail($id);
        try {
            DB::transaction(function () use ($role, $validated) {
                $role->update($validated);
            });

            return $this->successResponse($request, 'Data jabatan berhasil diperbarui.', $role);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui jabatan.', ['exception' => $e, 'role_id' => $id]);
            return $this->errorResponse($request, 'Data jabatan gagal diperbarui.');
        }
    }

    private function validated($request, $id = null)
    {
        $validated = $request->validate([
            'name'          => [
                'nullable',
                'string',
                'max:191',
                Rule::unique('roles', 'name')->ignore($id),
            ],
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        if ($request->boolean('is_system')) {
            $validated['parent_id'] = null;
        }

        return $validated;
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Role berhasil dihapus.'
        ]);
    }
}
