<?php

namespace App\Modules\CbtProctoringEvidence\Controllers;

use App\DataTables\CbtProctoringEvidenceDataTable;
use App\Http\Controllers\MainController;
use Models\CbtProctoringEvidence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CbtProctoringEvidenceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CbtProctoringEvidence());
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
        CbtProctoringEvidenceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cbt-proctoring-evidence?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CbtProctoringEvidence::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cbtProctoringEvidence = new CbtProctoringEvidence();
        $result = $this->getForm($cbtProctoringEvidence);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtProctoringEvidence::create',
            compact('cbtProctoringEvidence', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'session_id' => ['required', 'integer'],
            'evidence_type' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'captured_at' => ['required', 'string', 'max:255'],
            'ai_result' => ['required', 'string', 'max:255'],
            'is_flagged' => ['required', 'string', 'max:255'],
            'expires_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CbtProctoringEvidence::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtProctoringEvidence berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CbtProctoringEvidence.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtProctoringEvidence gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cbtProctoringEvidence = $this->model->findOrFail($id);
        $result = $this->getForm($cbtProctoringEvidence);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtProctoringEvidence::edit',
            compact('cbtProctoringEvidence', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'session_id' => ['required', 'integer'],
            'evidence_type' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'captured_at' => ['required', 'string', 'max:255'],
            'ai_result' => ['required', 'string', 'max:255'],
            'is_flagged' => ['required', 'string', 'max:255'],
            'expires_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cbtProctoringEvidence = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cbtProctoringEvidence,
                $validated
            ) {
                $cbtProctoringEvidence->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtProctoringEvidence berhasil diperbarui.',
                $cbtProctoringEvidence
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CbtProctoringEvidence.',
                [
                    'exception' => $e,
                    'CbtProctoringEvidence_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtProctoringEvidence gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cbtProctoringEvidence = $this->model->findOrFail($id);

            DB::transaction(function () use ($cbtProctoringEvidence) {
                $cbtProctoringEvidence->delete();
            });

            return $this->successResponse(
                $request,
                'Data CbtProctoringEvidence berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cbtProctoringEvidence->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CbtProctoringEvidence.',
                [
                    'exception' => $e,
                    'CbtProctoringEvidence_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtProctoringEvidence gagal dihapus.'
            );
        }
    } 
}
