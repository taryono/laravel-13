<?php

namespace App\Modules\ApplicationDocument\Controllers;

use App\DataTables\ApplicationDocumentDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicationDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicationDocumentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicationDocument());
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
        ApplicationDocumentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /application-document?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicationDocument::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicationDocument = new ApplicationDocument();
        $result = $this->getForm($applicationDocument);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicationDocument::create',
            compact('applicationDocument', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'document_type' => ['required', 'string', 'max:255'],
            'document_url' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'uploaded_at' => ['required', 'string', 'max:255'],
            'verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicationDocument::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicationDocument berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicationDocument.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicationDocument gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicationDocument = $this->model->findOrFail($id);
        $result = $this->getForm($applicationDocument);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicationDocument::edit',
            compact('applicationDocument', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'document_type' => ['required', 'string', 'max:255'],
            'document_url' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'uploaded_at' => ['required', 'string', 'max:255'],
            'verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicationDocument = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicationDocument, $validated) {
                $applicationDocument->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicationDocument berhasil diperbarui.',
                $applicationDocument
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApplicationDocument.',
                [
                    'exception' => $e,
                    'ApplicationDocument_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicationDocument gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicationDocument = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicationDocument) {
                $applicationDocument->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicationDocument berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicationDocument->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicationDocument.',
                [
                    'exception' => $e,
                    'ApplicationDocument_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicationDocument gagal dihapus.'
            );
        }
    }
}
