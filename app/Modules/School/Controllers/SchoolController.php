<?php

namespace App\Modules\School\Controllers;

use App\DataTables\SchoolDataTable;
use App\Http\Controllers\MainController;
use Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SchoolController extends MainController
{
    public function __construct()
    {
        parent::__construct(new School());
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
        SchoolDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /school?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'School::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $school = new School();
        $result = $this->getForm($school);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'School::edit',
            compact('school', 'forms', 'groupedForms')
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
                return School::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data School berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan School.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data School gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $school = $this->model->findOrFail($id);
        $result = $this->getForm($school);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'School::edit',
            compact('school', 'forms', 'groupedForms')
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
            $school = $this->model->findOrFail($id);

            DB::transaction(function () use ($school, $validated) {
                $school->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data School berhasil diperbarui.',
                $school
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $school->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $school belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui School.',
                [
                    'exception' => $e,
                    'School_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data School gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $school = $this->model->findOrFail($id);

            DB::transaction(function () use ($school) {
                $school->delete();
            });

            return $this->successResponse(
                $request,
                'Data School berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $school->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus School.',
                [
                    'exception' => $e,
                    'School_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data School gagal dihapus.'
            );
        }
    }
}
