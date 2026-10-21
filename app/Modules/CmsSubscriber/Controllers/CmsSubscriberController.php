<?php

namespace App\Modules\CmsSubscriber\Controllers;

use App\DataTables\CmsSubscriberDataTable;
use App\Http\Controllers\MainController;
use Models\CmsSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsSubscriberController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsSubscriber());
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
        CmsSubscriberDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-subscriber?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsSubscriber::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsSubscriber = new CmsSubscriber();
        $result = $this->getForm($cmsSubscriber);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsSubscriber::create',
            compact('cmsSubscriber', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'email' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'token' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'unsubscribed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsSubscriber::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsSubscriber berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsSubscriber.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsSubscriber gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsSubscriber = $this->model->findOrFail($id);
        $result = $this->getForm($cmsSubscriber);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsSubscriber::edit',
            compact('cmsSubscriber', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'email' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'token' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'unsubscribed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsSubscriber = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cmsSubscriber,
                $validated
            ) {
                $cmsSubscriber->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsSubscriber berhasil diperbarui.',
                $cmsSubscriber
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CmsSubscriber.',
                [
                    'exception' => $e,
                    'CmsSubscriber_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsSubscriber gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsSubscriber = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsSubscriber) {
                $cmsSubscriber->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsSubscriber berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cmsSubscriber->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsSubscriber.',
                [
                    'exception' => $e,
                    'CmsSubscriber_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsSubscriber gagal dihapus.'
            );
        }
    } 
}
