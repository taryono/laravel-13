<?php

namespace App\Modules\LibraryMembership\Controllers;

use App\DataTables\LibraryMembershipDataTable;
use App\Http\Controllers\MainController;
use Models\LibraryMembership;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LibraryMembershipController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LibraryMembership());
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
        LibraryMembershipDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /library-membership?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LibraryMembership::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $libraryMembership = new LibraryMembership();
        $result = $this->getForm($libraryMembership);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryMembership::create',
            compact('libraryMembership', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'member_type' => ['required', 'string', 'max:255'],
            'member_id' => ['required', 'integer'],
            'member_code' => ['required', 'string', 'max:255'],
            'max_borrow_limit' => ['required', 'string', 'max:255'],
            'max_borrow_days' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LibraryMembership::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryMembership berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LibraryMembership.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryMembership gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $libraryMembership = $this->model->findOrFail($id);
        $result = $this->getForm($libraryMembership);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryMembership::edit',
            compact('libraryMembership', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'member_type' => ['required', 'string', 'max:255'],
            'member_id' => ['required', 'integer'],
            'member_code' => ['required', 'string', 'max:255'],
            'max_borrow_limit' => ['required', 'string', 'max:255'],
            'max_borrow_days' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
        ]);

        try {
            $libraryMembership = $this->model->findOrFail($id);
            DB::transaction(function () use ($libraryMembership, $validated) {
                $libraryMembership->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryMembership berhasil diperbarui.',
                $libraryMembership
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui LibraryMembership.',
                [
                    'exception' => $e,
                    'LibraryMembership_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryMembership gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $libraryMembership = $this->model->findOrFail($id);

            DB::transaction(function () use ($libraryMembership) {
                $libraryMembership->delete();
            });

            return $this->successResponse(
                $request,
                'Data LibraryMembership berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $libraryMembership->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LibraryMembership.',
                [
                    'exception' => $e,
                    'LibraryMembership_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryMembership gagal dihapus.'
            );
        }
    }
}
