<?php

namespace App\Modules\AcademicRegistrationFee\Controllers;

use App\DataTables\AcademicRegistrationFeeDataTable;
use App\Http\Controllers\MainController;
use Models\AcademicRegistrationFee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AcademicRegistrationFeeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AcademicRegistrationFee());
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
        AcademicRegistrationFeeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /academic-registration-fee?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AcademicRegistrationFee::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicRegistrationFee = new AcademicRegistrationFee();
        $result = $this->getForm($academicRegistrationFee);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AcademicRegistrationFee::create',
            compact('academicRegistrationFee', 'forms', 'groupedForms')
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
                return AcademicRegistrationFee::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AcademicRegistrationFee berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AcademicRegistrationFee.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicRegistrationFee gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $academicRegistrationFee = $this->model->findOrFail($id);
        $result = $this->getForm($academicRegistrationFee);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AcademicRegistrationFee::edit',
            compact('academicRegistrationFee', 'forms', 'groupedForms')
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
            $academicRegistrationFee = $this->model->findOrFail($id);
            DB::transaction(function () use ($academicRegistrationFee, $validated) {
                $academicRegistrationFee->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AcademicRegistrationFee berhasil diperbarui.',
                $academicRegistrationFee
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AcademicRegistrationFee.',
                [
                    'exception' => $e,
                    'AcademicRegistrationFee_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicRegistrationFee gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $academicRegistrationFee = $this->model->findOrFail($id);

            DB::transaction(function () use ($academicRegistrationFee) {
                $academicRegistrationFee->delete();
            });

            return $this->successResponse(
                $request,
                'Data AcademicRegistrationFee berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $academicRegistrationFee->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AcademicRegistrationFee.',
                [
                    'exception' => $e,
                    'AcademicRegistrationFee_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicRegistrationFee gagal dihapus.'
            );
        }
    }
}
