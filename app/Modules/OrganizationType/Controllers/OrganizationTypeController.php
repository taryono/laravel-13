<?php

namespace App\Modules\OrganizationType\Controllers;

use App\DataTables\OrganizationTypeDataTable;
use App\Http\Controllers\MainController;
use Models\OrganizationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class OrganizationTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new OrganizationType());
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
        OrganizationTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /organization-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'OrganizationType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $organizationType = new OrganizationType();
        $result = $this->getForm($organizationType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'OrganizationType::create',
            compact('organizationType', 'forms', 'groupedForms')
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
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return OrganizationType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data OrganizationType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan OrganizationType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data OrganizationType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $organizationType = $this->model->findOrFail($id);
        $result = $this->getForm($organizationType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'OrganizationType::edit',
            compact('organizationType', 'forms', 'groupedForms')
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
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $organizationType = $this->model->findOrFail($id);
            DB::transaction(function () use ($organizationType, $validated) {
                $organizationType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data OrganizationType berhasil diperbarui.',
                $organizationType
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui OrganizationType.',
                [
                    'exception' => $e,
                    'OrganizationType_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data OrganizationType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $organizationType = $this->model->findOrFail($id);

            DB::transaction(function () use ($organizationType) {
                $organizationType->delete();
            });

            return $this->successResponse(
                $request,
                'Data OrganizationType berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $organizationType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus OrganizationType.',
                [
                    'exception' => $e,
                    'OrganizationType_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data OrganizationType gagal dihapus.'
            );
        }
    }
}
