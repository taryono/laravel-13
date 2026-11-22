<?php

namespace App\Modules\Notification\Controllers;

use App\DataTables\NotificationDataTable;
use App\Http\Controllers\MainController;
use Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class NotificationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Notification());
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
        NotificationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /notification?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Notification::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $notification = new Notification();
        $result = $this->getForm($notification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Notification::create',
            compact('notification', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
            'reference_table' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'channel' => ['required', 'string', 'max:255'],
            'is_read' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Notification::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Notification berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Notification.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Notification gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $notification = $this->model->findOrFail($id);
        $result = $this->getForm($notification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Notification::edit',
            compact('notification', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
            'reference_table' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'channel' => ['required', 'string', 'max:255'],
            'is_read' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $notification = $this->model->findOrFail($id);
            DB::transaction(function () use ($notification, $validated) {
                $notification->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Notification berhasil diperbarui.',
                $notification
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Notification.',
                [
                    'exception' => $e,
                    'Notification_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Notification gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $notification = $this->model->findOrFail($id);

            DB::transaction(function () use ($notification) {
                $notification->delete();
            });

            return $this->successResponse(
                $request,
                'Data Notification berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $notification->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Notification.',
                [
                    'exception' => $e,
                    'Notification_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Notification gagal dihapus.'
            );
        }
    }
}
