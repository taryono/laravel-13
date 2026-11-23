<?php

namespace App\Modules\RkasDocument\Controllers;

use App\DataTables\RkasDocumentDataTable;
use App\Http\Controllers\MainController;
use Models\RkasDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasDocumentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasDocument());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        RkasDocumentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-document?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasDocument::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasDocument = new RkasDocument();
        $result = $this->getForm($rkasDocument);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasDocument::create',
            compact('rkasDocument', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'revision_type' => ['required', 'string', 'max:255'],
            'parent_document_id' => ['required', 'integer'],
            'total_budget' => ['required', 'string', 'max:255'],
            'total_realization' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasDocument::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasDocument berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasDocument.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasDocument gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasDocument = $this->model->findOrFail($id);
        $result = $this->getForm($rkasDocument);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasDocument::edit',
            compact('rkasDocument', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'revision_type' => ['required', 'string', 'max:255'],
            'parent_document_id' => ['required', 'integer'],
            'total_budget' => ['required', 'string', 'max:255'],
            'total_realization' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasDocument = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasDocument, $validated) {
                $rkasDocument->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasDocument berhasil diperbarui.',
                $rkasDocument
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasDocument.',
                [
                    'exception' => $e,
                    'RkasDocument_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasDocument gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasDocument = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasDocument) {
                $rkasDocument->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasDocument berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasDocument->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasDocument.',
                [
                    'exception' => $e,
                    'RkasDocument_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasDocument gagal dihapus.'
            );
        }
    }
}
