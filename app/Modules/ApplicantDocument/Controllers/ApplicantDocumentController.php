<?php

namespace App\Modules\ApplicantDocument\Controllers;

use App\DataTables\ApplicantDocumentDataTable;
use App\Http\Controllers\MainController;
use Models\ApplicantDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApplicantDocumentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApplicantDocument());
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
        ApplicantDocumentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /applicant-document?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApplicantDocument::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $applicantDocument = new ApplicantDocument();
        $result = $this->getForm($applicantDocument);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantDocument::create',
            compact('applicantDocument', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'document_type' => ['required', 'string', 'max:255'],
            'document_url' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'uploaded_at' => ['required', 'string', 'max:255'],
            'verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApplicantDocument::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantDocument berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApplicantDocument.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantDocument gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $applicantDocument = $this->model->findOrFail($id);
        $result = $this->getForm($applicantDocument);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApplicantDocument::edit',
            compact('applicantDocument', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'document_type' => ['required', 'string', 'max:255'],
            'document_url' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'uploaded_at' => ['required', 'string', 'max:255'],
            'verified' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verification_notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $applicantDocument = $this->model->findOrFail($id);
            DB::transaction(function () use ($applicantDocument, $validated) {
                $applicantDocument->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApplicantDocument berhasil diperbarui.',
                $applicantDocument
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApplicantDocument.',
                [
                    'exception' => $e,
                    'ApplicantDocument_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantDocument gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $applicantDocument = $this->model->findOrFail($id);

            DB::transaction(function () use ($applicantDocument) {
                $applicantDocument->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApplicantDocument berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $applicantDocument->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApplicantDocument.',
                [
                    'exception' => $e,
                    'ApplicantDocument_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApplicantDocument gagal dihapus.'
            );
        }
    }
}
