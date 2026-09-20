<?php

namespace App\Modules\Subject\Controllers;

use App\DataTables\SubjectDataTable;
use App\Http\Controllers\MainController;
use Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SubjectController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Subject());
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
        SubjectDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /subject?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Subject::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $subject = new Subject();
        $result = $this->getForm($subject);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Subject::create',
            compact('subject', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'abbreviation' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Subject::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Subject berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Subject.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Subject gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $subject = $this->model->findOrFail($id);
        $result = $this->getForm($subject);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Subject::edit',
            compact('subject', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'abbreviation' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $subject = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $subject,
                $validated
            ) {
                $subject->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Subject berhasil diperbarui.',
                $subject
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Subject.',
                [
                    'exception' => $e,
                    'Subject_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Subject gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $subject = $this->model->findOrFail($id);

            DB::transaction(function () use ($subject) {
                $subject->delete();
            });

            return $this->successResponse(
                $request,
                'Data Subject berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $subject->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Subject.',
                [
                    'exception' => $e,
                    'Subject_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Subject gagal dihapus.'
            );
        }
    } 
}
