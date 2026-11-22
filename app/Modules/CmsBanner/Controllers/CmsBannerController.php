<?php

namespace App\Modules\CmsBanner\Controllers;

use App\DataTables\CmsBannerDataTable;
use App\Http\Controllers\MainController;
use Models\CmsBanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsBannerController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsBanner());
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
        CmsBannerDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-banner?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsBanner::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsBanner = new CmsBanner();
        $result = $this->getForm($cmsBanner);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsBanner::create',
            compact('cmsBanner', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'link_url' => ['required', 'string', 'max:255'],
            'button_text' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'start_at' => ['required', 'string', 'max:255'],
            'end_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsBanner::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsBanner berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsBanner.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsBanner gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsBanner = $this->model->findOrFail($id);
        $result = $this->getForm($cmsBanner);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsBanner::edit',
            compact('cmsBanner', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'link_url' => ['required', 'string', 'max:255'],
            'button_text' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'start_at' => ['required', 'string', 'max:255'],
            'end_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsBanner = $this->model->findOrFail($id);
            DB::transaction(function () use ($cmsBanner, $validated) {
                $cmsBanner->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsBanner berhasil diperbarui.',
                $cmsBanner
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CmsBanner.',
                [
                    'exception' => $e,
                    'CmsBanner_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsBanner gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsBanner = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsBanner) {
                $cmsBanner->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsBanner berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cmsBanner->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsBanner.',
                [
                    'exception' => $e,
                    'CmsBanner_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsBanner gagal dihapus.'
            );
        }
    }
}
