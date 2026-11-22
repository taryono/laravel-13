<?php

namespace App\Modules\ReportCardSignature\Controllers;

use App\DataTables\ReportCardSignatureDataTable;
use App\Http\Controllers\MainController;
use Models\ReportCardSignature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReportCardSignatureController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportCardSignature());
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
        ReportCardSignatureDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-card-signature?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportCardSignature::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportCardSignature = new ReportCardSignature();
        $result = $this->getForm($reportCardSignature);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardSignature::create',
            compact('reportCardSignature', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_card_id' => ['required', 'integer'],
            'role' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'integer'],
            'signed_at' => ['required', 'string', 'max:255'],
            'signature_path' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportCardSignature::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardSignature berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportCardSignature.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardSignature gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportCardSignature = $this->model->findOrFail($id);
        $result = $this->getForm($reportCardSignature);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardSignature::edit',
            compact('reportCardSignature', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'report_card_id' => ['required', 'integer'],
            'role' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'integer'],
            'signed_at' => ['required', 'string', 'max:255'],
            'signature_path' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportCardSignature = $this->model->findOrFail($id);
            DB::transaction(function () use ($reportCardSignature, $validated) {
                $reportCardSignature->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardSignature berhasil diperbarui.',
                $reportCardSignature
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ReportCardSignature.',
                [
                    'exception' => $e,
                    'ReportCardSignature_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardSignature gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportCardSignature = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportCardSignature) {
                $reportCardSignature->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportCardSignature berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $reportCardSignature->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportCardSignature.',
                [
                    'exception' => $e,
                    'ReportCardSignature_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardSignature gagal dihapus.'
            );
        }
    }
}
