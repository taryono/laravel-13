<?php

namespace App\Modules\CmsComment\Controllers;

use App\DataTables\CmsCommentDataTable;
use App\Http\Controllers\MainController;
use Models\CmsComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsCommentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsComment());
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
        CmsCommentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-comment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsComment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsComment = new CmsComment();
        $result = $this->getForm($cmsComment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsComment::create',
            compact('cmsComment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'post_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsComment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsComment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsComment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsComment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsComment = $this->model->findOrFail($id);
        $result = $this->getForm($cmsComment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsComment::edit',
            compact('cmsComment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'post_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsComment = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cmsComment,
                $validated
            ) {
                $cmsComment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsComment berhasil diperbarui.',
                $cmsComment
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CmsComment.',
                [
                    'exception' => $e,
                    'CmsComment_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsComment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsComment = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsComment) {
                $cmsComment->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsComment berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cmsComment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsComment.',
                [
                    'exception' => $e,
                    'CmsComment_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsComment gagal dihapus.'
            );
        }
    } 
}
