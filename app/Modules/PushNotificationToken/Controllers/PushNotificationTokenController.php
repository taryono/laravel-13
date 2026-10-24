<?php

namespace App\Modules\PushNotificationToken\Controllers;

use App\DataTables\PushNotificationTokenDataTable;
use App\Http\Controllers\MainController;
use Models\PushNotificationToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PushNotificationTokenController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PushNotificationToken());
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
        PushNotificationTokenDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /push-notification-token?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PushNotificationToken::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $pushNotificationToken = new PushNotificationToken();
        $result = $this->getForm($pushNotificationToken);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PushNotificationToken::create',
            compact('pushNotificationToken', 'forms', 'groupedForms')
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
            'token' => ['required', 'string', 'max:255'],
            'token_hash' => ['required', 'string', 'max:255'],
            'platform' => ['required', 'string', 'max:255'],
            'device_id' => ['required', 'integer'],
            'device_name' => ['required', 'string', 'max:255'],
            'device_model' => ['required', 'string', 'max:255'],
            'os_version' => ['required', 'string', 'max:255'],
            'app_version' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'locale' => ['required', 'string', 'max:255'],
            'timezone' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'last_used_at' => ['required', 'string', 'max:255'],
            'expires_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PushNotificationToken::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PushNotificationToken berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PushNotificationToken.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PushNotificationToken gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pushNotificationToken = $this->model->findOrFail($id);
        $result = $this->getForm($pushNotificationToken);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PushNotificationToken::edit',
            compact('pushNotificationToken', 'forms', 'groupedForms')
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
            'token' => ['required', 'string', 'max:255'],
            'token_hash' => ['required', 'string', 'max:255'],
            'platform' => ['required', 'string', 'max:255'],
            'device_id' => ['required', 'integer'],
            'device_name' => ['required', 'string', 'max:255'],
            'device_model' => ['required', 'string', 'max:255'],
            'os_version' => ['required', 'string', 'max:255'],
            'app_version' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'locale' => ['required', 'string', 'max:255'],
            'timezone' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'last_used_at' => ['required', 'string', 'max:255'],
            'expires_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $pushNotificationToken = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $pushNotificationToken,
                $validated
            ) {
                $pushNotificationToken->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PushNotificationToken berhasil diperbarui.',
                $pushNotificationToken
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui PushNotificationToken.',
                [
                    'exception' => $e,
                    'PushNotificationToken_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PushNotificationToken gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $pushNotificationToken = $this->model->findOrFail($id);

            DB::transaction(function () use ($pushNotificationToken) {
                $pushNotificationToken->delete();
            });

            return $this->successResponse(
                $request,
                'Data PushNotificationToken berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $pushNotificationToken->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PushNotificationToken.',
                [
                    'exception' => $e,
                    'PushNotificationToken_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PushNotificationToken gagal dihapus.'
            );
        }
    } 
}
