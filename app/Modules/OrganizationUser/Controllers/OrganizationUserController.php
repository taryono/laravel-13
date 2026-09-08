<?php

namespace App\Modules\OrganizationUser\Controllers;

use App\DataTables\OrganizationUserDataTable;
use App\Http\Controllers\MainController;
use Models\OrganizationUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class OrganizationUserController extends MainController
{
    public function __construct()
    {
        parent::__construct(new OrganizationUser());
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
        OrganizationUserDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /organization-user?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'OrganizationUser::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $organizationUser = new OrganizationUser();
        $result = $this->getForm($organizationUser);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'OrganizationUser::create',
            compact('organizationUser', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'is_default' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return OrganizationUser::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data OrganizationUser berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan OrganizationUser.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data OrganizationUser gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $organizationUser = $this->model->findOrFail($id);
        $result = $this->getForm($organizationUser);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'OrganizationUser::edit',
            compact('organizationUser', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'is_default' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $organizationUser = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $organizationUser,
                $validated
            ) {
                $organizationUser->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data OrganizationUser berhasil diperbarui.',
                $organizationUser
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui OrganizationUser.',
                [
                    'exception' => $e,
                    'OrganizationUser_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data OrganizationUser gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $organizationUser = $this->model->findOrFail($id);

            DB::transaction(function () use ($organizationUser) {
                $organizationUser->delete();
            });

            return $this->successResponse(
                $request,
                'Data OrganizationUser berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $organizationUser->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus OrganizationUser.',
                [
                    'exception' => $e,
                    'OrganizationUser_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data OrganizationUser gagal dihapus.'
            );
        }
    } 
}
