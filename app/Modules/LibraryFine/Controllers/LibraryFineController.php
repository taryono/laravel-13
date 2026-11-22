<?php

namespace App\Modules\LibraryFine\Controllers;

use App\DataTables\LibraryFineDataTable;
use App\Http\Controllers\MainController;
use Models\LibraryFine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LibraryFineController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LibraryFine());
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
        LibraryFineDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /library-fine?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LibraryFine::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $libraryFine = new LibraryFine();
        $result = $this->getForm($libraryFine);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryFine::create',
            compact('libraryFine', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'library_loan_id' => ['required', 'integer'],
            'library_membership_id' => ['required', 'integer'],
            'days_overdue' => ['required', 'string', 'max:255'],
            'fine_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'payment_status' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LibraryFine::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryFine berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LibraryFine.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryFine gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $libraryFine = $this->model->findOrFail($id);
        $result = $this->getForm($libraryFine);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryFine::edit',
            compact('libraryFine', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'library_loan_id' => ['required', 'integer'],
            'library_membership_id' => ['required', 'integer'],
            'days_overdue' => ['required', 'string', 'max:255'],
            'fine_amount' => ['required', 'string', 'max:255'],
            'paid_amount' => ['required', 'string', 'max:255'],
            'payment_status' => ['required', 'string', 'max:255'],
            'paid_at' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
        ]);

        try {
            $libraryFine = $this->model->findOrFail($id);
            DB::transaction(function () use ($libraryFine, $validated) {
                $libraryFine->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryFine berhasil diperbarui.',
                $libraryFine
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui LibraryFine.',
                [
                    'exception' => $e,
                    'LibraryFine_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryFine gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $libraryFine = $this->model->findOrFail($id);

            DB::transaction(function () use ($libraryFine) {
                $libraryFine->delete();
            });

            return $this->successResponse(
                $request,
                'Data LibraryFine berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $libraryFine->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LibraryFine.',
                [
                    'exception' => $e,
                    'LibraryFine_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryFine gagal dihapus.'
            );
        }
    }
}
