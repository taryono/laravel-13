<?php

namespace App\Modules\AdmissionSelection\Controllers;

use App\DataTables\AdmissionSelectionDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionSelection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionSelectionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionSelection());
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
        AdmissionSelectionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-selection?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionSelection::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionSelection = new AdmissionSelection();
        $result = $this->getForm($admissionSelection);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionSelection::create',
            compact('admissionSelection', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'selection_type' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'result' => ['required', 'string', 'max:255'],
            'evaluator_id' => ['required', 'integer'],
            'evaluation_date' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionSelection::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionSelection berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionSelection.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionSelection gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionSelection = $this->model->findOrFail($id);
        $result = $this->getForm($admissionSelection);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionSelection::edit',
            compact('admissionSelection', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'admission_id' => ['required', 'integer'],
            'applicant_id' => ['required', 'integer'],
            'selection_type' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'result' => ['required', 'string', 'max:255'],
            'evaluator_id' => ['required', 'integer'],
            'evaluation_date' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionSelection = $this->model->findOrFail($id);
            DB::transaction(function () use ($admissionSelection, $validated) {
                $admissionSelection->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionSelection berhasil diperbarui.',
                $admissionSelection
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AdmissionSelection.',
                [
                    'exception' => $e,
                    'AdmissionSelection_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionSelection gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionSelection = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionSelection) {
                $admissionSelection->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionSelection berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $admissionSelection->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionSelection.',
                [
                    'exception' => $e,
                    'AdmissionSelection_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionSelection gagal dihapus.'
            );
        }
    }
}
