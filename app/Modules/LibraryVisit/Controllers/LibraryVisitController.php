<?php

namespace App\Modules\LibraryVisit\Controllers;

use App\DataTables\LibraryVisitDataTable;
use App\Http\Controllers\MainController;
use Models\LibraryVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LibraryVisitController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LibraryVisit());
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
        LibraryVisitDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /library-visit?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LibraryVisit::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $libraryVisit = new LibraryVisit();
        $result = $this->getForm($libraryVisit);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryVisit::create',
            compact('libraryVisit', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'library_membership_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'visit_type' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'check_in_method' => ['required', 'string', 'max:255'],
            'check_out_method' => ['required', 'string', 'max:255'],
            'visitor_name' => ['required', 'string', 'max:255'],
            'visitor_phone' => ['required', 'string', 'max:255'],
            'visitor_institution' => ['required', 'string', 'max:255'],
            'visitor_id_number' => ['required', 'string', 'max:255'],
            'companion_count' => ['required', 'string', 'max:255'],
            'books_read' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LibraryVisit::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryVisit berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LibraryVisit.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryVisit gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $libraryVisit = $this->model->findOrFail($id);
        $result = $this->getForm($libraryVisit);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LibraryVisit::edit',
            compact('libraryVisit', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'library_membership_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'visit_type' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'check_in_method' => ['required', 'string', 'max:255'],
            'check_out_method' => ['required', 'string', 'max:255'],
            'visitor_name' => ['required', 'string', 'max:255'],
            'visitor_phone' => ['required', 'string', 'max:255'],
            'visitor_institution' => ['required', 'string', 'max:255'],
            'visitor_id_number' => ['required', 'string', 'max:255'],
            'companion_count' => ['required', 'string', 'max:255'],
            'books_read' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $libraryVisit = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $libraryVisit,
                $validated
            ) {
                $libraryVisit->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LibraryVisit berhasil diperbarui.',
                $libraryVisit
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui LibraryVisit.',
                [
                    'exception' => $e,
                    'LibraryVisit_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryVisit gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $libraryVisit = $this->model->findOrFail($id);

            DB::transaction(function () use ($libraryVisit) {
                $libraryVisit->delete();
            });

            return $this->successResponse(
                $request,
                'Data LibraryVisit berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $libraryVisit->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LibraryVisit.',
                [
                    'exception' => $e,
                    'LibraryVisit_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LibraryVisit gagal dihapus.'
            );
        }
    } 
}
