<?php

namespace App\Modules\LibraryBook\Controllers;

use App\DataTables\LibraryBookDataTable;
use App\Http\Controllers\MainController;
use Models\LibraryBook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LibraryBookController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LibraryBook());
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
        LibraryBookDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /library-book?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LibraryBook::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $libraryBook = new LibraryBook();
        $result = $this->getForm($libraryBook);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryBook::create',
            compact('libraryBook', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'isbn' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'year_published' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'cover_url' => ['required', 'string', 'max:255'],
            'total_copies' => ['required', 'string', 'max:255'],
            'available_copies' => ['required', 'string', 'max:255'],
            'shelf_location' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LibraryBook::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryBook berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LibraryBook.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryBook gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $libraryBook = $this->model->findOrFail($id);
        $result = $this->getForm($libraryBook);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryBook::edit',
            compact('libraryBook', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'isbn' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'year_published' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'cover_url' => ['required', 'string', 'max:255'],
            'total_copies' => ['required', 'string', 'max:255'],
            'available_copies' => ['required', 'string', 'max:255'],
            'shelf_location' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $libraryBook = $this->model->findOrFail($id);
            DB::transaction(function () use ($libraryBook, $validated) {
                $libraryBook->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryBook berhasil diperbarui.',
                $libraryBook
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui LibraryBook.',
                [
                    'exception' => $e,
                    'LibraryBook_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryBook gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $libraryBook = $this->model->findOrFail($id);

            DB::transaction(function () use ($libraryBook) {
                $libraryBook->delete();
            });

            return $this->successResponse(
                $request,
                'Data LibraryBook berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $libraryBook->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LibraryBook.',
                [
                    'exception' => $e,
                    'LibraryBook_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryBook gagal dihapus.'
            );
        }
    }
}
