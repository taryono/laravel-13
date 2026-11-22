<?php

namespace App\Modules\RkasApproval\Controllers;

use App\DataTables\RkasApprovalDataTable;
use App\Http\Controllers\MainController;
use Models\RkasApproval;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasApprovalController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasApproval());
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
        RkasApprovalDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-approval?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasApproval::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasApproval = new RkasApproval();
        $result = $this->getForm($rkasApproval);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasApproval::create',
            compact('rkasApproval', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rkas_document_id' => ['required', 'integer'],
            'approval_level' => ['required', 'string', 'max:255'],
            'approval_role' => ['required', 'string', 'max:255'],
            'approver_id' => ['required', 'integer'],
            'approver_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'signature_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasApproval::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasApproval berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasApproval.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasApproval gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasApproval = $this->model->findOrFail($id);
        $result = $this->getForm($rkasApproval);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasApproval::edit',
            compact('rkasApproval', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rkas_document_id' => ['required', 'integer'],
            'approval_level' => ['required', 'string', 'max:255'],
            'approval_role' => ['required', 'string', 'max:255'],
            'approver_id' => ['required', 'integer'],
            'approver_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'signature_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasApproval = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasApproval, $validated) {
                $rkasApproval->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasApproval berhasil diperbarui.',
                $rkasApproval
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasApproval.',
                [
                    'exception' => $e,
                    'RkasApproval_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasApproval gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasApproval = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasApproval) {
                $rkasApproval->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasApproval berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasApproval->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasApproval.',
                [
                    'exception' => $e,
                    'RkasApproval_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasApproval gagal dihapus.'
            );
        }
    }
}
