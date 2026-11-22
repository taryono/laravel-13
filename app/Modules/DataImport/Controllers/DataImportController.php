<?php

namespace App\Modules\DataImport\Controllers;

use App\DataTables\DataImportDataTable;
use App\Http\Controllers\MainController;
use Models\DataImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DataImportController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DataImport());
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
        DataImportDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /data-import?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DataImport::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dataImport = new DataImport();
        $result = $this->getForm($dataImport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DataImport::create',
            compact('dataImport', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'string', 'max:255'],
            'mapping_config' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DataImport::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DataImport berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DataImport.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DataImport gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dataImport = $this->model->findOrFail($id);
        $result = $this->getForm($dataImport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DataImport::edit',
            compact('dataImport', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'table_name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'string', 'max:255'],
            'mapping_config' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dataImport = $this->model->findOrFail($id);

            DB::transaction(function () use ($dataImport, $validated) {
                $dataImport->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DataImport berhasil diperbarui.',
                $dataImport
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $dataImport->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $dataImport belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui DataImport.',
                [
                    'exception' => $e,
                    'DataImport_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DataImport gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dataImport = $this->model->findOrFail($id);

            DB::transaction(function () use ($dataImport) {
                $dataImport->delete();
            });

            return $this->successResponse(
                $request,
                'Data DataImport berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $dataImport->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DataImport.',
                [
                    'exception' => $e,
                    'DataImport_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DataImport gagal dihapus.'
            );
        }
    }
}
