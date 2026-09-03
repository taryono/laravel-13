<?php

namespace App\Modules\AttendanceImportBatch\Controllers;

use App\DataTables\AttendanceImportBatchDataTable;
use App\Http\Controllers\MainController;
use Models\AttendanceImportBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AttendanceImportBatchController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AttendanceImportBatch());
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
        AttendanceImportBatchDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /attendance-import?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AttendanceImportBatch::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $attendanceImport = new AttendanceImportBatch();
        $result = $this->getForm($attendanceImport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttendanceImportBatch::create',
            compact('attendanceImport', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AttendanceImportBatch::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttendanceImportBatch berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AttendanceImportBatch.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceImportBatch gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $attendanceImport = $this->model->findOrFail($id);
        $result = $this->getForm($attendanceImport);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttendanceImportBatch::edit',
            compact('attendanceImport', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
        ]);

        try {
            $attendanceImport = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $attendanceImport,
                $validated
            ) {
                $attendanceImport->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttendanceImportBatch berhasil diperbarui.',
                $attendanceImport
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $attendanceImport->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $attendanceImport belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui AttendanceImportBatch.',
                [
                    'exception' => $e,
                    'AttendanceImportBatch_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceImportBatch gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $attendanceImport = $this->model->findOrFail($id);

            DB::transaction(function () use ($attendanceImport) {
                $attendanceImport->delete();
            });

            return $this->successResponse(
                $request,
                'Data AttendanceImportBatch berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $attendanceImport->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AttendanceImportBatch.',
                [
                    'exception' => $e,
                    'AttendanceImportBatch_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceImportBatch gagal dihapus.'
            );
        }
    } 
}
