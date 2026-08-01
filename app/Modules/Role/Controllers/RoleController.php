<?php

namespace App\Modules\Role\Controllers;

use Models\Role;
use App\DataTables\RoleDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\MainController;

class RoleController extends MainController
{
    public function __construct(){
        parent::__construct(new Role());
    }

    public function index(Request $request,RoleDataTable $dataTable)
    {
         if ($request->filled('draw')) {
            return $dataTable->ajax();
        }
        // Menggunakan namespace kustom modul untuk Blade view
        return $dataTable->render('Role::index', ['title' => 'Data Role']);
    }

    public function create()
    {
        return view('Role::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Role::create($validated);

        return redirect()->route('role.index')
            ->with('success', 'Data Role berhasil ditambahkan.');
    }

    public function edit($id)
    {   $role = Role::find($id);
      
        $result = $this->getForm($role);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];
        return view('Role::edit', compact('role', 'forms', 'groupedForms'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role->update($validated);

        return redirect()->route('role.index')
            ->with('success', 'Data Role berhasil diperbarui.');
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
