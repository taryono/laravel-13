<?php

namespace App\Modules\NotificationPreference\Controllers;

use App\DataTables\NotificationPreferenceDataTable;
use App\Http\Controllers\MainController;
use Models\NotificationPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class NotificationPreferenceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new NotificationPreference());
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
        NotificationPreferenceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /notification-preference?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'NotificationPreference::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $notificationPreference = new NotificationPreference();
        $result = $this->getForm($notificationPreference);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'NotificationPreference::create',
            compact('notificationPreference', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'user_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'category' => ['required', 'string', 'max:255'],
            'email_enabled' => ['required', 'string', 'max:255'],
            'whatsapp_enabled' => ['required', 'string', 'max:255'],
            'sms_enabled' => ['required', 'string', 'max:255'],
            'push_enabled' => ['required', 'string', 'max:255'],
            'in_app_enabled' => ['required', 'string', 'max:255'],
            'quiet_hours_start' => ['required', 'string', 'max:255'],
            'quiet_hours_end' => ['required', 'string', 'max:255'],
            'digest_frequency' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return NotificationPreference::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data NotificationPreference berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan NotificationPreference.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data NotificationPreference gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $notificationPreference = $this->model->findOrFail($id);
        $result = $this->getForm($notificationPreference);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'NotificationPreference::edit',
            compact('notificationPreference', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'user_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'category' => ['required', 'string', 'max:255'],
            'email_enabled' => ['required', 'string', 'max:255'],
            'whatsapp_enabled' => ['required', 'string', 'max:255'],
            'sms_enabled' => ['required', 'string', 'max:255'],
            'push_enabled' => ['required', 'string', 'max:255'],
            'in_app_enabled' => ['required', 'string', 'max:255'],
            'quiet_hours_start' => ['required', 'string', 'max:255'],
            'quiet_hours_end' => ['required', 'string', 'max:255'],
            'digest_frequency' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
        ]);

        try {
            $notificationPreference = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $notificationPreference,
                $validated
            ) {
                $notificationPreference->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data NotificationPreference berhasil diperbarui.',
                $notificationPreference
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui NotificationPreference.',
                [
                    'exception' => $e,
                    'NotificationPreference_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data NotificationPreference gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $notificationPreference = $this->model->findOrFail($id);

            DB::transaction(function () use ($notificationPreference) {
                $notificationPreference->delete();
            });

            return $this->successResponse(
                $request,
                'Data NotificationPreference berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $notificationPreference->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus NotificationPreference.',
                [
                    'exception' => $e,
                    'NotificationPreference_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data NotificationPreference gagal dihapus.'
            );
        }
    } 
}
