<?php

namespace App\Modules\DocumentType\Controllers;

use App\DataTables\DocumentTypeDataTable;
use App\Http\Controllers\MainController;
use Models\DocumentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DocumentTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DocumentType());
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
        DocumentTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /document-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DocumentType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $documentType = new DocumentType();
        $result = $this->getForm($documentType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DocumentType::create',
            compact('documentType', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DocumentType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DocumentType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DocumentType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $documentType = $this->model->findOrFail($id);
        $result = $this->getForm($documentType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DocumentType::edit',
            compact('documentType', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $documentType = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $documentType,
                $validated
            ) {
                $documentType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DocumentType berhasil diperbarui.',
                $documentType
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui DocumentType.',
                [
                    'exception' => $e,
                    'DocumentType_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $documentType = $this->model->findOrFail($id);

            DB::transaction(function () use ($documentType) {
                $documentType->delete();
            });

            return $this->successResponse(
                $request,
                'Data DocumentType berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $documentType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DocumentType.',
                [
                    'exception' => $e,
                    'DocumentType_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentType gagal dihapus.'
            );
        }
    } 
}
