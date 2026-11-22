<?php

namespace App\Modules\FileUpload\Controllers;

use App\DataTables\FileUploadDataTable;
use App\Http\Controllers\MainController;
use Models\FileUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FileUploadController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FileUpload());
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
        FileUploadDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /file-upload?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FileUpload::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $fileUpload = new FileUpload();
        $result = $this->getForm($fileUpload);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FileUpload::create',
            compact('fileUpload', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'uploaded_by' => ['required', 'string', 'max:255'],
            'uploadable_type' => ['required', 'string', 'max:255'],
            'uploadable_id' => ['required', 'integer'],
            'collection' => ['required', 'string', 'max:255'],
            'original_name' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'file_extension' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:255'],
            'file_hash' => ['required', 'string', 'max:255'],
            'disk' => ['required', 'string', 'max:255'],
            'is_public' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'thumbnail_path' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FileUpload::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FileUpload berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FileUpload.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FileUpload gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $fileUpload = $this->model->findOrFail($id);
        $result = $this->getForm($fileUpload);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FileUpload::edit',
            compact('fileUpload', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'uploaded_by' => ['required', 'string', 'max:255'],
            'uploadable_type' => ['required', 'string', 'max:255'],
            'uploadable_id' => ['required', 'integer'],
            'collection' => ['required', 'string', 'max:255'],
            'original_name' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'file_extension' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:255'],
            'file_hash' => ['required', 'string', 'max:255'],
            'disk' => ['required', 'string', 'max:255'],
            'is_public' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'thumbnail_path' => ['required', 'string', 'max:255'],
            'metadata' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        try {
            $fileUpload = $this->model->findOrFail($id);
            DB::transaction(function () use ($fileUpload, $validated) {
                $fileUpload->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FileUpload berhasil diperbarui.',
                $fileUpload
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui FileUpload.',
                [
                    'exception' => $e,
                    'FileUpload_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FileUpload gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $fileUpload = $this->model->findOrFail($id);

            DB::transaction(function () use ($fileUpload) {
                $fileUpload->delete();
            });

            return $this->successResponse(
                $request,
                'Data FileUpload berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $fileUpload->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FileUpload.',
                [
                    'exception' => $e,
                    'FileUpload_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FileUpload gagal dihapus.'
            );
        }
    }
}
