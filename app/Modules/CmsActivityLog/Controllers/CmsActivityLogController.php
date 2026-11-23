<?php

namespace App\Modules\CmsActivityLog\Controllers;

use App\DataTables\CmsActivityLogDataTable;
use App\Http\Controllers\MainController;
use Models\CmsActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsActivityLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsActivityLog());
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
        CmsActivityLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-activity-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsActivityLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsActivityLog = new CmsActivityLog();
        $result = $this->getForm($cmsActivityLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsActivityLog::create',
            compact('cmsActivityLog', 'forms', 'groupedForms')
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
            'subject_type' => ['required', 'string', 'max:255'],
            'subject_id' => ['required', 'integer'],
            'properties' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsActivityLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsActivityLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsActivityLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsActivityLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsActivityLog = $this->model->findOrFail($id);
        $result = $this->getForm($cmsActivityLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsActivityLog::edit',
            compact('cmsActivityLog', 'forms', 'groupedForms')
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
            'subject_type' => ['required', 'string', 'max:255'],
            'subject_id' => ['required', 'integer'],
            'properties' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsActivityLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($cmsActivityLog, $validated) {
                $cmsActivityLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsActivityLog berhasil diperbarui.',
                $cmsActivityLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CmsActivityLog.',
                [
                    'exception' => $e,
                    'CmsActivityLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsActivityLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsActivityLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsActivityLog) {
                $cmsActivityLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsActivityLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cmsActivityLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsActivityLog.',
                [
                    'exception' => $e,
                    'CmsActivityLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsActivityLog gagal dihapus.'
            );
        }
    }
}
