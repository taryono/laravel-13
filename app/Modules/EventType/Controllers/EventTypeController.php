<?php

namespace App\Modules\EventType\Controllers;

use App\DataTables\EventTypeDataTable;
use App\Http\Controllers\MainController;
use Models\EventType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EventTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EventType());
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
        EventTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /event-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EventType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $eventType = new EventType();
        $result = $this->getForm($eventType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EventType::create',
            compact('eventType', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EventType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EventType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EventType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EventType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $eventType = $this->model->findOrFail($id);
        $result = $this->getForm($eventType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EventType::edit',
            compact('eventType', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
        ]);

        try {
            $eventType = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $eventType,
                $validated
            ) {
                $eventType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EventType berhasil diperbarui.',
                $eventType
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui EventType.',
                [
                    'exception' => $e,
                    'EventType_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EventType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $eventType = $this->model->findOrFail($id);

            DB::transaction(function () use ($eventType) {
                $eventType->delete();
            });

            return $this->successResponse(
                $request,
                'Data EventType berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $eventType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EventType.',
                [
                    'exception' => $e,
                    'EventType_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EventType gagal dihapus.'
            );
        }
    } 
}
