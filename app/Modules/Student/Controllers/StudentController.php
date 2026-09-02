<?php

namespace App\Modules\Student\Controllers;

use App\DataTables\StudentDataTable;
use App\Http\Controllers\MainController;
use Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Student());
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
        StudentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Student::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $student = new Student();
        $result = $this->getForm($student);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Student::create',
            compact('student', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Student::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Student berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Student.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Student gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $student = $this->model->findOrFail($id);
        $result = $this->getForm($student);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Student::edit',
            compact('student', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $student = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $student,
                $validated
            ) {
                $student->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Student berhasil diperbarui.',
                $student
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $student->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $student belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui Student.',
                [
                    'exception' => $e,
                    'Student_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Student gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $student = $this->model->findOrFail($id);

            DB::transaction(function () use ($student) {
                $student->delete();
            });

            return $this->successResponse(
                $request,
                'Data Student berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $student->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Student.',
                [
                    'exception' => $e,
                    'Student_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Student gagal dihapus.'
            );
        }
    }
}
