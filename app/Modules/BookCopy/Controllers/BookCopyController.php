<?php

namespace App\Modules\BookCopy\Controllers;

use App\DataTables\BookCopyDataTable;
use App\Http\Controllers\MainController;
use Models\BookCopy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BookCopyController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BookCopy());
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
        BookCopyDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /book-copy?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BookCopy::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bookCopy = new BookCopy();
        $result = $this->getForm($bookCopy);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookCopy::create',
            compact('bookCopy', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'library_book_id' => ['required', 'integer'],
            'barcode' => ['required', 'string', 'max:255'],
            'rfid_tag' => ['required', 'string', 'max:255'],
            'call_number' => ['required', 'string', 'max:255'],
            'shelf_location' => ['required', 'string', 'max:255'],
            'copy_number' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'acquisition_date' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BookCopy::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookCopy berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BookCopy.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookCopy gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bookCopy = $this->model->findOrFail($id);
        $result = $this->getForm($bookCopy);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookCopy::edit',
            compact('bookCopy', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'library_book_id' => ['required', 'integer'],
            'barcode' => ['required', 'string', 'max:255'],
            'rfid_tag' => ['required', 'string', 'max:255'],
            'call_number' => ['required', 'string', 'max:255'],
            'shelf_location' => ['required', 'string', 'max:255'],
            'copy_number' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'acquisition_date' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bookCopy = $this->model->findOrFail($id);
            DB::transaction(function () use ($bookCopy, $validated) {
                $bookCopy->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookCopy berhasil diperbarui.',
                $bookCopy
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BookCopy.',
                [
                    'exception' => $e,
                    'BookCopy_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookCopy gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bookCopy = $this->model->findOrFail($id);

            DB::transaction(function () use ($bookCopy) {
                $bookCopy->delete();
            });

            return $this->successResponse(
                $request,
                'Data BookCopy berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bookCopy->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BookCopy.',
                [
                    'exception' => $e,
                    'BookCopy_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookCopy gagal dihapus.'
            );
        }
    }
}
