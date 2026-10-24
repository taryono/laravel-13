<?php

namespace App\Modules\EnrollmentConversion\Controllers;

use App\DataTables\EnrollmentConversionDataTable;
use App\Http\Controllers\MainController;
use Models\EnrollmentConversion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EnrollmentConversionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EnrollmentConversion());
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
        EnrollmentConversionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /enrollment-conversion?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EnrollmentConversion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $enrollmentConversion = new EnrollmentConversion();
        $result = $this->getForm($enrollmentConversion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EnrollmentConversion::create',
            compact('enrollmentConversion', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'applicant_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'converted_at' => ['required', 'string', 'max:255'],
            'converted_by' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EnrollmentConversion::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EnrollmentConversion berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EnrollmentConversion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EnrollmentConversion gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $enrollmentConversion = $this->model->findOrFail($id);
        $result = $this->getForm($enrollmentConversion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EnrollmentConversion::edit',
            compact('enrollmentConversion', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'applicant_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'admission_id' => ['required', 'integer'],
            'converted_at' => ['required', 'string', 'max:255'],
            'converted_by' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $enrollmentConversion = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $enrollmentConversion,
                $validated
            ) {
                $enrollmentConversion->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EnrollmentConversion berhasil diperbarui.',
                $enrollmentConversion
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui EnrollmentConversion.',
                [
                    'exception' => $e,
                    'EnrollmentConversion_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EnrollmentConversion gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $enrollmentConversion = $this->model->findOrFail($id);

            DB::transaction(function () use ($enrollmentConversion) {
                $enrollmentConversion->delete();
            });

            return $this->successResponse(
                $request,
                'Data EnrollmentConversion berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $enrollmentConversion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EnrollmentConversion.',
                [
                    'exception' => $e,
                    'EnrollmentConversion_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EnrollmentConversion gagal dihapus.'
            );
        }
    } 
}
