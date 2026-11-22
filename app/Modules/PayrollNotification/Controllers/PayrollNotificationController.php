<?php

namespace App\Modules\PayrollNotification\Controllers;

use App\DataTables\PayrollNotificationDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PayrollNotificationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollNotification());
    }

    public function index(Request $request, PayrollNotificationDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('System::payroll_notification.index');
    }

    public function create(): View
    {
        $notification = new PayrollNotification();
        $result = $this->getForm($notification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::payroll_notification.edit', compact('notification', 'forms', 'groupedForms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:info,warning,error,success',
            'category' => 'required|in:payroll,finance,hr,system',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string|max:500',
            'is_read' => 'boolean',
        ]);

        try {
            $notification = DB::transaction(function () use ($validated) {
                return PayrollNotification::create($validated);
            });

            return $this->successResponse($request, 'Data notifikasi berhasil ditambahkan.', $notification);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan notifikasi.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data notifikasi gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $notification = $this->model->findOrFail($id);
        $result = $this->getForm($notification);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('System::payroll_notification.edit', compact('notification', 'forms', 'groupedForms'));
    }

    public function update(Request $request, $id)
    {
        $notification = $this->model->findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:info,warning,error,success',
            'category' => 'required|in:payroll,finance,hr,system',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string|max:500',
            'is_read' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($notification, $validated) {
                $notification->update($validated);
            });

            return $this->successResponse($request, 'Data notifikasi berhasil diperbarui.', $notification);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui notifikasi.', ['exception' => $e, 'notification_id' => $id]);
            return $this->errorResponse($request, 'Data notifikasi gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $notification = $this->model->findOrFail($id);
            DB::transaction(function () use ($notification) {
                $notification->delete();
            });

            return $this->successResponse($request, 'Data notifikasi berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus notifikasi.', ['exception' => $e, 'notification_id' => $id]);
            return $this->errorResponse($request, 'Data notifikasi gagal dihapus.');
        }
    }

    public function markAsRead(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $notification = $this->model->findOrFail($id);
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            return $this->successResponse($request, 'Notifikasi berhasil ditandai terbaca.', $notification);
        } catch (Throwable $e) {
            Log::error('Gagal menandai notifikasi terbaca.', ['exception' => $e, 'notification_id' => $id]);
            return $this->errorResponse($request, 'Gagal menandai notifikasi terbaca.');
        }
    }

    public function markAllAsRead(Request $request): JsonResponse|RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                PayrollNotification::where('user_id', request()->user()?->id)
                    ->where('is_read', 0)
                    ->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);
            });

            return $this->successResponse($request, 'Semua notifikasi berhasil ditandai terbaca.');
        } catch (Throwable $e) {
            Log::error('Gagal menandai semua notifikasi terbaca.', ['exception' => $e]);
            return $this->errorResponse($request, 'Gagal menandai semua notifikasi terbaca.');
        }
    }

    public function getUnreadCount(): JsonResponse
    {
        try {
            $count = PayrollNotification::where('user_id', request()->user()?->id)
                ->where('is_read', 0)
                ->count();

            return response()->json(['success' => true, 'count' => $count]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil jumlah notifikasi.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
