<?php

namespace App\Modules\DigitalLibraryAccessLog\Controllers;

use App\DataTables\DigitalLibraryAccessLogDataTable;
use App\Http\Controllers\MainController;
use Models\DigitalLibraryAccessLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DigitalLibraryAccessLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DigitalLibraryAccessLog());
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
        DigitalLibraryAccessLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /digital-library-access-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DigitalLibraryAccessLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $digitalLibraryAccessLog = new DigitalLibraryAccessLog();
        $result = $this->getForm($digitalLibraryAccessLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DigitalLibraryAccessLog::create',
            compact('digitalLibraryAccessLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'digital_library_item_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'access_type' => ['required', 'string', 'max:255'],
            'access_duration_seconds' => ['required', 'string', 'max:255'],
            'pages_read' => ['required', 'string', 'max:255'],
            'last_page_read' => ['required', 'string', 'max:255'],
            'progress_percentage' => ['required', 'string', 'max:255'],
            'device_type' => ['required', 'string', 'max:255'],
            'device_identifier' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'referrer' => ['required', 'string', 'max:255'],
            'session_id' => ['required', 'integer'],
            'rating' => ['required', 'string', 'max:255'],
            'review' => ['required', 'string', 'max:255'],
            'is_offline' => ['required', 'string', 'max:255'],
            'accessed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DigitalLibraryAccessLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DigitalLibraryAccessLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DigitalLibraryAccessLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalLibraryAccessLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $digitalLibraryAccessLog = $this->model->findOrFail($id);
        $result = $this->getForm($digitalLibraryAccessLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DigitalLibraryAccessLog::edit',
            compact('digitalLibraryAccessLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'digital_library_item_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'access_type' => ['required', 'string', 'max:255'],
            'access_duration_seconds' => ['required', 'string', 'max:255'],
            'pages_read' => ['required', 'string', 'max:255'],
            'last_page_read' => ['required', 'string', 'max:255'],
            'progress_percentage' => ['required', 'string', 'max:255'],
            'device_type' => ['required', 'string', 'max:255'],
            'device_identifier' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'referrer' => ['required', 'string', 'max:255'],
            'session_id' => ['required', 'integer'],
            'rating' => ['required', 'string', 'max:255'],
            'review' => ['required', 'string', 'max:255'],
            'is_offline' => ['required', 'string', 'max:255'],
            'accessed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $digitalLibraryAccessLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($digitalLibraryAccessLog, $validated) {
                $digitalLibraryAccessLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DigitalLibraryAccessLog berhasil diperbarui.',
                $digitalLibraryAccessLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DigitalLibraryAccessLog.',
                [
                    'exception' => $e,
                    'DigitalLibraryAccessLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalLibraryAccessLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $digitalLibraryAccessLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($digitalLibraryAccessLog) {
                $digitalLibraryAccessLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data DigitalLibraryAccessLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $digitalLibraryAccessLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DigitalLibraryAccessLog.',
                [
                    'exception' => $e,
                    'DigitalLibraryAccessLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalLibraryAccessLog gagal dihapus.'
            );
        }
    }
}
