<?php

namespace App\Modules\ReportCardGrade\Controllers;

use App\DataTables\ReportCardGradeDataTable;
use App\Http\Controllers\MainController;
use Models\ReportCardGrade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReportCardGradeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ReportCardGrade());
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
        ReportCardGradeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /report-card-grade?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ReportCardGrade::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $reportCardGrade = new ReportCardGrade();
        $result = $this->getForm($reportCardGrade);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardGrade::create',
            compact('reportCardGrade', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'report_card_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'curriculum_structure_id' => ['required', 'integer'],
            'knowledge_score' => ['required', 'string', 'max:255'],
            'knowledge_predicate' => ['required', 'string', 'max:255'],
            'knowledge_description' => ['required', 'string', 'max:255'],
            'skill_score' => ['required', 'string', 'max:255'],
            'skill_predicate' => ['required', 'string', 'max:255'],
            'skill_description' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'final_predicate' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ReportCardGrade::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardGrade berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ReportCardGrade.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardGrade gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $reportCardGrade = $this->model->findOrFail($id);
        $result = $this->getForm($reportCardGrade);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ReportCardGrade::edit',
            compact('reportCardGrade', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'report_card_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'curriculum_structure_id' => ['required', 'integer'],
            'knowledge_score' => ['required', 'string', 'max:255'],
            'knowledge_predicate' => ['required', 'string', 'max:255'],
            'knowledge_description' => ['required', 'string', 'max:255'],
            'skill_score' => ['required', 'string', 'max:255'],
            'skill_predicate' => ['required', 'string', 'max:255'],
            'skill_description' => ['required', 'string', 'max:255'],
            'final_score' => ['required', 'string', 'max:255'],
            'final_predicate' => ['required', 'string', 'max:255'],
            'rank' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $reportCardGrade = $this->model->findOrFail($id);
            DB::transaction(function () use ($reportCardGrade, $validated) {
                $reportCardGrade->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ReportCardGrade berhasil diperbarui.',
                $reportCardGrade
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ReportCardGrade.',
                [
                    'exception' => $e,
                    'ReportCardGrade_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardGrade gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reportCardGrade = $this->model->findOrFail($id);

            DB::transaction(function () use ($reportCardGrade) {
                $reportCardGrade->delete();
            });

            return $this->successResponse(
                $request,
                'Data ReportCardGrade berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $reportCardGrade->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ReportCardGrade.',
                [
                    'exception' => $e,
                    'ReportCardGrade_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ReportCardGrade gagal dihapus.'
            );
        }
    }
}
