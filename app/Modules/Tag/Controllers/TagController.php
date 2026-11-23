<?php

namespace App\Modules\Tag\Controllers;

use App\DataTables\TagDataTable;
use App\Http\Controllers\MainController;
use Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TagController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Tag());
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
        TagDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /tag?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Tag::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tag = new Tag();
        $result = $this->getForm($tag);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Tag::create',
            compact('tag', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Tag::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Tag berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Tag.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Tag gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tag = $this->model->findOrFail($id);
        $result = $this->getForm($tag);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Tag::edit',
            compact('tag', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $tag = $this->model->findOrFail($id);
            DB::transaction(function () use ($tag, $validated) {
                $tag->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Tag berhasil diperbarui.',
                $tag
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Tag.',
                [
                    'exception' => $e,
                    'Tag_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Tag gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $tag = $this->model->findOrFail($id);

            DB::transaction(function () use ($tag) {
                $tag->delete();
            });

            return $this->successResponse(
                $request,
                'Data Tag berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $tag->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Tag.',
                [
                    'exception' => $e,
                    'Tag_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Tag gagal dihapus.'
            );
        }
    }
}
