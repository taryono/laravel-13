<?php

namespace App\Modules\Document\Controllers;

use App\DataTables\DocumentDataTable;
use App\Http\Controllers\MainController;
use Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DocumentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Document());
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
        DocumentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /document?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Document::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $document = new Document();
        $result = $this->getForm($document);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Document::create',
            compact('document', 'forms', 'groupedForms')
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
                return Document::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Document berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Document.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Document gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $document = $this->model->findOrFail($id);
        $result = $this->getForm($document);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Document::edit',
            compact('document', 'forms', 'groupedForms')
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
            $document = $this->model->findOrFail($id);
            DB::transaction(function () use ($document, $validated) {
                $document->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Document berhasil diperbarui.',
                $document
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Document.',
                [
                    'exception' => $e,
                    'Document_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Document gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $document = $this->model->findOrFail($id);

            DB::transaction(function () use ($document) {
                $document->delete();
            });

            return $this->successResponse(
                $request,
                'Data Document berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $document->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Document.',
                [
                    'exception' => $e,
                    'Document_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Document gagal dihapus.'
            );
        }
    }
}
