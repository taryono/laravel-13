<?php

namespace App\Modules\Discussion\Controllers;

use App\DataTables\DiscussionDataTable;
use App\Http\Controllers\MainController;
use Models\Discussion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DiscussionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Discussion());
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
        DiscussionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /discussion?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Discussion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $discussion = new Discussion();
        $result = $this->getForm($discussion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Discussion::create',
            compact('discussion', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'is_pinned' => ['required', 'string', 'max:255'],
            'is_locked' => ['required', 'string', 'max:255'],
            'views_count' => ['required', 'string', 'max:255'],
            'replies_count' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Discussion::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Discussion berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Discussion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Discussion gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $discussion = $this->model->findOrFail($id);
        $result = $this->getForm($discussion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Discussion::edit',
            compact('discussion', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'is_pinned' => ['required', 'string', 'max:255'],
            'is_locked' => ['required', 'string', 'max:255'],
            'views_count' => ['required', 'string', 'max:255'],
            'replies_count' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $discussion = $this->model->findOrFail($id);
            DB::transaction(function () use ($discussion, $validated) {
                $discussion->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Discussion berhasil diperbarui.',
                $discussion
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Discussion.',
                [
                    'exception' => $e,
                    'Discussion_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Discussion gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $discussion = $this->model->findOrFail($id);

            DB::transaction(function () use ($discussion) {
                $discussion->delete();
            });

            return $this->successResponse(
                $request,
                'Data Discussion berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $discussion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Discussion.',
                [
                    'exception' => $e,
                    'Discussion_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Discussion gagal dihapus.'
            );
        }
    }
}
