<?php

namespace App\Modules\LeaveType\Controllers;

use App\DataTables\LeaveTypeDataTable;
use App\Http\Controllers\MainController;
use Models\LeaveType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class LeaveTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LeaveType());
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
        LeaveTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /leave-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LeaveType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $leaveType = new LeaveType();
        $result = $this->getForm($leaveType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LeaveType::create',
            compact('leaveType', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $orgId = Auth::user()->current_organization_id;
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('leave_types')->where(fn($q) => $q->where('organization_id', $orgId))],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LeaveType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LeaveType berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LeaveType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LeaveType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $leave_type = $this->model->findOrFail($id);
        return view(
            'LeaveType::edit',
            compact('leave_type')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $orgId = Auth::user()->current_organization_id;
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('leave_types')->where(fn($q) => $q->where('organization_id', $orgId))->ignore($id)],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $leaveType = $this->model->findOrFail($id);
        try {
            DB::transaction(function () use ($leaveType, $validated) {
                $leaveType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LeaveType berhasil diperbarui.',
                $leaveType
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui tipe leave.',
                [
                    'exception' => $e,
                    'leave_type_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LeaveType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $leaveType = $this->model->findOrFail($id);

            DB::transaction(function () use ($leaveType) {
                $leaveType->delete();
            });

            return $this->successResponse(
                $request,
                'Data tipe leave berhasil dihapus.'
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal menghapus tipe leave.',
                [
                    'exception' => $e,
                    'leave_type_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LeaveType gagal dihapus.'
            );
        }
    }
}
