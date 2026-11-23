<?php

namespace App\Modules\SystemLog\Controllers;

use App\DataTables\SystemLogDataTable;
use App\Http\Controllers\MainController;
use Models\SystemLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SystemLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SystemLog());
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
        SystemLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /system-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SystemLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $systemLog = new SystemLog();
        $result = $this->getForm($systemLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SystemLog::create',
            compact('systemLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'action' => ['required', 'string', 'max:255'],
            'table_name' => ['required', 'string', 'max:255'],
            'record_id' => ['required', 'integer'],
            'old_data' => ['required', 'string', 'max:255'],
            'new_data' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'organization_id' => ['required', 'integer'],
            'level' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
            'context' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SystemLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SystemLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SystemLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SystemLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $systemLog = $this->model->findOrFail($id);
        $result = $this->getForm($systemLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SystemLog::edit',
            compact('systemLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'action' => ['required', 'string', 'max:255'],
            'table_name' => ['required', 'string', 'max:255'],
            'record_id' => ['required', 'integer'],
            'old_data' => ['required', 'string', 'max:255'],
            'new_data' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'organization_id' => ['required', 'integer'],
            'level' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
            'context' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', 'max:255'],
        ]);

        try {
            $systemLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($systemLog, $validated) {
                $systemLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SystemLog berhasil diperbarui.',
                $systemLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui SystemLog.',
                [
                    'exception' => $e,
                    'SystemLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SystemLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $systemLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($systemLog) {
                $systemLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data SystemLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $systemLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SystemLog.',
                [
                    'exception' => $e,
                    'SystemLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SystemLog gagal dihapus.'
            );
        }
    }
}
