<?php

namespace App\Modules\CmsRedirect\Controllers;

use App\DataTables\CmsRedirectDataTable;
use App\Http\Controllers\MainController;
use Models\CmsRedirect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class CmsRedirectController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsRedirect());
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
        CmsRedirectDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-redirect?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsRedirect::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cms_redirect = new CmsRedirect();
        $statusCodes = CmsRedirect::$statusCodes;

        return view(
            'CmsRedirect::create',
            compact('cms_redirect', 'statusCodes')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_url' => ['required', 'string', 'max:255'],
            'to_url' => ['required', 'string', 'max:255'],
            'status_code' => ['required', 'string', 'max:255'],
            'hits' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsRedirect::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsRedirect berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsRedirect.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsRedirect gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cms_redirect = $this->model->findOrFail($id);
        $statusCodes = CmsRedirect::$statusCodes;

        return view(
            'CmsRedirect::edit',
            compact('cms_redirect', 'statusCodes')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'from_url' => ['required', 'string', 'max:255'],
            'to_url' => ['required', 'string', 'max:255'],
            'status_code' => ['required', 'string', 'max:255'],
            'hits' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsRedirect = $this->model->findOrFail($id);
            DB::transaction(function () use ($cmsRedirect, $validated) {
                $cmsRedirect->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsRedirect berhasil diperbarui.',
                $cmsRedirect
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CmsRedirect.',
                [
                    'exception' => $e,
                    'CmsRedirect_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsRedirect gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsRedirect = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsRedirect) {
                $cmsRedirect->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsRedirect berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cmsRedirect->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsRedirect.',
                [
                    'exception' => $e,
                    'CmsRedirect_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsRedirect gagal dihapus.'
            );
        }
    }

    // Toggle aktif/nonaktif
    public function toggle(CmsRedirect $cmsRedirect)
    {
        $cmsRedirect->update(['is_active' => !$cmsRedirect->is_active]);

        return back()->with('success', $cmsRedirect->is_active ? 'Redirect dinonaktifkan.' : 'Redirect diaktifkan.');
    }
}
