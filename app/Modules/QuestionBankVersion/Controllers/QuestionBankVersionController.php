<?php

namespace App\Modules\QuestionBankVersion\Controllers;

use App\DataTables\QuestionBankVersionDataTable;
use App\Http\Controllers\MainController;
use Models\QuestionBankVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuestionBankVersionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuestionBankVersion());
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
        QuestionBankVersionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /question-bank-version?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QuestionBankVersion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $questionBankVersion = new QuestionBankVersion();
        $result = $this->getForm($questionBankVersion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionBankVersion::create',
            compact('questionBankVersion', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_bank_id' => ['required', 'integer'],
            'version_number' => ['required', 'string', 'max:255'],
            'snapshot_data' => ['required', 'string', 'max:255'],
            'change_note' => ['required', 'string', 'max:255'],
            'changed_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return QuestionBankVersion::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionBankVersion berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuestionBankVersion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionBankVersion gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $questionBankVersion = $this->model->findOrFail($id);
        $result = $this->getForm($questionBankVersion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionBankVersion::edit',
            compact('questionBankVersion', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question_bank_id' => ['required', 'integer'],
            'version_number' => ['required', 'string', 'max:255'],
            'snapshot_data' => ['required', 'string', 'max:255'],
            'change_note' => ['required', 'string', 'max:255'],
            'changed_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $questionBankVersion = $this->model->findOrFail($id);
            DB::transaction(function () use ($questionBankVersion, $validated) {
                $questionBankVersion->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionBankVersion berhasil diperbarui.',
                $questionBankVersion
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui QuestionBankVersion.',
                [
                    'exception' => $e,
                    'QuestionBankVersion_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionBankVersion gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $questionBankVersion = $this->model->findOrFail($id);

            DB::transaction(function () use ($questionBankVersion) {
                $questionBankVersion->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuestionBankVersion berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $questionBankVersion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuestionBankVersion.',
                [
                    'exception' => $e,
                    'QuestionBankVersion_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionBankVersion gagal dihapus.'
            );
        }
    }
}
