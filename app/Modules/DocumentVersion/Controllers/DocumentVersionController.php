<?php

namespace App\Modules\DocumentVersion\Controllers;

use App\DataTables\DocumentVersionDataTable;
use App\Http\Controllers\MainController;
use Models\DocumentVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DocumentVersionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DocumentVersion());
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
        DocumentVersionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /document-version?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DocumentVersion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $documentVersion = new DocumentVersion();
        $result = $this->getForm($documentVersion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DocumentVersion::create',
            compact('documentVersion', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'document_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'version_number' => ['required', 'string', 'max:255'],
            'version_label' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'file_extension' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:255'],
            'file_hash' => ['required', 'string', 'max:255'],
            'change_summary' => ['required', 'string', 'max:255'],
            'change_type' => ['required', 'string', 'max:255'],
            'is_current' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'uploaded_by' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DocumentVersion::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DocumentVersion berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DocumentVersion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentVersion gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $documentVersion = $this->model->findOrFail($id);
        $result = $this->getForm($documentVersion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DocumentVersion::edit',
            compact('documentVersion', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'document_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'version_number' => ['required', 'string', 'max:255'],
            'version_label' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'file_extension' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:255'],
            'file_hash' => ['required', 'string', 'max:255'],
            'change_summary' => ['required', 'string', 'max:255'],
            'change_type' => ['required', 'string', 'max:255'],
            'is_current' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'uploaded_by' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $documentVersion = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $documentVersion,
                $validated
            ) {
                $documentVersion->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DocumentVersion berhasil diperbarui.',
                $documentVersion
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui DocumentVersion.',
                [
                    'exception' => $e,
                    'DocumentVersion_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentVersion gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $documentVersion = $this->model->findOrFail($id);

            DB::transaction(function () use ($documentVersion) {
                $documentVersion->delete();
            });

            return $this->successResponse(
                $request,
                'Data DocumentVersion berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $documentVersion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DocumentVersion.',
                [
                    'exception' => $e,
                    'DocumentVersion_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentVersion gagal dihapus.'
            );
        }
    } 
}
