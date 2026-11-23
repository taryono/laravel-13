<?php

namespace App\Modules\ProviderMessageEvent\Controllers;

use App\DataTables\ProviderMessageEventDataTable;
use App\Http\Controllers\MainController;
use Models\ProviderMessageEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ProviderMessageEventController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ProviderMessageEvent());
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
        ProviderMessageEventDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /provider-message-event?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ProviderMessageEvent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $providerMessageEvent = new ProviderMessageEvent();
        $result = $this->getForm($providerMessageEvent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ProviderMessageEvent::create',
            compact('providerMessageEvent', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sms_log_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'payload' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ProviderMessageEvent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ProviderMessageEvent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ProviderMessageEvent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ProviderMessageEvent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $providerMessageEvent = $this->model->findOrFail($id);
        $result = $this->getForm($providerMessageEvent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ProviderMessageEvent::edit',
            compact('providerMessageEvent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sms_log_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'payload' => ['required', 'string', 'max:255'],
        ]);

        try {
            $providerMessageEvent = $this->model->findOrFail($id);
            DB::transaction(function () use ($providerMessageEvent, $validated) {
                $providerMessageEvent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ProviderMessageEvent berhasil diperbarui.',
                $providerMessageEvent
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ProviderMessageEvent.',
                [
                    'exception' => $e,
                    'ProviderMessageEvent_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ProviderMessageEvent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $providerMessageEvent = $this->model->findOrFail($id);

            DB::transaction(function () use ($providerMessageEvent) {
                $providerMessageEvent->delete();
            });

            return $this->successResponse(
                $request,
                'Data ProviderMessageEvent berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $providerMessageEvent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ProviderMessageEvent.',
                [
                    'exception' => $e,
                    'ProviderMessageEvent_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ProviderMessageEvent gagal dihapus.'
            );
        }
    }
}
