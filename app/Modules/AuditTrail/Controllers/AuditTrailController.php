<?php

namespace App\Modules\AuditTrail\Controllers;

use App\DataTables\AuditTrailDataTable;
use App\Http\Controllers\MainController;
use Models\AuditTrail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AuditTrailController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AuditTrail());
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
        AuditTrailDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /audit-trail?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AuditTrail::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $auditTrail = new AuditTrail();
        $result = $this->getForm($auditTrail);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AuditTrail::create',
            compact('auditTrail', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'table_name' => ['required', 'string', 'max:255'],
            'record_id' => ['required', 'integer'],
            'action' => ['required', 'string', 'max:255'],
            'old_values' => ['required', 'string', 'max:255'],
            'new_values' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AuditTrail::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AuditTrail berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AuditTrail.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AuditTrail gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $auditTrail = $this->model->findOrFail($id);
        $result = $this->getForm($auditTrail);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AuditTrail::edit',
            compact('auditTrail', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'table_name' => ['required', 'string', 'max:255'],
            'record_id' => ['required', 'integer'],
            'action' => ['required', 'string', 'max:255'],
            'old_values' => ['required', 'string', 'max:255'],
            'new_values' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
        ]);

        try {
            $auditTrail = $this->model->findOrFail($id);
            DB::transaction(function () use ($auditTrail, $validated) {
                $auditTrail->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AuditTrail berhasil diperbarui.',
                $auditTrail
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AuditTrail.',
                [
                    'exception' => $e,
                    'AuditTrail_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AuditTrail gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $auditTrail = $this->model->findOrFail($id);

            DB::transaction(function () use ($auditTrail) {
                $auditTrail->delete();
            });

            return $this->successResponse(
                $request,
                'Data AuditTrail berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $auditTrail->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AuditTrail.',
                [
                    'exception' => $e,
                    'AuditTrail_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AuditTrail gagal dihapus.'
            );
        }
    }
}
