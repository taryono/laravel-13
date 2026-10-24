<?php

namespace App\Modules\DocumentNumberSequence\Controllers;

use App\DataTables\DocumentNumberSequenceDataTable;
use App\Http\Controllers\MainController;
use Models\DocumentNumberSequence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DocumentNumberSequenceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DocumentNumberSequence());
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
        DocumentNumberSequenceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /document-number-sequence?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DocumentNumberSequence::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $documentNumberSequence = new DocumentNumberSequence();
        $result = $this->getForm($documentNumberSequence);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DocumentNumberSequence::create',
            compact('documentNumberSequence', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'document_type' => ['required', 'string', 'max:255'],
            'period_key' => ['required', 'string', 'max:255'],
            'prefix' => ['required', 'string', 'max:255'],
            'pattern' => ['required', 'string', 'max:255'],
            'last_number' => ['required', 'string', 'max:255'],
            'padding' => ['required', 'string', 'max:255'],
            'reset_frequency' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DocumentNumberSequence::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DocumentNumberSequence berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DocumentNumberSequence.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentNumberSequence gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $documentNumberSequence = $this->model->findOrFail($id);
        $result = $this->getForm($documentNumberSequence);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DocumentNumberSequence::edit',
            compact('documentNumberSequence', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'document_type' => ['required', 'string', 'max:255'],
            'period_key' => ['required', 'string', 'max:255'],
            'prefix' => ['required', 'string', 'max:255'],
            'pattern' => ['required', 'string', 'max:255'],
            'last_number' => ['required', 'string', 'max:255'],
            'padding' => ['required', 'string', 'max:255'],
            'reset_frequency' => ['required', 'string', 'max:255'],
        ]);

        try {
            $documentNumberSequence = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $documentNumberSequence,
                $validated
            ) {
                $documentNumberSequence->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DocumentNumberSequence berhasil diperbarui.',
                $documentNumberSequence
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui DocumentNumberSequence.',
                [
                    'exception' => $e,
                    'DocumentNumberSequence_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentNumberSequence gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $documentNumberSequence = $this->model->findOrFail($id);

            DB::transaction(function () use ($documentNumberSequence) {
                $documentNumberSequence->delete();
            });

            return $this->successResponse(
                $request,
                'Data DocumentNumberSequence berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $documentNumberSequence->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DocumentNumberSequence.',
                [
                    'exception' => $e,
                    'DocumentNumberSequence_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentNumberSequence gagal dihapus.'
            );
        }
    } 
}
