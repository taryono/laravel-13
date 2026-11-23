<?php

namespace App\Modules\MessageParticipant\Controllers;

use App\DataTables\MessageParticipantDataTable;
use App\Http\Controllers\MainController;
use Models\MessageParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MessageParticipantController extends MainController
{
    public function __construct()
    {
        parent::__construct(new MessageParticipant());
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
        MessageParticipantDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /message-participant?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'MessageParticipant::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $messageParticipant = new MessageParticipant();
        $result = $this->getForm($messageParticipant);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MessageParticipant::create',
            compact('messageParticipant', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message_thread_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'role' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:255'],
            'is_muted' => ['required', 'string', 'max:255'],
            'is_pinned' => ['required', 'string', 'max:255'],
            'last_read_at' => ['required', 'string', 'max:255'],
            'last_read_message_id' => ['required', 'integer'],
            'unread_count' => ['required', 'string', 'max:255'],
            'joined_at' => ['required', 'string', 'max:255'],
            'left_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'mute_until' => ['required', 'string', 'max:255'],
            'notification_preference' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return MessageParticipant::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data MessageParticipant berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan MessageParticipant.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MessageParticipant gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $messageParticipant = $this->model->findOrFail($id);
        $result = $this->getForm($messageParticipant);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MessageParticipant::edit',
            compact('messageParticipant', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'message_thread_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'role' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:255'],
            'is_muted' => ['required', 'string', 'max:255'],
            'is_pinned' => ['required', 'string', 'max:255'],
            'last_read_at' => ['required', 'string', 'max:255'],
            'last_read_message_id' => ['required', 'integer'],
            'unread_count' => ['required', 'string', 'max:255'],
            'joined_at' => ['required', 'string', 'max:255'],
            'left_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'mute_until' => ['required', 'string', 'max:255'],
            'notification_preference' => ['required', 'string', 'max:255'],
        ]);

        try {
            $messageParticipant = $this->model->findOrFail($id);
            DB::transaction(function () use ($messageParticipant, $validated) {
                $messageParticipant->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data MessageParticipant berhasil diperbarui.',
                $messageParticipant
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui MessageParticipant.',
                [
                    'exception' => $e,
                    'MessageParticipant_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MessageParticipant gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $messageParticipant = $this->model->findOrFail($id);

            DB::transaction(function () use ($messageParticipant) {
                $messageParticipant->delete();
            });

            return $this->successResponse(
                $request,
                'Data MessageParticipant berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $messageParticipant->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus MessageParticipant.',
                [
                    'exception' => $e,
                    'MessageParticipant_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MessageParticipant gagal dihapus.'
            );
        }
    }
}
