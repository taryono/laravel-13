<?php

namespace App\Modules\CmsPostRevision\Controllers;

use App\DataTables\CmsPostRevisionDataTable;
use App\Http\Controllers\MainController;
use Models\CmsPostRevision;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsPostRevisionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsPostRevision());
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
        CmsPostRevisionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-post-revision?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsPostRevision::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsPostRevision = new CmsPostRevision();
        $result = $this->getForm($cmsPostRevision);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsPostRevision::create',
            compact('cmsPostRevision', 'forms', 'groupedForms')
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
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'revision_number' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsPostRevision::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsPostRevision berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsPostRevision.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsPostRevision gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsPostRevision = $this->model->findOrFail($id);
        $result = $this->getForm($cmsPostRevision);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsPostRevision::edit',
            compact('cmsPostRevision', 'forms', 'groupedForms')
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
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'revision_number' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsPostRevision = $this->model->findOrFail($id);
            DB::transaction(function () use ($cmsPostRevision, $validated) {
                $cmsPostRevision->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsPostRevision berhasil diperbarui.',
                $cmsPostRevision
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CmsPostRevision.',
                [
                    'exception' => $e,
                    'CmsPostRevision_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsPostRevision gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsPostRevision = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsPostRevision) {
                $cmsPostRevision->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsPostRevision berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cmsPostRevision->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsPostRevision.',
                [
                    'exception' => $e,
                    'CmsPostRevision_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsPostRevision gagal dihapus.'
            );
        }
    }
}
