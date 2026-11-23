<?php

namespace App\Modules\PesantrenRuleViolation\Controllers;

use App\DataTables\PesantrenRuleViolationDataTable;
use App\Http\Controllers\MainController;
use Models\PesantrenRuleViolation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PesantrenRuleViolationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PesantrenRuleViolation());
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
        PesantrenRuleViolationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /pesantren-rule-violation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PesantrenRuleViolation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $pesantrenRuleViolation = new PesantrenRuleViolation();
        $result = $this->getForm($pesantrenRuleViolation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PesantrenRuleViolation::create',
            compact('pesantrenRuleViolation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pesantren_rule_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'dormitory_id' => ['required', 'integer'],
            'reporter_id' => ['required', 'integer'],
            'handler_id' => ['required', 'integer'],
            'violation_number' => ['required', 'string', 'max:255'],
            'violation_date' => ['required', 'string', 'max:255'],
            'violation_time' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'evidence_paths' => ['required', 'string', 'max:255'],
            'witnesses' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'string', 'max:255'],
            'sanction_type' => ['required', 'string', 'max:255'],
            'sanction_points' => ['required', 'string', 'max:255'],
            'sanction_description' => ['required', 'string', 'max:255'],
            'sanction_start_date' => ['required', 'string', 'max:255'],
            'sanction_end_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'student_statement' => ['required', 'string', 'max:255'],
            'parent_notified' => ['required', 'string', 'max:255'],
            'parent_notified_at' => ['required', 'string', 'max:255'],
            'parent_response' => ['required', 'string', 'max:255'],
            'resolved_at' => ['required', 'string', 'max:255'],
            'resolution_notes' => ['required', 'string', 'max:255'],
            'appeal_reason' => ['required', 'string', 'max:255'],
            'appeal_status' => ['required', 'string', 'max:255'],
            'appeal_decided_by' => ['required', 'string', 'max:255'],
            'appeal_decided_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PesantrenRuleViolation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PesantrenRuleViolation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PesantrenRuleViolation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PesantrenRuleViolation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pesantrenRuleViolation = $this->model->findOrFail($id);
        $result = $this->getForm($pesantrenRuleViolation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PesantrenRuleViolation::edit',
            compact('pesantrenRuleViolation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pesantren_rule_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'dormitory_id' => ['required', 'integer'],
            'reporter_id' => ['required', 'integer'],
            'handler_id' => ['required', 'integer'],
            'violation_number' => ['required', 'string', 'max:255'],
            'violation_date' => ['required', 'string', 'max:255'],
            'violation_time' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'evidence_paths' => ['required', 'string', 'max:255'],
            'witnesses' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'string', 'max:255'],
            'sanction_type' => ['required', 'string', 'max:255'],
            'sanction_points' => ['required', 'string', 'max:255'],
            'sanction_description' => ['required', 'string', 'max:255'],
            'sanction_start_date' => ['required', 'string', 'max:255'],
            'sanction_end_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'student_statement' => ['required', 'string', 'max:255'],
            'parent_notified' => ['required', 'string', 'max:255'],
            'parent_notified_at' => ['required', 'string', 'max:255'],
            'parent_response' => ['required', 'string', 'max:255'],
            'resolved_at' => ['required', 'string', 'max:255'],
            'resolution_notes' => ['required', 'string', 'max:255'],
            'appeal_reason' => ['required', 'string', 'max:255'],
            'appeal_status' => ['required', 'string', 'max:255'],
            'appeal_decided_by' => ['required', 'string', 'max:255'],
            'appeal_decided_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $pesantrenRuleViolation = $this->model->findOrFail($id);
            DB::transaction(function () use ($pesantrenRuleViolation, $validated) {
                $pesantrenRuleViolation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PesantrenRuleViolation berhasil diperbarui.',
                $pesantrenRuleViolation
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PesantrenRuleViolation.',
                [
                    'exception' => $e,
                    'PesantrenRuleViolation_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PesantrenRuleViolation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $pesantrenRuleViolation = $this->model->findOrFail($id);

            DB::transaction(function () use ($pesantrenRuleViolation) {
                $pesantrenRuleViolation->delete();
            });

            return $this->successResponse(
                $request,
                'Data PesantrenRuleViolation berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $pesantrenRuleViolation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PesantrenRuleViolation.',
                [
                    'exception' => $e,
                    'PesantrenRuleViolation_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PesantrenRuleViolation gagal dihapus.'
            );
        }
    }
}
