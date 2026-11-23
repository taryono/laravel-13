<?php

namespace App\Modules\AccessLog\Controllers;

use App\DataTables\AccessLogDataTable;
use App\Http\Controllers\MainController;
use Models\AccessLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AccessLogController extends MainController
{

    public function __construct()
    {
        parent::__construct(new AccessLog());
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
        AccessLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /access-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AccessLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $accessLog = new AccessLog();
        $forms = $this->getForm($accessLog);
        return view(
            'AccessLog::create',
            compact('accessLog', 'forms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AccessLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AccessLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AccessLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AccessLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $accessLog = $this->model->findOrFail($id);
        $forms = $this->getForm($accessLog);
        return view(
            'AccessLog::edit',
            compact('accessLog', 'forms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $accessLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($accessLog, $validated) {
                $accessLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AccessLog berhasil diperbarui.',
                $accessLog
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui AccessLog.',
                [
                    'exception' => $e,
                    'AccessLog_id' => $accessLog->getKey(),
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AccessLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $accessLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($accessLog) {
                $accessLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data AccessLog berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus AccessLog.',
                [
                    'exception' => $e,
                    'AccessLog_id' => $accessLog->getKey(),
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AccessLog gagal dihapus.'
            );
        }
    }

}