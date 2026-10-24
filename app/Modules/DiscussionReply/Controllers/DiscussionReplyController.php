<?php

namespace App\Modules\DiscussionReply\Controllers;

use App\DataTables\DiscussionReplyDataTable;
use App\Http\Controllers\MainController;
use Models\DiscussionReply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DiscussionReplyController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DiscussionReply());
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
        DiscussionReplyDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /discussion-reply?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DiscussionReply::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $discussionReply = new DiscussionReply();
        $result = $this->getForm($discussionReply);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DiscussionReply::create',
            compact('discussionReply', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'discussion_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'content' => ['required', 'string', 'max:255'],
            'is_solution' => ['required', 'string', 'max:255'],
            'likes_count' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DiscussionReply::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DiscussionReply berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DiscussionReply.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DiscussionReply gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $discussionReply = $this->model->findOrFail($id);
        $result = $this->getForm($discussionReply);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DiscussionReply::edit',
            compact('discussionReply', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'discussion_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'content' => ['required', 'string', 'max:255'],
            'is_solution' => ['required', 'string', 'max:255'],
            'likes_count' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $discussionReply = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $discussionReply,
                $validated
            ) {
                $discussionReply->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DiscussionReply berhasil diperbarui.',
                $discussionReply
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui DiscussionReply.',
                [
                    'exception' => $e,
                    'DiscussionReply_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DiscussionReply gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $discussionReply = $this->model->findOrFail($id);

            DB::transaction(function () use ($discussionReply) {
                $discussionReply->delete();
            });

            return $this->successResponse(
                $request,
                'Data DiscussionReply berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $discussionReply->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DiscussionReply.',
                [
                    'exception' => $e,
                    'DiscussionReply_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DiscussionReply gagal dihapus.'
            );
        }
    } 
}
