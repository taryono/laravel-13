<?php

namespace App\Modules\Module\Controllers;

use App\DataTables\ModuleDataTable;
use App\Http\Controllers\MainController;
use Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ModuleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Module());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        ModuleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /module?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Module::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $module = new Module();
        $result = $this->getForm($module);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Module::create',
            compact('module', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Module::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Module berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Module.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Module gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $module = $this->model->findOrFail($id);
        $result = $this->getForm($module);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Module::edit',
            compact('module', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
        ]);

        try {
            $module = $this->model->findOrFail($id);

            DB::transaction(function () use ($module, $validated) {
                $module->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Module berhasil diperbarui.',
                $module
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $module->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $module belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui Module.',
                [
                    'exception' => $e,
                    'Module_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Module gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $module = $this->model->findOrFail($id);

            DB::transaction(function () use ($module) {
                $module->delete();
            });

            return $this->successResponse(
                $request,
                'Data Module berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $module->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Module.',
                [
                    'exception' => $e,
                    'Module_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Module gagal dihapus.'
            );
        }
    }

}
