<?php

namespace App\Modules\Position\Controllers;

use App\DataTables\PositionDataTable;
use App\Http\Controllers\MainController;
use Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 
class PositionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Position());
    }

    public function index(Request $request, PositionDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Position::index');
    }

    public function create(): View
    {
        $position = new Position();
        $result = $this->getForm($position);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Position::edit', compact('position', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:positions,code',
            'name' => 'required|string|max:255',
            'level' => 'nullable|string|max:100',
            'salary_range_min' => 'nullable|numeric|min:0',
            'salary_range_max' => 'nullable|numeric|min:0|gte:salary_range_min',
            'is_active' => 'boolean',
        ]);

        try {
            $position = DB::transaction(function () use ($validated) {
                return Position::create($validated);
            });

            return $this->successResponse($request, 'Data jabatan berhasil ditambahkan.', $position);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan jabatan.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data jabatan gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $position = $this->model->findOrFail($id);
        $result = $this->getForm($position);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Position::edit', compact('position', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $position = $this->model->findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:positions,code,' . $id,
            'name' => 'required|string|max:255',
            'level' => 'nullable|string|max:100',
            'salary_range_min' => 'nullable|numeric|min:0',
            'salary_range_max' => 'nullable|numeric|min:0|gte:salary_range_min',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($position, $validated) {
                $position->update($validated);
            });

            return $this->successResponse($request, 'Data jabatan berhasil diperbarui.', $position);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui jabatan.', ['exception' => $e, 'position_id' => $id]);
            return $this->errorResponse($request, 'Data jabatan gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $position = $this->model->findOrFail($id);

            if ($position->employees()->exists()) {
                return $this->errorResponse($request, 'Jabatan tidak dapat dihapus karena masih memiliki karyawan.');
            }

            DB::transaction(function () use ($position) {
                $position->delete();
            });

            return $this->successResponse($request, 'Data jabatan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus jabatan.', ['exception' => $e, 'position_id' => $id]);
            return $this->errorResponse($request, 'Data jabatan gagal dihapus.');
        }
    }
}
