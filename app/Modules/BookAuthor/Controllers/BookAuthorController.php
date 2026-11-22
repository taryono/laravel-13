<?php

namespace App\Modules\BookAuthor\Controllers;

use App\DataTables\BookAuthorDataTable;
use App\Http\Controllers\MainController;
use Models\BookAuthor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BookAuthorController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BookAuthor());
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
        BookAuthorDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /book-author?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BookAuthor::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bookAuthor = new BookAuthor();
        $result = $this->getForm($bookAuthor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookAuthor::create',
            compact('bookAuthor', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'biography' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'string', 'max:255'],
            'death_date' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'photo_url' => ['required', 'string', 'max:255'],
            'website' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BookAuthor::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookAuthor berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BookAuthor.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookAuthor gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bookAuthor = $this->model->findOrFail($id);
        $result = $this->getForm($bookAuthor);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookAuthor::edit',
            compact('bookAuthor', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'biography' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'string', 'max:255'],
            'death_date' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'photo_url' => ['required', 'string', 'max:255'],
            'website' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bookAuthor = $this->model->findOrFail($id);
            DB::transaction(function () use ($bookAuthor, $validated) {
                $bookAuthor->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookAuthor berhasil diperbarui.',
                $bookAuthor
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BookAuthor.',
                [
                    'exception' => $e,
                    'BookAuthor_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookAuthor gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bookAuthor = $this->model->findOrFail($id);

            DB::transaction(function () use ($bookAuthor) {
                $bookAuthor->delete();
            });

            return $this->successResponse(
                $request,
                'Data BookAuthor berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bookAuthor->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BookAuthor.',
                [
                    'exception' => $e,
                    'BookAuthor_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookAuthor gagal dihapus.'
            );
        }
    }
}
