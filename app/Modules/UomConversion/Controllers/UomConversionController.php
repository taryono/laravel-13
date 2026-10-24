<?php

namespace App\Modules\UomConversion\Controllers;

use App\DataTables\UomConversionDataTable;
use App\Http\Controllers\MainController;
use Models\UomConversion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class UomConversionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new UomConversion());
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
        UomConversionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /uom-conversion?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'UomConversion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $uomConversion = new UomConversion();
        $result = $this->getForm($uomConversion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'UomConversion::create',
            compact('uomConversion', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'from_uom_id' => ['required', 'integer'],
            'to_uom_id' => ['required', 'integer'],
            'multiplier' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return UomConversion::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data UomConversion berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan UomConversion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data UomConversion gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $uomConversion = $this->model->findOrFail($id);
        $result = $this->getForm($uomConversion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'UomConversion::edit',
            compact('uomConversion', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'from_uom_id' => ['required', 'integer'],
            'to_uom_id' => ['required', 'integer'],
            'multiplier' => ['required', 'string', 'max:255'],
        ]);

        try {
            $uomConversion = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $uomConversion,
                $validated
            ) {
                $uomConversion->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data UomConversion berhasil diperbarui.',
                $uomConversion
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui UomConversion.',
                [
                    'exception' => $e,
                    'UomConversion_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data UomConversion gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $uomConversion = $this->model->findOrFail($id);

            DB::transaction(function () use ($uomConversion) {
                $uomConversion->delete();
            });

            return $this->successResponse(
                $request,
                'Data UomConversion berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $uomConversion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus UomConversion.',
                [
                    'exception' => $e,
                    'UomConversion_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data UomConversion gagal dihapus.'
            );
        }
    } 
}
