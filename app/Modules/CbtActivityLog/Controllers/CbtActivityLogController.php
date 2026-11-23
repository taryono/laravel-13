<?php

namespace App\Modules\CbtActivityLog\Controllers;

use App\DataTables\CbtActivityLogDataTable;
use App\Http\Controllers\MainController;
use Models\CbtActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CbtActivityLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CbtActivityLog());
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
        CbtActivityLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cbt-activity-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CbtActivityLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cbtActivityLog = new CbtActivityLog();
        $result = $this->getForm($cbtActivityLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtActivityLog::create',
            compact('cbtActivityLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer'],
            'activity_type' => ['required', 'string', 'max:255'],
            'activity_data' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'string', 'max:255'],
            'snapshot_url' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CbtActivityLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtActivityLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CbtActivityLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtActivityLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cbtActivityLog = $this->model->findOrFail($id);
        $result = $this->getForm($cbtActivityLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtActivityLog::edit',
            compact('cbtActivityLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer'],
            'activity_type' => ['required', 'string', 'max:255'],
            'activity_data' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'string', 'max:255'],
            'snapshot_url' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cbtActivityLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($cbtActivityLog, $validated) {
                $cbtActivityLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtActivityLog berhasil diperbarui.',
                $cbtActivityLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CbtActivityLog.',
                [
                    'exception' => $e,
                    'CbtActivityLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtActivityLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cbtActivityLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($cbtActivityLog) {
                $cbtActivityLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data CbtActivityLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cbtActivityLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CbtActivityLog.',
                [
                    'exception' => $e,
                    'CbtActivityLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtActivityLog gagal dihapus.'
            );
        }
    }
}
