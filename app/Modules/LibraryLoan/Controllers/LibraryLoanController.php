<?php

namespace App\Modules\LibraryLoan\Controllers;

use App\DataTables\LibraryLoanDataTable;
use App\Http\Controllers\MainController;
use Models\LibraryLoan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LibraryLoanController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LibraryLoan());
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
        LibraryLoanDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /library-loan?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LibraryLoan::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $libraryLoan = new LibraryLoan();
        $result = $this->getForm($libraryLoan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryLoan::create',
            compact('libraryLoan', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'loan_date' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'return_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'fine_amount' => ['required', 'string', 'max:255'],
            'fine_paid' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'handled_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LibraryLoan::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryLoan berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LibraryLoan.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryLoan gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $libraryLoan = $this->model->findOrFail($id);
        $result = $this->getForm($libraryLoan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryLoan::edit',
            compact('libraryLoan', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'book_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'loan_date' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'return_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'fine_amount' => ['required', 'string', 'max:255'],
            'fine_paid' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'handled_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $libraryLoan = $this->model->findOrFail($id);
            DB::transaction(function () use ($libraryLoan, $validated) {
                $libraryLoan->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryLoan berhasil diperbarui.',
                $libraryLoan
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui LibraryLoan.',
                [
                    'exception' => $e,
                    'LibraryLoan_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryLoan gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $libraryLoan = $this->model->findOrFail($id);

            DB::transaction(function () use ($libraryLoan) {
                $libraryLoan->delete();
            });

            return $this->successResponse(
                $request,
                'Data LibraryLoan berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $libraryLoan->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LibraryLoan.',
                [
                    'exception' => $e,
                    'LibraryLoan_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryLoan gagal dihapus.'
            );
        }
    }
}
