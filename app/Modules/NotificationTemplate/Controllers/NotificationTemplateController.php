<?php

namespace App\Modules\NotificationTemplate\Controllers;

use App\DataTables\NotificationTemplateDataTable;
use App\Http\Controllers\MainController;
use Models\NotificationTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class NotificationTemplateController extends MainController
{
    public function __construct()
    {
        parent::__construct(new NotificationTemplate());
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
        NotificationTemplateDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /notification-template?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'NotificationTemplate::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $notification_template = new NotificationTemplate();

        return view(
            'NotificationTemplate::create',
            compact('notification_template')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
            'body_html' => ['required', 'string', 'max:255'],
            'variables' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
            'sender_name' => ['required', 'string', 'max:255'],
            'sender_email' => ['required', 'string', 'max:255'],
            'sender_phone' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return NotificationTemplate::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data NotificationTemplate berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan NotificationTemplate.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data NotificationTemplate gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $notification_template = $this->model->findOrFail($id);

        return view(
            'NotificationTemplate::edit',
            compact('notification_template')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
            'body_html' => ['required', 'string', 'max:255'],
            'variables' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
            'sender_name' => ['required', 'string', 'max:255'],
            'sender_email' => ['required', 'string', 'max:255'],
            'sender_phone' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $notificationTemplate = $this->model->findOrFail($id);
            DB::transaction(function () use ($notificationTemplate, $validated) {
                $notificationTemplate->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data NotificationTemplate berhasil diperbarui.',
                $notificationTemplate
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui NotificationTemplate.',
                [
                    'exception' => $e,
                    'NotificationTemplate_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data NotificationTemplate gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $notificationTemplate = $this->model->findOrFail($id);

            DB::transaction(function () use ($notificationTemplate) {
                $notificationTemplate->delete();
            });

            return $this->successResponse(
                $request,
                'Data NotificationTemplate berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $notificationTemplate->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus NotificationTemplate.',
                [
                    'exception' => $e,
                    'NotificationTemplate_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data NotificationTemplate gagal dihapus.'
            );
        }
    }
}
