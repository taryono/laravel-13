<?php

namespace App\Modules\DocumentAccessLog\Controllers;

use App\DataTables\DocumentAccessLogDataTable;
use App\Http\Controllers\MainController;
use Models\DocumentAccessLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DocumentAccessLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DocumentAccessLog());
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
        DocumentAccessLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /document-access-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DocumentAccessLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $documentAccessLog = new DocumentAccessLog();
        $result = $this->getForm($documentAccessLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DocumentAccessLog::create',
            compact('documentAccessLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_id' => ['required', 'integer'],
            'document_version_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'access_type' => ['required', 'string', 'max:255'],
            'access_duration_seconds' => ['required', 'string', 'max:255'],
            'pages_viewed' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'device_type' => ['required', 'string', 'max:255'],
            'device_identifier' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'referrer' => ['required', 'string', 'max:255'],
            'share_method' => ['required', 'string', 'max:255'],
            'share_recipient' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'failure_reason' => ['required', 'string', 'max:255'],
            'session_id' => ['required', 'integer'],
            'accessed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DocumentAccessLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DocumentAccessLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DocumentAccessLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentAccessLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $documentAccessLog = $this->model->findOrFail($id);
        $result = $this->getForm($documentAccessLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DocumentAccessLog::edit',
            compact('documentAccessLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'document_id' => ['required', 'integer'],
            'document_version_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'access_type' => ['required', 'string', 'max:255'],
            'access_duration_seconds' => ['required', 'string', 'max:255'],
            'pages_viewed' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'device_type' => ['required', 'string', 'max:255'],
            'device_identifier' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'referrer' => ['required', 'string', 'max:255'],
            'share_method' => ['required', 'string', 'max:255'],
            'share_recipient' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'failure_reason' => ['required', 'string', 'max:255'],
            'session_id' => ['required', 'integer'],
            'accessed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $documentAccessLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($documentAccessLog, $validated) {
                $documentAccessLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DocumentAccessLog berhasil diperbarui.',
                $documentAccessLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DocumentAccessLog.',
                [
                    'exception' => $e,
                    'DocumentAccessLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentAccessLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $documentAccessLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($documentAccessLog) {
                $documentAccessLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data DocumentAccessLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $documentAccessLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DocumentAccessLog.',
                [
                    'exception' => $e,
                    'DocumentAccessLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DocumentAccessLog gagal dihapus.'
            );
        }
    }
}
