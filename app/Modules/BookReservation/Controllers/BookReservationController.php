<?php

namespace App\Modules\BookReservation\Controllers;

use App\DataTables\BookReservationDataTable;
use App\Http\Controllers\MainController;
use Models\BookReservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BookReservationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BookReservation());
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
        BookReservationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /book-reservation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BookReservation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bookReservation = new BookReservation();
        $result = $this->getForm($bookReservation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookReservation::create',
            compact('bookReservation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'library_book_id' => ['required', 'integer'],
            'library_membership_id' => ['required', 'integer'],
            'reservation_date' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BookReservation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookReservation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BookReservation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookReservation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bookReservation = $this->model->findOrFail($id);
        $result = $this->getForm($bookReservation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookReservation::edit',
            compact('bookReservation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'library_book_id' => ['required', 'integer'],
            'library_membership_id' => ['required', 'integer'],
            'reservation_date' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bookReservation = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $bookReservation,
                $validated
            ) {
                $bookReservation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookReservation berhasil diperbarui.',
                $bookReservation
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui BookReservation.',
                [
                    'exception' => $e,
                    'BookReservation_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookReservation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bookReservation = $this->model->findOrFail($id);

            DB::transaction(function () use ($bookReservation) {
                $bookReservation->delete();
            });

            return $this->successResponse(
                $request,
                'Data BookReservation berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $bookReservation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BookReservation.',
                [
                    'exception' => $e,
                    'BookReservation_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookReservation gagal dihapus.'
            );
        }
    } 
}
