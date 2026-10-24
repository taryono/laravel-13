<?php

namespace App\Modules\LibraryStockOpname\Controllers;

use App\DataTables\LibraryStockOpnameDataTable;
use App\Http\Controllers\MainController;
use Models\LibraryStockOpname;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LibraryStockOpnameController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LibraryStockOpname());
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
        LibraryStockOpnameDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /library-stock-opname?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LibraryStockOpname::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $libraryStockOpname = new LibraryStockOpname();
        $result = $this->getForm($libraryStockOpname);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryStockOpname::create',
            compact('libraryStockOpname', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'library_book_id' => ['required', 'integer'],
            'book_copy_id' => ['required', 'integer'],
            'opname_batch' => ['required', 'string', 'max:255'],
            'opname_date' => ['required', 'string', 'max:255'],
            'expected_quantity' => ['required', 'string', 'max:255'],
            'actual_quantity' => ['required', 'string', 'max:255'],
            'difference' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'discrepancy_reason' => ['required', 'string', 'max:255'],
            'action_taken' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'shelf_code' => ['required', 'string', 'max:255'],
            'checked_by' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LibraryStockOpname::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryStockOpname berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LibraryStockOpname.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryStockOpname gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $libraryStockOpname = $this->model->findOrFail($id);
        $result = $this->getForm($libraryStockOpname);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryStockOpname::edit',
            compact('libraryStockOpname', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'library_book_id' => ['required', 'integer'],
            'book_copy_id' => ['required', 'integer'],
            'opname_batch' => ['required', 'string', 'max:255'],
            'opname_date' => ['required', 'string', 'max:255'],
            'expected_quantity' => ['required', 'string', 'max:255'],
            'actual_quantity' => ['required', 'string', 'max:255'],
            'difference' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'discrepancy_reason' => ['required', 'string', 'max:255'],
            'action_taken' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'shelf_code' => ['required', 'string', 'max:255'],
            'checked_by' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'verified_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $libraryStockOpname = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $libraryStockOpname,
                $validated
            ) {
                $libraryStockOpname->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryStockOpname berhasil diperbarui.',
                $libraryStockOpname
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui LibraryStockOpname.',
                [
                    'exception' => $e,
                    'LibraryStockOpname_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryStockOpname gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $libraryStockOpname = $this->model->findOrFail($id);

            DB::transaction(function () use ($libraryStockOpname) {
                $libraryStockOpname->delete();
            });

            return $this->successResponse(
                $request,
                'Data LibraryStockOpname berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $libraryStockOpname->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LibraryStockOpname.',
                [
                    'exception' => $e,
                    'LibraryStockOpname_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryStockOpname gagal dihapus.'
            );
        }
    } 
}
