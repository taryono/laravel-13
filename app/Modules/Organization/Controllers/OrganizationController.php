<?php

namespace App\Modules\Organization\Controllers;

use App\DataTables\OrganizationDataTable;
use App\Http\Controllers\MainController;
use Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class OrganizationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Organization());
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
        OrganizationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /organization?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Organization::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $organization = new Organization();
        $result = $this->getForm($organization);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Organization::create',
            compact('organization', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Organization::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Organization berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Organization.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Organization gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $organization = $this->model->findOrFail($id);
        $result = $this->getForm($organization);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Organization::edit',
            compact('organization', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $organization = $this->model->findOrFail($id);
            DB::transaction(function () use ($organization, $validated) {
                $organization->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Organization berhasil diperbarui.',
                $organization
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Organization.',
                [
                    'exception' => $e,
                    'Organization_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Organization gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $organization = $this->model->findOrFail($id);

            DB::transaction(function () use ($organization) {
                $organization->delete();
            });

            return $this->successResponse(
                $request,
                'Data Organization berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $organization->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Organization.',
                [
                    'exception' => $e,
                    'Organization_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Organization gagal dihapus.'
            );
        }
    }
}
