<?php

namespace App\Modules\RkasRevision\Controllers;

use App\DataTables\RkasRevisionDataTable;
use App\Http\Controllers\MainController;
use Models\RkasRevision;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RkasRevisionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new RkasRevision());
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
        RkasRevisionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /rkas-revision?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'RkasRevision::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rkasRevision = new RkasRevision();
        $result = $this->getForm($rkasRevision);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasRevision::create',
            compact('rkasRevision', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rkas_document_id' => ['required', 'integer'],
            'parent_revision_id' => ['required', 'integer'],
            'revision_number' => ['required', 'string', 'max:255'],
            'revision_type' => ['required', 'string', 'max:255'],
            'revision_date' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'total_before' => ['required', 'string', 'max:255'],
            'total_after' => ['required', 'string', 'max:255'],
            'difference' => ['required', 'string', 'max:255'],
            'changes' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approval_notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return RkasRevision::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasRevision berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan RkasRevision.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasRevision gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rkasRevision = $this->model->findOrFail($id);
        $result = $this->getForm($rkasRevision);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'RkasRevision::edit',
            compact('rkasRevision', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rkas_document_id' => ['required', 'integer'],
            'parent_revision_id' => ['required', 'integer'],
            'revision_number' => ['required', 'string', 'max:255'],
            'revision_type' => ['required', 'string', 'max:255'],
            'revision_date' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'total_before' => ['required', 'string', 'max:255'],
            'total_after' => ['required', 'string', 'max:255'],
            'difference' => ['required', 'string', 'max:255'],
            'changes' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approval_notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $rkasRevision = $this->model->findOrFail($id);
            DB::transaction(function () use ($rkasRevision, $validated) {
                $rkasRevision->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data RkasRevision berhasil diperbarui.',
                $rkasRevision
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui RkasRevision.',
                [
                    'exception' => $e,
                    'RkasRevision_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasRevision gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $rkasRevision = $this->model->findOrFail($id);

            DB::transaction(function () use ($rkasRevision) {
                $rkasRevision->delete();
            });

            return $this->successResponse(
                $request,
                'Data RkasRevision berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $rkasRevision->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus RkasRevision.',
                [
                    'exception' => $e,
                    'RkasRevision_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data RkasRevision gagal dihapus.'
            );
        }
    }
}
