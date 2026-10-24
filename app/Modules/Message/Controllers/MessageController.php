<?php

namespace App\Modules\Message\Controllers;

use App\DataTables\MessageDataTable;
use App\Http\Controllers\MainController;
use Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MessageController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Message());
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
        MessageDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /message?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Message::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $message = new Message();
        $result = $this->getForm($message);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Message::create',
            compact('message', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'sender_id' => ['required', 'integer'],
            'receiver_id' => ['required', 'integer'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'parent_message_id' => ['required', 'integer'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_read' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Message::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Message berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Message.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Message gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $message = $this->model->findOrFail($id);
        $result = $this->getForm($message);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Message::edit',
            compact('message', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'sender_id' => ['required', 'integer'],
            'receiver_id' => ['required', 'integer'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'parent_message_id' => ['required', 'integer'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_read' => ['required', 'string', 'max:255'],
            'read_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $message = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $message,
                $validated
            ) {
                $message->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Message berhasil diperbarui.',
                $message
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Message.',
                [
                    'exception' => $e,
                    'Message_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Message gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $message = $this->model->findOrFail($id);

            DB::transaction(function () use ($message) {
                $message->delete();
            });

            return $this->successResponse(
                $request,
                'Data Message berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $message->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Message.',
                [
                    'exception' => $e,
                    'Message_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Message gagal dihapus.'
            );
        }
    } 
}
