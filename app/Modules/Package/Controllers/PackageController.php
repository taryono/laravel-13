<?php

namespace App\Modules\Package\Controllers;

use App\DataTables\PackageDataTable;
use App\Http\Controllers\MainController;
use Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PackageController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Package());
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
        PackageDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /package?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Package::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $package = new Package();
        $result = $this->getForm($package);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Package::create',
            compact('package', 'forms', 'groupedForms')
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
            'price' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Package::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Package berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Package.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Package gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $package = $this->model->findOrFail($id);
        $result = $this->getForm($package);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Package::edit',
            compact('package', 'forms', 'groupedForms')
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
            'price' => ['required', 'string', 'max:255'],
        ]);

        try {
            $package = $this->model->findOrFail($id);

            DB::transaction(function () use ($package, $validated) {
                $package->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Package berhasil diperbarui.',
                $package
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $package->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $package belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui Package.',
                [
                    'exception' => $e,
                    'Package_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Package gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $package = $this->model->findOrFail($id);

            DB::transaction(function () use ($package) {
                $package->delete();
            });

            return $this->successResponse(
                $request,
                'Data Package berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $package->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Package.',
                [
                    'exception' => $e,
                    'Package_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Package gagal dihapus.'
            );
        }
    }

}
