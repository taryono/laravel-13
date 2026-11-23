<?php

namespace App\Modules\MessageThread\Controllers;

use App\DataTables\MessageThreadDataTable;
use App\Http\Controllers\MainController;
use Models\MessageThread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MessageThreadController extends MainController
{
    public function __construct()
    {
        parent::__construct(new MessageThread());
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
        MessageThreadDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /message-thread?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'MessageThread::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $messageThread = new MessageThread();
        $result = $this->getForm($messageThread);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MessageThread::create',
            compact('messageThread', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'thread_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'avatar_path' => ['required', 'string', 'max:255'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'is_locked' => ['required', 'string', 'max:255'],
            'is_pinned' => ['required', 'string', 'max:255'],
            'allow_attachments' => ['required', 'string', 'max:255'],
            'allow_reactions' => ['required', 'string', 'max:255'],
            'max_participants' => ['required', 'string', 'max:255'],
            'last_message_at' => ['required', 'string', 'max:255'],
            'last_message_id' => ['required', 'integer'],
            'message_count' => ['required', 'string', 'max:255'],
            'is_archived' => ['required', 'string', 'max:255'],
            'archived_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return MessageThread::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data MessageThread berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan MessageThread.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MessageThread gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $messageThread = $this->model->findOrFail($id);
        $result = $this->getForm($messageThread);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MessageThread::edit',
            compact('messageThread', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'thread_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'avatar_path' => ['required', 'string', 'max:255'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'is_locked' => ['required', 'string', 'max:255'],
            'is_pinned' => ['required', 'string', 'max:255'],
            'allow_attachments' => ['required', 'string', 'max:255'],
            'allow_reactions' => ['required', 'string', 'max:255'],
            'max_participants' => ['required', 'string', 'max:255'],
            'last_message_at' => ['required', 'string', 'max:255'],
            'last_message_id' => ['required', 'integer'],
            'message_count' => ['required', 'string', 'max:255'],
            'is_archived' => ['required', 'string', 'max:255'],
            'archived_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $messageThread = $this->model->findOrFail($id);
            DB::transaction(function () use ($messageThread, $validated) {
                $messageThread->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data MessageThread berhasil diperbarui.',
                $messageThread
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui MessageThread.',
                [
                    'exception' => $e,
                    'MessageThread_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MessageThread gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $messageThread = $this->model->findOrFail($id);

            DB::transaction(function () use ($messageThread) {
                $messageThread->delete();
            });

            return $this->successResponse(
                $request,
                'Data MessageThread berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $messageThread->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus MessageThread.',
                [
                    'exception' => $e,
                    'MessageThread_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MessageThread gagal dihapus.'
            );
        }
    }
}
